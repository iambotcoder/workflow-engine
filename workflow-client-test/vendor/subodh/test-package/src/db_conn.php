<?php

require '../src/rb-mysql.php';

// Setup the database connection
R::setup('mysql:host=localhost;dbname=workflow_engine', 'root', ''); // Replace 'username' and 'password' with your database credentials

if (!R::testConnection()) {
    die('Failed to connect to the database');
}

?>
