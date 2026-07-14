<?php
require_once __DIR__ . '/_layout.php';
$productCount = scalar($conn, 'SELECT COUNT(*) total FROM products');
$customerCount = scalar($conn, "SELECT COUNT(*) total FROM users WHERE roles <>
'admin'"); $completedOrders = scalar($conn, "SELECT COUNT(*) total FROM orders
WHERE status = 'completed'"); $cancelledOrders = scalar($conn, "SELECT COUNT(*)
total FROM orders WHERE status = 'cancelled'"); $revenue = scalar($conn, "SELECT
COALESCE(SUM(total_amount), 0) total FROM orders WHERE status = 'completed'");
$products = $conn->query('SELECT p_id, p_title, p_price, p_qty, description,
p_image FROM products ORDER BY p_id DESC LIMIT 6'); admin_header('Overview',
'overviews.php'); page_title('Dashboard', 'Welcome back, ' .
ucwords($user['username']) . '.', 'Here is a live summary of your store today.',
['addProduct.php', 'Add product', 'plus-circle']); ?>
<section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-5">
  <?php foreach ([['box-seam','Products',$productCount,'bg-blue-500/15
  text-blue-500'],['bag-check','Completed',$completedOrders,'bg-amber-500/15
  text-amber-500'],['bag-x','Cancelled',$cancelledOrders,'bg-red-500/15
  text-red-500'],['people','Customers',$customerCount,'bg-indigo-500/15
  text-indigo-500'],['currency-dollar','Revenue','$' .
  number_format((float)$revenue, 2),'bg-emerald-500/15 text-emerald-500']] as
  [$icon,$label,$value,$colourClass]): ?>
  <article class="rounded-[24px] bg-[#f2f2f6] p-4">
    <div class="flex items-center gap-3">
      <span
        class="flex h-12 w-12 items-center justify-center rounded-full text-xl <?= $colourClass ?>"
        ><i class="bi bi-<?= $icon ?>"></i
      ></span>
      <div>
        <p class="text-sm text-slate-400"><?= e($label) ?></p>
        <p class="text-2xl font-semibold"><?= e($value) ?></p>
      </div>
    </div>
  </article>
  <?php endforeach; ?>
</section>
<section class="mt-6">
  <div class="mb-3 flex items-end justify-between">
    <div>
      <h2 class="text-2xl font-semibold tracking-tight">Latest products</h2>
      <p class="text-sm text-slate-400">
        Recently added items in your catalogue.
      </p>
    </div>
    <a class="text-sm text-emerald-600 hover:underline" href="products.php"
      >View all</a
    >
  </div>
  <div class="overflow-x-auto rounded-[24px] border border-slate-100">
    <table class="w-full min-w-[650px] text-left text-sm">
      <thead class="bg-black text-white">
        <tr>
          <th class="px-5 py-4">Product</th>
          <th class="px-5 py-4">Price</th>
          <th class="px-5 py-4">Stock</th>
          <th class="px-5 py-4">Description</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($products && $products->num_rows): while ($product =
        $products->fetch_assoc()): ?>
        <tr class="border-b border-slate-100 last:border-0 hover:bg-[#f2f2f6]">
          <td class="flex items-center gap-3 px-5 py-3 font-medium">
            <?php if ($product['p_image']): ?><img
              src="../../images/<?= e($product['p_image']) ?>"
              class="h-11 w-11 rounded-xl object-cover"
              alt=""
            /><?php endif; ?><?= e($product['p_title']) ?>
          </td>
          <td class="px-5 py-3">
            $<?= number_format((float)$product['p_price'], 2) ?>
          </td>
          <td class="px-5 py-3"><?= e($product['p_qty']) ?></td>
          <td class="max-w-xs truncate px-5 py-3 text-slate-500">
            <?= e($product['description']) ?>
          </td>
        </tr>
        <?php endwhile; else: ?>
        <tr>
          <td colspan="4" class="px-5 py-12 text-center text-slate-400">
            No products have been added yet.
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>
<?php admin_footer(); ?>
