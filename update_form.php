<?php
    include "./config/connect.php";

    $id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE p_id = '$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/output.css" />
    <title>Wexh | Store</title>
</head>
<body>
    <div class="w-screen h-max flex flex-col justify-center items-center bg-[#f2f2f6] gap-5">
        <h1 class="font-medium text-3xl text-black mt-10">Update product</h1>
        <form class="w-[500px] h-max flex flex-col gap-2 p-5 bg-white rounded-3xl mb-10" action="update.php" method="POST" enctype="multipart/form-data">
            <input id="id" value="<?= $row['p_id'] ?>" type="number" name="id" hidden>
            <input type="hidden" name="old_image" value="<?= $row['p_image'] ?>">
            <label class="text-base font-normal text-black">Product title<span class="text-red-600">*</span></label>
            <input id="p_title" value="<?= $row['p_title'] ?>" class="w-full h-12 border border-gray-300 transition duration-300 focus:border-gray-500 focus:outline-0 hover:border-gray-500 rounded-full text-black px-5" type="text" name="p_title" placeholder="Product title" require>
            <label class="text-base font-normal text-black">Product price<span class="text-red-600">*</span></label>
            <input id="p_price" value="<?= $row['p_price'] ?>" class="w-full h-12 border border-gray-300 transition duration-300 focus:border-gray-500 focus:outline-0 hover:border-gray-500 rounded-full text-black px-5" type="number" name="p_price" placeholder="Product price" require>
            <label class="text-base font-normal text-black">Product Qty<span class="text-red-600">*</span></label>
            <input id="p_qty" value="<?= $row['p_qty'] ?>" class="w-full h-12 border border-gray-300 transition duration-300 focus:border-gray-500 focus:outline-0 hover:border-gray-500 rounded-full text-black px-5" type="number" name="p_qty" placeholder="Product qty" require>
            <label class="text-base font-normal text-blac2 ">Product Description<span class="text-red-600">*</span></label>
            <textarea id="description" class="w-full h-[140px] rounded-3xl text-black p-5 border border-gray-300 transition duration-300 focus:border-gray-500 focus:outline-0 hover:border-gray-500" name="description" placeholder="Enter product description"><?= $row['description'] ?></textarea>
            <label class="text-base font-normal text-blac2 ">Product image<span class="text-red-600">*</span></label>
            <div class="w-full h-max flex flex-col p-2 rounded-3xl border border-gray-300 transition duration-300 hover:border-gray-500">
                <input id="p_image" class="cursor-pointer w-full" type="file" name="p_image" onchange="handleChange(this)">
                <img id="preview" src="./images/<?= $row['p_image'] ?>" class="w-[200px] h-[200px] transition duration-200 object-cover rounded-2xl mt-2.5">
            </div>
            <div class="w-full flex flex-row justify-start items-center gap-2">
                <button type="submit" class="cursor-pointer h-12 rounded-full px-12 text-white bg-emerald-500">Update product</button>   
                <a href="./admin/dashboard.php" class="cursor-pointer h-12 rounded-full px-12 text-white bg-red-500 flex justify-center items-center">Cencel</a>
            </div>
        </form>
        <!-- <div id="preview-pop-up" class="absolute opacity-0 w-1/2 h-1/2 rounded-3xl overflow-hidden flex justify-center items-center transition duration-200">
            <img class="w-full h-full object-cover" src="./images/<?= $row['p_image'] ?>">
        </div> -->
    </div>
<script>
    // const previewPopUp = document.getElementById('preview-pop-up');
    // previewPopUp.addEventListener('click', () =>{
    //     previewPopUp.style.opacity = '1';
    // })



    const handleChange = (input) =>{
        const preview = document.getElementById('preview');
        let file = input.files[0];
        if(file){
            preview.src = URL.createObjectURL(file);
        }
        
    }

</script>
</body>
</html>