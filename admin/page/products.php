<?php
require_once __DIR__ . '/_layout.php';
$products = $conn->query('SELECT p_id, p_title, p_price, p_qty, description,
p_image FROM products ORDER BY p_id DESC'); admin_header('Products',
'products.php'); page_title('Catalogue', 'All products', 'Manage your inventory,
pricing, and product information.', ['addProduct.php', 'Add product',
'plus-circle']); ?>
<div class="overflow-x-auto rounded-[24px] border border-slate-100">
  <table class="w-full min-w-[800px] text-left text-sm">
    <thead class="bg-black text-white">
      <tr>
        <th class="px-5 py-4">Product</th>
        <th class="px-5 py-4">Price</th>
        <th class="px-5 py-4">Stock</th>
        <th class="px-5 py-4">Description</th>
        <th class="px-5 py-4 text-right">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($products && $products->num_rows): while ($product =
      $products->fetch_assoc()): ?>
      <tr class="border-b border-slate-100 last:border-0 hover:bg-[#f2f2f6]">
        <td class="flex items-center gap-3 px-5 py-3 font-medium">
          <?php if ($product['p_image']): ?><img
            src="../../images/<?= e($product['p_image']) ?>"
            class="h-12 w-12 rounded-xl object-cover"
            alt=""
          /><?php endif; ?><span
            ><?= e($product['p_title']) ?><small
              class="mt-1 block font-normal text-slate-400"
              >#<?= e($product['p_id']) ?></small
            ></span
          >
        </td>
        <td class="px-5 py-3">
          $<?= number_format((float)$product['p_price'], 2) ?>
        </td>
        <td class="px-5 py-3">
          <span class="rounded-full bg-blue-50 px-3 py-1 text-blue-700"
            ><?= e($product['p_qty']) ?></span
          >
        </td>
        <td class="max-w-xs truncate px-5 py-3 text-slate-500">
          <?= e($product['description']) ?>
        </td>
        <td class="px-5 py-3 text-right">
          <a
            href="../../update_form.php?id=<?= e($product['p_id']) ?>"
            class="rounded-xl bg-amber-500/20 px-4 py-2.5 text-amber-700"
            >Edit</a
          >
          <a
            href="../../delete.php?id=<?= e($product['p_id']) ?>"
            onclick="return confirm('Delete this product?');"
            class="rounded-xl bg-red-500/20 px-4 py-2.5 text-red-600"
            >Delete</a
          >
        </td>
      </tr>
      <?php endwhile; else: ?>
      <tr>
        <td colspan="5" class="px-5 py-12 text-center text-slate-400">
          No products found.
          <a class="text-emerald-600" href="addProduct.php"
            >Create your first one.</a
          >
        </td>
      </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
<?php admin_footer(); ?>
