#!/bin/bash

echo "# Add the following to your .env file"

openssl rand -base64 32 | sed 's/^/APP_KEY=base64:/'
