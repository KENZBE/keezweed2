<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';

if (admin_is_authenticated()) {
    header('Location: settings.php');
} else {
    header('Location: login.php');
}
exit;
