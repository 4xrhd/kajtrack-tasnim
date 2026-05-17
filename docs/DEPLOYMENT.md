# 🚀 KajTrack Deployment & Environment Setup Guide

This guide details the procedures for deploying KajTrack in local zero-config developer sandboxes and hardened production LAMP stacks.

---

## 💻 1. Local Zero-Config Development (Fast Track)

KajTrack is designed to run out-of-the-box using standard PHP's built-in server and a self-seeding SQLite database. No database engine installation or migration setups are required.

### 👣 Step-by-Step Instructions
1. **Clone or Download** the KajTrack directory.
2. Ensure you have **PHP 8.x** (or higher) installed on your system.
3. Open a terminal, navigate to the KajTrack project root, and execute:
   ```bash
   php -S 127.0.0.1:8000
   ```
4. Access the login screen at:
   ```
   http://127.0.0.1:8000/login.php
   ```
   > [!TIP]
   > The system will automatically build `/database/task_management_db.sqlite` and seed all default employee and admin accounts with the default password `123`.

---

## 🏛️ 2. Apache & XAMPP Local Deployment

If you are using XAMPP or a local Apache installation:

1. **Move Folder**: Place the `kajtrack` folder into your web root directory (e.g. `/opt/lampp/htdocs/kajtrack` on Linux or `C:\xampp\htdocs\kajtrack` on Windows).
2. **Access Database Panel**: Start Apache and MySQL, then open `http://localhost/phpmyadmin`.
3. **Database Creation**: Create an empty database named `task_management_db`.
4. **Import Schema**: Select `task_management_db`, click the **Import** tab, upload `/database/task_management_db.sql`, and click **Go**.
5. **Credentials Alignment**: Open `/config/DB_connection.php` and verify/align the MySQL credentials parameters:
   ```php
   $host = "localhost";
   $user = "root";       // Default MySQL user
   $password = "";       // Default MySQL password
   $db_name = "task_management_db";
   ```
6. **Open Dashboard**: Access the application at `http://localhost/kajtrack/login.php`.

---

## 🌐 3. Hardened Production LAMP Deployment

Follow these standard practices when deploying KajTrack to public-facing cloud instances:

### ⚙️ Server Configuration
1. **Document Root**: Point your virtual host's root directly to the project folder. Ensure directory listing is disabled (`Options -Indexes`).
2. **Required Extensions**: Make sure the following PHP extensions are enabled:
   - `pdo`
   - `pdo_mysql` (for production MariaDB/MySQL)
   - `pdo_sqlite` (if using local fallbacks)
   - `session`
3. **Folder Permissions**: Make sure the `/database/` directory is write-accessible to the web server user (`www-data` on Ubuntu) to permit SQLite failover creation if necessary:
   ```bash
   chown -R www-data:www-data /var/www/html/database
   chmod -R 775 /var/www/html/database
   ```

---

## 🔒 4. Production Hardening Checklist

> [!CAUTION]
> Before going live, make sure to execute all of the following hardening steps:

- [ ] **Enforce TLS**: Set up an SSL certificate (e.g. Let's Encrypt) and force all traffic to HTTPS.
- [ ] **Change Passwords**: Immediately reset default account passwords (`admin`, `john`, etc.) through the user management interface.
- [ ] **Secure Cookies**: In production, configure secure session cookies by editing `bootstrap.php` or `php.ini` to set:
  ```php
  ini_set('session.cookie_secure', 1);
  ini_set('session.cookie_httponly', 1);
  ini_set('session.use_only_cookies', 1);
  ```
- [ ] **DB User Pruning**: Restrict DB user credentials to grant minimum necessary permissions (`SELECT`, `INSERT`, `UPDATE`, `DELETE`) on `task_management_db` only.
- [ ] **Error Visibility**: Disable user-facing display errors. In `bootstrap.php` or `php.ini`, enforce:
  ```php
  ini_set('display_errors', 0);
  ini_set('log_errors', 1);
  ```
