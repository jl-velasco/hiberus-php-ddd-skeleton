# General information

This project is the base structure for a microservice, applying Domain-Driven Design (DDD) and Command Query Responsibility Segregation (CQRS).
It has the necessary files to act as api or as web. It uses PHP 8.1 and symfony 6.0 although it is decoupled from symfony.
Use MySQL as the database and dbal, but an ORM can be changed if the team prefers.
Once you have decided which type of microservice is going to be used, it is recommended that you delete the files related to each type.

# Installation

How to install.

Steps:

1. Copy .env.example to .env and edit with correct values. Environment files are located in config/environments folder.
2. Run service
```
docker-compose -f docker-compose-dev.yml up -d
```
2. Install dependencies:
```
docker exec -it <container_name> php composer.phar install
```
`<container_name>`: The name in the docker-compose-dev.yml file


3. Add hook so that commits cannot be made if validations do not pass

Add _.git/hooks/pre-commit_ file
Make it executable `chmod +x .git/hooks/pre-commit`
Add content:
```
#!/bin/bash

exec < /dev/tty

docker exec <container_name> php composer.phar csstan &&
docker exec <container_name> php bin/phpunit --configuration /var/www/phpunit.xml.dist

rc=$?

exit $rc
```
## Use It
⚠️ The application container is mapped to port `8001` so the requests have to be like this:
`http://localhost:8001/healthcheck`

### Web version
```
http://localhost:8001/
```
### Api version
```
curl --location --request PUT 'localhost:8001/v1/user/a95c0896-1d05-4785-95ec-a7bc0229f356' \
--header 'Content-Type: application/json' \
--data-raw '{
    "name": "Jonh Doe",
    "email": "jonh@doe.com",
    "password": "jonhdoe"
}'
```
## File to delete according to type of microservice:

#### Delete the following unnecessary files if you want an API type microservice:
#TODO: List files to delete for API type
#### Delete the following unnecessary files if you want a Web type microservice:
#TODO: List files to delete for Web type
#### Other IMPORTANT Before using this skeleton you should

* Edit phpstan.neon package name
* Edit composer.json and change:
    - project name
    - project description
    - autoload and autoload-dev package names
* Edit app/Kernel.php package name
* Edit public/index.php Kernel package name import
* Edit bin/console.php Kernel package name import
* Edit config/services.yaml packages name for app and src
* Edit docker-compose-dev.yml service name, container name, image and ports
* Edit the log related classes namespace (/src/Shared/Log)
* Edit this file removing these instructions and filling content

## Important things to know

### Domain Events:

Currently events are fired and consumed in memory **InMemorySymfonyEventBus**, but the project is also prepared to store and consume them with rabbitMQ **RabbitMqEventBus**.

There is a parent domain event **StoreDomainEvent** that will store all the events that happen in the database (it doesn't matter the implementation: in memory, rabbitmq, etc).
For it to work you have to execute a migration (file Version20220115081254_create_table_domain_event.php), if it is not needed, delete said migration and the class.

### Consumers:

Supervisor has been added to be in charge of picking up the consumers. To do this, a configuration file **UserCreatedSendEmailConsumer.conf** has been created as an example, which is responsible for launching the rabbitMQ command **LaunchRabbitMqConsumerCommand**.
```
php bin/console rabbitmq:consume UserCreatedSendEmailConsumer
```

### Exception Listener:

This **ApiExceptionListener** listener is responsible for catching the exceptions that are thrown in the microservice and returning a json with the error.

### Auth Middleware:

Este middleware **BasicHttpAuthMiddleware** sirve authentificar las peticiones que se hacen al microservicio. Falta implementar AuthenticateUserCommand para comprobar el usuario y password. Se puede modificar para que maneje tokens.

### Transaction Middleware:

This middleware **BasicHttpAuthMiddleware** serves to authenticate the requests made to the microservice. It remains to implement AuthenticateUserCommand to check the username and password. It can be modified to handle tokens.

# Use

Go to this route (in browser): http://localhost:<container_binding_port>/healthcheck

## Composer scripts

There are several scripts in composer:

How to run:

```
docker exec -it <container_name> php composer.phar <script_name>
```

### Auto Scripts
Scripts that should be executed automatically each time together. You can run
them manually by doing:
> composer auto-scripts

### Post Install CMD
Those scripts in here will be executed after the `install` command has been
executed with a lock file present.

### Post Update CMD
Those scripts in here will be executed after the `update` command is executed or
before the `install` command is executed without a lock file present

### PHP CS
With this command you can know what changes you should do to your code to fix code
style. You can run it by executing:
> composer cs

### PHP CS FIX
With this command you can fix php code style automatically by executing:
> composer cs-fix

### STAN
With this command you can run the static analysis tool phpstan by executing:
> composer stan

### php CS FIX  + STAN
With this command you can fix your code style and run the static analysis tool phpstan
by executing:
> composer csstan

## Run tests

```
docker exec -it <container_name> php bin/phpunit
```

## Run console command

```
docker exec -it <container_name> php bin/console <command_name>
```

## Run symfony command

```
docker exec -it <container_name> symfony <command_name>
```

## View the syslog:

```
tail -f var/log/syslog.log
```

## Stop service

```
docker-compose -f docker-compose-dev.yml down
```
# Run the integration tests environment

This command run the application containers in a separate docker network for the integration tests.

When you build the image, the content of your project is copied into the container so it is the code you have locally that is tested.

```
docker-compose -f docker-compose-test.yml up -d --build
```

# TODO:
- [x] Add Auth Middleware:
- [x] Add Transaction Middleware
- [x] Add DomainEventStorer
- [x] Add Healthcheck
- [x] Change Postgres to MySQL
- [ ] Fix errors from cs-fixer, phpstan
- [ ] Do Make file
- [ ] Add more tests
- [ ] Migrate to PHP 8.2
- [ ] List files to delete according to type of microservice