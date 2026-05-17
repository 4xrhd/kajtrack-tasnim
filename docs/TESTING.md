# 🧪 KajTrack Quality Assurance & Testing Guide

This guide details the QA protocols, automated backend integration scripts, and manual UI regression verification checklists used to maintain KajTrack.

---

## ⚙️ 1. Automated Integration Testing

KajTrack includes a built-in automated backend integration script to verify database connectivity, model queries, metrics calculations, and authentication parameters without manual browser clicking.

### 👣 How to Run the Backend Suite
1. Ensure your local PHP server or standard LAMP environment is active.
2. In the project root, write a quick testing script or run the automated model checkpoints:
   ```bash
   php -f test_login.php
   ```
3. The test runner will automatically report on:
   - **Database Driver Matching**: Success of MySQL connection or SQLite fallback.
   - **User Count operations**: Confirms database table seeds match structural expectations.
   - **Task Filter Operations**: Confirms date parameters match standard `YYYY-MM-DD` and overdue flags are computed correctly.
   - **Authentication and BCrypt Hash Validation**: Confirms matching for default user credentials (`tasnim`, `admin`, `mitu`, and `john` with password `123`) and rejection of incorrect passwords.

---

## 👑 2. Administrator Manual Regression Checklist

Log in as the **Administrator** (`username: admin` | `password: 123`) and verify the following operational paths:

- [ ] **Dashboard Counters**: Verify that **Employees**, **Total Tasks**, **Due Today**, and **Overdue** metrics load immediately with high-contrast indicator glowing colors.
- [ ] **User Additions**: Go to **Manage Users** -> **Add Employee**. Submit valid credentials. Ensure the new employee appears in the table with role-aware controls.
- [ ] **User Deletions**: Click **Delete** on a test employee. Confirm that the employee is pruned from the system and that any assigned tasks are gracefully set to `Unassigned` instead of crashing.
- [ ] **Task Creation**: Go to **Create Task**. Populate title, deadline, and assign it to an employee. Confirm successful redirect and database write.
- [ ] **Dynamic Backlog Filters**: Go to **All Tasks**. Toggle dynamic filters:
  - *Due Today*
  - *Overdue*
  - *No Deadline*
  Confirm tasks are sorted and filtered correctly across SQLite and MySQL engines.

---

## 👤 3. Employee Manual Regression Checklist

Log in as an **Employee** (e.g. `username: mitu` | `password: 123`) and verify the following:

- [ ] **Personal Metrics**: Dashboard counters should only show tasks assigned specifically to the logged-in employee.
- [ ] **Work Backlog**: Ensure **My Tasks** displays only the items belonging to the current user's profile.
- [ ] **Progress Updates**: Open a task details page and toggle progress states (*Pending*, *In Progress*, *Completed*). Save changes, then verify the updated state is reflected correctly in both employee list and admin panel.
- [ ] **Self Profile Updates**: Go to **Profile** -> **Edit Profile**. Submit a password change request. Log out, then log in using your new credentials to confirm the BCrypt encryption matches.

---

## 🔔 4. Asynchronous Alerts & Notifications Checklist

- [ ] **Badge Count**: Assign a new task from the administrator panel to `mitu`. Keep `mitu` logged in on a second tab. Observe the bell icon's notification badge update asynchronously within 5 seconds.
- [ ] **Bell Dropdown**: Click the bell icon. A dynamic dropdown list should load.
- [ ] **Read State Toggle**: Click on an unread notification link. Ensure it marks the notification as read in the database and redirects the employee to their dashboard.

---

## 🛡️ 5. Edge-Case Validation & Negative Tests

- [ ] **Empty Logins**: Try submitting empty fields on `/login.php`. Ensure validation catches the event and returns `?error=Incorrect username or password`.
- [ ] **Unauthorized Views**: Attempt to navigate directly to `/views/user.php` when logged in as a normal employee. Ensure you are instantly intercepted and returned to `/login.php` or your employee dashboard.
- [ ] **Session Expirations**: Delete your browser's session cookies and attempt to access any dashboard path. Ensure you are redirected to login with a warning.
