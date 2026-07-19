<?php
require_once __DIR__ . '/_layout.php';
$notice = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    if ($username && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare('UPDATE users SET username = ?, email = ? WHERE id = ?');
        $stmt->bind_param('ssi', $username, $email, $userId);
        $stmt->execute();
        $user['username'] = $username;
        $user['email'] = $email;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $notice = 'Your profile has been updated.';
    } else {
        $notice = 'Enter a name and a valid email address.';
    }
}
admin_header('Edit profile', 'editProfile.php');
page_title('Account', 'Edit profile', 'Keep your administrator details up to date.');
?>
<?php if ($notice): ?><p class="mb-4 rounded-2xl bg-emerald-50 p-4 text-emerald-700"><?= e($notice) ?></p>
<?php endif; ?>
<form method="post" class="max-w-[calc(100vw-256px)] rounded-[28px] bg-[#f2f2f6] p-5">
    <div class="grid gap-4 sm:grid-cols-2"><label class="text-sm font-medium">Display name<input name="username"
                required value="<?= e($user['username']) ?>"
                class="mt-2 w-full rounded-full bg-white px-4 py-3 outline-none"></label><label
            class="text-sm font-medium">Email address<input name="email" type="email" required
                value="<?= e($user['email']) ?>"
                class="mt-2 w-full rounded-full bg-white px-4 py-3 outline-none"></label></div>
    <div class="mt-5 flex items-center justify-between"><span class="text-sm text-slate-400">Role:
            <?= e(ucwords($user['roles'])) ?></span><button
            class="rounded-full bg-black px-6 py-3 text-sm text-white">Save changes</button></div>
</form>
<?php admin_footer(); ?>