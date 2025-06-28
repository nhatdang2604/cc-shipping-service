# How to build the code ?

Step 1: Please run the below command to build container
```
docker compose up
```

Step 2: Generate autoload and installing libray by running commands
```
docker exec -it cc-php composer dump-autoload 
docker exec -it cc-php composer install
``` 

# How to run the code ?

Please access to 
'http://localhost:9091/public/'

There are the result render of the gross price


# How to config the code ?

- To change the input: please browse to 'public/index.php' to change the $order variable
- To config the fee parameter (such as weight coefficient, dimension coefficient,...): please browse to app/Configs/Config.php


# How to run the unit test ?

Please running the below command
```
docker exec -it cc-php vendor/bin/phpunit tests
```