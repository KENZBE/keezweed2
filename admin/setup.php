<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';
start_admin_session();

header('X-Robots-Tag: noindex, nofollow, noarchive');
header('Content-Type: text/html; charset=UTF-8');
$canRunSetup = admin_setup_allowed();
$hasCredentials = admin_has_credentials();
$errors = [];
$saved = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $canRunSetup) {
    if (!admin_csrf_is_valid((string) ($_POST['csrf'] ?? ''))) {
        $errors[] = 'Session expirée. Rechargez la page.';
    } else {
        $password = (string) ($_POST['password'] ?? '');
        $confirmation = (string) ($_POST['password_confirmation'] ?? '');
        if ($password !== $confirmation) $errors[] = 'Les deux mots de passe ne correspondent pas.';
        elseif (strlen($password) < 12) $errors[] = 'Le mot de passe doit contenir au moins 12 caractères.';
        elseif (admin_create_password($password)) $saved = true;
        else $errors[] = 'Impossible d’enregistrer le mot de passe.';
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="robots" content="noindex,nofollow,noarchive">
  <title>Configurer l’administration · Frankfurtweed</title>
<link rel="stylesheet" href="../assets/css/admin.css?v=20261011-1">
</head>
<body class="admin-login-page">
  <main class="admin-auth-card">
    <a class="admin-brand" href="../index.php"><span>Frankfurt</span>weed</a>
    <p class="admin-kicker"><?= $hasCredentials ? 'Réinitialisation sécurisée' : 'Première configuration' ?></p>
    <h1><?= $hasCredentials ? 'Choisir un nouveau mot de passe' : 'Créer votre accès' ?></h1>
    <p class="admin-intro"><?= $hasCredentials ? 'Un compte administrateur existe déjà. Définissez un nouveau mot de passe pour ce compte.' : 'Définissez le mot de passe qui protégera l’administration Frankfurtweed.' ?></p>
    <?php foreach ($errors as $error): ?><div class="admin-alert error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endforeach; ?>
    <?php if ($saved): ?>
      <div class="admin-alert success" role="status"><?= $hasCredentials ? 'Le mot de passe administrateur a été mis à jour.' : 'Le mot de passe administrateur a été créé.' ?></div>
      <a class="admin-button" href="login.php">Se connecter</a>
    <?php elseif (!$canRunSetup): ?>
      <div class="admin-alert error" role="alert">La configuration et la réinitialisation sont uniquement accessibles depuis le serveur local.</div>
    <?php else: ?>
      <form method="post" autocomplete="off">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars(admin_csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
        <label for="password">Nouveau mot de passe</label>
        <div class="password-control"><input id="password" name="password" type="password" minlength="12" required autocomplete="new-password"><button class="password-toggle" type="button" data-password-toggle aria-label="Afficher le mot de passe" aria-pressed="false"><svg class="eye-open" viewBox="0 0 24 24" aria-hidden="true"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="3"/></svg><svg class="eye-closed" viewBox="0 0 24 24" aria-hidden="true"><path d="m3 3 18 18M10.6 6.1A9.8 9.8 0 0 1 12 6c6 0 9.5 6 9.5 6a15.7 15.7 0 0 1-2.2 2.8M6.5 6.6C4 8.2 2.5 12 2.5 12s3.5 6 9.5 6c1.5 0 2.8-.4 4-1"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg></button></div>
        <label for="password_confirmation">Confirmer le mot de passe</label>
        <div class="password-control"><input id="password_confirmation" name="password_confirmation" type="password" minlength="12" required autocomplete="new-password"><button class="password-toggle" type="button" data-password-toggle aria-label="Afficher le mot de passe" aria-pressed="false"><svg class="eye-open" viewBox="0 0 24 24" aria-hidden="true"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="3"/></svg><svg class="eye-closed" viewBox="0 0 24 24" aria-hidden="true"><path d="m3 3 18 18M10.6 6.1A9.8 9.8 0 0 1 12 6c6 0 9.5 6 9.5 6a15.7 15.7 0 0 1-2.2 2.8M6.5 6.6C4 8.2 2.5 12 2.5 12s3.5 6 9.5 6c1.5 0 2.8-.4 4-1"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg></button></div>
        <button type="submit"><?= $hasCredentials ? 'Réinitialiser le mot de passe admin' : 'Créer l’accès administrateur' ?></button>
      </form>
    <?php endif; ?>
    <a class="admin-back" href="../index.php">Retour au site</a>
  </main>
  <script src="../assets/js/admin-password.js"></script>
</body>
</html>
