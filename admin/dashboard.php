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
<nav class="fixed top-0 left-40 right-0 h-16 flex justify-between items-center bg-white/80 backdrop-blur-3xl">
    <div class="flex gap-1.5 items-center">
        <i class="bi bi-justify text-black text-sm"></i>
        <h1 class="text-xl text-black font-medium">Dashboard</h1>
    </div>
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