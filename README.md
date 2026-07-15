# Tupachap Waste Management System

Tupachap is a Laravel-based waste collection and tracking platform for connecting residents, collectors, and administrators. Users can request pickup, track collection progress in real time, and complete payment flows for completed collections.

## Features

- User pickup requests for waste collection
- Collector dashboard for managing assigned requests
- Live tracking map and status updates
- Mobile money / M-Pesa payment initiation and demo payment flow
- Role-based access for users, collectors, and admins

## Tech Stack

- Laravel 13
- Livewire
- Tailwind CSS
- Vite
- PostgreSQL with PostGIS support
- Reverb / broadcasting

## Requirements

- PHP 8.3+
- Composer
- Node.js and npm
- PostgreSQL with the PostGIS extension enabled

## Installation

1. Clone the repository
   ```bash
   git clone <your-repo-url>
   cd tupachap-app
   ```

2. Install PHP dependencies
   ```bash
   composer install
   ```

3. Install frontend dependencies
   ```bash
   npm install
   ```

4. Create your environment file
   ```bash
   cp .env.example .env
   ```

5. Configure your database settings in .env for PostgreSQL/PostGIS.

6. Generate the application key and run migrations
   ```bash
   php artisan key:generate
   php artisan migrate
   ```

7. Build frontend assets
   ```bash
   npm run build
   ```

8. Start the app
   ```bash
   php artisan serve
   ```

## Development

For local development with Vite, queue workers, and broadcast services, you can use:

```bash
composer run dev
```

## Testing

```bash
php artisan test
```

## License

This project is open-source and available under the MIT license.
