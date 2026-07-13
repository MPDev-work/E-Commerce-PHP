<?php
    include "./config/connect.php";

    $id = $_GET['id'];
    $product = $conn->query("SELECT * FROM products WHERE p_id = $id")->fetch_assoc();
    
    $path = "./images/" . $product['p_image'];
    if(file_exists($path)){
        unlink($path);
    }

    $delete = $conn->query("DELETE FROM products WHERE p_id = $id");
    $conn->close();

    if($delete){
        header("location: ./admin/dashboard.php");
    }