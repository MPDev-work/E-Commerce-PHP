<?php
require_once __DIR__ . '/_layout.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $name = trim($_POST['category_name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        if ($name !== '') {
            $stmt = $conn->prepare('INSERT INTO categories (category_name, description) VALUES (?, ?)');
            $stmt->bind_param('ss', $name, $description);
            $message = $stmt->execute() ? 'Category added.' : 'That category already exists.';
        }
    }
    if ($action === 'delete') {
        $id = (int)($_POST['category_id'] ?? 0);
        $stmt = $conn->prepare('DELETE FROM categories WHERE category_id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $message = 'Category removed.';
    }
}
$categories = $conn->query('SELECT c.category_id, c.category_name, c.description, COUNT(p.p_id) AS product_count FROM categories c LEFT JOIN products p ON p.category_id = c.category_id GROUP BY c.category_id, c.category_name, c.description ORDER BY c.category_name');
admin_header('Categories', 'categories.php');
page_title('Catalogue', 'Skincare categories', 'Create the product groups customers can browse.');
?>
<?php if ($message): ?><p class="mb-4 rounded-2xl bg-emerald-50 p-4 text-emerald-700"><?= e($message) ?></p>
<?php endif; ?>
<div class="grid gap-5 lg:grid-cols-[360px_1fr]">
    <form method="post" class="rounded-[28px] bg-[#f2f2f6] p-5"><input type="hidden" name="action" value="create">
        <h2 class="text-xl font-semibold">Add category</h2><label class="mt-4 block text-sm font-medium">Category
            name<input required name="category_name" placeholder="e.g. Cleansers"
                class="mt-2 w-full rounded-full bg-white px-4 py-3 outline-none"></label><label
            class="mt-4 block text-sm font-medium">Description<textarea name="description" rows="4"
                placeholder="Short category description"
                class="mt-2 w-full rounded-2xl bg-white p-4 outline-none"></textarea></label><button
            class="mt-5 rounded-full bg-black px-5 py-3 text-sm text-white">Add category</button>
    </form>
    <div class="overflow-x-auto rounded-[28px] border border-slate-100">
        <table class="w-full min-w-[500px] text-left text-sm">
            <thead class="bg-black text-white">
                <tr>
                    <th class="px-5 py-4">Category</th>
                    <th class="px-5 py-4">Description</th>
                    <th class="px-5 py-4">Products</th>
                    <th class="px-5 py-4"></th>
                </tr>
            </thead>
            <tbody><?php if ($categories && $categories->num_rows): while ($category = $categories->fetch_assoc()): ?>
                        <tr class="border-b border-slate-100">
                            <td class="px-5 py-4 font-medium"><?= e($category['category_name']) ?></td>
                            <td class="px-5 py-4 text-slate-500"><?= e($category['description']) ?></td>
                            <td class="px-5 py-4"><?= e($category['product_count']) ?></td>
                            <td class="px-5 py-4 text-right">
                                <form method="post"><input type="hidden" name="action" value="delete"><input type="hidden"
                                        name="category_id" value="<?= e($category['category_id']) ?>"><button
                                        onclick="return confirm('Remove this category?')" class="text-red-600">Remove</button>
                                </form>
                            </td>
                        </tr><?php endwhile;
                        else: ?><tr>
                        <td colspan="4" class="p-8 text-center text-slate-400">No categories yet.</td>
                    </tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php admin_footer(); ?>