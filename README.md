# Laravel VolDB
A volunteer database for events written using the Laravel 5.6 framework


## Dependencies

1. A webserver that supports PHP (```nginx``` and ```php-fpm``` recommended)
2. ```mysql```
3. ```node.js``` and ```npm``` installed on your system
4. ```php```
    * ext-mbstring (```apt install php-mbstring```)
    * ext-dom (```apt install php-dom```)
    * ext-mysql (```apt instal php-mysql```)
5. ```composer```, the PHP package manager
6. ```laravel```, the projects PHP framework
6. ```redis```, if you want to use websockets


## Installing

1. Git clone this repo
2. Set **laravel/public/** as your document root
3. Run ```composer install``` within the **laravel** folder
4. Run ```npm install``` within the **laravel** folder (_Note_: currently only works with Node v10)
5. Run ```cp .env.example .env``` and configure ```DB_DATABASE```, ```DB_USERNAME```, and ```DB_PASSWORD```
    * _Note_: You must set this up ahead of time, an [easy guide for Ubuntu users is available](#mysql).
6. Run ```php artisan migrate``` within the **laravel** folder


## <a name="configuration"></a> Setup / Configuration

1. Configure your database and email settings in the **.env** file
2. run `php artisan key:generate` to generate an application key for Laravel
3. Optionally, configure your queue and broadcast drivers. If you want to use websockets, you'll need to use redis for broadcasting
4. In the **laravel/resources/js/** folder, copy **config.example.js** and rename it to **config.js**
5. Optionally, you may configure your websocket server to use a specific hostname, however by default it will use the current domain of the site
6. Run ```npm run build``` within the **laravel** folder.
7. Run ```php artisan db:seed``` within the **laravel** folder to populate the database with user roles


Alright! Now everything is compiled and the site is functional. You can register accounts, create events, and sign up for shifts.
If you want to use websockets for a couple extra features (auto-updating when shifts are taken or departments are changed), follow these steps:


## Extra websockets steps

1. In your **.env** file, make sure ```redis``` is installed and configured as the broadcast driver, and that the variable WEBSOCKETS_ENABLED is set to true
2. Run ```npm install``` within the **node** folder
3. Ensure that the websocket parameters in  ```laravel/resources/js/config.js``` are correct
4. Run ```node websocket-server.js``` within the **node** folder
5. Use a ```screen``` session or a process manager like ```pm2``` to keep the websocket server running indefinitely


## <a name="mysql"></a> MySQL Server Setup (Ubuntu)
1. Run ```sudo mysql -u root``` and enter your root password
2. Run ```create database $DB_DATABASE;```, replacing ```$DB_DATABASE``` with your database name
3. Run ```GRANT ALL PRIVILEGES ON $DB_DATABASE.* TO '$DB_USERNAME'@'localhost' IDENTIFIED BY '$DB_PASSWORD';```, replacing ```$DB_DATABASE``` with the database name picked previously, and ```$DB_USERNAME``` and ```$DB_PASSWORD``` with what you like
4. Run ```FLUSH PRIVILEGES;``` so the changes take effect
5. Edit your ```.env``` file to reflect the ```$DB_DATABASE```, ```$DB_USERNAME```, ```$DB_PASSWORD``` you picked.
