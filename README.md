# chessGame

Project for learning new features

# Technologies

* Laravel 5
* Postrges 9.5

# Steps to up development envinroment(only for linux systems)

* Install docker(https://www.docker.com/) and docker-compose(https://docs.docker.com/compose/) on your machine

* Go to project folder
* Run ```chmod 777 -R storage```
* Run ```chmod 777 -R bootstrap/cache```
* Run ```git checkout storage*```
* Run ```git checkout bootstrap*```
* Run ```cp .env.example .env```
* Run ```cp .pg-env.example .pg-env```
* Fill database and applicaion configs in .env and .pg-env files
* Run ```docker-compose build```
* Run ```docker-compose run php composer install```
* Run ```docker-compose run php composer run post-create-project-cmd```
* Run ```docker-compose run php php artisan migrate```
* Run ```docker-compose up -d``` for start project
* Run ```docker-compose stop``` for stop project
* Your app is avaliable on http://localhost
