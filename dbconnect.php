<?php

define('DB_HOST', 'ballast.proxy.rlwy.net');
define('DB_USER', 'root');
define('DB_PASS', 'cKISDkKhTThUFiXSZAhpfNKGCebSJOvT');
define('DB_NAME', 'railway');
define('DB_PORT', 28772);

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
?>