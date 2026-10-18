<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';

start_admin_session();
if (admin_is_authenticated()) {
    header('Location: settings.php');
    exit;
}

header('X-Robots-Tag: noindex, nofollow, noarchive');
header('Content-Type: text/html; charset=UTF-8');
header('X-Frame-Options: DENY');
header("Content-Security-Policy: default-src 'self'; style-src 'self' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; form-action 'self'; frame-ancestors 'none'; base-uri 'self'");

$isSetup = !admin_has_credentials();
$canRunSetup = admin_setup_allowed();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = (string) ($_POST['csrf'] ?? '');
    if (!admin_csrf_is_valid($token)) {
        $error = 'Session expirée. Rechargez la page puis réessayez.';
    } elseif ($isSetup && !$canRunSetup) {
        $error = 'Définissez SKUSHDROP_ADMIN_PASSWORD dans les variables d’environnement de l’hébergement pour initialiser le compte, puis rechargez cette page.';
    } elseif ($isSetup) {
        $password = (string) ($_POST['password'] ?? '');
        $confirmation = (string) ($_POST['password_confirmation'] ?? '');
        if ($password !== $confirmation) {
            $error = 'Les deux mots de passe ne correspondent pas.';
        } elseif (strlen($password) < 12) {
            $error = 'Choisissez un mot de passe d’au moins 12 caractères.';
        } elseif (!admin_create_password($password)) {
            $error = 'Impossible de créer le compte administrateur.';
        } else {
            admin_mark_authenticated();
            header('Location: settings.php');
            exit;
        }
    } elseif (!admin_login((string) ($_POST['password'] ?? ''))) {
        $error = 'Mot de passe incorrect.';
    } else {
        header('Location: settings.php');
        exit;
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex,nofollow,noarchive">
  <title><?= $isSetup ? 'Créer l’accès administrateur' : 'Connexion administrateur' ?> · Frankfurtweed</title>
<link rel="stylesheet" href="../assets/css/admin.css?v=20261011-1">
</head>
<body class="admin-login-page">
  <main class="admin-auth-card">
    <a class="admin-brand" href="../index.php"><span>Frankfurt</span>weed</a>
    <p class="admin-kicker">Administration sécurisée</p>
    <h1><?= $isSetup ? 'Créer votre accès' : 'Connexion' ?></h1>
    <p class="admin-intro"><?= $isSetup ? 'Premier accès : définissez le mot de passe qui protégera l’administration.' : 'Saisissez votre mot de passe pour gérer les contacts publics du site.' ?></p>

    <?php if ($error !== ''): ?><div class="admin-alert error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

    <?php if (!$isSetup || $canRunSetup): ?><form method="post" autocomplete="off">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars(admin_csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
      <label for="password">Mot de passe</label>
      <div class="password-control"><input id="password" name="password" type="password" minlength="12" required autocomplete="<?= $isSetup ? 'new-password' : 'current-password' ?>"><button class="password-toggle" type="button" data-password-toggle aria-label="Afficher le mot de passe" aria-pressed="false"><svg class="eye-open" viewBox="0 0 24 24" aria-hidden="true"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="3"/></svg><svg class="eye-closed" viewBox="0 0 24 24" aria-hidden="true"><path d="m3 3 18 18M10.6 6.1A9.8 9.8 0 0 1 12 6c6 0 9.5 6 9.5 6a15.7 15.7 0 0 1-2.2 2.8M6.5 6.6C4 8.2 2.5 12 2.5 12s3.5 6 9.5 6c1.5 0 2.8-.4 4-1"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg></button></div>
      <?php if ($isSetup): ?>
        <label for="password_confirmation">Confirmer le mot de passe</label>
        <div class="password-control"><input id="password_confirmation" name="password_confirmation" type="password" minlength="12" required autocomplete="new-password"><button class="password-toggle" type="button" data-password-toggle aria-label="Afficher le mot de passe" aria-pressed="false"><svg class="eye-open" viewBox="0 0 24 24" aria-hidden="true"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="3"/></svg><svg class="eye-closed" viewBox="0 0 24 24" aria-hidden="true"><path d="m3 3 18 18M10.6 6.1A9.8 9.8 0 0 1 12 6c6 0 9.5 6 9.5 6a15.7 15.7 0 0 1-2.2 2.8M6.5 6.6C4 8.2 2.5 12 2.5 12s3.5 6 9.5 6c1.5 0 2.8-.4 4-1"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg></button></div>
      <?php endif; ?>
      <button type="submit"><?= $isSetup ? 'Créer l’accès' : 'Se connecter' ?></button>
    </form><?php else: ?><div class="admin-alert error">Pour le premier accès hébergé, ajoutez temporairement SKUSHDROP_ADMIN_PASSWORD (12 caractères minimum) aux variables d’environnement, puis redéployez. La valeur sera enregistrée sous forme de hash dans la base.</div><?php endif; ?>
    <a class="admin-back" href="../index.php">Retour au site</a>
  </main>
  <script src="../assets/js/admin-password.js"></script>
</body>
</html>
