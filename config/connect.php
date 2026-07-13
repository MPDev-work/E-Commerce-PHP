<?php

$conn = new mysqli("localhost:3307", "root", "admin1221!", "webstore_db");
if ($conn->connect_error) {
    die("Connection Filed" . $conn->connect_error);
}