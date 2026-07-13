<?php

$conn = new mysqli("localhost", "root", "admin1221!", "webtore_db");
if ($conn->connect_error) {
    die("Connection Filed" . $conn->connect_error);
}