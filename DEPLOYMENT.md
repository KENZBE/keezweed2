# Déploiement Supabase + Render

Le site peut être déployé avec le Blueprint Render fourni dans `render.yaml`. Les contacts modifiés depuis l’administration sont enregistrés dans PostgreSQL, afin qu’ils restent présents après un redéploiement ou un redémarrage de Render.

## 1. Créer la base Supabase

1. Créez un projet Supabase.
2. Dans les paramètres de connexion à la base, copiez l’URI PostgreSQL destinée à une connexion externe. Elle doit contenir l’hôte, la base, l’utilisateur, le mot de passe et `sslmode=require`.
3. Gardez cette URI privée ; ne l’ajoutez pas aux fichiers du projet.

L’application crée automatiquement les tables `admin_users` et `site_settings` avec le compte utilisé par l’URI.

## 2. Déployer sur Render

1. Créez un Blueprint Render à partir du dépôt contenant ces fichiers.
2. Dans les variables demandées par Render, renseignez `DATABASE_URL` avec l’URI Supabase.
3. Renseignez temporairement `SKUSHDROP_ADMIN_PASSWORD` avec un mot de passe aléatoire d’au moins 12 caractères.
4. Déployez le service et attendez que la vérification de santé passe.
5. Ouvrez `/admin/login.php`, connectez-vous avec le mot de passe fourni, puis configurez Telegram et WhatsApp dans Réglages.
6. Après confirmation que la connexion fonctionne, retirez `SKUSHDROP_ADMIN_PASSWORD` des variables Render. Le mot de passe hashé reste enregistré dans PostgreSQL.

Le lien Telegram initial reste celui du fichier `includes/site-settings.json` jusqu’à sa première modification dans l’administration. Le lien WhatsApp peut rester vide ; sa bulle reste alors visible mais inactive.

## Synchroniser les traductions dans le code

Les phrases restent dans les dictionnaires français, anglais et allemand de `includes/i18n.php`. Un outil PowerShell fourni avec le projet surveille ce fichier depuis VS Code. Après modification et enregistrement d'une phrase existante dans n'importe quelle langue, il traduit cette phrase vers les deux autres langues et écrit les résultats dans les dictionnaires du même fichier PHP. Le site déployé reçoit ainsi les trois versions dans le code.

1. Créez une clé DeepL API, puis ajoutez `DEEPL_AUTH_KEY=votre-cle` dans le fichier local `.env.local` (ce fichier est ignoré par Git). Ne placez jamais la clé dans le code ou dans Render.
2. Ouvrez le dossier du projet dans VS Code et autorisez la tâche automatique « Synchroniser les traductions » à l'ouverture du dossier. Si la tâche n'a pas démarré, lancez-la via Terminal → Exécuter la tâche.
3. Modifiez une seule langue par clé à la fois dans `includes/i18n.php`, puis enregistrez le fichier. Les deux traductions sont écrites dans les autres sections.
4. Vérifiez les changements dans les dictionnaires `fr`, `en` et `de`, puis déployez normalement.

L'état de synchronisation non secret est conservé dans `.translation-sync-state.json` pour détecter quelle langue a été modifiée. Conservez ce fichier avec les changements de traduction dans Git. Si plusieurs langues d'une même clé sont changées avant la synchronisation, l'outil signale l'ambiguïté au lieu d'écraser un texte.

L'outil nécessite PowerShell (inclus avec Windows) et une clé DeepL API locale. Si DeepL est indisponible, les traductions de code ne sont pas modifiées et l'outil signale l'erreur pour permettre de réessayer.

## Connexion avec paramètres séparés

Si vous ne souhaitez pas utiliser une URI `DATABASE_URL`, configurez plutôt les variables Render `SKUSHDROP_DB_DSN`, `SKUSHDROP_DB_USER` et `SKUSHDROP_DB_PASS`. Pour PostgreSQL, le DSN suit ce format :

```text
pgsql:host=HOST;port=5432;dbname=postgres;sslmode=require
```

Ne mettez jamais les mots de passe dans le dépôt, `render.yaml` ou une archive publique. Supabase et Render doivent tous deux autoriser la connexion réseau entre le service et la base.
