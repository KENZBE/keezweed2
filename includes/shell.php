<?php
declare(strict_types=1);
// Load environment from .env if present (useful when SetEnv isn't available)
@require_once __DIR__ . '/load_env.php';

require_once __DIR__ . '/security.php';
require_once __DIR__ . '/i18n.php';
apply_security_headers(false);

function render_head(string $title, string $description, string $path, string $type = 'website'): void
{
    global $lang;
    $isCountryLanding = !empty($GLOBALS['country_landing']);
    $canonical = $isCountryLanding
        ? absolute_url($path)
        : absolute_url($path, ['lang' => $lang]);
    $socialImage = absolute_url('assets/images/social-preview.png', ['v' => '20261019-1']);
    ?>
<!doctype html>
<html lang="<?= e($lang) ?>">
<head>
  <meta charset="utf-8">
  <meta name="google-site-verification" content="B_U9Wos38O62QutUU3GstCpkfRg14ndSn_T7Axg4Nj4">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#073d2b">
  <link rel="icon" type="image/svg+xml" href="<?= e(absolute_url('assets/images/favicon.svg')) ?>">
  <title><?= e($title) ?></title>
  <meta name="description" content="<?= e($description) ?>">
  <meta name="robots" content="index,follow,max-image-preview:large">
  <link rel="canonical" href="<?= e($canonical) ?>">
  <?php if ($isCountryLanding): ?>
  <link rel="alternate" hreflang="<?= e((string) ($GLOBALS['country_hreflang'] ?? $lang)) ?>" href="<?= e($canonical) ?>">
  <link rel="alternate" hreflang="x-default" href="<?= e($canonical) ?>">
  <?php else: ?>
  <?php foreach (SKUSH_LANGS as $alternate): ?><link rel="alternate" hreflang="<?= e($alternate) ?>" href="<?= e(absolute_url($path, ['lang' => $alternate])) ?>"><?php endforeach; ?>
  <link rel="alternate" hreflang="en-IE" href="<?= e(absolute_url($path, ['lang' => 'en'])) ?>">
  <link rel="alternate" hreflang="en-GB" href="<?= e(absolute_url($path, ['lang' => 'en'])) ?>">
  <link rel="alternate" hreflang="de-DE" href="<?= e(absolute_url($path, ['lang' => 'de'])) ?>">
  <link rel="alternate" hreflang="de-CH" href="<?= e(absolute_url($path, ['lang' => 'de'])) ?>">
  <link rel="alternate" hreflang="fr-CH" href="<?= e(absolute_url($path, ['lang' => 'fr'])) ?>">
  <link rel="alternate" hreflang="x-default" href="<?= e(absolute_url($path, ['lang' => 'en'])) ?>">
  <?php endif; ?>
  <meta property="og:type" content="<?= e($type) ?>">
  <meta property="og:title" content="<?= e($title) ?>">
  <meta property="og:description" content="<?= e($description) ?>">
  <meta property="og:url" content="<?= e($canonical) ?>">
  <meta property="og:site_name" content="Frankfurtweed">
  <meta property="og:image" content="<?= e($socialImage) ?>">
  <meta property="og:image:type" content="image/png">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:image:alt" content="Frankfurtweed — information, prévention et repères généraux">
  <meta property="og:locale" content="<?= $lang === 'de' ? 'de_DE' : ($lang === 'en' ? 'en_GB' : 'fr_CH') ?>">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:image" content="<?= e($socialImage) ?>">
  <meta name="twitter:image:alt" content="Frankfurtweed — information, prévention et repères généraux">
  <script>try{document.documentElement.dataset.ageVerified=localStorage.getItem('skushdrop_age_verified')==='yes'?'true':'false'}catch(e){document.documentElement.dataset.ageVerified='false'}</script>
  <link rel="stylesheet" href="assets/css/v3.css?v=20261011-1">
  <link rel="stylesheet" href="assets/css/pages.css?v=20261011-1">
  <link rel="stylesheet" href="assets/css/age-gate.css?v=20261011-1">
<?php
}

function render_header(): void
{
    ?>
  <nav class="nav page-nav container" aria-label="<?= e(t('nav.label')) ?>">
    <a class="brand" href="<?= e(page_url('index.php')) ?>"><span>Frankfurt</span>weed</a>
    <div class="nav-links">
      <a href="<?= e(page_url('index.php')) ?>"><?= e(t('nav.home')) ?></a>
      <a href="#contact">Contact</a>
    </div>
    <div class="nav-tools">
      <span class="adult-badge"><?= e(t('adult')) ?></span>
      <?php render_mobile_menu(); ?>
    </div>
  </nav>
<?php
}

function render_mobile_menu(): void
{
    ?>
  <details class="mobile-menu">
    <summary aria-label="Menu"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg></summary>
    <div class="mobile-menu-links">
      <a class="mobile-menu-brand" href="<?= e(page_url('index.php')) ?>">Frankfurtweed</a>
      <a href="<?= e(page_url('index.php')) ?>"><?= e(t('nav.home')) ?></a>
      <a href="#contact">Contact</a>
    </div>
  </details>
<?php
}

function language_flag(string $code): string
{
    $ch = '<svg class="flag-icon" viewBox="0 0 30 20" aria-hidden="true"><rect width="30" height="20" rx="2" fill="#D52B1E"/><path d="M12.5 4h5v4h4v4h-4v4h-5v-4h-4V8h4z" fill="#fff"/></svg>';
    $ie = '<svg class="flag-icon" viewBox="0 0 30 20" aria-hidden="true"><rect width="10" height="20" fill="#169B62"/><rect x="10" width="10" height="20" fill="#fff"/><rect x="20" width="10" height="20" fill="#FF883E"/></svg>';
    $gb = '<svg class="flag-icon" viewBox="0 0 30 20" aria-hidden="true"><rect width="30" height="20" rx="2" fill="#21468B"/><path d="M0 0l30 20M30 0L0 20" stroke="#fff" stroke-width="5"/><path d="M0 0l30 20M30 0L0 20" stroke="#CF142B" stroke-width="2"/><path d="M15 0v20M0 10h30" stroke="#fff" stroke-width="6"/><path d="M15 0v20M0 10h30" stroke="#CF142B" stroke-width="3"/></svg>';
    $de = '<svg class="flag-icon" viewBox="0 0 30 20" aria-hidden="true"><rect width="30" height="7" fill="#111"/><rect y="7" width="30" height="6" fill="#DD0000"/><rect y="13" width="30" height="7" fill="#FFCE00"/></svg>';
    switch ($code) {
        case 'en':
            return '<span class="flag-set">' . $ie . $gb . '</span>';
        case 'de':
            return '<span class="flag-set">' . $de . '</span>';
        default:
            return '<span class="flag-set">' . $ch . '</span>';
    }
}

function render_language_switcher(): void
{
    global $lang;
    $labels = ['fr' => ['FR', 'Français · Suisse'], 'en' => ['EN', 'Ireland · UK'], 'de' => ['DE', 'Deutschland · Schweiz']];
    ?>
  <details class="language-switcher">
    <summary aria-label="<?= e(t('language')) ?>"><?= language_flag($lang) ?><b><?= e(strtoupper($lang)) ?></b><svg class="language-chevron" viewBox="0 0 12 8" aria-hidden="true"><path d="m1 1 5 5 5-5"/></svg></summary>
    <div class="language-menu" role="list">
      <?php foreach ($labels as $code => [$short, $label]): ?><a role="listitem" class="<?= $code === $lang ? 'active' : '' ?>" href="<?= e(language_url($code)) ?>" lang="<?= e($code) ?>"><?= language_flag($code) ?><b><?= e($short) ?></b><span><?= e($label) ?></span></a><?php endforeach; ?>
    </div>
  </details>
<?php
}

function render_age_gate(): void
{
    ?>
  <div class="age-gate" id="ageGate" role="dialog" aria-modal="true" aria-labelledby="ageGateTitle" aria-describedby="ageGateCopy">
    <div class="age-gate-backdrop" aria-hidden="true"></div>
    <section class="age-gate-panel" tabindex="-1">
      <span class="age-gate-grabber" aria-hidden="true"></span>
      <div class="age-gate-brand"><span>Manchester</span>weed</div>
      <div class="age-gate-mark" aria-hidden="true"><strong>18</strong><span>+</span></div>
      <div class="age-gate-content">
        <h2 id="ageGateTitle"><?= e(t('age.title')) ?></h2>
        <p id="ageGateCopy"><?= e(t('age.copy')) ?></p>
      </div>
      <div class="age-gate-actions">
        <button class="age-confirm" type="button" data-age-confirm><?= e(t('age.confirm')) ?><svg viewBox="0 0 20 20" aria-hidden="true"><path d="m7 4 6 6-6 6"/></svg></button>
        <button class="age-leave" type="button" data-age-leave><?= e(t('age.leave')) ?></button>
      </div>
      <p class="age-gate-note"><?= e(t('age.note')) ?></p>
      <div class="age-gate-denied" aria-live="polite">
        <h2><?= e(t('age.denied_title')) ?></h2>
        <p><?= e(t('age.denied_copy')) ?></p>
        <button type="button" data-age-back><?= e(t('age.back')) ?></button>
      </div>
    </section>
  </div>
<?php
}

function render_footer(): void
{
    ?>
  <footer class="site-footer">
    <div class="container footer-grid">
      <div><a class="brand" href="<?= e(page_url('index.php')) ?>"><span>Manchester</span>weed</a><p><?= e(t('footer.note')) ?></p></div>
      <div>
        <strong><?= e(t('footer.explore')) ?></strong>
        <a href="<?= e(page_url('index.php')) ?>#how"><?= e(t('nav.how')) ?></a>
        <a href="<?= e(page_url('index.php')) ?>#reviews"><?= e(t('nav.reviews')) ?></a>
        <a href="<?= e(page_url('index.php')) ?>#faq"><?= e(t('nav.faq')) ?></a>
        <a href="<?= e(page_url('index.php')) ?>#contact"><?= e(t('nav.contact')) ?></a>
      </div>
      <div><strong><?= e(t('footer.legal')) ?></strong><span>—</span></div>
      <div><strong><?= e(t('footer.connect')) ?></strong><a href="<?= e(TELEGRAM_URL) ?>" target="_blank" rel="noopener noreferrer">Telegram</a><a href="<?= e(WHATSAPP_URL !== '' ? WHATSAPP_URL : '#') ?>"<?= WHATSAPP_URL !== '' ? ' target="_blank" rel="noopener noreferrer"' : ' aria-disabled="true"' ?>>WhatsApp</a></div>
    </div>
    <div class="container copyright">© 2026 Manchesterweed</div>
  </footer>
  <nav class="contact-bubbles" aria-label="<?= e(t('channels.title')) ?>">
    <a class="contact-bubble contact-bubble--telegram" href="<?= e(TELEGRAM_URL) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= e(t('contact.bubble.telegram')) ?>" title="<?= e(t('contact.bubble.telegram')) ?>">
      <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21.4 4.6 18.2 20c-.24 1.09-.88 1.36-1.79.85l-4.94-3.64-2.38 2.29.35-5 9.1-8.22c.4-.35-.09-.55-.61-.2L5.68 13.63.84 12.12c-1.05-.33-1.07-1.05.22-1.55L20 3.27c.88-.32 1.65.21 1.4 1.33Z"/></svg>
    </a>
    <?php if (WHATSAPP_URL !== ''): ?>
      <a class="contact-bubble contact-bubble--whatsapp" href="<?= e(WHATSAPP_URL) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= e(t('contact.bubble.whatsapp')) ?>" title="<?= e(t('contact.bubble.whatsapp')) ?>">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.45 15.35L2 22l4.8-1.5A10 10 0 1 0 12 2Zm0 18.2a8.15 8.15 0 0 1-4.15-1.13l-.3-.18-2.85.89.92-2.77-.2-.32A8.2 8.2 0 1 1 12 20.2Zm4.5-6.13c-.25-.12-1.48-.73-1.7-.81-.23-.08-.4-.12-.57.12-.17.25-.65.8-.8.96-.15.16-.3.18-.55.06-1.5-.75-2.47-1.34-3.46-3.04-.26-.45.26-.42.75-1.4.08-.17.04-.31-.02-.43-.06-.12-.57-1.37-.78-1.87-.2-.49-.42-.42-.57-.42h-.48c-.17 0-.44.06-.67.3-.23.25-.88.86-.88 2.1s.9 2.44 1.03 2.61c.12.17 1.77 2.7 4.28 3.79.6.26 1.07.42 1.43.54.6.19 1.15.16 1.58.1.48-.07 1.48-.6 1.69-1.18.21-.58.21-1.08.15-1.18-.06-.1-.23-.16-.48-.28Z"/></svg>
      </a>
    <?php else: ?>
      <span class="contact-bubble contact-bubble--whatsapp is-unavailable" aria-label="<?= e(t('contact.bubble.whatsapp_unavailable')) ?>" title="<?= e(t('contact.bubble.whatsapp_unavailable')) ?>" aria-disabled="true">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.45 15.35L2 22l4.8-1.5A10 10 0 1 0 12 2Zm0 18.2a8.15 8.15 0 0 1-4.15-1.13l-.3-.18-2.85.89.92-2.77-.2-.32A8.2 8.2 0 1 1 12 20.2Zm4.5-6.13c-.25-.12-1.48-.73-1.7-.81-.23-.08-.4-.12-.57.12-.17.25-.65.8-.8.96-.15.16-.3.18-.55.06-1.5-.75-2.47-1.34-3.46-3.04-.26-.45.26-.42.75-1.4.08-.17.04-.31-.02-.43-.06-.12-.57-1.37-.78-1.87-.2-.49-.42-.42-.57-.42h-.48c-.17 0-.44.06-.67.3-.23.25-.88.86-.88 2.1s.9 2.44 1.03 2.61c.12.17 1.77 2.7 4.28 3.79.6.26 1.07.42 1.43.54.6.19 1.15.16 1.58.1.48-.07 1.48-.6 1.69-1.18.21-.58.21-1.08.15-1.18-.06-.1-.23-.16-.48-.28Z"/></svg>
      </span>
    <?php endif; ?>
  </nav>
  <?php render_age_gate(); ?>
  <?php render_language_switcher(); ?>
  <script src="assets/js/age-gate.js"></script>
  <script src="assets/js/admin-trigger.js"></script>
<?php
}
