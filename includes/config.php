<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'sneaker_store');
define('DB_USER', 'root');
define('DB_PASS', '');
define('BASE_URL', '/sneaker-store');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
