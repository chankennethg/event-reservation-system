# Event Reservation System
Simple implementation of an event reservation system

Built with the following technologies
- [Laravel 10](https://laravel.com/)
- [Vue.js 3](https://vuejs.org/)
- [Tailwind](https://tailwindui.com/)
- [MySQL](https://www.mysql.com/)
- [Docker](https://www.docker.com/)

*Postman Documentation included*

## Prerequisite
This project utilizes a Makefile to automate builds and tasks.

If you dont have make in your machine you can copy paste the commands in Makefile

You may run `make help` to list available commands. 

## Setup
1. Clone this repo
2. Generate .env file via `make env-dev`, configure as needed.
3. Build and start docker images by running the following commands in order:
```bash
make build
make start
make composer-install
make npm-install
make npm-build

# Optional for Vite HMR
make npm-run-dev
```

5. Setup DB Migration and seed.
```bash
make migrate
make seed
```

6. Generate key for the application
```bash
make key-generate
```

7. App is accessible thru [http://localhost](http://localhost)
```bash
Default user
Username: default@app.dev
Password: password
```

## Starting/Stopping the environment
```bash
make start
make stop
```
## Checking code standards (PHPStan & PHPInsights)

```bash
make phpstan
make phpinsights
```

## Running the test cases

```bash
make phpunit
```

## User Stories Implemented
- Database Migrations, seeders and factories.
- Usage of Laravel Sanctum for API Authentication.
- Middleware protection.
- Users Endpoint (Login, Register).
- Events Endpoint (Create, List).
- Tickets Endpoint (Create, List).
- Frontend Implementation.
    - Login/Logout
    - Registration
    - Event list
        - Available
        - User Reserved
    - Event Reservation

## Future Improvements / Todo
- Blacklisting invalidated JWT tokens.
- Additional Feature Tests.
- Update and Delete for existing endpoints.
- Improve implementation of dates in API. Migrate to iso8601 from datetime to support multiple timezones.
- Support for other global currency.
- Admin Management.
- Map support for location.
- Separation of frontend and backend.
- Email Confirmation.
- Queue support.


## License
[The MIT License (MIT)](LICENSE)
