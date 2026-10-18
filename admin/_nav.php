<?php
declare(strict_types=1);
?>
<header class="admin-topbar">
  <div class="admin-topbar-inner">
    <a class="admin-brand" href="settings.php" aria-label="Contacts publics Frankfurtweed"><span>Frankfurt</span>weed <small>Admin</small></a>
    <nav class="admin-nav admin-nav-desktop" aria-label="Navigation principale de l’administration">
      <a href="settings.php" aria-current="page"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 10h8M8 14h5"/><path d="M5 4h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H9l-5 3v-3a2 2 0 0 1-1-1.73V6a2 2 0 0 1 2-2Z"/></svg><span>Contacts publics</span></a>
    </nav>
    <div class="admin-topbar-actions">
      <a class="admin-site-link" href="../index.php" target="_blank" rel="noopener">Voir le site<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 17 17 7M8 7h9v9"/></svg></a>
      <form method="post" action="logout.php"><input type="hidden" name="csrf" value="<?= htmlspecialchars(admin_csrf_token(), ENT_QUOTES, 'UTF-8') ?>"><button class="admin-logout" type="submit" aria-label="Se déconnecter"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m10 17 5-5-5-5M15 12H3M14 3h5a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-5"/></svg></button></form>
      <button class="admin-menu-toggle" type="button" aria-expanded="false" aria-controls="adminMobileMenu" aria-label="Ouvrir le menu" data-admin-menu-toggle><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 7h14M5 12h14M5 17h14"/></svg></button>
    </div>
  </div>
</header>
<div class="admin-menu-backdrop" data-admin-menu-backdrop hidden></div>
<nav class="admin-mobile-menu" id="adminMobileMenu" aria-label="Navigation mobile de l’administration" data-admin-mobile-menu hidden>
  <span class="admin-sheet-handle" aria-hidden="true"></span>
  <div class="admin-mobile-menu-head"><div><strong>Navigation</strong><span>Administration Frankfurtweed</span></div><button type="button" aria-label="Fermer le menu" data-admin-menu-toggle><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m7 7 10 10M17 7 7 17"/></svg></button></div>
  <div class="admin-mobile-menu-links">
    <a href="settings.php" aria-current="page"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 10h8M8 14h5"/><path d="M5 4h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H9l-5 3v-3a2 2 0 0 1-1-1.73V6a2 2 0 0 1 2-2Z"/></svg><span><strong>Contacts publics</strong><small>Telegram et WhatsApp</small></span><b>›</b></a>
  </div>
</nav>
<div class="admin-toast-stack" aria-live="polite" aria-atomic="true" data-admin-toast-stack></div>
<div class="admin-confirm-layer" role="dialog" aria-modal="true" aria-labelledby="adminConfirmTitle" data-admin-confirm hidden>
  <section class="admin-confirm-sheet">
    <span class="admin-sheet-handle" aria-hidden="true"></span>
    <span class="admin-confirm-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 6h18M8 6V4h8v2M19 6l-1 15H6L5 6M10 11v6M14 11v6"/></svg></span>
    <h2 id="adminConfirmTitle" data-confirm-title>Confirmer la suppression</h2>
    <p data-confirm-message>Cette action est définitive.</p>
    <div class="admin-confirm-actions"><button class="admin-secondary-button" type="button" data-confirm-cancel>Annuler</button><button class="admin-danger-button" type="button" data-confirm-accept>Supprimer</button></div>
  </section>
</div>
<script src="../assets/js/admin-ui.js?v=20260813-2" defer></script>
