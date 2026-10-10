# ARCHITECTURE & PROJECT MAP — Landing « Abaya Collection »

> **Livre d'architecture technique, cartographie et guide d'intervention chirurgicale.**
> Dépôt : `Litbro1517/abayacollection-theme-clean` (branche `main`) — Thème WordPress **Abaya Canvas**, v1.11.
> Document créé par le mandat « Élaboration de la documentation architecturale & Project Map ».
> Principe directeur : **« Zéro lecture lourde »** — localiser le fichier exact, la fonction exacte et la variable exacte à modifier en **moins de 30 secondes** via l'index de la section 0, puis n'ouvrir que la section concernée.
>
> Document complémentaire à la racine : `PROJECT_MAP.md` (cartographie synthétique, historique des versions du thème). Le modèle des constantes d'environnement vit dans le bloc en tête de `functions.php` et en §3.4 (le fichier `wp-config-meta-sample.php` a été purgé au nettoyage v1.11 : valeurs placeholders, aucune référence code).
> Ce document décrit **exclusivement du code vérifié ligne à ligne dans ce dépôt**. Ce qui relève de l'hébergement ou du dashboard Cloudflare (hors dépôt) est explicitement marqué « *constat* » ou « *à confirmer par l'exploitant* » — aucune donnée inventée.

---

## SECTION 0 — INDEX DE RECHERCHE RAPIDE (LA RÈGLE DES 30 SECONDES)

**Procédure** : ① repérez l'intention dans la colonne « Je veux… » → ② ouvrez le fichier indiqué à la ligne indiquée → ③ lisez la section ciblée de ce document si besoin de contexte (§ renvoyé).

| Je veux… | Fichier | Ancre (ligne) | Détails |
|---|---|---|---|
| Changer un prix d'offre (299/499/699) | `functions.php` | `abaya_catalog()` → `'bundles'` (l. 137) | §1.3, §5.3 |
| Ajouter / retirer une couleur ou taille | `functions.php` | `abaya_catalog()` → `'colors'` / `'sizes'` (l. 131-141) | §5.2 |
| Rendre une couleur indisponible (stock épuisé, v1.10) | `functions.php` + `front-page.php` + `app.js` | drapeau `out_of_stock` (l. 134/138) ; garde 6b (l. 517-522) ; `#stock-overlay` + `syncOutOfStockState` | §5.2 |
| Renommer une couleur (nomenclature) | `functions.php` | clé + champ `label` du catalogue (l. 131-139) | §1.3, §5.2 |
| Changer l'image d'une couleur | `functions.php` | `image` / `thumb` du catalogue + fichiers `uploads/` (l. 132-139) | §5.2 |
| Changer le prix/badge d'accroche du hero | `front-page.php` | `.price-row` (l. 53-57) + catalogue | §2.2 F7 |
| Modifier le formulaire de commande (champs) | `front-page.php` | `#order-form` (l. 101-126) + `abaya_handle_create_order()` | §2.2 F7/F8 |
| Modifier la validation client (nom, téléphone…) | `assets/js/app.js` | handler `submit` de `#order-form` (l. 283-323) | §2.2 F12 |
| Modifier la validation serveur (prix, whitelist) | `functions.php` | `abaya_handle_create_order()` (l. 444-619) | §2.2 F8, §1.5 |
| Modifier le comportement des erreurs couleur/taille | `assets/js/app.js` | `setChoiceError` (l. 38) / `flagChoiceError` (l. 48) + `styles.css` bloc « Mandat 4P » (fin de fichier) | §2.2 F12, §6.2 |
| Changer le style du contour rouge d'erreur | `assets/css/styles.css` | `.choice-group.attribute-error-box` + `@keyframes abaya-shake` (l. 1223-1244) | §6.2 |
| Modifier la page de remerciement /merci/ | `page-merci.php` | tout le gabarit (154 l.) + CSS inline l. 25-54 | §2.2 F9 |
| Changer le texte du bandeau d'annonce | `template-parts/site-header.php` | `.announcement` (l. 4-7) | §2.2 F10 |
| Changer les liens du footer | `template-parts/site-footer.php` | `.footer-columns` (l. 11-30) | §2.2 F10 |
| Changer les titres/descriptions des pages légales | `inc-pages.php` | `abaya_pages()` (l. 5-16) | §2.2 F2 |
| Changer le contenu d'une page légale | `pages/{slug}.html` | fichiers HTML purs | §2.2 F13 |
| Modifier le Pixel / le CAPI | `functions.php` | stub Pixel (l. 218-229) / listener CAPI (l. 243-293) | §4.2, §4.3 |
| Changer les limites anti-abus (rate-limit) | `functions.php` | `abaya_rate_limit('order', 5, …)` (l. 478) / `('review', 3, …)` (l. 625) | §2.2 F8 |
| Changer la fenêtre d'idempotence | `functions.php` | `abaya_find_recent_order_by_event_id($client_event_id, 10 * MINUTE_IN_SECONDS)` (l. 462) | §2.2 F8 |
| Changer le produit commandé | `wp-config.php` (hébergement) | `define('LANDING_PRODUCT_ID', 15)` — bloc documenté en tête de `functions.php` et §3.4 | §3.4 |
| Changer l'image LCP (photo principale) | `functions.php` + `uploads/` | `abaya_lcp_image_src()` (l. 150-156) — chemin issu du catalogue | §6.2 |
| Changer le téléphone WhatsApp | `front-page.php` (l. 302) + `template-parts/site-footer.php` (l. 28) + `page-merci.php` (`$abaya_wa_number`, l. 59) | recherche `212698738664` | §6.2 |
| Changer les polices | `assets/fonts/` + `@font-face` en tête de `assets/css/styles.css` + `critical.css` (`__FONT_BASE__`) | §6.2 |
| Modifier le CSS critique (above-the-fold) | `assets/css/critical.css` | inline via hook `wp_head` p5 (functions.php l. 172-196) | §6.2 |

> ⚠️ Numéros de lignes = état du dépôt v1.11 (nettoyage export). Ils peuvent dériver de quelques lignes lors des évolutions — s'appuyer d'abord sur le **nom d'ancre** (fonction/classe/ID), jamais sur le numéro seul.

---

## SECTION 1 — INTRODUCTION & SCHÉMA DIRECTEUR (PROJECT MAP)

### 1.1 Identité & périmètre

| Élément | Valeur |
|---|---|
| Produit | Landing page **mono-produit** (une abaya), marché marocain, contenu **arabe RTL** |
| Modèle économique | COD — paiement à la livraison (passerelle WooCommerce `cod`, titre « الدفع عند الاستلام ») |
| Socle | **Thème WordPress classique** (PHP + HTML + JS vanilla) nommé **Abaya Canvas** (`style.css`, text domain `abaya-canvas`) |
| Version du thème | **v1.11** (`style.css` l. 4) — journal complet en §5.1 |
| E-commerce | WooCommerce utilisé **uniquement en coulisses** (création de commandes REST) ; aucun CSS/JS WooCommerce ne charge sur le front (déqueue massif, functions.php l. 86-93) |
| Environnements | Production `abayacollection.store` · Staging `dev.abayacollection.store` (§3.3) |
| Front-end dependencies | **Zéro** (pas de jQuery, pas de framework, pas de Google Fonts — polices auto-hébergées) |

### 1.2 Schéma global — arborescence annotée

```
abayacollection-theme-clean/
├── style.css                        # EN-TÊTE du thème uniquement (métadonnées complètes, Version 1.11) — aucun CSS réel
├── functions.php  (720 l.)          # ★ CERVEAU : constantes, head, catalogue, REST, CAPI, sécurité
├── inc-pages.php   (17 l.)          # abaya_pages() : registre des 8 pages (slug → titres/metas)
├── header.php                       # <head> minimal (lang=ar dir=rtl) + ouverture <body> + choix du header
├── footer.php                       # choix du footer + barre CTA sticky (front) + wp_footer
├── front-page.php (345 l.)          # ★ LANDING : galerie, choix, formulaire, offres, avis, FAQ
├── page-merci.php (154 l.)          # ★ /merci/ : récap dynamique sécurisé par order_key
├── index.php / page.php             # gabarits génériques (pages légales) — rendent the_content()
├── template-parts/
│   ├── site-header.php              # bandeau annonce + logo (front)
│   ├── site-footer.php              # logo blanc + 3 colonnes de liens + © (front)
│   ├── legal-header.php             # logo + « العودة إلى صفحة الطلب » (pages légales)
│   └── legal-footer.php             # nav liens légaux + © (pages légales)
├── assets/
│   ├── css/critical.css  (18 Ko)    # CSS critique inline (hero/LCP) — placeholder __FONT_BASE__
│   ├── css/styles.css    (51 Ko)    # FEUILLE UNIQUE : site entier + 23 @font-face + styles erreur 4P
│   ├── js/app.js        (423 l.)    # ★ UNIQUE script front (type="module", ES pur)
│   ├── fonts/                        # 5 woff2 VARIABLES : Cairo-arabic/latin/latin-ext, DMSans-latin/latin-ext
│   └── images/                       # logo-header-color.svg, logo-footer-white.svg
├── uploads/                          # 9 visuels produit webp 1170×1560 + favicon-officiel.svg
│   └── thumbs/                       # 8 miniatures ~170px (< 6 Ko) pour le sélecteur de couleurs
├── pages/                            # 8 graines HTML (contenu des pages légales, injectées à l'activation)
├── PROJECT_MAP.md                    # cartographie synthétique + historique versions thème
└── ARCHITECTURE_PROJECT_MAP.md       # ce document
```

★ = les 4 fichiers où vit 95 % de la logique.

### 1.3 Cartographie des données — origine unique → propagation

**Règle d'or du projet : toute donnée métier n'a qu'UNE source de vérité.** Les tableaux ci-dessous listent chaque donnée, sa source, et ses consommateurs. *Ne jamais dupliquer une donnée métier dans un second fichier* (c'est la duplication front/serveur qui a causé l'écart de prix historique 598/897 vs 499/699 MAD).

| Donnée | Source unique | Consommateurs |
|---|---|---|
| 8 couleurs (nom, hex, label ال…, image, thumb, `out_of_stock` optionnel v1.10) | `functions.php` → `abaya_catalog()['colors']` (l. 131-140) | vignettes + swatches (`front-page.php`), page merci (visuel), whitelist REST (422) + garde stock épuisé 6b, `window.LANDING.catalog.colors`, alts/aria dérivés du `label` |
| 5 tailles `S M L XL XXL` | `abaya_catalog()['sizes']` (l. 134) | boutons taille (`front-page.php`), whitelist REST, `window.LANDING.catalog.sizes` |
| Grille tarifaire `1=>299, 2=>499, 3=>699` (MAD) | `abaya_catalog()['bundles']` (l. 137) — **autorité serveur** | cartes d'offre (`front-page.php`), prix imposé sur la ligne de commande, `window.LANDING.catalog.bundles` (affichage JS) |
| Libellés/économies des offres | `abaya_catalog()['bundle_labels']` (l. 138-142) | badges cartes d'offre |
| Image LCP (#main-photo) | `abaya_lcp_image_src()` (l. 150) — lit `catalog.colors['بيج'].image` | preload `wp_head` p1 + `<img>` front-page.php l. 24 (URL strictement identique) |
| Endpoint REST + URL merci | hooks `wp_head` p5 (l. 200-216) → `window.LANDING` = `{endpoint, thanks, review_endpoint, catalog}` | `app.js` (fetch + redirection) |
| Textes légaux (titre H1, `<title>`, meta desc) | `inc-pages.php` → `abaya_pages()` (l. 5-16) | `pre_get_document_title` + `abaya_meta_description()` (functions.php l. 26-46), création auto des pages (`abaya_ensure_pages()` l. 683) |
| Contenu des pages légales | `pages/{slug}.html` | injecté en base à l'activation (`abaya_ensure_pages()`), rendu par `index.php`/`page.php` via `the_content()` |
| Choix de la cliente (couleur/taille/qty) | **runtime JS** : `state` dans `app.js` (l. 13) | résumé commande, payload REST, événements Pixel |
| Prix affiché dans le résumé JS | `window.LANDING.catalog.bundles` (repli local identique l. 18) | `updateSummary()` |
| Constantes d'environnement | `wp-config.php` de l'hébergement (bloc documenté en tête de `functions.php` et §3.4) : `LANDING_PRODUCT_ID`, `META_PIXEL_ID`, `META_CAPI_TOKEN`, `META_TEST_EVENT_CODE` | handler REST, stub Pixel, listener CAPI |

**Nomenclature officielle des 8 couleurs** : `أسود`, `بيج`, `كاكي`, `أبيض`, `أزرق داكن`, `بني`, `وردي ترابي`, `أحمر داكن` — la couleur bordeaux est nommée **`أحمر داكن`** partout ; l'ancien libellé `عنابي` est banni du code source (les commandes historiques portant `عنابي` en méta restent affichées telles quelles : données, pas code).

### 1.4 Glossaire des composants & découpage UI

| Bloc UI | Fichier / ancre DOM | Rôle | Dynamique |
|---|---|---|---|
| Bandeau d'annonce | `site-header.php` `.announcement` | réassurance livraison/paiement | statique |
| En-tête logo | `site-header.php` `.site-header` | wordmark SVG cliquable | statique |
| **Galerie produit (hero)** | `front-page.php` `.gallery` — `#main-photo` (l. 24), `.thumbnail-row` (l. 29), flèches `.gallery-prev/.gallery-next` | photo principale 1170×1560 (LCP) + 8 vignettes | clic vignette/flèche → swap `src` fondu (`updatePhoto`, app.js l. 60) ; zoom pointeur souris |
| **Sélecteur de couleurs** | `front-page.php` `fieldset[data-attribute="color"]` (l. 59), `.swatches` (8 pastilles) | choix couleur obligatoire, aucune présélection | clic → `selectColor()` (app.js l. 76) : état, `is-selected`, photo, légende `#color-value` |
| **Sélecteur de tailles** | `front-page.php` `fieldset[data-attribute="size"]` (l. 73), `.sizes` (5 boutons) + lien `جدول المقاسات` (l. 82) | choix taille obligatoire | clic → handler app.js l. 119 ; modal taille `<dialog id="size-guide-dialog">` (l. 306) |
| **Cartes d'offre (bundles)** | `front-page.php` `.bundle-grid` (l. 138), générées en boucle catalogue | 1/2/3 pièces 299/499/699 MAD + économies | clic → `state.quantity` + `updateSummary()` ; scroll vers formulaire |
| **Résumé de commande** | `front-page.php` `.order-summary` (l. 90-96) — `#summary-choice`, `#summary-price` | reflète `state` en temps réel (`aria-live="polite"`) | `updateSummary()` (app.js l. 97) |
| **Formulaire de commande** | `front-page.php` `#order-form` (l. 101-126) : nom, téléphone, ville, adresse + honeypot `extra_note` + bouton `.form-submit` | capture les coordonnées, envoie au REST | submit → validations → fetch POST (app.js l. 283+) |
| Micro-erreurs attributs (4P) | slots `<small class="field-error choice-error" data-choice-error="…">` (l. 63, 75) + contour `.attribute-error-box` | signaler le bloc manquant | `setChoiceError`/`flagChoiceError` (app.js l. 38/48) |
| Résultat d'envoi | `#form-result` (l. 127) | message succès/erreur réseau | `showFormError()` (app.js l. 271) |
| Barre CTA sticky | `footer.php` `.sticky-cta-btn` (l. 4-6) | « اضغطي هنا للطلب » ancré `#order-form-section` | ancre natif |
| Preuves sociales / **Avis** | `front-page.php` `#avis-clients` (l. 254), cartes rendues **serveur** par `abaya_render_review_card()` | liste des avis approuvés (type `review`) | bouton « إضافة تقييمك » → `<dialog id="review-dialog">` (l. 323) → POST REST |
| **FAQ** | `front-page.php` `#faq` (l. 274) | 4 questions/réponses statiques | statique |
| Sections réassurance visuelles | `front-page.php` `.visual-section` ×3 (l. 150-209) + `.trust-strip` (l. 211-226) + guide de commande 3 étapes (l. 228-249) | argumentaire, inspection colis, délais | statiques |
| Contact WhatsApp | `front-page.php` `#contact-section` (l. 297-300) + footer | lien `wa.me/212698738664` | statique |
| Modales | `<dialog class="info-modal">` ×2 (l. 306, 323) | taille-guide + formulaire d'avis | `[data-modal]` → `showModal()` (app.js l. 180-190) |
| **Page merci** | `page-merci.php` `.merci-page` | récap commande sécurisé, frise 3 étapes, WhatsApp prérempli | rendu PHP conditionné par `?order=ID&key=KEY` |

### 1.5 Flux de données macro (3 flux)

**① Commande (flux critique)**

```
Cliente (navigateur)                       WordPress (serveur)
────────────────────                       ───────────────────
app.js : state {color,size,quantity}
submit #order-form
  → validations locales (nom≥3, tel MA,
    ville≥2, adresse≥4, couleur+taille)
  → POST JSON /wp-json/landing/v1/create-order ──► abaya_handle_create_order() :
                                                    honeypot → WooCommerce → idempotence
                                                    (event_id, 10 min) → rate-limit (5/10 min/IP)
                                                    → revalidation champs → whitelist catalogue (422)
                                                    → prix AUTORITAIRE bundles[qty]
                                                    → wc_create_order + ligne (prix pack, Item Meta
                                                      اللون/المقاس) → COD → statut processing → note
                                                    → méta marketing (_abaya_*) → CAPI Purchase
  ◄── JSON {success, order_id, value,
           event_id, product_id, order_key}
fbq Purchase (eventID = event_id, value serveur)
  → location.href = /merci/?order=ID&key=KEY ──► page-merci.php : hash_equals(order_key)
                                                  + < 48 h + created_via=landing-abaya
                                                  → récap dynamique (sinon version générique)
```

**② Avis** : `#review-form` (dialog) → POST `/landing/v1/submit-review` → rate-limit 3/10 min → `wp_insert_comment` (type `review`, statut *en attente*) → affichage front après validation manuelle (rendu serveur `abaya_render_review_card`).

**③ Tracking** : Pixel navigateur (PageView + ViewContent au chargement ; InitiateCheckout au focus du formulaire ; Purchase au succès) **+** CAPI serveur (Purchase, dédupliqué par `event_id`) — détail complet en section 4.

---

## SECTION 2 — ARCHITECTURE TECHNIQUE & NATURE DU CODE

### 2.1 Stack technique & mode de rendu (réalité du projet)

| Dimension | Réalité (vérifiée dans le dépôt) |
|---|---|
| Framework | **Aucun** — thème WordPress « classique » en PHP procédural + HTML + **JavaScript vanilla (ES module)**. Ni React, ni Next.js, ni Vite, ni Tailwind. |
| Rendu | **Server-Side Rendering PHP à chaque requête** (WordPress) ; app.js n'*hydrate* pas : il attache des écouteurs sur le DOM rendu. Aucune SPA, aucune routing client. |
| Build / bundler | **AUCUN** — `npm run build`, `vite build`, etc. **n'existent pas**. Les fichiers servis sont les fichiers du dépôt. La « compilation » se réduit au cache-busting `?v=filemtime()` calculé à l'exécution (`abaya_asset_ver()`, functions.php l. 96-99). |
| CSS | 2 feuilles maison : `critical.css` (inline dans le `<head>`, hero above-the-fold) + `styles.css` (chargé **asynchrone** `media="print"` + `onload`, repli `<noscript>`). Variables CSS natives (`--olive`, `--swatch-color`…). |
| JS | Un seul fichier module : `app.js` (pas de transpilation, pas de polyfill, APIs natives : `fetch`, `<dialog>`, `URLSearchParams`, `matchMedia`). |
| Polices | Auto-hébergées, **variables woff2** par sous-ensemble (unicode-range) : Cairo (arabic/latin-ext/latin) + DM Sans (latin/latin-ext) — 23 `@font-face` dans styles.css, 3 dans critical.css. |
| Base de données | MySQL via WordPress ; commandes WooCommerce (compatible HPOS — le code n'utilise que `wc_get_orders`/méta standard). |
| PHP | Compatible PHP 8.x (opérateurs modernes, `match` non utilisé — code conservatif). |
| RTL | `lang="ar" dir="rtl"` (header.php l. 3) ; `.sizes` repasse en `direction: ltr` (ordre S→XXL). |

**Environnements d'exécution** : voir section 3 (WordPress + nginx + Cloudflare + plugin LiteSpeed Cache ; WooCommerce actif).

### 2.2 Anatomie des composants & modules (décorticage fichier par fichier)

> Convention : « **État** » = données qui varient à l'exécution (JS) ou configuration persistée (WP). « **Événements** » = écouteurs DOM (JS) ou hooks WordPress (PHP). Chaque fiche est auto-suffisante.

---

**F1 — `style.css`** · *En-tête de thème WordPress*
- Nature : métadonnées obligatoires WP (Nom `Abaya Canvas`, Version `1.9`, Text Domain `abaya-canvas`). Aucun CSS réel.
- ⚠️ Ne pas y mettre de styles : la feuille réelle est `assets/css/styles.css`.

**F2 — `inc-pages.php`** · *Registre des pages* (17 l.)
- Nature : utilitaire PHP. Une fonction : `abaya_pages()` → tableau `slug => [titre H1, <title>, meta description]` pour 8 pages : `about`, `modes-paiement`, `livraison`, `conditions-utilisation`, `conditions-retour`, `politique-confidentialite`, `contact`, `merci`.
- État : aucune. Événements : aucune (fonction pure, appelée par functions.php).
- ⚠️ Le contenu des pages vit dans `pages/{slug}.html` ; ce fichier ne gère que titres/descriptions.

**F3 — `header.php`** · *Gabarit coquille* 
- Nature : gabarit UI. `<head>` minimal (charset, viewport, theme-color `#f8f6f2`, description conditionnelle) + `wp_head()` + `<body class="product-landing|legal-page">` + `wp_body_open()` + inclusion `site-header` (front) ou `legal-header` (autres).
- État : aucune. Événements : délègue à `wp_head` (hooks functions.php priorités 1 et 5).

**F4 — `footer.php`** · *Gabarit coquille*
- Nature : gabarit UI. Front → `site-footer` + **barre CTA sticky** (lien `#order-form-section`) ; autres pages → `legal-footer`. Clôt par `wp_footer()`.
- Événements : aucun JS (ancre HTML native).

**F5 — `index.php` / `page.php`** · *Gabarits génériques* (identiques, 17 l.)
- Nature : gabarits UI des pages légales : boucle WP (`the_title()`, `the_content()`), repli « page non trouvée ». `wpautop`/`wptexturize` sont retirés (functions.php l. 67-69) : le HTML des pages est servi tel quel.

**F6 — `template-parts/` (4 partiels)** · *Blocs d'en-tête/pied*
- `site-header.php` : `.announcement` (livraison gratuite + 🇲🇦) + `.site-header` (logo SVG).
- `site-footer.php` : logo blanc + nav 3 colonnes (`عن المتجر`, `الشروط والسياسات`, `اتصل بنا` dont WhatsApp `wa.me/212698738664`) + `© 2026`.
- `legal-header.php` / `legal-footer.php` : équivalents simplifiés pour les pages légales.
- État/événements : aucun (statiques).

**F7 — `front-page.php`** · *LA LANDING* (345 l.) · Nature : gabarit PHP alimenté par le catalogue
- Données d'entrée : `abaya_catalog()` (boucles vignettes l. 32-44, swatches l. 64-69, tailles l. 74-79, cartes d'offre l. 138-146) ; `abaya_lcp_image_src()` (l. 24) ; cache-busting bordeaux `$visuel_ver` (l. 13-17).
- Structure : `.hero` (galerie + product-info) → `.order-section` (`#order-form-section`, résumé, `#order-form`) → `.bundles-section` → 3 `.visual-section` → `.trust-strip` → `.order-guide` → `#avis-clients` → `#faq` → `#contact-section` → 2 `<dialog>`.
- Ancres critiques : `#main-photo` (l. 24, LCP — **ne rien ajouter avant**), fieldsets `data-attribute="color|size"` + slots `data-choice-error` (l. 59-76), `#order-form` (l. 101) avec honeypot `.hp-field input[name=extra_note]` (l. 122), section d'avis avec rendu serveur des commentaires approuvés (l. 260-267).
- État : aucun (le gabarit est sans état ; l'état vit dans app.js).
- Événements : aucun listener ici (tous dans app.js).

**F8 — `functions.php`** · *LE CERVEAU* (706 l.) · Nature : logique métier + intégrations + sécurité. Découpage linéaire :

| Lignes | Bloc | Rôle |
|---|---|---|
| 4-20 | require inc-pages + constantes | `ABAYA_PAGES_VERSION 1.2` ; défauts `META_*` vides, `LANDING_PRODUCT_ID` 15 (surchargeables par wp-config) |
| 22-46 | titres & descriptions | `pre_get_document_title` (front : « Abaya Collection ») + `abaya_meta_description()` |
| 48-64 | /merci/ protégée | `wp_robots` noindex + `template_redirect` : `nocache_headers()` + hook LiteSpeed `litespeed_control_set_nocache` |
| 66-93 | nettoyage head | retrait emoji/RSD/generator/oEmbed + **dequeue** CSS/JS WooCommerce & blocks (front léger) |
| 96-99 | `abaya_asset_ver()` | cache-busting `?v=filemtime()` |
| 105-156 | ★ `abaya_catalog()` + `abaya_lcp_image_src()` | **source unique de vérité** (couleurs/tailles/bundles/labels) + URL LCP |
| 160-170 | hook `wp_head` **p1** | preload image LCP (`fetchpriority=high`) + 2 polices critiques (Cairo-arabic, DMSans-latin) — front uniquement |
| 172-230 | hook `wp_head` **p5** | favicon, **critical.css inline** (`__FONT_BASE__` remplacé), styles.css async + preload + noscript, `window.LANDING`, `<script type="module" app.js>`, **stub Meta Pixel** (PageView + ViewContent) si `META_PIXEL_ID` |
| 232-293 | listener CAPI | `abaya_order_created` → POST `graph.facebook.com/v19.0/{pixel}/events` (timeout 5 s, **non bloquant**), `user_data` hashé SHA-256 (ph, ct, fn, ln) + `fbp/fbc`, `data = wp_json_encode([$event])` (encodage unique — corrige le double-encodage historique), `test_event_code` optionnel |
| 295-372 | sécurité réseau | `abaya_cloudflare_ranges()` (15 IPv4 + 7 IPv6), `abaya_ip_in_ranges()` (CIDR v4/v6), `abaya_client_ip()` (CF-Connecting-IP seulement derrière plages CF ; XFF ignoré derrière CF, legacy hors CF) |
| 374-399 | idempotence + rate-limit | `abaya_find_recent_order_by_event_id()` (balayage 20 commandes < fenêtre, compatible HPOS/CPT) ; `abaya_rate_limit()` (transients `abaya_rl_{md5(bucket|IP)}`) |
| 401-423 | helpers | `abaya_normalize_phone_ma()` (→ 06/07XXXXXXXX), `abaya_reviews_target_post_id()` (page_on_front sinon produit) |
| 425-440 | `abaya_render_review_card()` | carte d'avis serveur (5 étoiles SVG, note `rating` en méta commentaire) |
| 442-619 | ★ `abaya_handle_create_order()` | handler REST commande — séquence 12 étapes détaillée §1.5① ; prix pack imposé sur la **ligne** (`add_product(..., ['subtotal'=>$price,'total'=>$price])`), Item Meta clair `اللون`/`المقاس` + `save()`, COD, statut processing, note service client, méta `_abaya_*` (event_id, page_url, color/size, client_ip, utm_*, _fbp/_fbc), réponse `+ order_key` |
| 621-666 | `abaya_handle_submit_review()` | rate-limit 3/10 min → rating 1-5 + commentaire 3-2000 car. → `wp_insert_comment` (type `review`, **statut 0 = en attente**) |
| 668-680 | `rest_api_init` | 2 routes POST publiques (`permission_callback => __return_true`) : `landing/v1/create-order`, `landing/v1/submit-review` |
| 682-707 | `abaya_ensure_pages()` | à l'activation du thème + chaque `admin_init` (versionnée par option `abaya_pages_version`) : permaliens `/%postname%/`, création des 8 pages depuis `pages/*.html` **sans jamais écraser**, `flush_rewrite_rules()` |

- État persisté : options WP (`abaya_pages_version`, `permalink_structure`), transients rate-limit, commandes WooCommerce + méta.
- Événements émis : `do_action('abaya_order_created', $payload, $order)` (l. 595).

**F9 — `page-merci.php`** · *Page de remerciement sécurisée* (154 l.)
- Nature : gabarit PHP auto-associé au slug `merci` (hiérarchie `page-{slug}.php`).
- Sécurité (l. 61-75) : `?order=ID&key=KEY` obligatoire → `wc_get_order` → **`hash_equals(order_key)`** (timing-safe) → fraîcheur **< 48 h** → `created_via === 'landing-abaya'` ; sinon **version générique sans aucune donnée**. Anti-cache : voir F8 l. 60-64 + `<meta name="referrer" content="no-referrer">` (l. 24). CSS spécifique **inline** (l. 25-54, priorité 6) : zéro ressource externe ajoutée.
- Contenu dynamique : titre féminisé avec prénom, carte récap (visuel couleur via catalogue, taille, couleur, total formaté, téléphone, adresse), frise 3 étapes (Reçu → Confirmation tél → Expédition), WhatsApp prérempli « طلبي رقم #… » (`$abaya_wa_number = '212698738664'`, l. 59), référence de commande.
- Couleur/taille lues : méta commande `_abaya_color/_abaya_size` **puis repli** Item Meta `اللون/المقاس` (l. 87-94 — couvre les commandes antérieures à v1.8).
- Pixel : **n'émet aucun événement** (Purchase déjà émis sur la landing ; pas de double comptage au refresh).

**F10 — `assets/js/app.js`** · *Unique script front* (398 l., ES module) · Nature : gestionnaire d'état + UI + client REST
- **État global (l. 13)** : `const state = { color: null, size: null, quantity: 1 }` — **aucune présélection** (exigence P0-3) ; `quantity` vaut 1 (carte d'offre 1 présélectionnée côté PHP).
- **Dictionnaires** : `bundlePrices` depuis `window.LANDING.catalog.bundles` avec repli local identique (l. 17-18) ; `quantityLabels` (l. 19) ; `CHOICE_ERROR_MESSAGES` (l. 33-36).
- **Fonctions clés** : `updatePhoto` (l. 60, swap fondu anti-course via `photoRequestId`), `selectColor` (l. 76, met à jour vignettes+swatches+photo+légende+erreur), `updateSummary` (l. 97, résumé + cartes d'offre), `setChoiceError`/`flagChoiceError` (l. 38/48, erreurs par bloc 4P : micro-texte + contour + secousse relancée par reflow `void block.offsetWidth`), `setFieldError` (l. 248, erreurs inputs), `showFormError` (l. 271), `track` (l. 267, wrapper `fbq` try/catch).
- **Événements écoutés** : clic `.swatch` (l. 113) ; clic `.size-option` (l. 119) ; clic `.bundle-card` (l. 129) ; clic `.thumbnail` (l. 136) ; flèches galerie (l. 143-152) ; `pointermove/leave` zoom (l. 154-166) ; clic cartes d'offre → scroll `#order-form-section` (l. 168-178, reduced-motion aware) ; `[data-modal]` → `showModal()` (l. 180) ; `focusin` `#order-form` → **InitiateCheckout** (l. 277-281, une seule fois via `checkoutTracked`) ; **submit** `#order-form` (l. 283) ; `input/change` des champs → effacement erreur (l. 254-257) ; submit `#review-form` (l. 205).
- **Submit de commande (l. 283-394)** — séquence : efface `form-result` → mesures champs (téléphone normalisé regex `^(?:(?:\+|00)212)?0?[67]\d{8}$`) → erreurs inputs (`setFieldError`) → erreurs attributs par bloc indépendants (`flagChoiceError`) → scroll vers 1er manquant (couleur prioritaire) sinon focus 1er champ invalide → **return** si invalide (aucun envoi) → POST JSON (credentials `same-origin` pour cookies `_fbp/_fbc`, payload `full_name, phone, city, address, color, size, quantity, website_hp, event_id, page_url` + `utm_*` de l'URL) → succès : **fbq Purchase** (`eventID = out.event_id`, `value = out.value` **serveur**) puis redirection `thanks + ?order=ID&key=KEY` (bouton laissé désactivé) ; échec : message (bouton réactivé).
- **event_id (l. 263)** : `pageEventId` généré **une fois par chargement** → un retry ne peut pas créer de doublon CAPI.
- Contrat REST consommé : `{success, order_id, value, event_id, product_id, order_key}` — **tout ajout doit rester additif**.

**F11 — `assets/css/styles.css` + `critical.css`** · *Présentation*
- `styles.css` (51 Ko, 23 `@font-face`) : tout le site, sections commentées, RTL ; fin de fichier = bloc « Mandat 4P » (`@keyframes abaya-shake` l. 1228, `.choice-group.attribute-error-box` l. 1234, `.choice-error` l. 1240-1241, media reduced-motion l. 1242).
- `critical.css` (18 Ko, 3 `@font-face`) : inline via `wp_head` p5 ; placeholder `__FONT_BASE__` remplacé par l'URL des polices à l'exécution. **Discipline** : n'y mettre que le rendu above-the-fold (validé au pixel) ; chaque octet pèse sur le premier rendu.

**F12 — `pages/*.html` (8 graines)** · Nature : HTML pur (contenu réglementaire arabe). Injectés en base **une fois** (`abaya_ensure_pages`) ; ensuite le contenu vit en base (édition via admin WP ou re-création de page). `merci.html` = graine de la page merci (le gabarit `page-merci.php` prend le dessus au rendu).

**F13 — `uploads/` + `assets/images/` + `assets/fonts/`** · Nature : médias et ressources binaires. 9 visuels plein format (1170×1560 webp), 8 miniatures `thumbs/` (< 6 Ko), favicon **`favicon-officiel.svg`** (front) ; ⚠️ les pages légales référencent `favicon.svg` qui **n'existe pas dans le dépôt** (favicon 404 silencieux sur ces pages — point consigné §5.4). 2 logos SVG. 5 polices woff2.

---

## SECTION 3 — ENVIRONNEMENT D'EXÉCUTION, HÉBERGEMENT & DÉPLOIEMENT

### 3.1 Infrastructure web

**Constats vérifiables depuis le code et les audits d'instance :**

| Couche | Élément | Détail |
|---|---|---|
| CDN / proxy | **Cloudflare** devant les 2 environnements | le code en dépend activement : `abaya_client_ip()` ne fait confiance à `CF-Connecting-IP` que si `REMOTE_ADDR` ∈ plages officielles CF (functions.php l. 352-372) |
| Serveur web | **nginx** (constat production) | XFF traité « à l'ancienne » hors CF (premier IP public de la chaîne) |
| CMS | WordPress + **WooCommerce** | WooCommerce requis à l'exécution (le handler renvoie 500 s'il manque) ; CSS/JS WC déchargés du front |
| Cache | Plugin **LiteSpeed Cache** actif | le thème lui parle : `do_action('litespeed_control_set_nocache')` sur /merci/ |
| PHP | WordPress standard (PHP 8.x) | aucune extension exotique requise (`inet_pton`, `hash`, `mb_*` — standard) |
| Process JS | **Aucun** Node.js en production | pas de build, pas de process manager — pages statiques PHP |

**À confirmer/consigner par l'exploitant (non déductible du dépôt)** : hébergeur et modèle exact du VPS (le mandat mentionne un VPS **Hostinger VKM1** — à valider), OS, version PHP du serveur, panneau de contrôle, sauvegardes. Un tableau vierge est prévu en §5.4 pour consigner ces informations sans toucher au code.

### 3.2 DNS & sécurité Cloudflare

| Sujet | État | Action |
|---|---|---|
| IP client réelle | géré **dans le thème** (CF-Connecting-IP validé par plages CF) | rien à faire côté CF |
| Cache des pages | landing et pages légales : cacheable ; `/merci/` : no-cache émis par PHP (headers + LiteSpeed) | **règle Cloudflare « Cache Bypass » sur `/merci/*` recommandée mais pas encore créée** (dashboard CF — hors thème) |
| SSL/TLS | HTTPS de bout en bout (constat : sites en 200 derrière CF) | conserver Full (strict) si origine certifié |
| Minification/bundler CF | aucune trace de dépendance dans le code | peut rester désactivé (le front est déjà minimal) |
| Cache-busting assets | automatique côté thème (`?v=filemtime`) → toute modification de fichier invalide l'URL | ne pas activer « Cache Everything » agressif sans respecter les query strings |

### 3.3 Architecture multi-environnement

| | Production `abayacollection.store` | Staging `dev.abayacollection.store` |
|---|---|---|
| Rôle | vente réelle (COD) | qualification / tests |
| `LANDING_PRODUCT_ID` | **15** (produit publié, 299 MAD) | **24** (produit publié staging) — imposé par `define()` dans le wp-config staging (le défaut 15 y provoquait l'erreur 500 `abaya_bad_product`) |
| Stack | WP + Woo + CF + LiteSpeed (constat) | idem, instance séparée |
| Données | commandes réelles | commandes de test (#44-#48 suspectées) — ne jamais tester un POST sans le dire |
| Isolation | — | même code, base séparée ; les tests d'affichage se font ici **avant** toute fusion |

**Mécanisme de synchronisation : le dépôt Git est la seule source commune.** Le déploiement = faire tirer le bon commit sur l'instance concernée (tirage `git` sur l'hébergement ou mécanisme d'intégration GitHub→hébergement configuré par l'exploitant). Le mandat historique a prouvé que le déploiement n'est **pas** automatique (correctif poussé mais staging inchangé jusqu'au tirage) — toujours **vérifier la version servie** après push (voir checklist §6.3).

**Règle de conformité en vigueur** : aucun changement de code pour un problème relevant de l'environnement (wp-config, ID produit, réglage staging) — diagnostic + recommandation seulement.

### 3.4 Pipeline de build & configuration (équivalent .env)

- **Commandes de build : AUCUNE.** Il n'existe ni `package.json`, ni composer, ni transpilation. « Déployer » = placer les fichiers à jour sur l'instance (git pull) puis purger le cache LiteSpeed/CF si nécessaire.
- **Équivalent des `.env.*`** : le bloc de constantes dans **`wp-config.php`** de chaque instance (même bloc rappelé en tête de `functions.php` l. 8-20 ; le fichier d'échantillon `wp-config-meta-sample.php` a été purgé au nettoyage v1.11 — voir §5.1) :

```php
// wp-config.php — PRODUCTION
define('LANDING_PRODUCT_ID', 15);
define('META_PIXEL_ID', '1234567890');
define('META_CAPI_TOKEN', 'EAAG…');
// META_TEST_EVENT_CODE : à RETIRER avant les campagnes réelles

// wp-config.php — STAGING (différence clé)
define('LANDING_PRODUCT_ID', 24);
define('META_TEST_EVENT_CODE', 'TEST12345'); // événements routés vers l'outil de test Meta
```

- Commandes d'intervention réelles (poste de développement / instance) :

```bash
# Cycle officiel (voir §6.4)
git checkout -b fix/… && …commit… && git checkout main && git merge --no-ff fix/… && git push origin main
# Sur l'instance WordPress (après push) :
git pull origin main                # + vider le cache LiteSpeed si CSS/JS modifiés
# Vérifier la version servie :
curl -s https://…/ | grep -o 'app.js?ver=[0-9]*'   # ?ver= doit changer (filemtime)
```

---

## SECTION 4 — MODULES TIERCE-PARTIE & INTÉGRATIONS

### 4.1 Inventaire des intégrations (constaté sur l'instance, audit plugins)

| Intégration | Où elle vit | Activée par |
|---|---|---|
| **Meta Pixel (navigateur)** | **thème** — functions.php l. 218-229 (stub + fbevents.js différé sur `load`) | constante `META_PIXEL_ID` (wp-config) |
| **Conversions API (CAPI)** | **thème** — listener `abaya_order_created` (l. 243-293) | constantes `META_CAPI_TOKEN` + `META_PIXEL_ID` |
| **GTM (via plugin GTM4WP)** | plugin WordPress (hors dépôt) — injecte gtm.js + dataLayer | configuration plugin sur l'instance |
| LiteSpeed Cache | plugin WordPress (hors dépôt) | coopération : hook `litespeed_control_set_nocache` |
| WooCommerce | plugin (hors dépôt) | commandes + COD |
| Novamira | plugin constaté sur l'instance | hors périmètre thème |
| n8n / webhooks / Google Apps Script / Sheets | **aucun connecteur détecté** dans le code ni sur l'instance | — |
| SMTP (e-mails transactionnels) | **absent** (constaté) — les e-mails WooCommerce (admin/client) peuvent ne pas partir | ⚠️ point d'attention §5.4 |

### 4.2 Meta Pixel navigateur — mécanisme exact

1. Si `META_PIXEL_ID` est défini non vide, le hook `wp_head` p5 imprime le **stub officiel** `fbq` (file d'attente) puis `fbq('init', ID)` ;
2. `fbevents.js` n'est chargé qu'**après l'événement `load`** de la page (préserve PageSpeed/LCP) ;
3. Événements émis immédiatement : `PageView` + `ViewContent` (`content_type: product`, `content_ids: [LANDING_PRODUCT_ID]`, `currency: MAD`, `value: 299`) ;
4. Événements émis par app.js plus tard : voir table §4.4 (le wrapper `track()` est fail-safe : un blocage du Pixel n'interrompt jamais le tunnel).

### 4.3 Conversions API serveur — mécanisme exact

- Déclencheur : `do_action('abaya_order_created', $payload, $order)` — **une seule fois par commande réelle** (l'idempotence §1.5① empêche le second envoi sur retry).
- Déduplication Pixel/CAPI : même `event_id` des deux côtés (navigateur → `eventID`, serveur → `event_id`) ; Meta fusionne.
- `user_data` (matching avancé) : `ph` (téléphone normalisé +212), `ct` (ville), `fn`/`ln` (prénom/nom) — tous **SHA-256 minuscules** ; `fbp`/`fbc` en clair (cookies collectés car le POST part en `credentials: same-origin`).
- Requête : `POST https://graph.facebook.com/v19.0/{PIXEL_ID}/events`, `access_token` = `META_CAPI_TOKEN`, **`data` = `wp_json_encode([$event])` (la liste encodée UNE fois)**, `test_event_code` si défini, `timeout 5 s`, **`blocking false`** (l'acheteuse n'attend jamais Meta).

### 4.4 Table complète des événements trackés

| Événement | Surface | Déclencheur exact | Emplacement code | Payload clé |
|---|---|---|---|---|
| `PageView` | Pixel | chargement de la landing | functions.php l. 226 | — |
| `ViewContent` | Pixel | chargement de la landing | functions.php l. 227 | content_ids `[15]`, MAD, 299 |
| `InitiateCheckout` | Pixel | **premier `focusin`** dans `#order-form` (1×/page) | app.js l. 277-281 | MAD, value = prix bundle courant |
| `Purchase` (navigateur) | Pixel | réponse `{success:true}` du REST | app.js l. 371-376 | eventID = `out.event_id`, value = `out.value` (serveur), content_ids `[order.product_id]` |
| `Purchase` (serveur) | **CAPI** | `abaya_order_created` après création commande | functions.php l. 243-293 | même `event_id`, value = total réel, user_data hashé |
| *(aucun)* | dataLayer thème | **le thème n'émet aucun `dataLayer.push`** — GTM4WP gère le sien côté plugin | — | pour ajouter un événement GTM : passer par GTM4WP ou ajouter un `window.dataLayer.push` dédié (§6.2) |

> Cohérence comptable : la `value` du Purchase est **toujours** celle du serveur (jamais celle affichée côté client) — garantie anti-écart de prix.

### 4.5 Backend & automation

- **Surface backend = les 2 routes REST** (`landing/v1/create-order`, `landing/v1/submit-review`) — publiques, durcies (honeypot, rate-limit par IP réelle, whitelist catalogue, revalidation intégrale).
- Aucun webhook sortant (hormis CAPI), aucun connecteur CRM/n8n/Sheets. Toute future automatisation (ex. envoi des commandes vers un Sheet) doit se brancher sur `do_action('abaya_order_created')` ou sur les méta `_abaya_*` de la commande — pas sur le front.
- E-mails : dépendance aux réglages WP `wp_mail()` — **aucun plugin SMTP constaté** → à vérifier chez l'exploitant (risque : notifications admin/client non reçues).

---

## SECTION 5 — HISTORIQUE & FEUILLE DE ROUTE (PROJECT MAP DIFFS)

### 5.1 Journal chronologique des modifications (dépôt `abayacollection-theme-clean`)

| Version | Commit(s) | Contenu de l'évolution |
|---|---|---|
| 1.6 (origine) | `36309e2` | État d'origine production (« initialisation du nouveau dossier propre ») |
| — (correctif annulé) | `4ec8efd`/`3ef63f4` puis **revert `23d9418`** | correctif 500 staging par résolution auto de produit → annulé par mandat de conformité (le 500 relevant du wp-config, réglé par `define('LANDING_PRODUCT_ID', 24)`) |
| **1.8** | `e0b8a58` → merge `4dbbe23` | **Mandat global unifié tunnel checkout REST** : `abaya_catalog()` source unique (8 couleurs dont `أحمر داكن`, bundles 299/499/699) + `window.LANDING.catalog` ; handler REST refondu (whitelist 422, prix pack autoritaire sur la ligne, Item Meta اللون/المقاس en clair, note commande, `order_key`) ; suppression des présélections بيج/M + validation obligatoire ; page `/merci/` dynamique sécurisée ; idempotence `_abaya_event_id` 10 min ; IP Cloudflare durcie ; COD imposé. *Parallèle : la lignée v1.7 (mêmes correctifs) avait été livrée sur l'ancien dépôt staging, aligné bit-à-bit — hors périmètre de ce dépôt.* |
| **1.9** | `d04fdfd`/`899d5a0` → merge `bfb5df3` | **Mandat 4P — ergonomie des alertes attributs** : suppression du message global volumineux `#attribute-error` près du bouton ; ciblage par bloc (`fieldset[data-attribute]` + slots `data-choice-error`) : contour rouge 2px (`outline`, zéro décalage), micro-secousse `abaya-shake` relancée à chaque soumission (neutralisée `prefers-reduced-motion`), micro-texte discret au-dessus du bloc (style `.field-error`) effacé dès la sélection ; blocs signalés indépendamment (remplissage partiel géré). |
| **1.10** | branche `feature/out-of-stock-overlay` | **Mandat 4P allégé — « Stock épuisé » (overlay + blocage)** : كاكي et وردي ترابي temporairement épuisées (campagnes pub actives) — gamme de 8 conservée (8 swatches/vignettes, phrase « 8 ألوان » inchangée) ; drapeau `out_of_stock` dans `abaya_catalog()` (l. 134/138, source unique) ; garde serveur 6b → 422 `abaya_out_of_stock` (l. 517-522) ; overlay `#stock-overlay` sur `#main-photo` (voile `rgba(0,0,0,.12)` + badge vitré glassmorphism : fond `rgba(255,255,255,.4)`, `backdrop-filter: blur(8px)`, bordure `1px solid rgba(255,255,255,.9)`, radius 8px, texte rouge `#e53e3e` « غير متوفر حالياً في هذا اللون ») ; bouton de commande désactivé (`.is-unavailable`) + double garde front (`outOfStockColors` + réalignement post-envoi) ; validation : 106/106 statique (bloc OOS-01…07), 63/63 handler (T9b 6/6 disponibles + T9c refus épuisées), 26/26 merci, 16/16 visuel Playwright (clics réels + captures) ; revert préalable vérifié : aucun reliquat du mandat « retrait couleurs » (main resté à 0cf1797). |
| **1.11** | branche `chore/cleanup-export-final` | **Mandat « développement, suivi & export » — nettoyage final** : purge des résidus de développement (`wp-config-meta-sample.php` — zéro référence code, constantes documentées en tête de `functions.php` et §3.4 ; `uploads/LISEZ-MOI.txt` et `assets/images/LISEZ-MOI.txt` — notes obsolètes, fichiers déjà présents) ; **`pages/*.html` conservés** (graines runtime d'`abaya_ensure_pages()` l. 697-720 — la conversion n'est pas « achevée » : ces HTML sont l'architecture F12) ; correction favicon cassé (`favicon.svg` inexistant → `favicon-officiel.svg` sur toutes les pages, functions.php l. 181) ; en-tête `style.css` enrichi (Requires at least 6.0, Requires PHP 7.4, License GPL-2.0-or-later, Version 1.11) ; zéro changement fonctionnel (catalogue, tunnel, overlay, tracking intouchés). |
| — (cette mission) | branche `docs/architecture-project-map` | création du présent livre d'architecture + renvoi depuis PROJECT_MAP.md |

*Consigne : chaque future mission ajoute UNE ligne ici (version/commits/contenu) ET la ligne correspondante dans `PROJECT_MAP.md` §9.*

### 5.2 Procédure « gammes produit » — retirer / ajouter une couleur ou une taille

**Tout se passe dans `abaya_catalog()` (functions.php l. 125-152) — JAMAIS dans front-page.php ni app.js** (les boucles et la validation s'adaptent toutes seules ; `window.LANDING` suit automatiquement).

**Retirer une couleur en rupture (ex. `وردي ترابي`) — 5 minutes :**
1. Ouvrir `functions.php`, bloc `abaya_catalog()['colors']` (l. 131-140) ;
2. Supprimer la ligne entière `وردي ترابي => ['hex' => …, 'image' => …, 'thumb' => …],` ;
3. Vérifier qu'elle n'est pas l'image LCP : le LCP est `بيج` (l. 157) — si un jour on retire `بيج`, désigner une nouvelle image LCP dans `abaya_lcp_image_src()` **et** vérifier le critical.css ;
4. Optionnel : garder les fichiers `uploads/` (inoffensifs) ;
5. Checklist §6.3 (le rendu doit afficher 7 vignettes/7 pastilles, la whitelist REST doit rejeter la couleur retirée, `window.LANDING.catalog.colors` doit en contenir 7).
- ⚠️ Les commandes passées portant cette couleur en méta restent affichées telles quelles (données ≠ code). La page merci retombera proprement (visuel absent → bloc omis).

**Rendre une couleur temporairement indisponible — « Stock épuisé » (v1.10) — 2 minutes :**
1. Dans `abaya_catalog()`, ajouter `'out_of_stock' => true` à l'entrée de la couleur (l. 134 / l. 138 pour كاكي et وردي ترابي) ; **rétablir = supprimer le drapeau, aucun autre fichier** ;
2. Tout suit automatiquement : le swatch et la vignette restent affichés (gamme complète), `app.js` (Set `outOfStockColors` alimenté par `data-out-of-stock` rendu par PHP) affiche l'overlay `#stock-overlay` (voile + badge vitré) et désactive le bouton de commande, et le handler refuse la commande en 422 `abaya_out_of_stock` — même si le front est contourné.

**Ajouter une couleur (ex. `رمادي`) :**
1. Déposer `uploads/{visuel}.webp` (1170×1560, < ~150 Ko) **et** `uploads/thumbs/{visuel}.webp` (~170 px, < 6 Ko) ;
2. Ajouter dans `abaya_catalog()['colors']` : `'رمادي' => ['hex' => '#808080', 'label' => 'الرمادي', 'image' => 'uploads/…webp', 'thumb' => 'uploads/thumbs/…webp'],` — le `label` est la **forme définie arabe** (ال…) utilisée pour alt/aria ;
3. Rien d'autre : vignettes, pastilles, whitelist, LANDING, page merci suivent automatiquement ;
4. Si la couleur a besoin d'un cache-busting spécifique comme le bordeaux (image remplacée à l'identique), reproduire le mécanisme `$visuel_ver` de front-page.php l. 13-17 ;
5. Checklist §6.3.

**Retirer/ajouter une taille** : même principe sur `'sizes' => ['S','M','L','XL','XXL']` (l. 134) — vérifier en plus la cohérence de la table des tailles (`<dialog id="size-guide-dialog">`, front-page.php l. 306-318).

### 5.3 Procédure « tarifs & offres »

1. Modifier `'bundles' => [1 => 299, 2 => 499, 3 => 699]` (l. 137) **et** les libellés d'économie `bundle_labels` (l. 138-142) ;
2. Le prix affiché par défaut du hero (`front-page.php` l. 54) lit `bundles[1]` → suit automatiquement ; le `value` ViewContent du Pixel est en dur à 299 (l. 227) → **à ajuster si le prix unitaire change** ;
3. L'ancien prix barré `349 درهم` et le pill « توفير 50 درهم » du hero sont du HTML statique (l. 55-56) → à aligner à la main ;
4. Vérifier le résumé JS et les cartes d'offre (auto via `window.LANDING`), puis checklist §6.3 + test commande réelle staging (prix sur la ligne = grille).

### 5.4 Registre à compléter par l'exploitant (informations hors dépôt)

| Rubrique | Valeur à consigner |
|---|---|
| VPS (hébergeur, modèle — ex. Hostinger VKM1 à valider, vCPU/RAM) | … |
| OS + panneau (Ubuntu ?, CyberPanel/cPanel ?) | … |
| Version PHP serveur / mode (FPM ?) | … |
| Mécanisme de déploiement GitHub→instances (webhook ? pull manuel ?) | … |
| Sauvegardes (fichiers + base, fréquence, restauration testée ?) | … |
| Accès Cloudflare (règles existantes, plan) | … |

**Feuille de route / points d'attention connus (état v1.9) :**
- `favicon.svg` référencé par les pages légales mais absent du dépôt (404 silencieux) — ajouter le fichier ou basculer sur `favicon-officiel.svg` ;
- Règle Cloudflare **Cache Bypass `/merci/*`** toujours à créer côté dashboard ;
- **SMTP absent** : vérifier l'envoi réel des notifications WooCommerce (admin + cliente) ;
- COD : vérifier dans Réglages WooCommerce → Paiements que « الدفع عند الاستلام » reste activé ;
- `ViewContent` value en dur (299) : à synchroniser avec la grille si les prix changent (§5.3) ;
- Commandes de test suspectées en staging (#44-#48 même numéro/ville) — purge possible côté admin.

---

## SECTION 6 — PROTOCOLE D'INTERVENTION CHIRURGICALE (ZÉRO RÉGRESSION)

### 6.1 Règles d'isolation (les 8 commandements)

1. **Source unique** : catalogue/prix/nomenclature → uniquement dans `abaya_catalog()` ; jamais de duplication front/serveur.
2. **Périmètre minimal** : une intervention = le(s) fichier(s) strictement nécessaires. Un correctif de style ne touche pas au JS, un correctif JS ne touche pas au handler PHP.
3. **CSS** : styles globaux uniquement dans `styles.css` (sections commentées en fin de fichier pour les ajouts récents) ; sélecteurs **spécifiques** (`.choice-group.attribute-error-box`, pas de `div { }`) ; `critical.css` réservé au above-the-fold, chaque octet compte ; page merci = CSS inline propre au gabarit.
4. **LCP sacro-saint** : ne rien ajouter de bloquant avant le preload (hook p1) ni dans le `<head>` sans mesure ; ne jamais changer l'URL de `#main-photo` sans passer par `abaya_lcp_image_src()`.
5. **JS** : un seul module ; pas de variable globale nouvelle ; wrapper `track()` obligatoire pour tout événement Pixel ; tout listener doit être null-safe (les éléments peuvent manquer).
6. **Contrat REST additif** : la réponse `create-order` ne doit jamais renommer/retirer une clé (`app.js` en dépend) — on ajoute, on ne casse pas.
7. **Nomenclature & RTL** : tout libellé de couleur passe par le champ `label` ; alt/aria dérivés ; textes arabes, chiffres DM Sans.
8. **Environnement ≠ code** : un problème de wp-config/ID produit/réglage serveur ne se corrige jamais dans le thème.

### 6.2 Procédures par type d'intervention

| Type | Procédure | Impact autorisé |
|---|---|---|
| **Style isolé** (couleur, espacement, animation) | éditer `styles.css` → section concernée ; si bloc hero visible d'emblée → évaluer critical.css ; tester mobile + desktop | styles.css ± critical.css |
| **Composant UI** (ajouter un bloc à la landing) | éditer `front-page.php` (boucle catalogue si données produit) → listeners null-safe dans `app.js` → styles dédiés | front-page.php, app.js, styles.css |
| **Catalogue** (couleurs/tailles/prix) | §5.2 / §5.3 uniquement | functions.php (+ uploads) |
| **Validation/formulaire** | client : `app.js` submit (l. 283+) ; serveur : `abaya_handle_create_order()` — **toujours les deux en miroir** (mêmes messages arabes) | app.js + functions.php |
| **Tracking** | Pixel/CAPI : functions.php l. 218-293 ; événements client : wrapper `track()` ; GTM : passer par GTM4WP (ou `window.dataLayer.push` dédié dans app.js, fail-safe) | functions.php ± app.js |
| **Page légale** | contenu = admin WP (base) ; titre/meta = `inc-pages.php` ; gabarit = `index.php`/`page.php` | selon besoin |
| **/merci/** | `page-merci.php` (CSS inline + gabarit) ; sécurité : ne jamais afficher sans `hash_equals` valide | page-merci.php |
| **Head/performance** | toute addition au `<head>` exige : mesure LCP avant/après (PSI/CrUX) + validation diff de rendu | functions.php (hooks p1/p5) |

### 6.3 Checklist de validation avant push (à exécuter intégralement)

**Automatique (poste de développement, ~2 min) :**
```bash
php -l functions.php && php -l front-page.php && php -l page-merci.php   # syntaxe PHP
node --check assets/js/app.js                                            # syntaxe JS
# Diff de rendu (harnais agent : render_front.php → comparer avant/après) :
#   seules les lignes volontairement modifiées doivent différer — LCP/hero bit-à-bit identiques
# Suite tunnel (harnais agent) : validateur statique 98 contrôles + tests handler 60 + tests merci 26
```
- [ ] `node --check` + `php -l` verts ;
- [ ] diff de rendu = **uniquement** les changements voulus (aucune dérive LCP/vignettes/offres) ;
- [ ] suite de tests tunnel au vert (prix 299/499/699, whitelist 422, idempotence, honeypot, IP CF, page merci sans fuite) ;
- [ ] version `style.css` bumpée (+ lignes §5.1 et PROJECT_MAP.md §9 remplies) si le code a changé.

**Manuelle (staging, mobile + desktop) :**
- [ ] Affichage : hero, galerie (8 vignettes → swap photo), pastilles/tailles (aucune présélection), offres, FAQ, footer ;
- [ ] Tunnel : soumission sans couleur → contour rouge + secousse + micro-texte sur le bon bloc (et seulement lui), nettoyage dès sélection ; soumission sans taille seule → seul le bloc taille signalé ; formulaire complet → commande créée, redirection `/merci/?order=…&key=…` avec récap exact ;
- [ ] Cartes d'offre → résumé et prix corrects (299/499/699) ;
- [ ] Tracking : Pixel Helper / Events Manager (ou `META_TEST_EVENT_CODE` sur staging) → PageView, ViewContent, InitiateCheckout, Purchase navigateur **et** CAPI, dédupliqués (1 Purchase pour 1 commande) ; un refresh de /merci/ ne doit pas créer de second Purchase ;
- [ ] Avis : soumission → « en attente » ; validation admin → visible ;
- [ ] Version servie vérifiée après déploiement (`?ver=` changé).

### 6.4 Flux Git officiel

```bash
git checkout main && git pull origin main          # partir de l'état publié
git checkout -b fix/… | feat/… | docs/…            # JAMAIS de commit direct sur main
# … travail + checklist §6.3 …
git add … && git commit -m "…"
git checkout main && git merge --no-ff fix/…       # merge commit = trace de mission
git push origin main && git push origin fix/…      # traçabilité de la branche
# puis : déploiement de l'instance (pull) + purge cache + vérification version servie
```

> Fusion sans feu vert = uniquement si le mandat en cours accorde la dérogation explicite (comme les mandats tunnel checkout, 4P et la présente mission documentaire). Sinon : proposer le merge et attendre.

---

*Fin du livre d'architecture — en cas de divergence entre ce document et le code, **le code fait foi** ; ouvrir immédiatement une mission « mise à jour documentation ». Document v1.0 (thème v1.9, commit `bfb5df3`).*

