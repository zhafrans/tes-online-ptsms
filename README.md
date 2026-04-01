# PT SMS - Backend Developer Test
Created by: Programmer Candidate (Online Test)

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
    To run the migrations and seed the default test user:
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

## Architecture & Best Practices

### 1. Unified Response (ApiResponse Trait)
To maintain consistency across JSON responses, this project uses the `ApiResponse` trait located in `app/Traits/ApiResponse.php`.
*   **Success Response Structure:** `{ "success": true, "data": [...], "message": "..." }`
*   **Error Response Structure:** `{ "success": false, "message": "...", "errors": [...] }`

### 2. Form Request Validation
Validation logic is extracted from the Controller to keep the code clean (Clean Code) and follow the *Single Responsibility* principle. All validation rules are defined in the `app/Http/Requests/` directory:
*   `LoginRequest.php`
*   `StoreProductRequest.php`
*   `UpdateProductRequest.php`
*   `StorePurchaseRequest.php`
*   `ReportPurchaseRequest.php`

### 3. Database Transactions
The `store` method in the `PurchaseController` is wrapped in `DB::beginTransaction()` to ensure data integrity. If any item fails to save, the entire transaction (including the main purchase data) will be automatically rolled back.

### 4. Eager Loading & Pagination
*   Uses `with('items.product')` to avoid N+1 query issues.
*   Implementation of `paginate(10)` on Product and Purchase lists for better performance.
