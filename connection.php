<?php
    $conn = new mysqli("localhost:3307", "root", "123456", "php_3_5");

    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}