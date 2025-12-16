<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'dramaland_db');

// Site Configuration
define('SITE_NAME', 'DramaLand');
define('SITE_URL', 'http://localhost/dramaland');

// Session Configuration
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Timezone
date_default_timezone_set('Asia/Colombo');
?>