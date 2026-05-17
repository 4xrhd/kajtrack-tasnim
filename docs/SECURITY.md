# 🛡️ KajTrack Security & Hardening Reference

This document highlights the built-in defenses, authorization matrices, security safeguards, and structural hardening guidelines implemented in KajTrack.

---

## 🔒 1. Core Security Controls

The application enforces security-in-depth across the following layers:

### 🔑 Cryptographic Password Hashing
- Passwords are never stored in plain-text.
- User accounts created inside `/handlers/add-user.php` or modified in `/handlers/update-user.php` / `/handlers/update-profile.php` are dynamically encrypted using **BCrypt** (`PASSWORD_BCRYPT` inside PHP's native `password_hash()` library).
- Authentication verification is processed exclusively via cryptographically secure timing-attack-safe `password_verify()`.

---

### 🛡️ SQL Injection Prevention
- The data access layer `/models/` is completely decoupled from visual templates.
- **Prepared Statements**: All SQL operations use PDO parameterized bindings. User inputs are never concatenated directly into raw query strings.
- Example:
  ```php
  $sql = "SELECT * FROM users WHERE username = :username";
  $stmt = $conn->prepare($sql);
  $stmt->execute(['username' => $username]);
  ```

---

### 👤 Role-Based Session Gating
- Views are protected using session-based verification gates at the top of each view file.
- Unauthorized or unauthenticated attempts are rejected and immediately redirected back to `/login.php?error=First login`.
- If an employee tries to access an administrative page (e.g. `/views/user.php`), the routing gate catches the invalid `$_SESSION['role']` state, kills the execution, and redirects the client to their respective landing page.

---

## 🚦 2. Authorization Security Matrix

The table below describes route authorization constraints enforced across the platform:

| Endpoint | Guest Access | Employee Access | Admin Access | Enforcement Mode |
| :--- | :---: | :---: | :---: | :--- |
| `/login.php` | ✅ Allowed | 🔄 Auto-Redirect | 🔄 Auto-Redirect | Direct View Gate |
| `/index.php` | ❌ Rejected | ✅ Allowed (Self Only)| ✅ Allowed (Global) | Session Validation |
| `/my_task.php` | ❌ Rejected | ✅ Allowed (Self Only)| ✅ Allowed (Admin Work) | Session Validation |
| `/user.php` | ❌ Rejected | ❌ Redirected | ✅ Allowed | Role Gate (`role === 'admin'`) |
| `/add-user.php` | ❌ Rejected | ❌ Redirected | ✅ Allowed | Role Gate (`role === 'admin'`) |
| `/tasks.php` | ❌ Rejected | ❌ Redirected | ✅ Allowed | Role Gate (`role === 'admin'`) |
| `/edit-task.php`| ❌ Rejected | ❌ Redirected | ✅ Allowed | Role Gate (`role === 'admin'`) |
| `/profile.php` | ❌ Rejected | ✅ Allowed (Self Only)| ✅ Allowed (Admin User) | Ownership Enforcement |

---

## ⚡ 3. Recommended Hardening Checklist for Production

When transitioning KajTrack to a public web server, enforce these critical settings:

### 🌐 1. Secure Session Cookie Flags
Modify `bootstrap.php` or configure `php.ini` to enforce secure cookie delivery:
```php
ini_set('session.cookie_secure', 1);       // Only transmit session IDs over HTTPS
ini_set('session.cookie_httponly', 1);     // Block Javascript document.cookie access
ini_set('session.use_only_cookies', 1);    // Prevent session ID passing in URLs
```

### 🏷️ 2. Web Server Directives
Ensure directory indexing is disabled in your Apache `.htaccess` or virtual host configuration:
```apache
Options -Indexes
```

### 📢 3. Error Exposure Limits
Disable client-side error displays to prevent path disclosures and stack traces:
```php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
```
