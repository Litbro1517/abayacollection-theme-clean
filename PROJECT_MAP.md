# PROJECT MAP — Thème « Abaya Canvas » (abayacollection-theme-clean)

> Cartographie complète du thème — **dépôt cible `Litbro1517/abayacollection-theme-clean`** (dépôt propre, relié à l'instance WordPress de production/staging).
> Créée lors du mandat global unifié « audit & correctif du tunnel de vente REST (Abaya Checkout) ».
> Dernière mise à jour : **mandat « développement, suivi & export — nettoyage final » — thème v1.11** (purge des résidus de développement, correction favicon, conformité production).
> Livre d'architecture complet : **`ARCHITECTURE_PROJECT_MAP.md`** — index de recherche 30 s (§0), anatomie fichier par fichier (§2), intégrations tracking (§4), procédures d'évolution (§5) et protocole d'intervention chirurgicale (§6).

---

## 1. Identité du projet

| Élément | Valeur |
|---|---|
| Thème WordPress | **Abaya Canvas** (racine du dépôt) |
| Rôle | Landing page produit unique (COD — paiement à la livraison), marché marocain, contenu arabe (RTL) |
| Site de production | abayacollection.store (nginx + Cloudflare ; plugin LiteSpeed Cache actif côté WP) |
| Site de staging | dev.abayacollection.store (problème d'environnement 500 réglé par `define('LANDING_PRODUCT_ID', 24)` dans wp-config.php — côté serveur, hors thème) |
| Dépôt GitHub | `Litbro1517/abayacollection-theme-clean` (cible unique — aucun alignement requis avec les anciens dépôts) |
| Branche principale | `main` — fusion via branche dédiée ; **feu vert intégré accordé pour les mandats tunnel checkout & 4P** |
| Branche du mandat | `fix/tunnel-checkout-rest` (v1.8) puis `fix/tunnel-attribute-ergonomics` (v1.9) → fusionnées dans `main` |
| Version du thème | **v1.11 sur `main`** |
| Text domain | `abaya-canvas` |

## 2. Nomenclature officielle des couleurs (8)

`أسود`, `بيج`, `كاكي`, `أبيض`, `أزرق داكن`, `بني`, `وردي ترابي`, **`أحمر داكن`**.

⚠️ La couleur bordeaux/grenat est nommée **`أحمر داكن`** partout (catalogue PHP, gabarits, JS, validation REST, page /merci/). L'ancien libellé « عنابي » a été intégralement retiré du code source (les commandes historiques portant `عنابي` en méta restent affichées telles quelles — données, pas code).

## 3. Arborescence et rôles des fichiers

```
.
├── style.css                     # En-tête du thème (métadonnées complètes : Nom, Version 1.11, Requires, License). Pas de CSS réel.
├── functions.php                 # Cerveau du thème (voir détail §4)
├── header.php                    # <head> minimal + ouverture <body> (classes product-landing / legal-page)
├── footer.php                    # Pied : site-footer (front) ou legal-footer
├── front-page.php                # Landing : galerie, choix, bundles, avis, FAQ…
│                                 #   (v1.8 : vignettes/swatches/tailles/offres générés depuis abaya_catalog())
├── page-merci.php                # (v1.8) Template de la page /merci/ — récapitulatif dynamique sécurisé par order_key
├── index.php / page.php          # Templates génériques (pages légales)
├── inc-pages.php                 # Chargement des pages réglementaires (titre merci féminisé v1.8)
├── template-parts/
│   ├── site-header.php           # Bandeau annonce + logo header (logo-header-color.svg)
│   ├── site-footer.php           # Logo footer + colonnes de liens
│   └── legal-header.php          # En-tête des pages légales
├── assets/
│   ├── css/styles.css            # FEUILLE UNIQUE (~50 Ko) : tout le site + @font-face locaux + ciblage erreur par bloc .attribute-error-box / .choice-error (v1.9)
│   ├── css/critical.css          # CSS critique inline (~18 Ko) — above-the-fold
│   ├── fonts/                    # Cairo-arabic/latin/latin-ext, DMSans-latin/latin-ext (woff2 variables)
│   ├── js/app.js                 # Script front (type="module") : galerie, couleurs, tailles, bundles,
│   │                             #   formulaire → REST, avis → REST, modales.
│   │                             #   (v1.8 : state couleur/taille null par défaut, validation obligatoire,
│   │                             #   grille tarifaire lue dans window.LANDING.catalog, redirection
│   │                             #   /merci/?order=ID&key=KEY ; v1.9 : erreurs attributs ciblées
│   │                             #   par bloc — setChoiceError/flagChoiceError — plus de message global)
│   └── images/                   # logo-header-color.svg, logo-footer-white.svg
├── uploads/                      # Images produit (webp 1170×1560) + favicon-officiel.svg (toutes pages)
│   └── thumbs/                   # 8 miniatures WebP ~170px (2-4 Ko chacune)
├── pages/                        # 8 graines HTML des pages réglementaires (injectées en base par abaya_ensure_pages())
├── PROJECT_MAP.md                # Ce document
└── ARCHITECTURE_PROJECT_MAP.md   # Livre d'architecture : index 30 s, anatomie, intégrations, protocole
```

## 4. functions.php — points d'accroche

| Bloc | Rôle |
|---|---|
| `abaya_catalog()` | **(v1.8, P0-1) SOURCE UNIQUE DE VÉRITÉ** : 8 couleurs (nom, hex, label défini ال…, image, thumb, + drapeau optionnel `out_of_stock` **v1.10** — couleur temporairement épuisée : overlay front + refus serveur 422) **dont `أحمر داكن`**, 5 tailles (S–XXL), grille tarifaire autoritaire `[1=>299, 2=>499, 3=>699]`, libellés/économies des offres. Alimente le rendu PHP (front-page.php, page-merci.php), la validation REST et `window.LANDING.catalog`. |
| `abaya_lcp_image_src()` | URL de l'image LCP (#main-photo, robe beige) avec `?v=filemtime` — chemin issu d'`abaya_catalog()` ; source unique partagée preload + `<img>` |
| Hook `wp_head` **priorité 1** | `<link rel="preload" as="image" fetchpriority="high">` + preload 2 polices critiques — front page uniquement |
| Hook `wp_head` **priorité 5** | Favicon, **CSS critique inline**, styles.css async (`media="print"` + onload + noscript + repli), `window.LANDING` (**v1.8 : + catalog** : colors/sizes/bundles), app.js module, stub Meta Pixel |
| `abaya_cloudflare_ranges()` / `abaya_ip_in_ranges()` | **(v1.8, P2)** Plages réseau Cloudflare officielles (IPv4+IPv6) + test CIDR |
| `abaya_client_ip()` | **(v1.8, P2)** Derrière Cloudflare (REMOTE_ADDR ∈ plages CF) : `CF-Connecting-IP`, XFF ignoré (forgeable). Hors CF : XFF legacy (nginx) puis REMOTE_ADDR |
| `abaya_find_recent_order_by_event_id()` | **(v1.8, P1-2) IDEMPOTENCE** : recherche une commande landing avec le même `_abaya_event_id` créée dans les 10 dernières minutes (balayage borné `wc_get_orders` date_created, compatible HPOS/CPT) |
| Hook `template_redirect` | **(v1.8, P1-1)** Page /merci/ : `nocache_headers()` + `do_action('litespeed_control_set_nocache')` |
| Hook `wp_robots` | noindex sur /merci/ (conservé) |
| REST `landing/v1/create-order` | **POST public, refondu v1.8 (P0-2)** — voir §5 |
| REST `landing/v1/submit-review` | POST public (rate-limit, note 1-5) → `wp_insert_comment` (modération) — inchangé |
| Listener `abaya_order_created` | Meta CAPI serveur (Purchase), non bloquant, dédup par `event_id` — inchangé, reçoit le vrai total pack |
| Constantes (wp-config) | `LANDING_PRODUCT_ID` (15 en production, 24 en staging), `META_PIXEL_ID`, `META_CAPI_TOKEN`, `META_TEST_EVENT_CODE` |

## 5. Route REST `POST /landing/v1/create-order` (v1.8)

Séquence de traitement (chaque étape peut rejeter) :

1. **Honeypot** `website_hp` non vide → 400.
2. **WooCommerce actif** : `wc_create_order` présent, sinon 500.
3. **Idempotence (P1-2)** : si `event_id` fourni correspond à une commande landing < 10 min → renvoyer la même réponse (order_id, value, order_key, `duplicate:true`) sans créer de doublon ni renvoyer CAPI.
4. **Rate-limit** 5 commandes / 10 min / IP (transients) → 429 — placé APRÈS l'idempotence (un retry du même event_id ne consomme pas le quota).
5. **Validation champs** : nom ≥ 3, téléphone MA normalisé, ville ≥ 2, adresse ≥ 4 → 422.
6. **Whitelist catalogue (P0-2)** : `color` ∈ `abaya_catalog()['colors']` ET `size` ∈ `sizes`, sinon **422** (`أحمر داكن` accepté, tout libellé hors catalogue — dont l'ancien `عنابي` — est rejeté).
7. **Prix autoritaire (P0-2)** : `price = bundles[quantity]` (1→299, 2→499, 3→699) ; quantité bornée 1–3. L'ancien calcul « prix produit × quantité » (598/897 MAD) est supprimé.
8. **Commande WooCommerce** : `wc_create_order` (created_via=`landing-abaya`) → `add_product($product, $qty, ['subtotal'=>$price,'total'=>$price])` → **Item Meta en clair `اللون` / `المقاس`** sur la ligne d'article (+ `save()`) → adresses → COD `الدفع عند الاستلام` → `calculate_totals()` → statut `processing` → **note de commande** (couleur, taille, offre, event_id).
9. **Méta marketing** : `_abaya_event_id`, `_abaya_page_url`, `_abaya_color`/`_abaya_size` (conservées niveau commande pour rétrocompatibilité + page merci), `_abaya_client_ip` (IP réelle CF), `utm_*`, `_fbp/_fbc`.
10. **CAPI** : `do_action('abaya_order_created')` → Purchase serveur, value = total réel du pack.
11. **Réponse JSON** : `{success, order_id, value, event_id, product_id, order_key}` — format historique app.js **enrichi de `order_key`**.

## 6. Page de remerciement /merci/ (v1.8 — page-merci.php)

- **Accès au récapitulatif** : `?order=ID&key=KEY` ; `hash_equals(order_key)` (timing-safe) + fraîcheur **< 48 h** + `created_via === 'landing-abaya'`. Sinon → **version générique sans aucune donnée personnelle** (un simple `?order=ID` n'expose rien).
- **Anti-cache** : `nocache_headers()` + hook LiteSpeed (`template_redirect`) ; contournement Cloudflare `/merci/*` à créer côté dashboard CF (règle Cache Bypass — hors thème) ; `<meta name="referrer" content="no-referrer">` + noindex conservé.
- **Contenu** : titre féminisé « شكراً لكِ {prénom}! تم استلام طلبكِ بنجاح », carte récap (visuel couleur via catalogue, taille, couleur, total MAD, téléphone, adresse), frise 3 étapes (Reçu → Confirmation tél → Expédition), bouton WhatsApp prérempli avec n° de commande (+212 698-738664), retour boutique. Délais alignés landing : 24–72 h, couture 5–6 jours.
- **Couleur/taille** : lus d'abord dans `_abaya_color`/`_abaya_size` (méta commande), repli sur Item Meta `اللون`/`المقاس` (couvre les commandes créées avant v1.8).
- **Pixel** : Purchase reste émis sur la landing avant redirection (event_id dédupliqué CAPI) — la page merci n'émet aucun événement (pas de double envoi au rafraîchissement).

## 7. Flux de données

1. **Commande** : app.js (state null par défaut) → validation couleur/taille obligatoire → **ciblage visuel par bloc (v1.9)** : chaque groupe manquant reçoit `.attribute-error-box` (contour rouge 2px via `outline` — zéro décalage de mise en page), micro-secousse `@keyframes abaya-shake` (neutralisée `prefers-reduced-motion`) relancée à chaque tentative via reflow, et micro-texte discret `.choice-error` (style `.field-error`, `role="alert"`) directement au-dessus du bloc — nettoyé dès la sélection de l'attribut (les deux blocs peuvent être signalés ensemble si aucun choix ; défilement vers le premier manquant, couleur prioritaire) → POST JSON `/create-order` → séquence §5 → Pixel Purchase (eventID serveur, value = prix pack réel) → redirection `/merci/?order=ID&key=KEY`.
   - **Ancrage DOM (v1.9)** : `fieldset.choice-group[data-attribute="color|size"]` + slots `<small class="field-error choice-error" data-choice-error="color|size">` rendus par front-page.php ; l'ancien `<p id="attribute-error">` au-dessus du bouton est supprimé du gabarit et de app.js.
2. **Avis** : app.js → POST `/submit-review` → commentaire en attente → rendu serveur des approuvés.
3. **Visuels** : vignettes thumbs/ pour l'affichage ; `data-src` pleine taille pour le swap couleur sur #main-photo ; les URLs viennent du catalogue (visuel أحمر داكن conservé avec `?v=` cache-busting). L'image LCP (#main-photo, بيج) reste intouchée.

## 8. Conventions techniques

- **Source de vérité** : toute évolution catalogue/prix se fait dans `abaya_catalog()` (functions.php) uniquement — ni dans front-page.php, ni dans app.js.
- **Nomenclature couleurs** : toute évolution de libellé (ex. `أحمر داكن`) se fait dans `abaya_catalog()` ; les alt/aria des gabarits dérivent du champ `label`.
- **Cache-busting** : `?v=filemtime()` (helpers `abaya_asset_ver`, `abaya_lcp_image_src`, `$visuel_ver`).
- **Aucune dépendance externe** CSS/JS : polices auto-hébergées.
- **Priorité LCP** : ne rien ajouter de bloquant au `<head>` sans mesure ; critical.css + preload priorité 1.
- **RTL/arabe** : `dir="rtl"`, Cairo (texte) / DM Sans (chiffres). Forme définie (ال…) des couleurs = champ `label` du catalogue.
- **app.js** : le contrat de réponse REST est `{success, order_id, value, event_id, product_id, order_key}` — tout ajout doit rester additif.
- **Rapports/audits** : fixer une seule clé de méta de référence (Item Meta `اللون`/`المقاس`) ; `_abaya_color`/`_abaya_size` restent au niveau commande pour rétrocompatibilité.

## 9. Historique des versions

| Version | Mandat | Contenu |
|---|---|---|
| **1.19** | **Mandat 4PLP — espaces (section avis) & nettoyage du code mort** | Double objectif livré en 1 commit. (1) Espaces : `.social-section` déjà aligné sur `.trust-grid` en v1.18 (padding-block: 26px desktop / 19px ≤540px), aucune retouche nécessaire. (2) Nettoyage : scan automatique Python (`scan_orphan_css.py`) croisant les 169 classes CSS définies dans `styles.css` avec tous les fichiers PHP/JS/HTML du thème → identification de **24 classes orphelines** jamais appliquées dans le HTML. Suppression chirurgicale de ~60 lignes CSS mortes : `.benefit-card`, `.benefit-icon`, `.benefit-layout`, `.benefit-list`, `.benefits-section` (+ leur media queries), `.button-arrow`, `.detail-photo` (+ img + figcaption), `.measurement-note`, `.measurements-section`, `.modal-intro`, `.pulse` (+ `@keyframes pulse`), `.rating-copy`, `.rating-line`, `.rating-muted`, `.review-result` (+ `.review-result[hidden]`), `.social-grid` (+ img), `.stars`, `.swatch-black/blue/beige/khaki` (anciennes swatches hardcoded remplacées par le système dynamique arabe), `.visual-detail-copy` (3 règles + 1 media query), `.wordmark-name` (conservation de `.footer-wordmark` qui est utilisé dans template-parts/site-footer.php et legal-header.php), `.wordmark-rule`. Re-scan final : **0 classe orpheline**. Critical.css non touché (préservation du above-the-fold validé 0% écart mobile). Aucune régression visuelle : classes critiques (.review-card, .review-card-stars, .review-star, .review-card-reply, .review-form-row, .social-section, .trust-grid, .footer-wordmark, etc.) toutes préservées (branche `fix/reviews-spacing-and-code-cleanup`) |
| **1.18** | **Mandat 4PLP — harmonisation des espaces (section avis)** | Constat : la section `.social-section` (#avis-clients) avait `padding-block: 82px 88px` (desktop) / `52px 58px` (≤800px) — ~3x plus d'espace que la section de référence `#delivery > div` (`.trust-grid` à `padding-block: 26px` desktop / `19px` ≤540px). Ces « zones mortes » cassaient le rythme de lecture. Correctif CSS (assets/css/styles.css, 3 règles) : `.social-section` desktop → `padding-block: 26px` (aligné sur trust-grid desktop) ; suppression règle `52px 58px` dans media query ≤800px (devenue inutile) ; ajout `.social-section { padding-block: 19px; }` dans media query ≤540px juste à côté de `.trust-grid` (alignement breakpoints strict + valeurs). Espacement interne (`.section-heading` margin-bottom 27px + `.reviews-list-container` margin-top 24px) non touché : seules les marges AVANT/APRÈS la section sont harmonisées. Aucun autre composant touché (branche `fix/reviews-spacing-harmonization`) |
| **1.17** | **Mandat 4P / 4PLP — fix fluide des avis clients (pleine largeur)** | Constat : la `max-width: 480px` sur `.review-card` (introduite en v1.16 sur la branche non-mergée `fix/reviews-layout-responsiveness`) bloquait l'expansion naturelle des cartes — avec 2 avis, elles restaient condensées à droite (RTL) laissant un grand espace blanc à gauche. Solution flexbox pure imposée par le mandat : `.reviews-list-container` passe en `display: flex !important; flex-wrap: wrap !important; gap: 20px !important; width: min(1100px, 100%) !important; margin: 24px auto 0 !important; justify-content: center !important; align-items: stretch;` + `.review-card { flex: 1 1 320px !important; max-width: 100% !important; box-sizing: border-box !important; ...design interne conservé... }`. Les `!important` garantissent l'application stricte (override de toute spécificité concurrente, y compris media queries). Mobile ≤768px : `gap: 12px !important` (au lieu de 20px) pour resserrer l'empilement vertical. Design interne des cartes **préservé** (étoiles volumineuses dorées arrondies, bordure bordeaux, ligne author — city, commentaire, replies boutique). Comportement : 1 avis → carte étirée à ~1100px (max-width 100%), 2 avis → 2 cartes fluides occupant chacune ~540px côte à côte, 3 avis → 3 cartes fluides ~353px chacune (branche `fix/reviews-fluid-layout`) — **attente feu vert avant merge sur main** |
| **1.11** | **Mandat « développement, suivi & export — nettoyage final »** | Purge des résidus de développement : `wp-config-meta-sample.php` (constantes placeholders — modèle conservé en tête de `functions.php` l. 8-20 et §3.4 du livre d'architecture), `uploads/LISEZ-MOI.txt` et `assets/images/LISEZ-MOI.txt` (notes de déploiement obsolètes — fichiers déjà présents) ; **`pages/*.html` conservés** (graines runtime d'`abaya_ensure_pages()`, functions.php l. 697-720) ; correction du lien favicon cassé (`favicon.svg` inexistant sur les pages légales → `favicon-officiel.svg` sur toutes les pages) ; en-tête `style.css` enrichi (Requires at least 6.0, Requires PHP 7.4, License GPL-2.0-or-later) ; aucun changement fonctionnel (branche `chore/cleanup-export-final`) |
| **1.10** | **Mandat 4P allégé — « Stock épuisé » (overlay + blocage de commande)** | كاكي et وردي ترابي temporairement épuisées (campagnes pub actives) — gamme de 8 conservée (8 swatches, phrase « 8 ألوان » inchangée) ; drapeau `out_of_stock` dans `abaya_catalog()` (source unique) ; overlay `#stock-overlay` sur `#main-photo` (voile `rgba(0,0,0,.12)` + badge vitré glassmorphism `rgba(255,255,255,.4)` + `backdrop-filter: blur(8px)` + bordure blanche 1px + texte rouge `#e53e3e` « غير متوفر حالياً في هذا اللون ») ; bouton désactivé `.is-unavailable` + double garde front `outOfStockColors` ; garde serveur 422 `abaya_out_of_stock` (handler 6b) ; `style.css` 1.9→1.10 ; suites : 106/106 statique (OOS-01…07) + 63/63 handler (T9b 6/6 + T9c) + 26/26 merci + 16/16 visuel Playwright ; revert préalable du mandat « retrait couleurs » vérifié (aucun reliquat, main resté à 0cf1797) (branche `feature/out-of-stock-overlay`) |
| **1.9** | **Mandat 4P — ajustement ergonomique du tunnel (alertes attributs)** | Suppression du message global volumineux `#attribute-error` au-dessus du bouton (gabarit + app.js + styles) ; remplacement par un ciblage par bloc : `fieldset[data-attribute]` encadré par `.attribute-error-box` (contour rouge 2px `#e53e3e` via `outline`, zéro décalage layout) + micro-secousse `abaya-shake` (relancée par reflow à chaque soumission, neutralisée `prefers-reduced-motion`) + micro-texte `.choice-error` (style identique `.field-error`, 10px `#a14f43`, `role="alert"`) au-dessus du bloc, effacé dès la sélection ; signalement indépendant des deux blocs (remplissage partiel géré) (branche `fix/tunnel-attribute-ergonomics`) |
| 1.9-doc | Mandat « documentation architecturale & Project Map » | Création de `ARCHITECTURE_PROJECT_MAP.md` (livre d'architecture en 6 sections + index de recherche 30 s) ; renvoi croisé depuis ce Project Map ; **aucune évolution de code** (branche `docs/architecture-project-map`) |
| **1.8** | **Mandat global unifié tunnel checkout REST** | P0-1 `abaya_catalog()` (8 couleurs dont `أحمر داكن`, 5 tailles, bundles 299/499/699) + injection `window.LANDING.catalog` ; P0-2 handler REST (whitelist 422, prix pack sur la ligne, Item Meta اللون/المقاس, note, order_key) ; P0-3 suppression des défauts بيج/M + validation couleur/taille obligatoire + `#attribute-error` + scroll ; P1-1 `page-merci.php` dynamique sécurisé (order_key, 48 h, frise, WhatsApp, nocache, no-referrer) ; P1-2 idempotence `_abaya_event_id` 10 min ; P2 CF-Connecting-IP + COD conservé (branche `fix/tunnel-checkout-rest`) |
| 1.6 | État d'origine (production) | Commit initial `36309e2` « initialisation du nouveau dossier propre » ; breve tentative de correctif 500 (`4ec8efd`, merge `3ef63f4`) **annulée par mandat de conformité** (revert `23d9418`) — le 500 staging étant réglé par wp-config.php |

## 10. État performance

| Axe | État |
|---|---|
| Image LCP | `fetchpriority="high"` + preload priorité 1 — **intouché par v1.8 et v1.9** (aucune ressource ajoutée au head de la landing) |
| Vignettes / polices / CSS critique | inchangés |
| v1.8 / v1.9 | 0 requête ajoutée sur la landing ; v1.9 = styles CSS existants + JS, aucun poids réseau nouveau ; page-merci.php = CSS inline spécifique (page séparée, sans impact PSI landing) |

## 11. Règles de contribution

1. **Jamais de commit direct sur `main`** — branche dédiée (`feat/…`, `fix/…`).
2. Fusion vers `main` sur feu vert (dérogation : feu vert intégré accordé aux mandats tunnel checkout et 4P).
3. Mettre à jour ce PROJECT MAP à la fin de chaque mission.
4. **Conformité mandat** : aucun changement de code pour tout problème relevant de la configuration d'environnement (wp-config.php), d'un identifiant de produit ou d'un réglage de staging — diagnostic + recommandation seulement.
