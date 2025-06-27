# Project Title (e.g., My Awesome App)

This project is a web application built with the TALL stack's modern cousin: a **Laravel** backend serving a **Vue 3** frontend via **Inertia.js**, with **Vuetify 3** for the component library.

## Overview

This repository contains the source code for the application. The `publish` directory holds the distributable/deployable code after a build process.

## Tech Stack

- **Backend**: Laravel 10, PHP 8.1+
- **Frontend**: Vue 3, Inertia.js, Vuetify 3
- **Build Tool**: Vite

## Getting Started

Follow these instructions to get a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

Before you begin, ensure you have the following installed:

- PHP >= 8.1
- Composer
- Node.js >= 18.0
- A database server (e.g., MySQL, PostgreSQL)

### Local Development Setup

1.  **Clone the repository:**

    ```bash
    git clone <repository-url>
    cd <project-directory>
    ```

2.  **Install PHP dependencies:**

    ```bash
    composer install
    ```

3.  **Install JavaScript dependencies:**

    ```bash
    npm install
    ```

4.  **Setup environment file:**

    ```bash
    cp .env.example .env
    ```

5.  **Generate application key:**

    ```bash
    php artisan key:generate
    ```

6.  **Configure your `.env` file:**
    Open `.env` and set your `DB_*` connection details and `APP_URL`.

7.  **Run database migrations:**

    ```bash
    php artisan migrate
    ```

8.  **Run the development servers:**
    In one terminal, run the Vite development server:
    ```bash
    npm run dev
    ```
    In a second terminal, run the PHP development server:
    ```bash
    php artisan serve
    ```

You can now access the application at the URL provided by `php artisan serve` (usually `http://127.0.0.1:8000`).

## Code Style & Linting

This project uses Laravel Pint for PHP and ESLint/Prettier for JavaScript to maintain code quality.

- **Check PHP Style:**
  ```bash
  ./vendor/bin/pint --test
  ```
- **Fix PHP Style:**
  ```bash
  ./vendor/bin/pint
  ```
- **Check JS/Vue Style:**
  ```bash
  npm run lint
  ```

## Building for Production

To compile the frontend assets for production, run:

```bash
npm run build
```
# publish.project
