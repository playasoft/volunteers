# Laravel VolDB
A volunteer database for events written using the Laravel 5.1 framework


## Dependencies

1. A webserver that supports PHP (```nginx``` and ```php-fpm``` recommended)
5. ```mysql```
2. ```node.js``` and ```npm``` installed on your system
3. ```gulp``` installed globally (```npm install -g gulp```) or locally if you know what you're doing
4. ```composer```, the PHP package manager
6. ```redis```, if you want to use websockets


## Installing

1. Git clone this repo
2. Set **laravel/public/** as your document root
3. Run ```composer install``` within the **laravel** folder
4. Run ```npm install``` within the **laravel** folder
5. Run ```php artisan migrate``` within the **laravel** folder


## Setup / Configuration

1. In the **laravel** folder, copy **.env.example** and rename it to **.env**
2. Configure your database and email settings in the **.env** file
3. Optionally, configure your queue and broadcast drivers. If you want to use websockets, you'll need to use redis for broadcasting
4. In the **laravel/resources/js/** folder, copy **config.example.js** and rename it to **config.js**
5. Optionally, you may configure your websocket server to use a specific hostname, however by default it will use the current domain of the site
6. Run ```gulp``` within the **laravel** folder


Alright! Now everything is compiled and the site is functional. You can register accounts, create events, and sign up for shifts.
If you want to use websockets for a couple extra features (auto-updating when shifts are taken or departments are changed), follow these steps:


## Extra websockets steps

1. Make sure ```redis``` is installed and configured as the broadcast driver in your **.env** file
2. Run ```npm install``` within the **node** folder
3. Run ```node websocket-server.js``` within the **node** folder
4. Use a ```screen``` session or a process manager like ```pm2``` to keep the websocket server running indefinitely
