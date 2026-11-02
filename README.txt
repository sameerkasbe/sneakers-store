SNEAKER STORE - PHP + MariaDB (XAMPP)
1. Extract sneaker-store.zip into C:\xampp\htdocs\ so the folder is C:\xampp\htdocs\sneaker-store.
2. Start Apache and MySQL in XAMPP.
3. Open http://localhost/phpmyadmin, choose Import, and import database/sneaker_store.sql.
4. Check database settings in includes/config.php (default XAMPP: root with blank password).
5. Open http://localhost/sneaker-store/

Admin login: admin@sneakerstore.com
Password: admin123

Features: PHP includes/components, PDO MariaDB connection, registration/login, MD5 as required for class, sessions, protected admin pages, product CRUD/image upload, dynamic catalogue, cart, checkout/order records, order status and customer list.

MD5 is weak and is included only for the academic requirement. For real production systems use password_hash() and password_verify(). This demo checkout records orders but does not process payments.
If you rename the project folder, update BASE_URL in includes/config.php.
