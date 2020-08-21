FROM debian:buster
MAINTAINER Taegan (taeganavery@pm.me)
RUN apt update
RUN apt install -y nodejs npm composer sed
RUN apt install -y apt-transport-https ca-certificates curl software-properties-common
RUN curl -fsSL https://packages.sury.org/php/apt.gpg | apt-key add -
RUN add-apt-repository "deb https://packages.sury.org/php/ $(lsb_release -cs) main"
RUN apt update
RUN apt install -y php7.2-common php7.2-cli php7.2-fpm php7.2-mbstring php7.2-xml php7.2-mysql
RUN update-alternatives --set php /usr/bin/php7.2
RUN addgroup --system --gid 10000 volunteers
RUN adduser --system --uid 10000 --home /home/volunteers/ --group volunteers
WORKDIR /home/volunteers
RUN git clone https://github.com/jcam/volunteers.git
WORKDIR /home/volunteers/volunteers/laravel
RUN sed -i 's/\^2.6/2.9.2/' composer.json
WORKDIR /etc/php/7.2/fpm/pool.d
RUN sed -i 's/user = www-data/user = volunteers/' www.conf
RUN sed -i 's/group = www-data/group = volunteers/' www.conf
WORKDIR /home/volunteers/volunteers/laravel
RUN composer install
RUN npm install
RUN npm build
RUN php artisan db:seed
RUN composer update
RUN php artisan migrate --force
