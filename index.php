<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/shell.php';
$homeDescription = t('hero.lead');
$homeTitle = 'Manchesterweed | ' . t('hero.title') . ' ' . t('hero.accent');
render_head($homeTitle, $homeDescription, 'index.php');
$websiteSchema = [
  '@context' => 'https://schema.org',
  '@type' => 'WebSite',
  'name' => 'Manchesterweed',
  'url' => absolute_url('index.php'),
  'inLanguage' => $lang,
  'description' => $homeDescription,
  'areaServed' => ['Ireland', 'United Kingdom', 'Germany', 'Switzerland'],
  'sameAs' => [TELEGRAM_URL, WHATSAPP_URL !== '' ? WHATSAPP_URL : null],
];
$organizationSchema = [
  '@context' => 'https://schema.org',
  '@type' => 'Organization',
  'name' => 'Manchesterweed',
  'url' => absolute_url('index.php'),
  'logo' => absolute_url('assets/images/skushdrop-hero.png'),
  'sameAs' => [TELEGRAM_URL, WHATSAPP_URL !== '' ? WHATSAPP_URL : null],
];
$homeFaqHeading = [t('home.faq.kicker'), t('home.faq.title')];
$homeFaq = [];
for ($index = 1; $index <= 5; $index++) {
  $homeFaq[] = [t("home.faq.{$index}.question"), t("home.faq.{$index}.answer")];
}
$homeSteps = [
  'heading' => [t('home.steps.kicker'), t('home.steps.title')],
  'items' => [],
];
for ($index = 1; $index <= 4; $index++) {
  $homeSteps['items'][] = [
    str_pad((string) $index, 2, '0', STR_PAD_LEFT),
    t("home.step.{$index}.title"),
    t("home.step.{$index}.copy"),
  ];
}
?>
  <script type="application/ld+json"><?= json_encode([$websiteSchema, $organizationSchema], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
</head>
<body>
<main class="page">
  <section class="hero" id="accueil">
    <nav class="nav container" aria-label="<?= e(t('nav.label')) ?>">
      <a class="brand" href="#accueil" data-admin-trigger data-admin-url="<?= e(page_url('admin/login.php')) ?>"><span>Manchester</span>weed</a>
      <div class="nav-links">
        <a href="#accueil"><?= e(t('nav.home')) ?></a>
        <a href="#how"><?= e(t('nav.how')) ?></a>
        <a href="#reviews"><?= e(t('nav.reviews')) ?></a>
        <a href="#faq"><?= e(t('nav.faq')) ?></a>
        <a href="#contact"><?= e(t('nav.contact')) ?></a>
      </div>
      <span class="adult-badge"><?= e(t('adult')) ?></span>
      <?php render_mobile_menu(); ?>
    </nav>
    <div class="hero-grid container">
      <div class="hero-copy">
        <h1><?= e(t('hero.title')) ?><br><em><?= e(t('hero.accent')) ?></em></h1>
        <p class="lead"><?= e(t('hero.lead')) ?></p>
        <div class="proof" aria-label="<?= e(t('proof.label')) ?>">
          <div class="proof-item"><span class="proof-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 12l2 2 4-4"/><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg></span><span><b><?= e(t('proof.clear')) ?></b><small><?= e(t('proof.clear_sub')) ?></small></span></div>
          <div class="proof-item"><span class="proof-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 12 2 2 4-4"/><path d="m12 3 2.4 2.4 3.4-.5.5 3.4 2.4 2.4-2.4 2.4.5 3.4-3.4-.5L12 19l-2.4-2.4-3.4.5.5-3.4-2.4-2.4 2.4-2.4-.5-3.4 3.4.5L12 3Z"/></svg></span><span><b><?= e(t('proof.multi')) ?></b><small><?= e(t('proof.multi_sub')) ?></small></span></div>
          <div class="proof-item"><span class="proof-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21l7.8-7.5 1.1-1.1a5.5 5.5 0 0 0-.1-7.8Z"/></svg></span><span><b><?= e(t('proof.safe')) ?></b><small><?= e(t('proof.safe_sub')) ?></small></span></div>
        </div>
        <a class="primary-button" href="#contact"><?= e(t('hero.cta')) ?> <span>→</span></a>
      </div>
      <figure class="hero-photo"><img src="assets/images/skushdrop-hero.png" alt="Frankfurtweed" fetchpriority="high"></figure>
    </div>
  </section>

  <section class="channels container" aria-label="<?= e(t('channels.title')) ?>">
    <a class="channel-card telegram-card" href="<?= e(TELEGRAM_URL) ?>" target="_blank" rel="noopener noreferrer"><span class="channel-logo"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21.4 4.6 18.2 20c-.24 1.09-.88 1.36-1.79.85l-4.94-3.64-2.38 2.29c-.26.26-.48.48-.99.48l.35-5 9.1-8.22c.4-.35-.09-.55-.61-.2L5.68 13.63.84 12.12c-1.05-.33-1.07-1.05.22-1.55L20 3.27c.88-.32 1.65.21 1.4 1.33Z"/></svg></span><span><small><?= e(t('telegram.kicker')) ?></small><strong>Telegram</strong><em><?= e(t('telegram.copy')) ?></em></span><span class="corner-arrow">→</span></a>
    <a class="channel-card whatsapp-card" href="<?= e(WHATSAPP_URL !== '' ? WHATSAPP_URL : '#') ?>"<?= WHATSAPP_URL !== '' ? ' target="_blank" rel="noopener noreferrer"' : ' aria-disabled="true"' ?>><span class="channel-logo"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.45 15.35L2 22l4.8-1.5A10 10 0 1 0 12 2Zm0 18.2a8.15 8.15 0 0 1-4.15-1.13l-.3-.18-2.85.89.92-2.77-.2-.32A8.2 8.2 0 1 1 12 20.2Zm4.5-6.13c-.25-.12-1.48-.73-1.7-.81-.23-.08-.4-.12-.57.12-.17.25-.65.8-.8.96-.15.16-.3.18-.55.06-1.5-.75-2.47-1.34-3.46-3.04-.26-.45.26-.42.75-1.4.08-.17.04-.31-.02-.43-.06-.12-.57-1.37-.78-1.87-.2-.49-.42-.42-.57-.42h-.48c-.17 0-.44.06-.67.3-.23.25-.88.86-.88 2.1s.9 2.44 1.03 2.61c.12.17 1.77 2.7 4.28 3.79.6.26 1.07.42 1.43.54.6.19 1.15.16 1.58.1.48-.07 1.48-.6 1.69-1.18.21-.58.21-1.08.15-1.18-.06-.1-.23-.16-.48-.28Z"/></svg></span><span><small><?= e(t('whatsapp.kicker')) ?></small><strong>WhatsApp</strong><em><?= e(t('whatsapp.copy')) ?></em></span><span class="corner-arrow">→</span></a>
  </section>

  <section class="content-section blog-section" id="reviews"><div class="container" id="blog">
    <header class="section-title"><small><?= e(t('info.section.kicker')) ?></small><h2><?= e(t('info.section.title')) ?></h2></header>
    <div class="reviews-summary">
      <div class="reviews-stars"><?= e(t('info.section.mark')) ?></div>
      <div>
        <div class="reviews-count"><?= e(t('info.section.summary')) ?></div>
        <div class="reviews-kicker"><?= e(t('info.section.source')) ?></div>
      </div>
    </div>
    <div class="reviews-grid">
      <?php for ($card = 1; $card <= 3; $card++): ?>
        <article class="review-card">
          <p><?= e(t('info.card' . $card . '.copy')) ?></p>
          <div class="review-author"><?= e(t('info.card' . $card . '.title')) ?></div>
          <div class="review-location"><?= e(t('info.card' . $card . '.meta')) ?></div>
        </article>
      <?php endfor; ?>
    </div>
    <div class="section-action"><a class="outline-button" href="#faq"><?= e(t('info.section.link')) ?> →</a></div>
  </div></section>

  <section class="responsible container" id="approche"><header class="section-title"><small><?= e(t('approach.kicker')) ?></small><h2><?= e(t('approach.title')) ?></h2></header><div class="responsible-grid"><article><span class="responsible-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 12l2 2 4-4"/><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg></span><div><h3><?= e(t('approach.legal')) ?></h3><p><?= e(t('approach.legal_copy')) ?></p></div></article><article><span class="responsible-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 5h6M9 9h6M9 13h4"/><path d="M6 2h12a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Z"/></svg></span><div><h3><?= e(t('approach.verified')) ?></h3><p><?= e(t('approach.verified_copy')) ?></p></div></article><article><span class="responsible-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21l7.8-7.5a5.5 5.5 0 0 0 1-7.9Z"/></svg></span><div><h3><?= e(t('approach.risk')) ?></h3><p><?= e(t('approach.risk_copy')) ?></p></div></article></div></section>

  <section class="content-section steps-home" id="how">
    <div class="container">
      <header class="section-title">
        <small><?= e($homeSteps['heading'][0]) ?></small>
        <h2><?= e($homeSteps['heading'][1]) ?></h2>
      </header>
      <ol class="steps-grid">
        <?php foreach ($homeSteps['items'] as [$number, $title, $description]): ?>
          <li class="step-card">
            <span class="step-number" aria-hidden="true"><?= e($number) ?></span>
            <div>
              <h3><?= e($title) ?></h3>
              <p><?= e($description) ?></p>
            </div>
          </li>
        <?php endforeach; ?>
      </ol>
    </div>
  </section>

  <section class="content-section faq-home" id="faq">
    <div class="container">
      <header class="section-title">
        <small><?= e($homeFaqHeading[0]) ?></small>
        <h2><?= e($homeFaqHeading[1]) ?></h2>
      </header>
      <div class="faq-list">
        <?php foreach ($homeFaq as [$question, $answer]): ?>
          <details>
            <summary><?= e($question) ?><span aria-hidden="true">+</span></summary>
            <p><?= e($answer) ?></p>
          </details>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <div class="container explore-pills" aria-label="<?= e(t('explore.label')) ?>">
    <a href="#how"><?= e(t('nav.how')) ?></a>
    <a href="#reviews"><?= e(t('nav.reviews')) ?></a>
    <a href="#faq"><?= e(t('nav.faq')) ?></a>
  </div>

  <section class="contact-cta container" id="contact"><div><small><?= e(t('cta.kicker')) ?></small><h2><?= e(t('cta.title')) ?></h2><p><?= e(t('cta.copy')) ?></p></div><div class="contact-actions"><a class="telegram-button" href="<?= e(TELEGRAM_URL) ?>" target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21.4 4.6 18.2 20c-.24 1.09-.88 1.36-1.79.85l-4.94-3.64-2.38 2.29.35-5 9.1-8.22c.4-.35-.09-.55-.61-.2L5.68 13.63.84 12.12c-1.05-.33-1.07-1.05.22-1.55L20 3.27c.88-.32 1.65.21 1.4 1.33Z"/></svg><?= e(t('cta.telegram')) ?></a><a class="whatsapp-button" href="<?= e(WHATSAPP_URL !== '' ? WHATSAPP_URL : '#') ?>"<?= WHATSAPP_URL !== '' ? ' target="_blank" rel="noopener noreferrer"' : ' aria-disabled="true"' ?>><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.45 15.35L2 22l4.8-1.5A10 10 0 1 0 12 2Zm0 18.2a8.15 8.15 0 0 1-4.15-1.13l-.3-.18-2.85.89.92-2.77-.2-.32A8.2 8.2 0 1 1 12 20.2Zm4.5-6.13c-.25-.12-1.48-.73-1.7-.81-.23-.08-.4-.12-.57.12-.17.25-.65.8-.8.96-.15.16-.3.18-.55.06-1.5-.75-2.47-1.34-3.46-3.04-.26-.45.26-.42.75-1.4.08-.17.04-.31-.02-.43-.06-.12-.57-1.37-.78-1.87-.2-.49-.42-.42-.57-.42h-.48c-.17 0-.44.06-.67.3-.23.25-.88.86-.88 2.1s.9 2.44 1.03 2.61c.12.17 1.77 2.7 4.28 3.79.6.26 1.07.42 1.43.54.6.19 1.15.16 1.58.1.48-.07 1.48-.6 1.69-1.18.21-.58.21-1.08.15-1.18-.06-.1-.23-.16-.48-.28Z"/></svg><?= e(t('cta.whatsapp')) ?></a></div></section>
</main>
<?php render_footer(); ?>
</body></html>
