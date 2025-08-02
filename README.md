# Sedame MVC Project

This project is a lightweight PHP MVC (Model-View-Controller) project designed to help in understanding the core concepts and implementation of the MVC architectural pattern.

## Technologies Used

*   **PHP**: The primary programming language for the application logic.
*   **Composer**: Used for dependency management.
*   **SQLite**: A file-based relational database used for data storage.
*   **PHPUnit**: The testing framework for PHP unit tests.
*   **MVC Architecture**: The foundational design pattern structuring the application.
*   **Frontend Assets**: Includes basic JavaScript (Alpine.js) and CSS (Tailwind.js) for client-side functionality and styling.

## Getting Started

### Prerequisites

*   PHP (version 8.4 or higher recommended)
*   Composer

### Installation

1.  Navigate to the project root directory.
2.  Install PHP dependencies using Composer:
    ```bash
    composer install
    ```

### Database Setup

The project uses SQLite. The database file `db/db.sqlite` should be present. If you need to initialize or reset the test database, you can run:
```bash
php db/init_test_db.php
```

### Running the Application

To start the PHP development server, run the following command from the project root:
```bash
composer run dev
```
After starting the server, open your web browser and navigate to `http://localhost:3333`.

### Running Tests

To execute the test suite, use the Composer script:
```bash
composer run test
```
