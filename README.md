# Laravel VolDB
A volunteer database for events written using the Laravel 5.6 framework


## Dependencies

1. A webserver that supports PHP (```nginx``` and ```php-fpm``` recommended)
2. ```mysql```
3. ```node.js``` and ```npm``` installed on your system
4. ```composer```, the PHP package manager
5. ```redis```, if you want to use websockets

## Environement Setup

Instructions here for official laravel vagrant box: https://laravel.com/docs/5.8/homestead
You can modify some of the networking settings or ssh settings before you install if you want.  
homestead will handle some of the document root stuff for you and can be configured. 

1. Install vagrant
2. Install virtualbox
3. mkdir Homestead and cd in
4. vagrant box add laravel/homestead
5. vagrant up
6. vagrant global-staus to get vagrant box name
7. vagrant ssh {vagrant box name}
8. mkdir code
9. clone volunteers repo into code directory 

[optional] 10. In your local environment ssfs vagrant@192.168.10.10:/home/vagrant/code {local path of your choice here} to mount vagrant's code directory locally.

## Installing

1. Git clone this repo
2. Set **laravel/public/** as your document root
3. Run ```composer install``` within the **laravel** folder
4. Run ```npm install``` within the **laravel** folder  
5. Set up your environment configuration. See the [Setup / Configuration](#configuration) section below. 
6. Run ```php artisan migrate``` within the **laravel** folder


## <a name="configuration"></a> Setup / Configuration

1. In the **laravel** folder, copy **.env.example** and rename it to **.env**
2. Configure your database and email settings in the **.env** file
3. run `php artisan key:generate` to generate an application key for Laravel
4. Optionally, configure your queue and broadcast drivers. If you want to use websockets, you'll need to use redis for broadcasting
5. In the **laravel/resources/js/** folder, copy **config.example.js** and rename it to **config.js**
6. Optionally, you may configure your websocket server to use a specific hostname, however by default it will use the current domain of the site
7. Run ```npm run build``` within the **laravel** folder.
8. Run ```php artisan db:seed``` within the **laravel** folder to populate the database with user roles


Alright! Now everything is compiled and the site is functional. You can register accounts, create events, and sign up for shifts.
If you want to use websockets for a couple extra features (auto-updating when shifts are taken or departments are changed), follow these steps:


## Extra websockets steps

1. In your **.env** file, make sure ```redis``` is installed and configured as the broadcast driver, and that the variable WEBSOCKETS_ENABLED is set to true
2. Run ```npm install``` within the **node** folder
3. Ensure that the websocket parameters in  ```laravel/resources/js/config.js``` are correct
4. Run ```node websocket-server.js``` within the **node** folder
5. Use a ```screen``` session or a process manager like ```pm2``` to keep the websocket server running indefinitely

## Troubleshooting

Issue: On composer install : PHP fatal error laravel/bootstrap/autoload.php on line 17
resolve: 
```
composer install --no-scripts
composer install
```
