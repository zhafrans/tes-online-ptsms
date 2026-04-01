# PT SMS - Backend Developer Test
Created by: Programmer Candidate (Test Online)

## Setup Instructions

1. **Environment Setup**
    Ensure you have PHP 8.2+ and Composer installed. The database is assumed to be accessible as per your `docker` setup.
    ```bash
    cp .env.example .env
    # Adjust your .env variables accordingly if not already set
    ```

2. **Install Dependencies**
    ```bash
    composer install
    ```

3. **Run Migrations & Seeders**
    To run the migrations including the stored procedure, and seed the default test user:
    ```bash
    php artisan migrate:fresh --seed
    ```

4. **Serve Application**
    ```bash
    php artisan serve
    ```


### Test User Credentials
- **Email:** test@example.com
- **Password:** password

