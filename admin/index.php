<?php
session_start();
include "../config/connect.php";

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();
$userName = $user['username'];

$id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user =
  $stmt->get_result()->fetch_assoc();
$sql = $conn->query("SELECT * FROM products
    ORDER BY p_id DESC");
?>

<!DOCTYPE html>
<html>

<head>
  <title>Admin | Dashboard</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <nav
    class="fixed z-[999] inset-x-0 top-0 h-16 flex justify-between items-center bg-[#f2f2f6] backdrop-blur-3xl px-2.5">
    <h1 class="uppercase font-medium text-3xl text-black">solis skin</h1>
    <div class="h-12 flex items-center gap-5">
      <div class="h-full w-max flex justify-center items-center gap-2 pr-8 border-r-2 border-gray-500">
        <div class="h-10 w-10 rounded-full bg-blue-500 flex justify-center items-center text-white">
          <?= htmlspecialchars(ucwords(substr($user['username'], 0, 1))) ?>
        </div>
        <div class="flex flex-col h-full justify-center">
          <h1 class="text-base text-black">
            <?= htmlspecialchars(ucwords($user['username'])) ?>
          </h1>
          <p class="text-blue-500 text-xs">
            <?= htmlspecialchars(ucwords($user['roles'])) ?>
          </p>
        </div>
      </div>
      <a class="h-full px-6 flex justify-center items-center border border-black text-black transition duration-200 hover:bg-black hover:text-white"
        href="../auth/logout.php">Log out</a>
    </div>
  </nav>
  <!-- sidebar -->
  <nav class="fixed z-[998] left-0 top-16 bottom-0 w-[250px] bg-[#f2f2f6] p-2.5 flex flex-col gap-2.5">
    <div class="w-full h-max flex flex-col gap-2">
      <p class="text-sm text-gray-400">Main menu</p>
      <div class="w-full flex flex-col bg-white rounded-[30px] overflow-hidden gap-1 p-2.5">
        <a class="group w-full h-10 flex justity-start items-center gap-2 pl-2.5 rounded-full text-white text-sm bg-black"
          href="#">
          <i class="bi bi-house-fill hidden group-hover:block"></i>
          <i class="bi bi-house group-hover:hidden"></i>
          Overview
        </a>
        <a class="group w-full h-10 flex justity-start items-center gap-2 pl-2.5 rounded-full text-gray-400 text-sm transition duration-200 hover:text-white hover:bg-black"
          href="#">
          <i class="bi bi-house-fill hidden group-hover:block"></i>
          <i class="bi bi-house group-hover:hidden"></i>
          Order
        </a>
        <a class="group w-full h-10 flex justity-start items-center gap-2 pl-2.5 rounded-full text-gray-400 text-sm transition duration-200 hover:text-white hover:bg-black"
          href="#">
          <i class="bi bi-house-fill hidden group-hover:block"></i>
          <i class="bi bi-house group-hover:hidden"></i>
          Add Products
        </a>
        <a class="group w-full h-10 flex justity-start items-center gap-2 pl-2.5 rounded-full text-gray-400 text-sm transition duration-200 hover:text-white hover:bg-black"
          href="#">
          <i class="bi bi-house-fill hidden group-hover:block"></i>
          <i class="bi bi-house group-hover:hidden"></i>
          All products
        </a>
        <a class="group w-full h-10 flex justity-start items-center gap-2 pl-2.5 rounded-full text-gray-400 text-sm transition duration-200 hover:text-white hover:bg-black"
          href="#">
          <i class="bi bi-house-fill hidden group-hover:block"></i>
          <i class="bi bi-house group-hover:hidden"></i>
          Products category
        </a>
      </div>
    </div>
    <div class="w-full h-max flex flex-col gap-2">
      <p class="text-sm text-gray-400">Account Info</p>
      <div class="w-full flex flex-col bg-white rounded-[30px] overflow-hidden gap-1 p-2.5">
        <a class="group w-full h-10 flex justity-start items-center gap-2 pl-2.5 rounded-full text-gray-400 text-sm transition duration-200 hover:text-white hover:bg-black"
          href="#">
          <i class="bi bi-house-fill hidden group-hover:block"></i>
          <i class="bi bi-house group-hover:hidden"></i>
          Edit profile
        </a>
        <a class="group w-full h-10 flex justity-start items-center gap-2 pl-2.5 rounded-full text-gray-400 text-sm transition duration-200 hover:text-white hover:bg-black"
          href="#">
          <i class="bi bi-house-fill hidden group-hover:block"></i>
          <i class="bi bi-house group-hover:hidden"></i>
          Setting
        </a>
      </div>
    </div>
    <div class="w-full h-max flex flex-col gap-2">
      <p class="text-sm text-gray-400">Support</p>
      <div class="w-full flex flex-col bg-white rounded-[30px] overflow-hidden gap-1 p-2.5">
        <a class="group w-full h-10 flex justity-start items-center gap-2 pl-2.5 rounded-full text-gray-400 text-sm transition duration-200 hover:text-white hover:bg-black"
          href="www.google.com" target="_blank">
          <i class="bi bi-house-fill hidden group-hover:block"></i>
          <i class="bi bi-house group-hover:hidden"></i>
          Help Center
        </a>
      </div>
    </div>
    <a class="group absolute bottom-2.5 w-[calc(100%-20px)] h-10 rounded-full border border-gray-300 text-gray-400 flex justify-center items-center gap-1 text-sm transition duration-200 hover:bg-black hover:text-white hover:border-black"
      href="../auth/logout.php"><i
        class="bi bi-arrow-bar-left text-gray-400 transition duration-200 group-hover:text-white"></i>
      Log out</a>
  </nav>
  <!-- <?php include "includes/sidebar.php"; ?>
<div class="main">
     <?php include "includes/navbar.php"; ?>
    <div id="content">
    <?php include "pages/dashboard.php"; ?>
    </div> -->
  <!-- </div> -->
  <script src="assets/js/app.js"></script>
</body>

</html>