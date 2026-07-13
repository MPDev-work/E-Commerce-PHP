<?php
include "./config/connect.php";

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $title = $_POST['p_title'];
    $price = $_POST['p_price'];
    $qty = $_POST['p_qty'];
    $description = $_POST['description'];

    if(!empty($_FILES['p_image']['name'])){
        $image_name = $_FILES['p_image']['name'];
        $image_tmp  = $_FILES['p_image']['tmp_name'];
        
        $path = "images/" . time() . $image_name;
        move_uploaded_file($image_tmp, $path);

        $sql = $conn->query("INSERT INTO products (p_title, p_price, p_qty, description, p_image)
        VALUES ('$title', '$price', '$qty', '$description', '$image_name')");
        $conn->close();
    }else{
         $sql = $conn->query("INSERT INTO products (p_title, p_price, p_qty, description)
        VALUES ('$title', '$price', '$qty', '$description')");
        $conn->close();
    }

    if ($sql) {
        header("Location: ./admin/dashboard.php");
        exit;
    } else {
        header("location: insert_form.php");
        exit;
    }
}