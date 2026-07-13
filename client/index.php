<?php 
    include "../config/connect.php";
    require_once("../middleware/auth.php")
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/output.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Boldonse&family=Cal+Sans&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Questrial&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>Document</title>
</head>
<body>
     <nav
      id="nav-shadow"
      class="fixed z-[999] inset-x-0 top-0 h-[100px] flex flex-col justify-between bg-white/80 backdrop-blur-[50px] px-[20px]"
      >
      <div class="flex flex-row justify-between items-center pt-[6px]">
        <a href="#" class="poppins uppercase font-semibold text-4xl">
          wexh
          <span class="text-gray-400 pl-2">Store</span>
        </a>
        <form class="relative flex w-[400px] h-[50px] justify-center items-center">
          <button
            type="submit"
            class="bi bi-search text-white absolute right-1.5 cursor-pointer bg-[#dc3545] rounded-full h-[80%] px-5"
          ></button>
          <input
            class="w-full h-full text-[16px] pl-4 pr-17 text-black border-1 border-gray-200 outline-0 rounded-full transition duration-300 hover:border-[#dc3545] focus:border-[#dc3545] "
            type="text"
            placeholder="Search"
            name="search"
          />
        </form>
        <div class="relative h-[40px] flex justify-center items-center">
          <div class="flex flex-row justify-center items-center gap-5 mr-5">
            <a
              href="../auth/login.php"
              class="h-10 px-5 border-1 border-black text-base flex justify-center items-center transition duration-200 hover:bg-black hover:text-white hover:border-transparent"
            >
              Login
            </a>
            <a
              href="../auth/register.php"
              class="h-10 px-4 border-1 border-black text-base flex justify-center items-center transition duration-200 hover:bg-black hover:text-white hover:border-transparent"
            >
              Register
            </a>
          </div>
          <a
            href="/notification"
            class="bi bi-bell h-full w-auto text-[32px] pl-6 border-l-2 border-gray-300 flex justify-center items-center"
          ></a>
          <a
            href="/bag"
            class="bi bi-bag h-full w-auto text-[32px] pl-6 flex justify-center items-center"
          ></a>
        </div>
      </div>
      <ul class="flex flex-row items-center gap-6 pb-[5px]">
        <a href="index.php">
          Home
        </a>
        <a href="shopAll.php">
          Shop All
        </a>
        <a href="category.php">
          Category
        </a>
        <a href="brand.php">
          Brand
        </a>
        <a href="freeDelivery.php">
          Free delivery
        </a>
        <a href="explore.php">
          Explore
        </a>
      </ul>
    </nav>
</body>
</html>