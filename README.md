Contact handler setup
=====================

Files:
- `contact.php` - receives form POST (name, email, subject, message), stores in DB, and attempts to email site owner.
- `db.php` - PDO connection used by `contact.php`.

Installation / configuration:
1. Install dependencies with Composer (from project root):

   composer install

   This will install PHPMailer as configured in `composer.json`.

2. Configure SMTP options at the top of `databases/contact.php`:
   - `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME`
   - `SMTP_HOST`, `SMTP_PORT`, `SMTP_USERNAME`, `SMTP_PASSWORD`, `SMTP_SECURE`

3. Ensure your webserver can send mail (either via SMTP credentials above or PHP's `mail()` function).

4. Create a `users` table in your `tata` database with columns matching the insert used in `contact.php`:

   CREATE TABLE users (
     id INT AUTO_INCREMENT PRIMARY KEY,
     name VARCHAR(255) NOT NULL,
     email VARCHAR(255) NOT NULL,
     subject VARCHAR(255),
     message TEXT,
     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );

Usage:
- The contact form in `8.html` posts to `databases/contact.php`. After submission the user is redirected back to `8.html` with a session message stored in `$_SESSION['message']`.
