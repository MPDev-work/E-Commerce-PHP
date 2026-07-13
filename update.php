<?php
include "./config/connect.php";

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

        $path = "images/" . time() . "_" . $image_name;

        if (move_uploaded_file($tmp_name, "images/" . $image_name)) {
            $old_path = "images/" . time() . "_" . $old_image;
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
        header("Location: ./admin/dashboard.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!-- <?php
    require_once "db.php";

    if($_SERVER['REQUEST_METHOD'] === "POST"){
        $id = $_POST["id"];
        $title = $_POST["title"];
        $price = $_POST["price"];
        $qty = $_POST["qty"];
        $des = $_POST["des"];

        if(!empty($_FILES["image"]["tmp_name"])){
            $old_image = $conn->query("SELECT * FROM products WHERE p_id = $id")->fetch_assoc();
            unlink("images/" . $old_image['p_image']);

            $image_name = $_FILES["image"]["name"];
            $tmp_name = $_FILES["image"]["tmp_name"];
            $path = time() . "_" . $image_name;
            print_r($path);
            move_uploaded_file($tmp_name, $path);

            $update = $conn->query("UPDATE products SET p_title = '$title',
                                                        p_price = $price,
                                                        p_qty = $qty,
                                                        p_image = '$path',
                                                        descrition = '$des'
                                    WHERE p_id = $id ");

        }else{
            $update = $conn->query("UPDATE products SET p_title = '$title',
                                                        p_price = $price,
                                                        p_qty = $qty,
                                                        descrition = '$des'
                                    WHERE p_id = $id ");
        }
        if($update){
            header("location: index.php");
        }
} 
?>-->