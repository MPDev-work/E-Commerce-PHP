<?php
require_once __DIR__ . '/_layout.php';
admin_header('Help center', 'helpCenter.php');
page_title('Support', 'Help center', 'Quick answers for managing your Solis Skin store.');
?>
<div class="grid max-w-[calc(100vw-256px)] gap-4 md:grid-cols-2">
    <?php foreach ([['How do I add a product?', 'Open Add product from the sidebar, complete the product details, and save it. Your product appears immediately in the catalogue.'], ['Where can I view orders?', 'The Orders page lists every order, customer, total, status, and date. Completed orders also contribute to dashboard revenue.'], ['How do I update my details?', 'Use Edit profile to change your display name or email address. Your administrator role remains protected.'], ['Which image files can I upload?', 'Product uploads accept JPG, JPEG, PNG, and WEBP images. Use a clear square or portrait image for the best catalogue result.']] as [$question, $answer]): ?>
    <article class="rounded-[28px] bg-[#f2f2f6] p-5"><span
            class="mb-4 flex h-11 w-11 items-center justify-center rounded-full bg-blue-500/15 text-blue-500"><i
                class="bi bi-question-lg"></i></span>
        <h2 class="text-lg font-semibold"><?= e($question) ?></h2>
        <p class="mt-2 leading-6 text-slate-500"><?= e($answer) ?></p>
    </article><?php endforeach; ?>
</div>
<div class="mt-5 max-w-[calc(100vw-256px)] rounded-[28px] bg-black p-6 text-white">
    <h2 class="text-xl font-semibold">Need more help?</h2>
    <p class="mt-1 text-slate-300">Contact your store administrator or technical support with the page and action that
        caused the problem.</p><a href="mailto:pisalmony.creator@gmail.com"
        class="mt-4 inline-block rounded-full bg-white px-5 py-2 text-sm text-black">Email support</a>
</div>
<?php admin_footer(); ?>