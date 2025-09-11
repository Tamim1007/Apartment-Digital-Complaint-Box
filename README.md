# Apartment Digital Complaint Box

A PHP + MySQL web app for apartment residents and admins to manage complaints, comments, and announcements.

## Quick Start (XAMPP)

1. Copy the entire folder `apartment-digital-complaint-box` into your XAMPP `htdocs` directory.
2. Start Apache and MySQL from XAMPP Control Panel.
3. Open phpMyAdmin and create a database named **apartment_box** (utf8mb4).
4. Import `sql/schema.sql` into the `apartment_box` database.
5. (Optional) Edit database credentials in `includes/db.php` if your MySQL user/password differ.
6. In your browser, visit: `http://localhost/apartment-digital-complaint-box/`
7. Register as a **User** or **Admin**.
   - To register as Admin, you'll need the Admin Registration Code: **ADMIN123** (change in `register.php` and/or use ENV var ADMIN_CODE).
8. Log in and explore:
   - Users: Submit complaints, comment on them, and read admin announcements.
   - Admins: Manage all complaints, comment/respond, and post announcements.

## Default Technology Stack

- Frontend: HTML5, CSS3, Vanilla JS
- Backend: PHP (PDO, prepared statements)
- Database: MySQL
- Server: XAMPP (Apache + MySQL)

## Folder Structure

```
apartment-digital-complaint-box/
├─ admin/
│  ├─ announcements.php
│  ├─ complaints.php
│  └─ dashboard.php
├─ assets/
│  ├─ css/style.css
│  └─ js/app.js
├─ includes/
│  ├─ auth.php
│  ├─ db.php
│  ├─ functions.php
│  ├─ header.php
│  └─ footer.php
├─ user/
│  ├─ announcements.php
│  ├─ complaint_view.php
│  └─ complaints.php
├─ sql/schema.sql
├─ dashboard.php
├─ index.php
├─ register.php
├─ logout.php
└─ README.md
```

## Security Notes

- Passwords are hashed using `password_hash()`.
- All SQL uses prepared statements (PDO).
- Output is escaped via `e()` helper in `includes/functions.php`.
- CSRF tokens are **not** implemented in this demo. Add them in production.

## Common Tweaks

- Change admin code: set `putenv('ADMIN_CODE=YourSecret')` in `includes/auth.php` or system env; default fallback is `ADMIN123`.
- Update site styles in `assets/css/style.css`.
