<?php
require_once __DIR__ . '/_layout.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['p_title'] ?? ''); $price = (float) ($_POST['p_price'] ?? 0); $qty = (int) ($_POST['p_qty'] ?? 0); $description = trim($_POST['description'] ?? ''); $image = null;
    if ($title !== '' && $price >= 0 && $qty >= 0) { if
(!empty($_FILES['p_image']['name']) && $_FILES['p_image']['error'] ===
UPLOAD_ERR_OK) { $extension = strtolower(pathinfo($_FILES['p_image']['name'],
PATHINFO_EXTENSION)); if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'],
true)) { $image = uniqid('product_', true) . '.' . $extension;
move_uploaded_file($_FILES['p_image']['tmp_name'], __DIR__ . '/../../images/' .
$image); } } $stmt = $conn->prepare('INSERT INTO products (p_title, p_price,
p_qty, description, p_image) VALUES (?, ?, ?, ?, ?)');
$stmt->bind_param('sdiss', $title, $price, $qty, $description, $image); if
($stmt->execute()) { header('Location: products.php?created=1'); exit; }
$message = 'The product could not be saved. Please try again.'; } else {
$message = 'Please complete the required product information.'; } }
admin_header('Add product', 'addProduct.php'); page_title('Catalogue', 'Add a
product', 'Create a new item for your store.'); ?> <?php if ($message): ?>
<p class="mb-4 rounded-2xl bg-red-50 p-4 text-red-600"><?= e($message) ?></p>
<?php endif; ?>
<form
  method="post"
  enctype="multipart/form-data"
  class="max-w-screen rounded-[28px] bg-[#f2f2f6] p-5"
>
  <div class="grid gap-4 md:grid-cols-2">
    <label class="block text-sm font-medium"
      >Product name<input
        required
        name="p_title"
        value="<?= e($_POST['p_title'] ?? '') ?>"
        class="mt-2 w-full rounded-full border border-slate-200 bg-white px-4 py-3 outline-none focus:border-black" /></label
    ><label class="block text-sm font-medium"
      >Price (USD)<input
        required
        min="0"
        step="0.01"
        type="number"
        name="p_price"
        value="<?= e($_POST['p_price'] ?? '') ?>"
        class="mt-2 w-full rounded-full border border-slate-200 bg-white px-4 py-3 outline-none focus:border-black" /></label
    ><label class="block text-sm font-medium"
      >Quantity<input
        required
        min="0"
        type="number"
        name="p_qty"
        value="<?= e($_POST['p_qty'] ?? '0') ?>"
        class="mt-2 w-full rounded-full border border-slate-200 bg-white px-4 py-3 outline-none focus:border-black" /></label
    ><label class="block text-sm font-medium"
      >Product image<input
        id="image"
        accept="image/png,image/jpeg,image/webp"
        type="file"
        name="p_image"
        class="mt-2 block w-full rounded-full border border-slate-200 bg-white px-4 py-3 text-sm"
    /></label>
  </div>
  <label class="mt-4 block text-sm font-medium"
    >Description<textarea
      name="description"
      rows="5"
      class="mt-2 w-full rounded-2xl border border-slate-200 bg-white p-4 outline-none focus:border-black"
    >
<?= e($_POST['description'] ?? '') ?></textarea
    >
  </label>
  <img id="previews" class="w-[240px] h-[240px] object-cover rounded-2xl hidden shadow-[0_0_10px_#00000010] mt-2.5">
  <div class="mt-5 flex gap-3">
    <button class="rounded-full bg-black px-6 py-3 text-sm text-white">
      Save product</button
    ><a
      href="products.php"
      class="rounded-full border border-slate-300 px-6 py-3 text-sm"
      >Cancel</a
    >
  </div>
</form>
<script>
  const imageInput = document.getElementById("image");
  const previews = document.getElementById("previews");

  imageInput.addEventListener("change", function () {
    const file = this.files[0];

    if (!file) return;
    
    previews.src = URL.createObjectURL(file);
    previews.style.display = 'block';
});
</script>
<?php admin_footer(); ?>
