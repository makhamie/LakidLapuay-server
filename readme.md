# lakid-lapuay server

> A backend for lakidlapuay project

# Requirements
  * install php version 7.1 (or upper)
  * install composer version 1.6 (or upper)
  ``` bash
    composer global require laravel/installer
    composer require laravel/passport
    composer require doctrine/dbal
    composer require nesbot/carbon
   ```
  # database
  * create sql database named “lakidlapuay”
  * install database local server eg. Xampp or Mamp
  * create .env file like this [link](https://drive.google.com/file/d/1oAzMJVL3wji7CXv0kYyxyV5YcYSZBC0g/view)

# How to run the project
# run back-end server
php artisan serve
# migrate database
php artisan migrate
# install passport
php artisan passport:install

# Database local server
# open the Xampp of Mamp application and start server
```
