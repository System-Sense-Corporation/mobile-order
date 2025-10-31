# Mobile Order

This project uses Laravel. Database data files are not tracked in Git so you can manage your own local environment.

## Database setup

1. Ensure your `.env` is configured to use the SQLite connection that points to `database/database.sqlite` (Laravel's default configuration works once the file exists).
2. Create the SQLite database file if it does not exist:
   ```bash
   touch database/database.sqlite
   ```
3. Run the migrations and seeders to build the schema, including the latest `orders` table structure:
   ```bash
   php artisan migrate --seed
   ```

If you need to rebuild the database, remove `database/database.sqlite` locally and repeat the steps above.
