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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="../src/output.css" rel="stylesheet">
    <title>Wexh | Store</title>
</head>
<body class="bg-gray-100 mt-[140px]">
    <nav class="fixed z-[999] inset-x-0 top-0 h-16 flex justify-between items-center bg-white/80 backdrop-blur-3xl pl-[260px] pr-5 border-b border-gray-300">
        <div class="flex gap-1.5 items-center">
            <i class="bi bi-justify text-black text-xl"></i>
            <h1 class="text-xl text-black font-medium">Dashboard</h1>
        </div>
        <div class="h-12 flex items-center gap-5">
            <div class="h-full w-max flex justify-center items-center gap-2 pr-8 border-r-2 border-gray-500">
                <div class="h-10 w-10 rounded-full bg-blue-500 flex justify-center items-center text-white"><?= htmlspecialchars(ucwords(substr($user['username'], 0, 1))) ?></div>
                <div class="flex flex-col h-full justify-center">
                    <h1 class="text-base text-black"><?= htmlspecialchars(ucwords($user['username'])) ?></h1>
                    <p class="text-blue-500 text-xs"><?= htmlspecialchars(ucwords($user['roles'])) ?></p>
                </div>
            </div>
            <a class="h-full px-6 flex justify-center items-center border border-black text-black ml-8 transition duration-200 hover:bg-black hover:text-white" href="../auth/logout.php">Log out</a></div></nav>5
    <!-- sidebar -->
     <nav class="absolute z-[999] left-0 top-0 bottom-0 w-[240px] bg-[#f2f2f6] p-4 flex flex-col gap-2 border-r border-gray-300">
        <h1 class="uppercase text-3xl text-black font-bold text-nowrap whitespace-nowrap tracking-tighter">wexh store</h1>
        <div class="w-full h-max flex flex-col gap-2 mt-10">
            <a class="group w-full h-10 flex justity-start items-center gap-2 pl-2.5 rounded-[12px] text-gray-400 text-sm transition duration-300 hover:text-black hover:bg-white" href="#">
                <i class="bi bi-house-fill hidden group-hover:block"></i>    
                <i class="bi bi-house group-hover:hidden"></i>    
                Home
            </a>
            <a class="group w-full h-10 flex justity-start items-center gap-2 pl-2.5 rounded-[12px] text-gray-400 text-sm transition duration-300 hover:text-black hover:bg-white" href="#">
                <i class="bi bi-house-fill hidden group-hover:block"></i>    
                <i class="bi bi-house group-hover:hidden"></i>    
                Performance
            </a>
            <a class="group w-full h-10 flex justity-start items-center gap-2 pl-2.5 rounded-[12px] text-gray-400 text-sm transition duration-300 hover:text-black hover:bg-white" href="#">
                <i class="bi bi-house-fill hidden group-hover:block"></i>    
                <i class="bi bi-house group-hover:hidden"></i>    
                Product control
            </a>
        </div>
     </nav>
        <div class="w-[calc(100vw-240px)] ml-[240px] px-8 pb-6 flex justify-between items-center">
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
        </div>
    <!-- Table -->
    <main class="w-[calc(100vw-240px)] ml-[240px] p-8">
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
                                        <?= $row['description'] ?>
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