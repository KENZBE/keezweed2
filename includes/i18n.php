<?php
declare(strict_types=1);

const SKUSH_LANGS = ['fr', 'en', 'de'];
require_once __DIR__ . '/settings.php';
$siteSettings = load_site_settings();
define('TELEGRAM_URL', (string) $siteSettings['telegram_url']);
define('WHATSAPP_URL', (string) $siteSettings['whatsapp_url']);

$requestedLanguage = strtolower((string) ($_GET['lang'] ?? 'en'));
$lang = in_array($requestedLanguage, SKUSH_LANGS, true) ? $requestedLanguage : 'en';

$translations = [
    'fr' => [
        // Navigation
        'nav.home' => 'Accueil',
        'nav.explore' => 'Découvrir',
        'nav.countries' => 'Pays',
        'nav.articles' => 'Conseils',
        'nav.info' => 'Information',
        'nav.faq' => 'FAQ',
        'nav.account' => 'Mon compte',
        'nav.how' => 'Guide',
        'nav.reviews' => 'Repères santé',
        'nav.contact' => 'Contact',
        'nav.label' => 'Navigation principale',

        // Age confirmation
        'adult' => '18+ · Adultes',
        'age.title' => 'Avez-vous 18 ans ou plus ?',
        'age.copy' => 'Ce site contient des informations et des contenus réservés aux adultes. Confirmez votre âge pour continuer.',
        'age.confirm' => 'Oui, j’ai 18 ans ou plus',
        'age.leave' => 'Non, quitter le site',
        'age.note' => 'En entrant, vous confirmez avoir l’âge légal requis dans votre pays.',
        'age.denied_title' => 'Accès réservé aux adultes',
        'age.denied_copy' => 'Vous ne pouvez pas accéder au contenu de Frankfurtweed.',
        'age.back' => 'Retourner à la page précédente',

        // Main banner
        'hero.title' => ' Official Manchester Weed.',
        'hero.accent' => 'Telegram Hub.',
        'hero.lead' => 'Nous vous connectons à la communauté Telegram de Manchester Weed la plus fiable au monde. Que vous soyez résident ou visiteur, notre plateforme garantit un accès sécurisé à tous les réseaux Telegram vérifiés de Manchester Weed à travers la ville.',
        'hero.cta' => 'Contact',

        // Key messages
        'proof.clear' => 'Information claire',
        'proof.clear_sub' => 'Des ressources accessibles',
        'proof.multi' => 'Contenus utiles',
        'proof.multi_sub' => 'Des sujets expliqués simplement',
        'proof.safe' => 'Contacts d’information',
        'proof.safe_sub' => 'Telegram et WhatsApp',
        'proof.label' => 'Nos engagements',

        // Contact links
        'channels.title' => 'Contacts pour information générale',
        'telegram.kicker' => 'Information et prévention',
        'telegram.copy' => 'Contactez-nous pour vos achat et question.',
        'whatsapp.kicker' => 'Information et prévention',
        'whatsapp.copy' => 'Écrivez-nous pour vos achat et question.',
        'cta.kicker' => 'Contacts ',
        'cta.title' => 'Besoin d’une information générale ?',
        'cta.copy' => 'Telegram et WhatsApp sont proposés ',
        'cta.telegram' => 'Information sur Telegram',
        'cta.whatsapp' => 'Information sur WhatsApp',
        'contact.bubble.telegram' => 'Telegram — ',
        'contact.bubble.whatsapp' => 'WhatsApp — ',
        'contact.bubble.whatsapp_unavailable' => 'WhatsApp — ajoutez le lien dans les réglages admin',

        // Information topics
        'categories.kicker' => 'Comprendre les sujets',
        'categories.title' => 'Information et prévention',
        'categories.copy' => 'Des repères généraux sur les effets, les risques, la santé et les règles locales.',
        'cat.cannabis' => 'Cannabis et cannabinoïdes',
        'cat.cannabis_copy' => 'Informations générales sur les effets possibles et les risques pour la santé.',
        'cat.stimulants' => 'Stimulants',
        'cat.stimulants_copy' => 'Repères de prévention et informations sur les risques associés aux stimulants.',
        'cat.psy' => 'Psychédéliques',
        'cat.psy_copy' => 'Informations générales sur les effets, la sécurité et les ressources de soutien.',
        'cat.gear' => 'Santé et sécurité',
        'cat.gear_copy' => 'Ressources pour prendre des décisions éclairées et réduire les risques.',

        // Articles
        'journal.kicker' => 'Informer et rassurer',
        'journal.title' => 'Nos articles & conseils',
        'journal.all' => 'Voir tous les articles',
        'article.read' => 'Lire l’article',

        // Health and prevention
        'approach.kicker' => 'Pour mieux vous informer',
        'approach.title' => 'Des repères fiables et responsables',
        'approach.legal' => 'Règles locales',
        'approach.legal_copy' => 'Les lois varient selon les pays. Vérifiez les informations auprès des autorités officielles de votre région.',
        'approach.verified' => 'Sources de santé',
        'approach.verified_copy' => 'Pour un avis médical adapté à votre situation, adressez-vous à un professionnel de santé.',
        'approach.risk' => 'Aide en urgence',
        'approach.risk_copy' => 'En cas de symptôme grave ou de danger immédiat, contactez les services d’urgence locaux.',

        // Information cards
        'info.section.kicker' => 'Information et prévention',
        'info.section.title' => 'Manchester bewertet uns mit 4,9 von 5.',
        'info.section.mark' => 'Temoignage',
        'info.section.summary' => 'VOS approches',
        'info.section.source' => 'NOS SOLUTION',
        'info.card1.copy' => 'Commandé à midi sur WhatsApp, javais mon herbe à 16h. Lemballage était discret. De loin la meilleure herbe que jai essayée à Mancherster weed.',
        'info.card1.title' => 'Amelia R.',
        'info.card1.meta' => 'Telegram',
        'info.card2.copy' => 'Les produits comestibles sont incroyables. Les réponses sur Telegram ont pris deux minutes et la livraison est arrivée le lendemain matin. C’est comme ça que le shopping en ligne devrait être.',
        'info.card2.title' => 'SAM D.',
        'info.card2.meta' => 'Whatsapp',
        'info.card3.copy' => 'As a first-time buyer I checked their lab reports first — clean, high-quality, premium weed. Three orders later, Im a monthly customer.',
        'info.card3.title' => 'Liam S.',
        'info.card3.meta' => 'google',
        'info.section.link' => 'Consulter les questions fréquentes',

        // Footer
        'footer.explore' => 'Explorer',
        'footer.legal' => 'Mentions légales',
        'footer.note' => 'Manchester Weed Telegram te connecte avec les réseaux locaux de weed. Notre plateforme est réservée aux adultes (18+). Nos produits ne sont pas des médicaments : ils ne sont pas faits pour diagnostiquer, traiter, guérir ou prévenir une maladie. À ne pas utiliser si vous êtes enceinte ou si vous allaitez. Consulte un médecin avant utilisation si tu prends des médicaments.',
        'footer.connect' => 'Contacts',

        // Interface labels
        'info_bubble.label' => 'Informations utiles',
        'info_bubble.title' => 'Information et prévention',
        'info_bubble.copy' => 'Consultez les réponses générales et les ressources de prévention.',
        'info_bubble.faq' => 'Questions fréquentes',
        'info_bubble.resources' => 'Ressources et prévention',
        'language' => 'Langue',
        'back_home' => 'Retour à l’accueil',
        'updated' => 'Mis à jour le 11 août 2026',
        'explore.label' => 'Explorer la page',

        // Guide and FAQ
        'home.steps.kicker' => 'Pour bien vous informer',
        'home.steps.title' => 'Comprendre les sujets, étape par étape',
        'home.step.1.title' => 'Choisissez vos produit',
        'home.step.1.copy' => '– des fleurs, des produits comestibles ou des extraits – ainsi que votre budget. Nous vous recommanderons l\'option qui vous convient le mieux.',
        'home.step.2.title' => 'Ecrivez vos message',
        'home.step.2.copy' => 'Telegram, WhatsApp ou Snapchat. De vraies personnes répondent en quelques minutes de 10h à 20h, sept jours sur sept.',
        'home.step.3.title' => 'Virement bancaire ou carte ', 
        'home.step.3.copy' => ' choisissez ce qui vous est le plus facile. Le paiement en espèces est disponible dans tout Belfast.',
        'home.step.4.title' => 'Emballage discret et sans logo.',
        'home.step.4.copy' => ' Livraison par coursier ou en main propre dans tout Belfast ; livraison le lendemain partout ailleurs.',
        'home.faq.kicker' => 'À savoir',
        'home.faq.title' => 'Questions fréquentes',
        'home.faq.1.question' => 'Les règles sont-elles les mêmes dans tous les pays ?',
        'home.faq.1.answer' => 'Non. Les lois varient selon le pays et peuvent distinguer possession, usage, vente, transport et importation. Consultez les informations officielles à jour de votre région.',
        'home.faq.2.question' => 'Quels effets le THC peut-il avoir ?',
        'home.faq.2.answer' => 'Les effets varient selon la personne, la quantité et le mode de consommation. Le THC peut altérer l’attention, la mémoire, la coordination et le jugement. Évitez de conduire ou d’utiliser des machines sous son influence.',
        'home.faq.3.question' => 'Le cannabis peut-il présenter des risques pour la santé ?',
        'home.faq.3.answer' => 'Oui. Les effets peuvent inclure anxiété, confusion, accélération du rythme cardiaque ou troubles de la coordination. Les risques dépendent notamment de la personne et du produit. Un professionnel de santé peut vous conseiller.',
        'home.faq.4.question' => 'Le THC peut-il être détecté lors d’un dépistage ?',
        'home.faq.4.answer' => 'Oui, certains tests recherchent le THC ou ses métabolites. La durée de détection varie selon plusieurs facteurs; aucun délai précis ne peut être garanti.',
        'home.faq.5.question' => 'Que faire en cas de réaction grave ?',
        'home.faq.5.answer' => 'En cas de difficulté à respirer, perte de connaissance, convulsions, douleur thoracique ou détresse sévère, contactez immédiatement les services d’urgence locaux.',
    ],    'en' => [
        // Navigation
        'nav.home' => 'Home',
        'nav.explore' => 'Explore',
        'nav.countries' => 'Countries',
        'nav.articles' => 'Guides',
        'nav.info' => 'Information',
        'nav.faq' => 'FAQ',
        'nav.account' => 'My account',
        'nav.how' => 'Guide',
        'nav.reviews' => 'Health guidance',
        'nav.contact' => 'Contact',
        'nav.label' => 'Main navigation',

        // Age confirmation
        'adult' => '18+ · Adults',
        'age.title' => 'Are you 18 or older?',
        'age.copy' => 'This website contains information and content intended for adults. Confirm your age to continue.',
        'age.confirm' => 'Yes, I am 18 or older',
        'age.leave' => 'No, leave this website',
        'age.note' => 'By entering, you confirm that you meet the legal age requirement in your country.',
        'age.denied_title' => 'Adults-only access',
        'age.denied_copy' => 'You cannot access Frankfurtweed content.',
        'age.back' => 'Return to the previous page',

        // Main banner
        'hero.title' => 'Official Manchester Weed.',
        'hero.accent' => 'Telegram Hub.',
        'hero.lead' => 'Connecting you to the world\'s most reliable Manchester Weed Telegram community. Whether you are a resident  a visitor, our platform ensures safe access to every verified Manchester Weed Telegram network across the city.',
        'hero.cta' => 'Contact',

        // Key messages
        'proof.clear' => 'Clear information',
        'proof.clear_sub' => 'Accessible resources',
        'proof.multi' => 'Helpful content',
        'proof.multi_sub' => 'Topics explained simply',
        'proof.safe' => 'Information contacts',
        'proof.safe_sub' => 'Telegram and WhatsApp',
        'proof.label' => 'Our commitments',

        // Contact links
       'channels.title' => 'Contacts for general information',
       'telegram.kicker' => 'Information and prevention',
       'telegram.copy' => 'Contact us for your purchases and questions.',
       'whatsapp.kicker' => 'Information and prevention',
       'whatsapp.copy' => 'Write to us for your purchases and questions.',
       'cta.kicker' => 'Contacts ',
       'cta.title' => 'Need general information?',
       'cta.copy' => 'Telegram and WhatsApp are available ',
       'cta.telegram' => 'Information on Telegram',
       'cta.whatsapp' => 'Information on WhatsApp',
       'contact.bubble.telegram' => 'Telegram — ',
       'contact.bubble.whatsapp' => 'WhatsApp — ',
       'contact.bubble.whatsapp_unavailable' => 'WhatsApp — add the link in admin settings',

        // Information topics
        'categories.kicker' => 'Understand the topics',
        'categories.title' => 'Information and prevention',
        'categories.copy' => 'General guidance about effects, risks, health and local regulations.',
        'cat.cannabis' => 'Cannabis and cannabinoids',
        'cat.cannabis_copy' => 'General information about possible effects and health risks.',
        'cat.stimulants' => 'Stimulants',
        'cat.stimulants_copy' => 'Prevention guidance and information about risks associated with stimulants.',
        'cat.psy' => 'Psychedelics',
        'cat.psy_copy' => 'General information about effects, safety and support resources.',
        'cat.gear' => 'Health and safety',
        'cat.gear_copy' => 'Resources to support informed decisions and reduce risks.',

        // Articles
        'journal.kicker' => 'Inform and reassure',
        'journal.title' => 'Our articles & advice',
        'journal.all' => 'View all articles',
        'article.read' => 'Read article',

        // Health and prevention
        'approach.kicker' => 'For informed decisions',
        'approach.title' => 'Reliable, responsible guidance',
        'approach.legal' => 'Local regulations',
        'approach.legal_copy' => 'Rules differ by country. Check information with official authorities in your area.',
        'approach.verified' => 'Health resources',
        'approach.verified_copy' => 'For advice about your own health, speak with a qualified healthcare professional.',
        'approach.risk' => 'Emergency support',
        'approach.risk_copy' => 'For severe symptoms or immediate danger, contact local emergency services.',

        // Information cards
        'info.section.kicker' => 'Information et prévention',
        'info.section.title' => 'Manchester bewertet uns mit 4,9 von 5.',
        'info.section.mark' => 'Temoignage',
        'info.section.summary' => 'YOUR approaches',
        'info.section.source' => 'our solutions',
        'info.card1.copy' => 'Ordered at noon on WhatsApp, had my weed by 4pm. Packaging was discreet. Easily the best Weed I\'ve tried in Belfast',
        'info.card1.title' => 'Amelia R.',
        'info.card1.meta' => 'Telegram',
        'info.card2.copy' => 'The edibles are unreal. Telegram replies took two minutes and delivery was next morning. This is how online shopping should feel.',
        'info.card2.title' => 'SAM D.',
        'info.card2.meta' => 'Whatsapp',
        'info.card3.copy' => 'As a first-time buyer I checked their lab reports first — clean, high-quality, premium weed. Three orders later, I\'m a monthly customer',
        'info.card3.title' => 'liam S.',
        'info.card3.meta' => 'google',
        'info.section.link' => 'Read frequently asked questions',

        // Footer
        'footer.explore' => 'Explore',
        'footer.legal' => 'Legal',
        'footer.note' => 'Manchester Weed Telegram connects you with local weed networks. Our platform is for adults only (18+). Our products are not medicines: they are not meant to diagnose, treat, cure, or prevent any disease. Not for use by pregnant or breastfeeding women. Consult a doctor before use if you are taking medication.',
        'footer.connect' => 'Contacts',

        // Interface labels
        'info_bubble.label' => 'Helpful information',
        'info_bubble.title' => 'Information and prevention',
        'info_bubble.copy' => 'Browse general answers and prevention resources.',
        'info_bubble.faq' => 'Frequently asked questions',
        'info_bubble.resources' => 'Resources and prevention',
        'language' => 'Language',
        'back_home' => 'Back to home',
        'updated' => 'Updated 11 August 2026',
        'explore.label' => 'Explore this page',

        // Guide and FAQ
        'home.steps.kicker' => 'A guide to the information',
        'home.steps.title' => 'Understand the topics, step by step',
        'home.step.1.title' => 'Choose a topic',
        'home.step.1.title' => 'Choose your products',
        'home.step.1.copy' => '– flowers, edibles, or extracts – as well as your budget. We will recommend the option that suits you best.',
        'home.step.2.title' => 'Write your messages',
        'home.step.2.copy' => 'Telegram, WhatsApp, or Snapchat. Real people respond within minutes from 10 a.m. to 8 p.m., seven days a week.',
        'home.step.3.title' => 'Bank transfer or card',
        'home.step.3.copy' => 'choose whichever is easiest for you. Cash payment is available throughout Belfast.',
        'home.step.4.title' => 'Discreet, logo-free packaging.',
        'home.step.4.copy' => 'Delivery by courier or hand delivery across Belfast; next-day delivery everywhere else.',
        'home.faq.kicker' => 'Good to know',
        'home.faq.title' => 'Frequently asked questions',
        'home.faq.1.question' => 'Are the rules the same in every country?',
        'home.faq.1.answer' => 'No. Laws differ by country and may distinguish possession, use, sale, transport and importation. Check current official information for your location.',
        'home.faq.2.question' => 'What effects can THC have?',
        'home.faq.2.answer' => 'Effects vary by person, amount and method of use. THC can impair attention, memory, coordination and judgment. Do not drive or operate machinery while impaired.',
        'home.faq.3.question' => 'Can cannabis pose health risks?',
        'home.faq.3.answer' => 'Yes. Effects may include anxiety, confusion, a faster heart rate or impaired coordination. Risks depend on the person and the product. A healthcare professional can provide advice.',
        'home.faq.4.question' => 'Can THC be detected in a drug test?',
        'home.faq.4.answer' => 'Yes. Some tests look for THC or its metabolites. Detection time varies with several factors, so no exact timeframe can be guaranteed.',
        'home.faq.5.question' => 'What should I do in a serious emergency?',
        'home.faq.5.answer' => 'For breathing difficulty, loss of consciousness, seizures, chest pain or severe distress, contact local emergency services immediately.',
    ],    'de' => [
        // Navigation
        'nav.home' => 'Startseite',
        'nav.explore' => 'Entdecken',
        'nav.countries' => 'Länder',
        'nav.articles' => 'Ratgeber',
        'nav.info' => 'Information',
        'nav.faq' => 'FAQ',
        'nav.account' => 'Mein Konto',
        'nav.how' => 'Leitfaden',
        'nav.reviews' => 'Gesundheitshinweise',
        'nav.contact' => 'Kontakt',
        'nav.label' => 'Hauptnavigation',

        // Age confirmation
        'adult' => '18+ · Erwachsene',
        'age.title' => 'Sind Sie mindestens 18 Jahre alt?',
        'age.copy' => 'Diese Website enthält Informationen und Inhalte nur für Erwachsene. Bestätigen Sie Ihr Alter, um fortzufahren.',
        'age.confirm' => 'Ja, ich bin mindestens 18',
        'age.leave' => 'Nein, Website verlassen',
        'age.note' => 'Mit dem Eintritt bestätigen Sie, dass Sie das in Ihrem Land geltende Mindestalter erreicht haben.',
        'age.denied_title' => 'Zugang nur für Erwachsene',
        'age.denied_copy' => 'Sie können nicht auf die Inhalte von Frankfurtweed zugreifen.',
        'age.back' => 'Zur vorherigen Seite zurückkehren',

        // Main banner
        'hero.title' => 'Official Manchester Weed.',
        'hero.accent' => 'Telegram Hub.',
        'hero.lead' => 'Wir verbinden Sie mit der weltweit zuverlässigsten Belfast Weed Telegram-Community. Egal, ob Sie Einwohner oder Besucher sind – unsere Plattform gewährleistet sicheren Zugang zu jedem verifizierten Belfast Weed Telegram-Netzwerk in der ganzen Stadt.',
        'hero.cta' => 'Kontakt',

        // Key messages
        'proof.clear' => 'Klare Informationen',
        'proof.clear_sub' => 'Zugängliche Ressourcen',
        'proof.multi' => 'Nützliche Inhalte',
        'proof.multi_sub' => 'Themen einfach erklärt',
        'proof.safe' => 'Informationskontakte',
        'proof.safe_sub' => 'Telegram und WhatsApp',
        'proof.label' => 'Unsere Leitlinien',

        // Contact links
        'channels.title' => 'Kontakte für allgemeine Informationen',
        'telegram.kicker' => 'Information und Prävention',
        'telegram.copy' => 'Kontaktieren Sie uns für Ihre Einkäufe und Fragen.',
        'whatsapp.kicker' => 'Information und Prävention',
        'whatsapp.copy' => 'Schreiben Sie uns für Ihre Einkäufe und Fragen.',
        'cta.kicker' => 'Kontakte ',
        'cta.title' => 'Brauchen Sie allgemeine Informationen?',
        'cta.copy' => 'Telegram und WhatsApp werden angeboten ',
        'cta.telegram' => 'Informationen über Telegram',
        'cta.whatsapp' => 'Informationen über WhatsApp',
        'contact.bubble.telegram' => 'Telegram — ',
        'contact.bubble.whatsapp' => 'WhatsApp — ',
        'contact.bubble.whatsapp_unavailable' => 'WhatsApp — Link in den Admin-Einstellungen hinzufügen',

        // Information topics
        'categories.kicker' => 'Themen verstehen',
        'categories.title' => 'Information und Prävention',
        'categories.copy' => 'Allgemeine Hinweise zu Wirkungen, Risiken, Gesundheit und örtlichen Vorschriften.',
        'cat.cannabis' => 'Cannabis und Cannabinoide',
        'cat.cannabis_copy' => 'Allgemeine Informationen zu möglichen Wirkungen und Gesundheitsrisiken.',
        'cat.stimulants' => 'Stimulanzien',
        'cat.stimulants_copy' => 'Präventionshinweise und Informationen zu Risiken von Stimulanzien.',
        'cat.psy' => 'Psychedelika',
        'cat.psy_copy' => 'Allgemeine Informationen zu Wirkungen, Sicherheit und Hilfsangeboten.',
        'cat.gear' => 'Gesundheit und Sicherheit',
        'cat.gear_copy' => 'Ressourcen für informierte Entscheidungen und zur Risikominderung.',

        // Articles
        'journal.kicker' => 'Informieren und beruhigen',
        'journal.title' => 'Unsere Artikel & Tipps',
        'journal.all' => 'Alle Artikel ansehen',
        'article.read' => 'Artikel lesen',

        // Health and prevention
        'approach.kicker' => 'Für gute Information',
        'approach.title' => 'Zuverlässige und verantwortungsvolle Hinweise',
        'approach.legal' => 'Örtliche Vorschriften',
        'approach.legal_copy' => 'Die Regeln unterscheiden sich je nach Land. Prüfen Sie offizielle Informationen Ihrer Region.',
        'approach.verified' => 'Gesundheitsressourcen',
        'approach.verified_copy' => 'Für persönliche Gesundheitsfragen wenden Sie sich an medizinisches Fachpersonal.',
        'approach.risk' => 'Hilfe im Notfall',
        'approach.risk_copy' => 'Bei schweren Symptomen oder unmittelbarer Gefahr kontaktieren Sie den örtlichen Notdienst.',

        // Information cards
        'info.section.kicker' => 'Information et prévention',
        'info.section.title' => 'Manchester bewertet uns mit 4,9 von 5.',
        'info.section.mark' => 'Bericht',
        'info.section.summary' => 'eure Herangehensweise',
        'info.section.source' => 'unsere Lösungen',
        'info.card1.copy' => 'Mittags über WhatsApp bestellt, um 16 Uhr hatte ich mein Weed. Die Verpackung war diskret. Mit Abstand das beste Weed, das ich in Belfast ausprobiert habe.',
        'info.card1.title' => 'Amelia R.',
        'info.card1.meta' => 'Telegram',
        'info.card2.copy' => 'Die Edibles sind unglaublich. Antworten auf Telegram dauerten zwei Minuten und die Lieferung kam am nächsten Morgen. So sollte Online-Shopping sein.',
        'info.card2.title' => 'SAM D.',
        'info.card2.meta' => 'Whatsapp',
        'info.card3.copy' => 'Als Erstkäufer habe ich zuerst ihre Laborberichte geprüft – sauber, hochwertige, Premium-Weed. Nach drei Bestellungen bin ich jetzt Monatskunde.',
        'info.card3.title' => 'liam S.',
        'info.card3.meta' => 'google', 
        'info.section.link' => 'Häufige Fragen lesen',

        // Footer
        'footer.explore' => 'Entdecken',
        'footer.legal' => 'Rechtliches',
        'footer.note' => 'Belfast Weed Telegram verbindet Sie mit lokalen Weed-Netzwerken. Unsere Plattform ist ausschließlich für Erwachsene (18+). Unsere Produkte sind keine Medikamente: Sie sind nicht dazu gedacht, eine Krankheit zu diagnostizieren, zu behandeln, zu heilen oder vorzubeugen. Nicht für die Verwendung durch schwangere oder stillende Frauen. Konsultieren Sie vor der Anwendung einen Arzt, wenn Sie Medikamente einnehmen.',
        'footer.connect' => 'Kontakte',

        // Interface labels
        'info_bubble.label' => 'Nützliche Informationen',
        'info_bubble.title' => 'Information und Prävention',
        'info_bubble.copy' => 'Hier finden Sie allgemeine Antworten und Präventionsressourcen.',
        'info_bubble.faq' => 'Häufige Fragen',
        'info_bubble.resources' => 'Ressourcen und Prävention',
        'language' => 'Sprache',
        'back_home' => 'Zurück zur Startseite',
        'updated' => 'Aktualisiert am 11. August 2026',
        'explore.label' => 'Diese Seite erkunden',

        // Guide and FAQ
        'home.steps.kicker' => 'Ein Leitfaden zu den Informationen',
        'home.steps.title' => 'Themen Schritt für Schritt verstehen',
        'home.step.1.title' => 'Thema auswählen',
        'home.step.1.title' => 'Wähle deine Produkte',
        'home.step.1.copy' => '– Blumen, Esswaren oder Extrakte – sowie dein Budget. Wir empfehlen dir die Option, die am besten zu dir passt.',
        'home.step.2.title' => 'Schreibe deine Nachrichten',
        'home.step.2.copy' => 'Telegram, WhatsApp oder Snapchat. Echtpersonen antworten innerhalb weniger Minuten von 10 Uhr bis 20 Uhr, sieben Tage die Woche.',
        'home.step.3.title' => 'Banküberweisung oder Karte',
        'home.step.3.copy' => 'Wähle einfach, was dir am leichtesten fällt. Barzahlung ist in ganz Belfast möglich.',
        'home.step.4.title' => 'Diskrete, logo-freie Verpackung.',
        'home.step.4.copy' => 'Lieferung per Kurier oder persönlich in Belfast; Lieferung am nächsten Tag überall sonst.',
        'home.faq.kicker' => 'Gut zu wissen',
        'home.faq.title' => 'Häufige Fragen',
        'home.faq.1.question' => 'Gelten in allen Ländern dieselben Regeln?',
        'home.faq.1.answer' => 'Nein. Die Gesetze unterscheiden sich je nach Land und können Besitz, Konsum, Verkauf, Transport und Einfuhr unterschiedlich regeln. Prüfen Sie die aktuellen offiziellen Informationen für Ihren Wohnort.',
        'home.faq.2.question' => 'Welche Wirkungen kann THC haben?',
        'home.faq.2.answer' => 'Die Wirkung hängt unter anderem von der Person, der Menge und der Konsumform ab. THC kann Aufmerksamkeit, Gedächtnis, Koordination und Urteilsvermögen beeinträchtigen. Fahren Sie nicht und bedienen Sie keine Maschinen, wenn Sie beeinträchtigt sind.',
        'home.faq.3.question' => 'Kann Cannabis gesundheitliche Risiken bergen?',
        'home.faq.3.answer' => 'Ja. Mögliche Wirkungen sind Angst, Verwirrtheit, ein beschleunigter Herzschlag oder beeinträchtigte Koordination. Die Risiken hängen von der Person und dem Produkt ab. Medizinisches Fachpersonal kann beraten.',
        'home.faq.4.question' => 'Kann THC bei einem Drogentest nachgewiesen werden?',
        'home.faq.4.answer' => 'Ja. Manche Tests suchen nach THC oder seinen Abbauprodukten. Die Nachweiszeit hängt von verschiedenen Faktoren ab; ein genauer Zeitraum kann nicht garantiert werden.',
        'home.faq.5.question' => 'Was soll ich bei einem schweren Notfall tun?',
        'home.faq.5.answer' => 'Bei Atemnot, Bewusstlosigkeit, Krampfanfällen, Brustschmerzen oder schwerer psychischer Belastung wenden Sie sich sofort an den örtlichen Rettungsdienst.',
    ],
];

function t(string $key): string
{
    global $translations, $lang;
    return $translations[$lang][$key] ?? $translations['fr'][$key] ?? $key;
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function page_url(string $path, array $params = []): string
{
    global $lang;
    $params = ['lang' => $lang] + $params;
    return $path . '?' . http_build_query($params);
}

function language_url(string $targetLanguage): string
{
    if (!empty($GLOBALS['country_landing'])) {
        return 'index.php?' . http_build_query(['lang' => $targetLanguage]);
    }
    $params = $_GET;
    $params['lang'] = $targetLanguage;
    $path = basename((string) ($_SERVER['SCRIPT_NAME'] ?? 'index.php'));
    return $path . '?' . http_build_query($params);
}

function absolute_url(string $path, array $params = []): string
{
    $configured = rtrim((string) getenv('SKUSHDROP_URL'), '/');
    if ($configured === '') {
        $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        $scheme = $https ? 'https' : 'http';
        $host = (string) ($_SERVER['HTTP_HOST'] ?? 'localhost');
        $script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
        $basePath = rtrim(str_replace('\\', '/', dirname($script)), '/');
        if (preg_match('#/(auth|admin|api)$#', $basePath)) {
            $basePath = preg_replace('#/(auth|admin|api)$#', '', $basePath);
        }
        $basePath = str_replace(' ', '%20', $basePath);
        $configured = $scheme . '://' . $host . $basePath;
    }
    $query = $params ? '?' . http_build_query($params) : '';
    return $configured . '/' . ltrim($path, '/') . $query;
}
