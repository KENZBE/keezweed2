<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';
require_once __DIR__ . '/../includes/settings.php';
start_admin_session();
if (!admin_is_authenticated()) { header('Location: login.php'); exit; }
header('X-Robots-Tag: noindex, nofollow,noarchive');
header('Content-Type: text/html; charset=UTF-8');
$settings = load_site_settings();
$errors = [];
$saved = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'save') {
    if (!admin_csrf_is_valid((string) ($_POST['csrf'] ?? ''))) $errors[] = 'Session expirée. Rechargez la page.';
    else {
        $telegram = normalize_contact_url('telegram', (string) ($_POST['telegram_url'] ?? ''));
        $whatsapp = normalize_contact_url('whatsapp', (string) ($_POST['whatsapp_url'] ?? ''));
        if ($telegram === null) $errors[] = 'Le lien Telegram doit être valide.';
        if ($whatsapp === null) $errors[] = 'Le lien WhatsApp doit être valide.';
        $settings['telegram_url'] = $telegram ?? '';
        $settings['whatsapp_url'] = $whatsapp ?? '';
        $settings['updated_at'] = gmdate('c');
        if ($errors === []) {
            $saved = save_site_settings($settings);
            if (!$saved) $errors[] = 'Les réglages n’ont pas pu être enregistrés.';
        }
    }
}
?>
<!doctype html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow,noarchive"><title>Réglages · Administration Frankfurtweed</title><link rel="stylesheet" href="../assets/css/admin.css?v=20261011-1"></head><body>
<?php include __DIR__ . '/_nav.php'; ?>
<main class="admin-shell settings-shell">
  <header class="admin-page-header"><div class="admin-heading"><h1>Réglages</h1><p>Modifiez les contacts Telegram et WhatsApp pour les questions générales et l’information de prévention.</p></div></header>
  <?php if ($saved): ?><div class="admin-alert success" role="status" data-admin-toast>Les liens de contact ont été mis à jour.</div><?php endif; ?>
  <?php foreach ($errors as $error): ?><div class="admin-alert error" role="alert" data-admin-toast><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endforeach; ?>
  <form class="admin-settings-form" method="post"><input type="hidden" name="csrf" value="<?= htmlspecialchars(admin_csrf_token(), ENT_QUOTES, 'UTF-8') ?>"><input type="hidden" name="action" value="save">
    <section class="admin-settings settings-section" aria-labelledby="contactSettingsTitle">
      <header class="settings-section-head"><span class="settings-section-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 10h8M8 14h5"/><path d="M5 4h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H9l-5 3v-3a2 2 0 0 1-1-1.73V6a2 2 0 0 1 2-2Z"/></svg></span><div><h2 id="contactSettingsTitle">Contacts pour information générale</h2><p>Ces liens restent visibles sur l’accueil et dans le pied de page. Utilisez-les pour des questions générales et des ressources de prévention.</p></div></header>
      <div class="admin-settings-grid"><div class="admin-field"><label for="telegram_url">Lien Telegram</label><p>Adresse de votre canal d’information ou du contact dédié aux questions générales.</p><input id="telegram_url" name="telegram_url" type="text" value="<?= htmlspecialchars((string) $settings['telegram_url'], ENT_QUOTES, 'UTF-8') ?>" required></div><div class="admin-field"><label for="whatsapp_url">Lien ou numéro WhatsApp</label><p>Laissez ce champ vide pour désactiver le lien WhatsApp.</p><input id="whatsapp_url" name="whatsapp_url" type="text" value="<?= htmlspecialchars((string) $settings['whatsapp_url'], ENT_QUOTES, 'UTF-8') ?>"></div></div>
    </section>

    <div class="admin-settings-savebar"><span>Réservez ces contacts à l’information générale et à la prévention.</span><button class="admin-save" type="submit">Enregistrer les réglages</button></div>
  </form>
</main></body></html>
