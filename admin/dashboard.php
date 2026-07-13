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

$sql = $conn->query("SELECT * FROM products ORDER BY p_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../src/output.css" rel="stylesheet">
    <title>Wexh | Store</title>
</head>
<nav
      id="nav-shadow"
      class="fixed z-[999] inset-x-0 top-0 h-[100px] flex flex-col justify-between bg-white/80 backdrop-blur-[50px] px-[20px]"
      >
      <div class="flex flex-row justify-between items-center pt-[6px]">
        <a href="index.php" class="poppins uppercase font-semibold text-4xl">
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
          <div class="flex flex-row justify-center items-center gap-5 pr-5 border-r-2 border-gray-400">
            <div class="flex flex-row gap-1/2 gap-1">
                <div class="w-12 h-12 rounded-full bg-gray-300"></div>
                <div class="flex flex-col justify-center">
                    <h1 class="text-base text-black"><?= htmlspecialchars(ucwords($user['username'])) ?></h1>
                    <p class="text-blue-500 text-xs"><?= htmlspecialchars(ucwords($user['roles'])) ?></p>
                </div>
            </div>
          </div>
          <a class="h-full px-8 text-black border border-black text-sm flex justify-center items-center ml-5" href="../auth/logout.php">Logout</a>
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
        <a href="../src/pages/explore.php">
          Explore
        </a>
      </ul>
    </nav>

<body class="bg-gray-100 mt-[140px]">
    <header>
        <div class="max-w-7xl mx-auto px-8 py-6 flex justify-between items-center">
            <div>
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
        </div>
    </header>
    <!-- Table -->
    <main class="max-w-7xl mx-auto p-8">

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

                        <?php if(mysqli_num_rows($sql) > 0){ ?>

                            <?php while($row = mysqli_fetch_assoc($sql)){ ?>

                                <tr class="border-b hover:bg-gray-50 transition">

                                    <td class="px-5 py-4">
                                        <?= $row['p_id'] ?>
                                    </td>

                                    <td class="px-5 py-4">
                                        <img
                                            src="../images/<?= $row['p_image'] ?>"
                                            class="w-20 h-20 object-cover rounded-lg">
                                    </td>

                                    <td class="px-5 py-4 font-medium">
                                        <?= $row['p_title'] ?>
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">
                                            $<?= number_format($row['p_price'],2) ?>
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full">
                                            <?= $row['p_qty'] ?>
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 text-gray-600 max-w-sm">
                                        <?= $row['desciption'] ?>
                                    </td>

                                    <td class="px-5 py-4">

                                        <div class="flex justify-center gap-3">

                                            <a
                                                href="../update_form.php?id=<?= $row['p_id'] ?>"
                                                class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg">
                                                Edit
                                            </a>

                                            <a
                                                href="../delete.php?id=<?= $row['p_id'] ?>"
                                                onclick="return confirm('Delete this product?')"
                                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                                                Delete
                                            </a>

                                        </div>

                                    </td>

                                </tr>

                            <?php } ?>

                        <?php } else { ?>

                            <tr>

                                <td colspan="7" class="py-16 text-center text-gray-500 text-lg">
                                    No products found.
                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </main>

</body>
</html>