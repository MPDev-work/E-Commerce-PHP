<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/output.css" />
    <title>Document</title>
</head>
<body>
    <div class="w-screen h-max flex flex-col justify-center items-center bg-[#f2f2f6] gap-5">
        <h1 class="font-medium text-3xl text-black mt-10">Add product</h1>
        <form class="w-[500px] h-max flex flex-col gap-2 p-5 bg-white rounded-3xl mb-10" action="insert.php" method="POST" enctype="multipart/form-data">
            <input class="bg-[#f2f2f6] w-full h-12 border border-gray-300 transition duration-300 focus:border-gray-500 focus:outline-0 hover:border-gray-500 rounded-full text-black px-5" type="number" name="id" hidden>
            <label class="text-base font-normal text-black">Product title<span class="text-red-600">*</span></label>
            <input class="bg-[#f2f2f6] w-full h-12 border border-gray-300 transition duration-300 focus:border-gray-500 focus:outline-0 hover:border-gray-500 rounded-full text-black px-5" type="text" name="p_title" placeholder="Product title" require>
            <label class="text-base font-normal text-black">Product price<span class="text-red-600">*</span></label>
            <input class="bg-[#f2f2f6] w-full h-12 border border-gray-300 transition duration-300 focus:border-gray-500 focus:outline-0 hover:border-gray-500 rounded-full text-black px-5" type="number" name="p_price" placeholder="Product price" require>
            <label class="text-base font-normal text-black">Product Qty<span class="text-red-600">*</span></label>
            <input class="bg-[#f2f2f6] w-full h-12 border border-gray-300 transition duration-300 focus:border-gray-500 focus:outline-0 hover:border-gray-500 rounded-full text-black px-5" type="number" name="p_qty" placeholder="Product qty" require>
            <label class="text-base font-normal text-blac2 ">Product Description<span class="text-red-600">*</span></label>
            <textarea class="bg-[#f2f2f6] w-full h-[140px] rounded-3xl text-black p-5 border border-gray-300 transition duration-300 focus:border-gray-500 focus:outline-0 hover:border-gray-500" name="description" placeholder="Enter product description"></textarea>
            <label class="text-base font-normal text-blac2 ">Product image<span class="text-red-600">*</span></label>
            <div class="bg-[#f2f2f6] w-full h-max flex flex-col p-2 rounded-3xl border border-gray-300 transition duration-300 hover:border-gray-500">
                <input class="cursor-pointer w-full" type="file" name="p_image" onchange="handleChange(this)">
                <div class="w-full hidden my-2.5" id="line">
                    <hr class="w-full border-1/2 border-dashed border-gray-500">
                </div>
                <img id="preview" class="w-[200px] h-0 transition duration-200 object-cover rounded-2xl">
            </div>
            <div class="w-full flex flex-row justify-start items-center gap-2">
                <button class="cursor-pointer h-12 rounded-full px-12 text-white bg-emerald-500" type="submit">Add product</button>
                <a href="./admin/dashboard.php" class="cursor-pointer h-12 rounded-full px-12 text-white bg-red-500 flex justify-center items-center">Cencel</a>
            </div>
        </form>
    </div>
<script>
    const handleChange = (input) =>{
        const preview = document.getElementById('preview');
        const Line = document.getElementById('line')
        let file = input.files[0];
        if(file){
            preview.src = URL.createObjectURL(file);
            Line.style.display = "block";
            preview.style.height = "200px";
            preview.style.marginTop = "10px";
        }
        
    }

</script>
</body>
</html>