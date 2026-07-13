<?php
session_start();
include "../config/connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id); $stmt->execute(); $user =
$stmt->get_result()->fetch_assoc(); $sql = $conn->query("SELECT * FROM products
ORDER BY p_id DESC"); ?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    />
    <link rel="stylesheet" href="../src/output.css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Wexh | Store</title>
  </head>
  <body class="bg-gray-100">
    <nav
      class="fixed z-[999] inset-x-0 top-0 h-16 flex justify-between items-center bg-[#f2f2f6] backdrop-blur-3xl pl-[270px] pr-5"
    >
      <div class="flex gap-1.5 items-center">
        <i class="bi bi-justify text-black text-xl"></i>
        <h1 class="text-xl text-black font-medium">Dashboard</h1>
      </div>
      <div class="h-12 flex items-center gap-5">
        <div
          class="h-full w-max flex justify-center items-center gap-2 pr-8 border-r-2 border-gray-500"
        >
          <div
            class="h-10 w-10 rounded-full bg-blue-500 flex justify-center items-center text-white"
          >
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
        <a
          class="h-full px-6 flex justify-center items-center border border-black text-black ml-8 transition duration-200 hover:bg-black hover:text-white"
          href="../auth/logout.php"
          >Log out</a
        >
      </div>
    </nav>
    <!-- sidebar -->
    <nav
      class="fixed z-[999] left-0 top-0 bottom-0 w-[250px] bg-[#f2f2f6] p-2.5 flex flex-col gap-2.5"
    >
      <h1
        class="uppercase text-3xl text-black font-bold text-nowrap whitespace-nowrap tracking-tighter"
      >
        wexh store
      </h1>
      <div class="w-full h-max flex flex-col gap-2 mt-5">
        <p class="text-sm text-gray-400">Main menu</p>
        <div
          class="w-full flex flex-col bg-white rounded-[30px] overflow-hidden gap-1 p-2.5"
        >
          <a
            class="group w-full h-10 flex justity-start items-center gap-2 pl-2.5 rounded-full text-white text-sm bg-black"
            href="#"
          >
            <i class="bi bi-house-fill hidden group-hover:block"></i>
            <i class="bi bi-house group-hover:hidden"></i>
            Overview
          </a>
          <a
            class="group w-full h-10 flex justity-start items-center gap-2 pl-2.5 rounded-full text-gray-400 text-sm transition duration-300 hover:text-black hover:bg-[#f2f2f6]"
            href="#"
          >
            <i class="bi bi-house-fill hidden group-hover:block"></i>
            <i class="bi bi-house group-hover:hidden"></i>
            Order
          </a>
          <a
            class="group w-full h-10 flex justity-start items-center gap-2 pl-2.5 rounded-full text-gray-400 text-sm transition duration-300 hover:text-black hover:bg-[#f2f2f6]"
            href="#"
          >
            <i class="bi bi-house-fill hidden group-hover:block"></i>
            <i class="bi bi-house group-hover:hidden"></i>
            Products
          </a>
        </div>
      </div>
      <div class="w-full h-max flex flex-col gap-2">
        <p class="text-sm text-gray-400">Account</p>
        <div
          class="w-full flex flex-col bg-white rounded-[30px] overflow-hidden gap-1 p-2.5"
        >
          <a
            class="group w-full h-10 flex justity-start items-center gap-2 pl-2.5 rounded-full text-gray-400 text-sm transition duration-300 hover:text-black hover:bg-[#f2f2f6]"
            href="#"
          >
            <i class="bi bi-house-fill hidden group-hover:block"></i>
            <i class="bi bi-house group-hover:hidden"></i>
            Edit profile
          </a>
          <a
            class="group w-full h-10 flex justity-start items-center gap-2 pl-2.5 rounded-full text-gray-400 text-sm transition duration-300 hover:text-black hover:bg-[#f2f2f6]"
            href="#"
          >
            <i class="bi bi-house-fill hidden group-hover:block"></i>
            <i class="bi bi-house group-hover:hidden"></i>
            Setting
          </a>
        </div>
      </div>
      <a
        class="group absolute bottom-2.5 w-[calc(100%-20px)] h-10 rounded-full border border-gray-300 text-gray-400 flex justify-center items-center gap-1 text-sm transition duration-200 hover:bg-black hover:text-white hover:border-black"
        href="../auth/logout.php"
        ><i
          class="bi bi-arrow-bar-left text-gray-400 transition duration-200 group-hover:text-white"
        ></i>
        Log out</a
      >
    </nav>
    <!-- <div class="w-[calc(100vw-250px)] ml-[250px] px-8 pb-6 flex justify-between items-center">
                <div class="flex flex-col">
                    <h1 class="text-5xl font-bold tracking-tighter text-gray-800">
                        Welcome <?= htmlspecialchars(ucwords($user['username'])) ?> !
                    </h1>
                    <p class="text-gray-500 mt-1">
                        Product Management Dashboard
                    </p>
                </div>
            <a href="../insert_form.php"
                class="bg-sky-600 hover:bg-sky-700 text-white px-6 py-3 rounded-lg font-medium transition">
                + Add Product
            </a>
        </div> -->
    <!-- Table -->
    <section
      class="w-[calc(100vw-250px)] h-[calc(100vh-64px)] absolute mt-16 right-0 overflow-x-hidden overflow-y-scroll rounded-[30px] bg-white"
    >
      <div class="w-full h-max flex flex-col items-center p-2.5 gap-2.5">
        <div class="w-full grid grid-cols-3 grid-flow-row gap-2.5">
          <div class="h-[200px] bg-[#f2f2f6] rounded-[25px]"></div>
          <div class="h-[200px] bg-[#f2f2f6] rounded-[25px]"></div>
          <div class="h-[200px] bg-[#f2f2f6] rounded-[25px]"></div>
        </div>
        <div class="w-full grid grid-cols-2 grid-flow-row gap-2.5">
          <div class="h-[200px] bg-[#f2f2f6] rounded-[25px]"></div>
          <div class="h-[200px] bg-[#f2f2f6] rounded-[25px]"></div>
        </div>
        <main class="w-full">
          <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead class="bg-gray-800 text-white">
                  <tr>
                    <th class="py-4 px-5 text-left">ID</th>
                    <th class="py-4 px-5 text-left">Image</th>
                    <th class="py-4 px-5 text-left">Title</th>
                    <th class="py-4 px-5 text-left">Price</th>
                    <th class="py-4 px-5 text-left">Stock</th>
                    <th class="py-4 px-5 text-left">Description</th>
                    <th class="py-4 px-5 text-center">Action</th>
                  </tr>
                </thead>

                <tbody>
                  <?php if(mysqli_num_rows($sql) > 0){ ?> <?php while($row =
                  mysqli_fetch_assoc($sql)){ ?>

                  <tr class="border-b hover:bg-gray-50 transition">
                    <td class="px-5 py-4"><?= $row['p_id'] ?></td>

                    <td class="px-5 py-4">
                      <img
                        src="../images/<?= $row['p_image'] ?>"
                        class="w-20 h-20 object-cover rounded-lg"
                      />
                    </td>

                    <td class="px-5 py-4 font-medium">
                      <?= $row['p_title'] ?>
                    </td>

                    <td class="px-5 py-4">
                      <span
                        class="bg-green-100 text-green-700 px-3 py-1 rounded-full"
                      >
                        $<?= number_format($row['p_price'],2) ?>
                      </span>
                    </td>

                    <td class="px-5 py-4">
                      <span
                        class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full"
                      >
                        <?= $row['p_qty'] ?>
                      </span>
                    </td>

                    <td class="px-5 py-4 text-gray-600 max-w-sm">
                      <?= $row['description'] ?>
                    </td>

                    <td class="px-5 py-4">
                      <div class="flex justify-center gap-3">
                        <a
                          href="../update_form.php?id=<?= $row['p_id'] ?>"
                          class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg"
                        >
                          Edit
                        </a>

                        <a
                          href="../delete.php?id=<?= $row['p_id'] ?>"
                          onclick="return confirm('Delete this product?');"
                          class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg"
                        >
                          Delete
                        </a>
                      </div>
                    </td>
                  </tr>

                  <?php } ?> <?php } else { ?>

                  <tr>
                    <td
                      colspan="7"
                      class="py-16 text-center text-gray-500 text-lg"
                    >
                      No products found.
                    </td>
                  </tr>

                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </main>
      </div>
    </section>
  </body>
</html>
