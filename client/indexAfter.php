<?php
// Kept as the login destination for backwards compatibility. The storefront
// detects the session and shows the account link in its shared navigation.
header('Location: index.php');
exit;
