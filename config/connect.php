<?php

$conn = new mysqli("localhost:3307", "root", "123456", "php_3_5");

if ($conn->connect_error) {
    die("Connection Filed" . $conn->connect_error);
}