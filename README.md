# Web Systems and Technologies: Five Use Cases
**JOHN DARWIN A. ANTONIO** | CETC, Information Technology, 3rd Year

Five record systems built with HTML, PHP, MySQL, and PDO. Each creates, lists, views, edits, and deletes records.

| # | System | Database | Case rule |
| --- | --- | --- | --- |
| 1 | [Equipment Information System](equipment-system/) | `equipment_db` | Quantity is a whole number, 0 or more |
| 2 | [Activity Registration Information System](activity-registration-system/) | `activity_registration_db` | Status is Registered or Withdrawn |
| 3 | [Employee Information System](employee-system/) | `employee_db` | Valid email; shift 1 to 4 |
| 4 | [Vehicle Rental Information System](vehicle-rental-system/) | `vehicle_rental_db` | Return date is later than start date |
| 5 | [Service Information System](service-system/) | `service_db` | Fee has up to 2 decimals; slots are whole numbers |

## Run locally (XAMPP)
1. Copy the contents of this repository into `htdocs`.
2. Start Apache and MySQL.
3. Import `database/all_databases.sql` in phpMyAdmin (once, on a fresh setup).
4. Check the credentials in each `config.php` (default: `root`, empty password).
5. Open, for example, http://localhost/equipment-system/index.php

GitHub Pages cannot run PHP, so use a local server or PHP/MySQL hosting.

## Structure
Each system folder has `config.php`, `index.php`, `create.php`, `view.php`, `edit.php`, `delete.php`, `database.sql`, and `README.md`. `assets/crud.css` is optional styling.

All sample records are fictional. Do not commit real database passwords.
