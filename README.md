# Phone Book API (phone-book-api)

This is a RESTful API based on Symfony `6.1` that serves the following actions to use by frontend phone-book-ui project:

## Supported actions in `phone-book-api`

- `Add other customers as contact`,
- `Edit created contacts.`,
- `Delete existing contacts`,
- `Search for contacts by name`,

# Installation

There are two options to install this API and make it ready to use:

## 1. Install by Docker Compose (Recommended)

1.  You must already have Docker installed, to install docker follow instuctions in [install docker](https://docs.docker.com/engine/install/) link to install the latest version of `docker` and `docker compose` based on your operating system.

2.  Then you need to clone the project with the following command:

        $ git clone https://github.com/msh-shf/phone-book-api.git

3.  Change your directory in order to point to the project directory that you've just cloned and run following command:

        $ docker compose up -d

4.  That's it. Enjoy using API and read the next section to know what docker provides.

### What docker provides for us:

- install `mariadb:10.5.9` and expose port `33016` (db_server service in docker-compose.yml)
- install `phpmyadmin` to manage database on port `8001`, accessible from `http://localhost:8001` (db_admin service in docker-compose.yml)
- install nginx and make configuration to serve API on port `8000`, accessible from `http://localhost:8000` (server service in docker-compose.yml)
- install PHP version `8.1` that is required by Symfony version `6.1`
- install composer version `2.1`
- execute `composer install` command in docker container to install symfony dependencies and make project ready to use.
- create `dev` and `test` databases
- run `migrations` to create database tables in both `test` and `dev` environments
- load `fixtures` to import some dummy data to tables in both `test` and `dev` environments

## 2. Install Manually

1.  Then you need to clone the project with the following command:

        $ git clone https://github.com/msh-shf/phone-book-api.git

2.  install `PHP 8.1` that is compatible with `Symfony 6.1`

3.  install composer version `2.1` or above,
    then change the directory to point to `codebase-api` directory and run the following command to install symfony dependencies:

            $ composer install

4.  install MySQL database and modify `DATABASE_URL` key in `/codebase-api/.env` and `/codebase-api/.env.test` based on your database connection.

5.  install your desired web server like `Nginx` to serve the project on port `8000` (or whatever ports you want) or instead of installing a webserver you can use Symfony local web server like so:

        $ php bin/console server:start --port=8000

    if you use `Nginx` as web server, then you can use the `phone-book-api.conf` file that included in the project. The `phone-book-api.conf` directory path is (based on the root of project):

    `/docker/server/nginx/phone-book-api.conf`

6.  create `database`, run `migrations` to create tables and load `fixtures` to import sample data:

    to create `dev` database:

        $ php bin/console doctrine:database:create

    to create `test` database:

        $ php bin/console doctrine:database:create --env=test

    to run `migration` for `dev` environment:

        $ php bin/console doctrine:migrations:migrate

    to run `migration` for `test` environment:

        $ php bin/console doctrine:migrations:migrate --env=test

    to load `fixture` for `dev` environment:

        $ php bin/console doctrine:fixtures:load

    to load `fixture` for `test` environment:

        $ php bin/console doctrine:fixtures:load --env=test

## API Routes:

After installing the project, you can access the following routes in the API:

| Method   | Route           | Description                                                                                           |
| -------- | --------------- | ----------------------------------------------------------------------------------------------------- |
| `GET`    | `/contact`      | List contacts with features for search names and pagination with query params (?name=&page=1&size=10) |
| `POST`   | `/contact`      | Create a new contact                                                                                  |
| `PUT`    | `/contact/{id}` | Update the information of a contact with a given id                                                   |
| `GET`    | `/contact/{id}` | Show the information of a contact with a given id                                                     |
| `DELETE` | `/contact/{id}` | Delete a contact with a given id                                                                      |
