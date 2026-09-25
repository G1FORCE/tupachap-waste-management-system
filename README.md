# TupaChap Waste Management System

TupaChap is an on-demand waste collection platform for Dar es Salaam, Tanzania. This repository contains the Laravel web application: a public product site and web interfaces for system administration, collector management, and customer account services.

## Features

- Public landing page, service information, and legal pages
- Admin dashboard for collector verification, platform analytics, and fleet monitoring
- Collector dashboard for fleet, driver, and performance management
- API client and WebSocket support for live platform data
- Account deletion request page for customer and waste-picker roles

The dashboards expect a separately running TupaChap API for authentication and operational data. The local Laravel login submission is currently a placeholder, so the dashboard API integration should be configured through environment variables.

The account deletion page is also demo-only: submitting it saves a request message but does not delete an account.

## Technology

- PHP 8.3+ and Laravel 13
- SQLite by default (configurable through Laravel environment settings)
- Vite, Tailwind CSS, and JavaScript
- Axios for API requests; WebSockets for live updates

## Getting Started

### Requirements

- PHP 8.3 or later with the extensions required by Laravel
- Composer
- Node.js and npm

### Install

From the project root, install the PHP dependencies and create the local environment file:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

The default `.env.example` uses SQLite. Create the database file and run the migrations:

```bash
touch database/database.sqlite
php artisan migrate
```

Install frontend dependencies and compile the assets:

```bash
npm install
npm run build
```

### Run Locally

Start the Laravel server:

```bash
php artisan serve
```

Open [http://localhost:8000](http://localhost:8000). During frontend development, run Vite in a second terminal:

```bash
npm run dev
```

Alternatively, `composer run dev` starts Laravel, the queue listener, log tailing, and Vite together.

## API Configuration

The frontend uses `https://tupachap-engine.onrender.com` as its default API host and the corresponding `wss://` host for WebSockets. To point the web app at another environment, set these in `.env`:

```dotenv
VITE_API_URL=https://your-api-host.example
VITE_WS_URL=wss://your-api-host.example
```

The configured API must provide the endpoints and WebSocket events consumed by the dashboards. See [DASHBOARD_IMPLEMENTATION.md](DASHBOARD_IMPLEMENTATION.md) for the integration details.

## Tests

Run the Laravel test suite with:

```bash
php artisan test
```

## Project Layout

```text
app/                 Laravel controllers, models, and providers
database/            Migrations, factories, and seeders
resources/views/     Blade pages and dashboard views
resources/js/        API clients, dashboard scripts, and utilities
resources/css/       Application styles
routes/              Web and console routes
tests/               PHPUnit feature and unit tests
docs/                Product and platform documentation
```

## Contributing

Contributions are welcome. Open an issue to discuss a significant change, then submit a pull request with a clear description and relevant tests.

## License

This project is open-source and available under the MIT license.
