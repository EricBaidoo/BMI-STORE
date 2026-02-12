# BMI Bookshop (PHP + Bootstrap scaffold)

This is a minimal PHP + Bootstrap bookshop scaffold intended for development with XAMPP.

How to run

1. Make sure this folder (`BMI STORE`) is in `C:\xampp\htdocs`.
2. Start Apache from the XAMPP Control Panel.
3. Open http://localhost/BMI%20STORE/ (or http://localhost/BMI STORE/) in your browser.

Notes

- Data is stored in `data/books.json` by default. To use MySQL, edit `config.php` and set `db.name`/`db.user`/`db.pass`, then run the schema in `data/schema.sql`.
- Admin login now supports MySQL. If the `admins` table is empty, you can log in once with the credentials in `config.php` to seed the admin account automatically.
