# Edurio test task

Implementation of Edurio test task. Task description included in `Edurio_back-end_test_task_2019_v2.pdf`

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

You'll need to have [docker-compose](https://docs.docker.com/compose/install/) installed in order to run this project.

### Installing

From the project root, go to the docker/ directory:

`cd ./docker/`

Start up docker-compose as daemon:

`docker-compose up -d`

Then run Composer to install the dependencies (this has to be done after the volumes have been mounted - not possible from a Dockerfile during the build stage):

`docker exec -it docker_php_1 composer install`

## Usage

Once you have the project set up and running, go to `http://localhost:8080/dbs/foo/tables/source` in your browser.

## Built With

* [Slim](http://www.slimframework.com/docs/) - The web framework used
* [Composer](https://getcomposer.org/doc/) - Dependency Management
* [Docker Compose](https://docs.docker.com/compose/) - Containerisation
