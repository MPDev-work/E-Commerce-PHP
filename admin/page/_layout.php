<?php
require_once __DIR__ . '/_bootstrap.php';

function admin_store_name(): string
{
  global $conn;
  static $name = null;

  if ($name !== null) return $name;

  $name = 'Solis Skin';
  $result = $conn->query("SELECT setting_value FROM store_settings WHERE setting_key = 'store_name' LIMIT 1");
  if ($result && ($row = $result->fetch_assoc())) {
    $savedName = trim((string)$row['setting_value']);
    if ($savedName !== '') $name = $savedName;
  }

  return $name;
}

function admin_header(string $title, string $active): void
{
  global $user;
  $storeName = admin_store_name(); ?>
  <!doctype html>
  <html lang="en">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= e($title) ?> | <?= e($storeName) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  </head>

  <body class="bg-[#f2f2f6] text-slate-900 antialiased">
    <header class="fixed inset-x-5 top-0 z-50 flex h-16 items-center justify-between bg-[#f2f2f6]/95 backdrop-blur">
      <a href="overviews.php" class="text-2xl font-semibold tracking-tight uppercase"><?= e($storeName) ?></a>
      <div class="flex items-center gap-4">
        <div class="hidden items-center gap-2 sm:flex">
          <span
            class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-500 font-medium text-white"><?= e(strtoupper(substr($user['username'], 0, 1))) ?></span>
          <span class="flex flex-col justify-center items-start gap-1">
            <span class="block text-base leading-[1]"><?= e($user['username']) ?></span>
            <span class="text-blue-500 text-xs leading-[1]"><?= e($user['roles']) ?></span>
          </span>
        </div>
      </div>
    </header>
    <aside
      class="fixed bottom-0 left-0 top-16 z-20 hidden w-64 flex-col gap-5 overflow-y-auto bg-[#f2f2f6] p-3 md:flex scrollbar-none">
      <?php admin_nav_group(
        'Main menu',
        [['overviews.php', 'Overview', 'house'], ['orders.php', 'Orders', 'bag-check'], ['products.php', 'All
      products', 'box-seam'], ['addProduct.php', 'Add
      product', 'plus-circle'], ['categories.php', 'Categories', 'tags'], ['customers.php', 'Customers', 'people']],
        $active
      );
      ?> <?php admin_nav_group('Account Info', [['editProfile.php', 'Edit
      profile', 'person'], ['settting.php', 'Settings', 'gear']], $active); ?> <?php
                                                                                admin_nav_group('Support', [['helpCenter.php', 'Help
      center', 'question-circle'], ['../../auth/logout.php', 'Log out', 'arrow-bar-left']], $active); ?>
    </aside>
    <main class="relative z-0 min-h-screen pt-16 md:ml-64">
      <div class="min-h-[calc(100vh-4rem)] rounded-tl-[30px] bg-white p-4 md:p-6">
      <?php }
    function admin_nav_group(string $label, array $items, string
    $active): void
    { ?>
        <section>
          <p class="mb-2 px-2 text-sm text-slate-400"><?= e($label) ?></p>
          <div class="rounded-[25px] bg-white p-[5px] flex flex-col gap-1">
            <?php foreach ($items as [$href, $name, $icon]): $isActive = $active
                === $href; ?>
              <a href="<?= e($href) ?>"
                class="flex h-10 items-center gap-3 rounded-[30px] px-3 text-sm transition <?= $isActive ? 'bg-black text-white' : 'text-slate-500 hover:bg-black hover:text-white' ?>"><i
                  class="bi bi-<?= e($icon) ?>"></i><?= e($name) ?></a>
            <?php endforeach; ?>
          </div>
        </section>
      <?php }
    function admin_footer(): void
    { ?>
        </span>
    </main>
  </body>

  </html>
<?php }
    function page_title(
      string $eyebrow,
      string $title,
      string $description,
      ?array $action = null
    ): void { ?>
  <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
    <div>
      <p class="text-sm text-emerald-500"><?= e($eyebrow) ?></p>
      <h1 class="text-4xl font-semibold tracking-tight"><?= e($title) ?></h1>
      <p class="mt-1 text-slate-400"><?= e($description) ?></p>
    </div>
    <?php if ($action): ?><a href="<?= e($action[0]) ?>"
        class="rounded-full bg-black px-5 py-3 text-sm text-white transition hover:bg-slate-700"><i
          class="bi bi-<?= e($action[2]) ?> mr-1"></i><?= e($action[1]) ?></a><?php endif; ?>
  </div>
<?php }
