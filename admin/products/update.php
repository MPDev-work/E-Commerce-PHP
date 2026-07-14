<?php
include "../../config/connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = (int)$_POST['id'];
    $title = mysqli_real_escape_string($conn, $_POST['p_title']);
    $price = $_POST['p_price'];
    $qty = $_POST['p_qty'];
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $old_image = $_POST['old_image'];
    $image_name = $old_image;

    if (!empty($_FILES['p_image']['name'])) {

        $image_name = $_FILES['p_image']['name'];
        $tmp_name = $_FILES['p_image']['tmp_name'];

        $path = __DIR__ . "/../../images/" . time() . "_" . $image_name;

        if (move_uploaded_file($tmp_name, __DIR__ . "/../../images/" . $image_name)) {
            $old_path = __DIR__ . "/../../images/" . time() . "_" . $old_image;
            if (file_exists($old_path)) {
                unlink($old_path);
            }
        } else {
            $image_name = $old_image;
        }
    }

    $sql = "UPDATE products SET
            p_title = '$title',
            p_price = '$price',
            p_qty = '$qty',
            description = '$desc',
            p_image = '$image_name'
            WHERE p_id = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../page/products.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
