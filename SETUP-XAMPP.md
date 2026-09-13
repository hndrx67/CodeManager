# Code Manager — XAMPP setup

This Laravel 12 application supports PHP 8.2 as bundled with the current XAMPP install.

1. Start Apache and MySQL in XAMPP.
2. In phpMyAdmin, create `code_manager` if it does not already exist. Existing users are preserved and missing Laravel columns are added by the migration.
3. From `C:\xampp\htdocs\codemanager\laravel-app`, run:

   ```powershell
   C:\xampp\php\php.exe artisan migrate
   C:\xampp\php\php.exe artisan storage:link
   ```

4. For the simplest local launch, run:

   ```powershell
   C:\xampp\php\php.exe artisan serve
   ```

   Open `http://127.0.0.1:8000`.

For Apache-only use, browse to `http://localhost/codemanager/laravel-app/public`. In production, configure Apache's document root to the `public` directory so `.env`, source, and storage are never web-accessible.

The app reads MySQL settings from `.env`. Change `DB_USERNAME` and `DB_PASSWORD` there if your XAMPP MySQL credentials differ.

## Grant the first administrator

After running the migrations, mark one trusted existing account as an administrator in phpMyAdmin:

```sql
UPDATE users SET is_admin = 1 WHERE username = 'your_username';
```

That user will see the **Admin Dashboard** sidebar tab and can grant or revoke administrator access for other accounts.

## Public snippet sharing

Snippets are private by default. Select **Share with the community** when creating or editing a snippet to publish it on the landing page and authenticated Home feed. Clearing the option removes it from both public feeds.
