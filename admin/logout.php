<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';

start_admin_session();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && admin_csrf_is_valid((string) ($_POST['csrf'] ?? ''))) {
    admin_logout();
}
header('Location: login.php');
exit;

