#!/usr/bin/env bash
set -euo pipefail

# =============================================================================
# SSL Setup — Issue, renew, and configure SSL certificates
# Reads SSL_DOMAINS from .env to determine which domains to cover
# =============================================================================

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"
ENV_FILE="$PROJECT_DIR/.env"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
NC='\033[0m'

# ---- Read SSL_DOMAINS from .env ----

if [[ ! -f "$ENV_FILE" ]]; then
    echo -e "${RED}Error: .env file not found at $ENV_FILE${NC}"
    echo "Run 'cp .env.example .env' and set SSL_DOMAINS first."
    exit 1
fi

SSL_DOMAINS=$(grep -E "^SSL_DOMAINS=" "$ENV_FILE" | cut -d'=' -f2- | tr -d '"' | tr -d "'")

if [[ -z "$SSL_DOMAINS" ]]; then
    echo -e "${RED}Error: SSL_DOMAINS is not set in .env${NC}"
    echo ""
    echo "Add a line like this to your .env file:"
    echo "  SSL_DOMAINS=example.com,www.example.com"
    exit 1
fi

# Parse domains: first is primary, rest are alternates
IFS=',' read -ra DOMAINS <<< "$SSL_DOMAINS"
PRIMARY_DOMAIN="${DOMAINS[0]}"

echo -e "${CYAN}SSL Configuration${NC}"
echo -e "  Primary domain: ${GREEN}$PRIMARY_DOMAIN${NC}"
if [[ ${#DOMAINS[@]} -gt 1 ]]; then
    echo -e "  Additional domains: ${GREEN}${DOMAINS[*]:1}${NC}"
fi
echo ""

# Build certbot -d flags
CERTBOT_DOMAINS=""
for domain in "${DOMAINS[@]}"; do
    CERTBOT_DOMAINS="$CERTBOT_DOMAINS -d $domain"
done

# ---- Command handling ----

show_help() {
    cat <<EOF
Usage: ./scripts/ssl-setup.sh [command]

Commands:
  issue       Issue a new certificate (stops containers for standalone verification)
  renew       Renew existing certificates
  configure   Generate nginx production config and restart with HTTPS
  status      Show certificate status

If no command is given, runs all steps: issue + configure.
EOF
}

issue_cert() {
    echo -e "${YELLOW}Issuing certificate...${NC}"
    echo "This requires stopping the containers so certbot can bind to port 80."
    echo ""

    # Check if running as root or with sudo
    if [[ $EUID -ne 0 ]]; then
        echo -e "${RED}Error: Certificate issuance requires root privileges.${NC}"
        echo "Run: sudo $0 issue"
        exit 1
    fi

    # Stop containers if running
    if docker compose -f "$PROJECT_DIR/docker-compose.yml" ps --quiet 2>/dev/null | grep -q .; then
        echo "Stopping containers..."
        su - "$(stat -c '%U' "$PROJECT_DIR")" -c "cd $PROJECT_DIR && docker compose down"
    fi

    echo "Running certbot..."
    certbot certonly --standalone $CERTBOT_DOMAINS

    echo -e "${GREEN}Certificate issued successfully.${NC}"
}

renew_cert() {
    echo -e "${YELLOW}Renewing certificates...${NC}"

    if [[ $EUID -ne 0 ]]; then
        echo -e "${RED}Error: Certificate renewal requires root privileges.${NC}"
        echo "Run: sudo $0 renew"
        exit 1
    fi

    OWNER="$(stat -c '%U' "$PROJECT_DIR")"

    certbot renew \
        --pre-hook "su - $OWNER -c 'cd $PROJECT_DIR && docker compose -f docker-compose.yml -f docker-compose.production.yml down'" \
        --post-hook "su - $OWNER -c 'cd $PROJECT_DIR && docker compose -f docker-compose.yml -f docker-compose.production.yml up -d'"

    echo -e "${GREEN}Renewal complete.${NC}"
}

configure() {
    echo -e "${YELLOW}Generating nginx production config...${NC}"

    export SSL_DOMAIN="$PRIMARY_DOMAIN"
    envsubst '${SSL_DOMAIN}' \
        < "$PROJECT_DIR/docker/nginx/production.conf.template" \
        > "$PROJECT_DIR/docker/nginx/production.conf"

    echo -e "  Written to: ${GREEN}docker/nginx/production.conf${NC}"
    echo ""

    echo -e "${YELLOW}Starting containers with HTTPS...${NC}"
    cd "$PROJECT_DIR"

    # Determine the right user to run docker compose
    OWNER="$(stat -c '%U' "$PROJECT_DIR")"
    if [[ "$(whoami)" == "$OWNER" ]]; then
        docker compose -f docker-compose.yml -f docker-compose.production.yml up -d
    else
        su - "$OWNER" -c "cd $PROJECT_DIR && docker compose -f docker-compose.yml -f docker-compose.production.yml up -d"
    fi

    echo ""
    echo -e "${GREEN}HTTPS is live!${NC}"
    for domain in "${DOMAINS[@]}"; do
        echo -e "  https://$domain"
    done
}

show_status() {
    echo -e "${YELLOW}Certificate status:${NC}"
    if [[ -d "/etc/letsencrypt/live/$PRIMARY_DOMAIN" ]]; then
        echo -e "  Cert directory: ${GREEN}/etc/letsencrypt/live/$PRIMARY_DOMAIN${NC}"
        openssl x509 -in "/etc/letsencrypt/live/$PRIMARY_DOMAIN/fullchain.pem" -noout \
            -subject -enddate -ext subjectAltName 2>/dev/null || echo -e "  ${RED}Could not read certificate (need root?)${NC}"
    else
        echo -e "  ${RED}No certificate found for $PRIMARY_DOMAIN${NC}"
        echo "  Run: sudo ./scripts/ssl-setup.sh issue"
    fi
}

# ---- Main ----

case "${1:-all}" in
    issue)      issue_cert ;;
    renew)      renew_cert ;;
    configure)  configure ;;
    status)     show_status ;;
    all)        issue_cert; echo ""; configure ;;
    help|--help|-h) show_help ;;
    *)
        echo -e "${RED}Unknown command: $1${NC}"
        show_help
        exit 1
        ;;
esac