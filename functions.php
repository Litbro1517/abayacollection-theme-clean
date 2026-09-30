<?php
if (!defined('ABSPATH')) exit;

require_once get_template_directory() . '/inc-pages.php';

define('ABAYA_PAGES_VERSION', '1.2');

/* ---------- Suivi Meta (Pixel + CAPI) ----------
 * Les identifiants se définissent dans le wp-config.php de l'hébergement :
 *   define('LANDING_PRODUCT_ID', 15);
 *   define('META_PIXEL_ID', 'VOTRE_PIXEL_ID');
 *   define('META_CAPI_TOKEN', 'VOTRE_CAPI_TOKEN');
 *   define('META_TEST_EVENT_CODE', 'TESTxxxx');
 * Valeurs par défaut vides ci-dessous : le thème reste fonctionnel même si le
 * bloc n'a pas encore été inséré dans wp-config.php (suivi simplement inactif).
 */
if (!defined('META_PIXEL_ID'))      define('META_PIXEL_ID', '');
if (!defined('META_CAPI_TOKEN'))    define('META_CAPI_TOKEN', '');
if (!defined('META_TEST_EVENT_CODE')) define('META_TEST_EVENT_CODE', '');
if (!defined('LANDING_PRODUCT_ID')) define('LANDING_PRODUCT_ID', 15); // produit réel du landing

add_theme_support('title-tag');
add_theme_support('woocommerce');

/* ---------- Titres et descriptions (repris de vos fichiers HTML d'origine) ---------- */
add_filter('pre_get_document_title', function ($title) {
    if (is_front_page()) return 'Abaya Collection';
    if (is_page()) {
        $pages = abaya_pages();
        $slug  = get_post_field('post_name', get_queried_object_id());
        if (isset($pages[$slug])) return $pages[$slug][1];
    }
    return $title;
});

function abaya_meta_description() {
    if (is_front_page()) {
        return 'عباية خريفية من توب لولان، أنيقة وانسيابية. توصيل مجاني والدفع عند الاستلام في جميع مدن المغرب.';
    }
    if (is_page()) {
        $pages = abaya_pages();
        $slug  = get_post_field('post_name', get_queried_object_id());
        if (isset($pages[$slug])) return $pages[$slug][2];
    }
    return '';
}

/* ---------- noindex sur la page de remerciement ---------- */
add_filter('wp_robots', function ($robots) {
    if (is_page('merci')) $robots['noindex'] = true;
    return $robots;
});

/* ---------- Le contenu des pages est du HTML propre : pas de retouche automatique ---------- */
remove_filter('the_content', 'wpautop');
remove_filter('the_content', 'wptexturize');
remove_filter('the_title', 'wptexturize');

/* ---------- Nettoyage du <head> ---------- */
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
remove_action('wp_footer', 'wp_enqueue_global_styles', 1);
remove_action('wp_enqueue_scripts', 'wp_enqueue_classic_theme_styles');

add_filter('woocommerce_enqueue_styles', '__return_empty_array');

add_action('wp_enqueue_scripts', function () {
    foreach (['wp-block-library', 'wp-block-library-theme', 'wc-blocks-style', 'global-styles', 'classic-theme-styles'] as $h) {
        wp_dequeue_style($h);
    }
    foreach (['wc-cart-fragments', 'woocommerce', 'wc-add-to-cart', 'sourcebuster-js', 'wc-order-attribution'] as $h) {
        wp_dequeue_script($h);
    }
}, 100);

/* ---------- Feuille de style, polices, script, Pixel ---------- */
function abaya_asset_ver($rel) {
    $f = get_template_directory() . '/' . $rel;
    return file_exists($f) ? filemtime($f) : '1';
}

/* Polices : auto-hébergées dans assets/fonts/ (voir @font-face en tête de styles.css).
 * Plus aucune dépendance Google Fonts : pas de requête externe, pas de WebFontLoader,
 * pas de chaîne HTML -> CSS -> woff2. font-display: swap conservé. */

/* Image LCP (#main-photo, robe beige) : URL unique partagée entre le preload
 * (wp_head) et la balise <img>, avec cache-busting filemtime identique. */
function abaya_lcp_image_src() {
    $rel = 'uploads/Robe_comfy_Robe_chemise_avec_un_col_officier_et_deux_poche_tr_s_pratique_et_confortable_pour_tt___12_.webp';
    $f   = get_template_directory() . '/' . $rel;
    $ver = file_exists($f) ? filemtime($f) : '1';
    return get_template_directory_uri() . '/' . $rel . '?v=' . $ver;
}

/* Priorité 1 : émis AVANT les autres ressources du wp_head (priorité 5),
 * pour que le navigateur découvre l'image LCP et les polices critiques en premier. */
add_action('wp_head', function () {
    if (!is_front_page()) return;

    $base  = get_template_directory_uri();
    $fonts = $base . '/assets/fonts';
    ?>
<link rel="preload" as="image" href="<?php echo esc_url(abaya_lcp_image_src()); ?>" fetchpriority="high" />
<link rel="preload" as="font" type="font/woff2" crossorigin href="<?php echo esc_url($fonts . '/Cairo-arabic.woff2'); ?>" />
<link rel="preload" as="font" type="font/woff2" crossorigin href="<?php echo esc_url($fonts . '/DMSans-latin.woff2'); ?>" />
<?php
}, 1);

add_action('wp_head', function () {
    $base    = get_template_directory_uri();
    $favicon = is_front_page() ? 'favicon-officiel.svg' : 'favicon.svg';
    ?>
<link rel="icon" href="<?php echo esc_url($base . '/uploads/' . $favicon); ?>" type="image/svg+xml" />
<?php
    /* CSS critique inline (header + hero + galerie + prix + @font-face variables)
     * puis styles.css en asynchrone : le premier rendu n'attend plus les 39 Ko de CSS.
     * Le fichier critique est généré et validé au pixel près (0 % d'écart mobile). */
    $critical_file = get_template_directory() . '/assets/css/critical.css';
    $css_url = esc_url($base . '/assets/css/styles.css?ver=' . abaya_asset_ver('assets/css/styles.css'));
    if (file_exists($critical_file)) {
        $critical = file_get_contents($critical_file);
        $critical = str_replace('__FONT_BASE__', $base . '/assets/fonts', $critical);
        echo '<style id="abaya-critical">' . $critical . '</style>' . "\n";
?>
<link rel="preload" as="style" href="<?php echo $css_url; ?>" />
<link rel="stylesheet" href="<?php echo $css_url; ?>" media="print" onload="this.media='all';" />
<noscript><link rel="stylesheet" href="<?php echo $css_url; ?>" /></noscript>
<?php
    } else {
        /* Repli sûr : pas de CSS critique -> feuille de style bloquante classique. */
?>
<link rel="stylesheet" href="<?php echo $css_url; ?>" />
<?php } ?>
<?php
    if (!is_front_page()) return;

    $endpoint        = wp_make_link_relative(rest_url('landing/v1/create-order'));
    $review_endpoint = wp_make_link_relative(rest_url('landing/v1/submit-review'));
    $thanks          = wp_make_link_relative(home_url('/merci/'));
    ?>
<script>window.LANDING = <?php echo wp_json_encode(['endpoint' => $endpoint, 'thanks' => $thanks, 'review_endpoint' => $review_endpoint]); ?>;</script>
<script type="module" src="<?php echo esc_url($base . '/assets/js/app.js?ver=' . abaya_asset_ver('assets/js/app.js')); ?>"></script>
<?php if (defined('META_PIXEL_ID') && META_PIXEL_ID) : ?>
<script>
/* Meta Pixel : stub immédiat, fbevents.js chargé après l'événement "load" (préserve PageSpeed) */
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];
f.addEventListener('load',function(){t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)});
}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init','<?php echo esc_js(META_PIXEL_ID); ?>');
fbq('track','PageView');
fbq('track','ViewContent',{content_type:'product',content_ids:['<?php echo (int) (defined('LANDING_PRODUCT_ID') ? LANDING_PRODUCT_ID : 0); ?>'],currency:'MAD',value:299});
</script>
<?php endif;
}, 5);

/* ---------- Meta Conversions API (CAPI) : envoi serveur de l'événement Purchase ----------
 * Actif uniquement si META_CAPI_TOKEN et META_PIXEL_ID sont renseignés dans wp-config.php.
 * Point d'intégration : le traitement de la commande déclenche l'action
 *   do_action('abaya_order_created', [
 *       'event_id'  => 'order_xxx',      // identique à celui envoyé par le Pixel (déduplication)
 *       'value'     => 299,              // montant réel de la commande en MAD
 *       'full_name' => '...', 'phone' => '...', 'city' => '...',
 *       'page_url'  => 'https://.../...',
 *   ]);
 * L'appel Meta est non bloquant (blocking=false) pour ne jamais ralentir la réponse.
 */
add_action('abaya_order_created', function ($order) {
    if (!META_CAPI_TOKEN || !META_PIXEL_ID) return;

    $user_data = [];
    if (!empty($order['phone'])) {
        $phone = preg_replace('/\D/', '', (string) $order['phone']);
        if (strpos($phone, '212') !== 0 && strlen($phone) === 10) $phone = '212' . substr($phone, 1);
        if (strlen($phone) >= 10) $user_data['ph'] = [hash('sha256', $phone)];
    }
    if (!empty($order['city'])) $user_data['ct'] = [hash('sha256', mb_strtolower(trim((string) $order['city']), 'UTF-8'))];
    if (!empty($order['full_name'])) {
        $parts = preg_split('/\s+/', trim((string) $order['full_name']), 2);
        $user_data['fn'] = [hash('sha256', mb_strtolower($parts[0], 'UTF-8'))];
        if (!empty($parts[1])) $user_data['ln'] = [hash('sha256', mb_strtolower($parts[1], 'UTF-8'))];
    }
    // Identifiants Meta collectés par le navigateur (cookies _fbp/_fbc envoyés avec la requête) : améliorent la qualité de matching CAPI.
    if (!empty($order['fbp'])) $user_data['fbp'] = (string) $order['fbp'];
    if (!empty($order['fbc'])) $user_data['fbc'] = (string) $order['fbc'];

    $event = [
        'event_name'       => 'Purchase',
        'event_time'       => time(),
        'event_id'         => (string) ($order['event_id'] ?? ''),
        'event_source_url' => (string) ($order['page_url'] ?? home_url('/')),
        'action_source'    => 'website',
        'user_data'        => $user_data,
        'custom_data'      => [
            'currency'     => 'MAD',
            'value'        => (float) ($order['value'] ?? 0),
            'content_type' => 'product',
            'content_ids'  => [(string) LANDING_PRODUCT_ID],
        ],
    ];
    /* Correction mandat D1 : le champ « data » doit être la LISTE d'événements encodée une seule
     * fois (wp_json_encode([$event])). L'ancien code envoyait wp_json_encode(['data' => [$event]])
     * soit un double encodage — Meta attendait [event] et recevait {"data":[event]} : rejet. */
    $body = [
        'access_token' => META_CAPI_TOKEN,
        'data'         => wp_json_encode([$event]),
    ];
    if (META_TEST_EVENT_CODE) $body['test_event_code'] = META_TEST_EVENT_CODE;

    wp_remote_post(
        'https://graph.facebook.com/v19.0/' . rawurlencode(META_PIXEL_ID) . '/events',
        [
            'timeout'  => 5,
            'blocking' => false,
            'body'     => $body,
        ]
    );
});

/* =====================================================================
 * API REST du landing (correctifs mandat) :
 *   - POST /wp-json/landing/v1/create-order  : création d'une commande
 *     WooCommerce (paiement à la livraison) — corrige l'erreur 404
 *     « No route was found matching the URL and request method »
 *     (la route appelée par app.js n'était déclarée nulle part) ;
 *   - POST /wp-json/landing/v1/submit-review : persistance des avis
 *     clients en base MySQL (commentaires natifs, modération requise).
 * Routes publiques (permission_callback => '__return_true') : le visiteur
 * n'est pas connecté. Sécurité : champ piège anti-robots, limitation de
 * débit par IP, revalidation intégrale des champs côté serveur.
 * ===================================================================== */

/** Adresse IP du client : premier IP public de X-Forwarded-For (derrière CDN/proxy), sinon REMOTE_ADDR. */
function abaya_client_ip() {
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        foreach (explode(',', (string) $_SERVER['HTTP_X_FORWARDED_FOR']) as $candidate) {
            $candidate = trim($candidate);
            if ($candidate !== '' && filter_var($candidate, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return $candidate;
            }
        }
    }
    return isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : '';
}

/** Limitation de débit simple par IP (transients). Retourne false si le quota ($limit sur $window_seconds) est épuisé. */
function abaya_rate_limit($bucket, $limit, $window_seconds) {
    $key  = 'abaya_rl_' . md5($bucket . '|' . abaya_client_ip());
    $hits = (int) get_transient($key);
    if ($hits >= (int) $limit) return false;
    set_transient($key, $hits + 1, (int) $window_seconds);
    return true;
}

/** Normalise un numéro marocain vers le format local 06XXXXXXXX / 07XXXXXXXX. Retourne '' si invalide. */
function abaya_normalize_phone_ma($raw) {
    $digits = preg_replace('/\D/', '', (string) $raw);
    if (strpos($digits, '00212') === 0) {
        $digits = substr($digits, 5);
    } elseif (strpos($digits, '212') === 0) {
        $digits = substr($digits, 3);
    }
    if (strlen($digits) === 10 && $digits[0] === '0') {
        $digits = substr($digits, 1);
    }
    if (!preg_match('/^[67][0-9]{8}$/', $digits)) return '';
    return '0' . $digits;
}

/** Post cible des avis : page d'accueil statique si définie, sinon produit du landing (LANDING_PRODUCT_ID). */
function abaya_reviews_target_post_id() {
    $front_id = (int) get_option('page_on_front');
    if ($front_id > 0 && get_post($front_id)) return $front_id;
    $product_id = (int) (defined('LANDING_PRODUCT_ID') ? LANDING_PRODUCT_ID : 15);
    if ($product_id > 0 && get_post($product_id)) return $product_id;
    return 0;
}

/** Rendu serveur d'une carte d'avis — structure DOM identique à celle construite par app.js (.review-card). */
function abaya_render_review_card($comment) {
    $rating = (int) get_comment_meta($comment->comment_ID, 'rating', true);
    if ($rating < 1 || $rating > 5) return;
    echo '<article class="review-card">';
    echo '<div class="review-card-header">';
    echo '<span class="review-card-stars" aria-label="' . esc_attr($rating) . ' / 5">';
    for ($i = 0; $i < 5; $i++) {
        echo '<svg class="review-star' . ($i >= $rating ? ' is-empty' : '') . '" viewBox="0 0 24 24" aria-hidden="true"><path d="m12 2.5 2.9 5.9 6.5.9-4.7 4.6 1.1 6.5-5.8-3.1-5.8 3.1 1.1-6.5-4.7-4.6 6.5-.9z"/></svg>';
    }
    echo '</span>';
    echo '<span class="review-card-score">' . esc_html($rating) . '/5</span>';
    echo '</div>';
    echo '<p class="review-card-comment">' . esc_html(get_comment_text($comment)) . '</p>';
    echo '</article>';
}

/* ---------- Route 1 : création de commande WooCommerce (COD) ---------- */

/** Correctif chirurgical (audit 500 staging) : retourne l'ID de l'unique produit
 * simple publié du catalogue, ou 0 si le repli n'est pas applicable (aucun ou
 * plusieurs produits publiés — le comportement de production reste inchangé,
 * car le produit LANDING_PRODUCT_ID y existe et le repli n'est jamais atteint). */
function abaya_resolve_unique_landing_product() {
    if (!function_exists('wc_get_products')) return 0;
    $published = wc_get_products([
        'status' => 'publish',
        'type'   => 'simple',
        'limit'  => 2,
        'return' => 'ids',
    ]);
    return (is_array($published) && count($published) === 1) ? (int) $published[0] : 0;
}

function abaya_handle_create_order(WP_REST_Request $request) {
    // 1) Champ piège anti-robots (input « extra_note » masqué côté front) : doit rester vide.
    if (trim((string) $request->get_param('website_hp')) !== '') {
        return new WP_Error('abaya_spam', 'تم رفض الطلب.', ['status' => 400]);
    }

    // 2) Anti-flood : maximum 5 commandes par tranche de 10 minutes et par IP.
    if (!abaya_rate_limit('order', 5, 10 * MINUTE_IN_SECONDS)) {
        return new WP_Error('abaya_rate_limited', 'عدد كبير من المحاولات. يرجى المحاولة بعد قليل.', ['status' => 429]);
    }

    // 3) Revalidation serveur des champs (mêmes règles que app.js).
    $full_name = sanitize_text_field((string) $request->get_param('full_name'));
    $city      = sanitize_text_field((string) $request->get_param('city'));
    $address   = sanitize_textarea_field((string) $request->get_param('address'));
    $phone     = abaya_normalize_phone_ma((string) $request->get_param('phone'));

    if (mb_strlen($full_name) < 3) {
        return new WP_Error('abaya_invalid_field', 'يرجى إدخال الاسم الكامل.', ['status' => 422]);
    }
    if ($phone === '') {
        return new WP_Error('abaya_invalid_field', 'أدخلي رقم هاتف مغربي صحيحاً.', ['status' => 422]);
    }
    if (mb_strlen($city) < 2) {
        return new WP_Error('abaya_invalid_field', 'يرجى اختيار المدينة.', ['status' => 422]);
    }
    if (mb_strlen($address) < 4) {
        return new WP_Error('abaya_invalid_field', 'يرجى إدخال العنوان الكامل.', ['status' => 422]);
    }

    // 4) Montant calculé à partir du produit réel (LANDING_PRODUCT_ID = 15), jamais depuis le client.
    if (!function_exists('wc_create_order')) {
        return new WP_Error('abaya_no_woocommerce', 'خطأ في الخدمة. يرجى المحاولة لاحقاً.', ['status' => 500]);
    }
    $product_id = (int) (defined('LANDING_PRODUCT_ID') ? LANDING_PRODUCT_ID : 15);
    $product    = $product_id > 0 ? wc_get_product($product_id) : false;
    if (!$product || (float) $product->get_price() <= 0) {
        /* Correctif chirurgical (audit 500 staging) : LANDING_PRODUCT_ID pointe vers
         * l'ID produit de la production. Sur un environnement dont la base diffère
         * (staging : produit réimporté sous un autre ID), le handler renvoyait un
         * 500 « abaya_bad_product » et le tunnel de vente était bloqué. Repli
         * strictement borné : si EXACTEMENT UN produit simple est publié, il est
         * résolu automatiquement ; sinon l'erreur d'origine est conservée. En
         * production le produit configuré existe : comportement inchangé. */
        $resolved_id = abaya_resolve_unique_landing_product();
        if ($resolved_id > 0) {
            $product_id = $resolved_id;
            $product    = wc_get_product($product_id);
        }
        if (!$product || (float) $product->get_price() <= 0) {
            return new WP_Error('abaya_bad_product', 'خطأ في الخدمة. يرجى المحاولة لاحقاً.', ['status' => 500]);
        }
    }
    $quantity = max(1, min(3, (int) $request->get_param('quantity')));

    // 5) event_id de déduplication Pixel/CAPI : celui du navigateur, sinon généré serveur (réutilisé par le Pixel).
    $event_id = sanitize_text_field((string) $request->get_param('event_id'));
    if ($event_id === '') {
        $event_id = 'order_' . time() . '_' . wp_generate_password(8, false, false);
    }
    $page_url = esc_url_raw((string) $request->get_param('page_url'));

    // 6) Création de la commande WooCommerce officielle, en paiement à la livraison.
    $order = wc_create_order(['status' => 'pending', 'created_via' => 'landing-abaya']);
    if (is_wp_error($order) || !is_object($order)) {
        return new WP_Error('abaya_order_failed', 'خطأ في الخدمة. يرجى المحاولة لاحقاً.', ['status' => 500]);
    }
    $order->add_product($product, $quantity);

    $name_parts = preg_split('/\s+/', $full_name, 2);
    $billing    = [
        'first_name' => $name_parts[0],
        'last_name'  => isset($name_parts[1]) ? $name_parts[1] : '',
        'phone'      => $phone,
        'address_1'  => $address,
        'city'       => $city,
        'country'    => 'MA',
    ];
    $order->set_address($billing, 'billing');
    $order->set_address([
        'first_name' => $billing['first_name'],
        'last_name'  => $billing['last_name'],
        'address_1'  => $address,
        'city'       => $city,
        'country'    => 'MA',
    ], 'shipping');
    $order->set_payment_method('cod');
    $order->set_payment_method_title('الدفع عند الاستلام');
    $order->calculate_totals();
    $order->update_status('processing', 'Commande COD créée depuis le formulaire du landing (Abaya Collection).');

    // 7) Persistance des métadonnées marketing (event_id, page_url, utm_*, _fbp/_fbc) et de configuration.
    $order->update_meta_data('_abaya_event_id', $event_id);
    $order->update_meta_data('_abaya_page_url', $page_url);
    $order->update_meta_data('_abaya_color', sanitize_text_field((string) $request->get_param('color')));
    $order->update_meta_data('_abaya_size', sanitize_text_field((string) $request->get_param('size')));
    $order->update_meta_data('_abaya_client_ip', abaya_client_ip());
    foreach (['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term'] as $utm_key) {
        $utm_value = sanitize_text_field((string) $request->get_param($utm_key));
        if ($utm_value !== '') $order->update_meta_data('_abaya_' . $utm_key, $utm_value);
    }
    if (!empty($_COOKIE['_fbp'])) $order->update_meta_data('_abaya_fbp', sanitize_text_field((string) $_COOKIE['_fbp']));
    if (!empty($_COOKIE['_fbc'])) $order->update_meta_data('_abaya_fbc', sanitize_text_field((string) $_COOKIE['_fbc']));
    $order->save();

    // 8) Déclenche le listener CAPI existant (envoi serveur Purchase, non bloquant, dédupliqué par event_id).
    do_action('abaya_order_created', [
        'event_id'   => $event_id,
        'value'      => (float) $order->get_total(),
        'full_name'  => $full_name,
        'phone'      => $phone,
        'city'       => $city,
        'page_url'   => $page_url !== '' ? $page_url : home_url('/'),
        'fbp'        => isset($_COOKIE['_fbp']) ? (string) $_COOKIE['_fbp'] : '',
        'fbc'        => isset($_COOKIE['_fbc']) ? (string) $_COOKIE['_fbc'] : '',
        'product_id' => $product_id,
        'order_id'   => $order->get_id(),
    ], $order);

    // 9) Réponse au format exact consommé par app.js — aucune modification front-end requise.
    return rest_ensure_response([
        'success'    => true,
        'order_id'   => $order->get_id(),
        'value'      => (float) $order->get_total(),
        'event_id'   => $event_id,
        'product_id' => $product_id,
    ]);
}

/* ---------- Route 2 : soumission d'avis clients (persistance MySQL + modération) ---------- */

function abaya_handle_submit_review(WP_REST_Request $request) {
    // 1) Anti-flood : maximum 3 avis par tranche de 10 minutes et par IP.
    if (!abaya_rate_limit('review', 3, 10 * MINUTE_IN_SECONDS)) {
        return new WP_Error('abaya_rate_limited', 'عدد كبير من المحاولات. يرجى المحاولة بعد قليل.', ['status' => 429]);
    }

    // 2) Validation du contenu.
    $rating  = (int) $request->get_param('rating');
    $comment = trim(sanitize_textarea_field((string) $request->get_param('comment')));
    if ($rating < 1 || $rating > 5) {
        return new WP_Error('abaya_invalid_field', 'يرجى اختيار عدد النجوم.', ['status' => 422]);
    }
    if (mb_strlen($comment) < 3 || mb_strlen($comment) > 2000) {
        return new WP_Error('abaya_invalid_field', 'يرجى كتابة رأيك في المنتج.', ['status' => 422]);
    }

    // 3) Post cible : page d'accueil statique si définie, sinon produit du landing.
    $target_id = abaya_reviews_target_post_id();
    if (!$target_id) {
        return new WP_Error('abaya_no_target', 'خطأ في الخدمة. يرجى المحاولة لاحقاً.', ['status' => 500]);
    }

    // 4) Persistance via le système natif de commentaires : type « review », statut « en attente » (0).
    $comment_id = wp_insert_comment([
        'comment_post_ID'      => $target_id,
        'comment_author'       => 'عميل',
        'comment_author_email' => '',
        'comment_author_url'   => '',
        'comment_content'      => $comment,
        'comment_type'         => 'review',
        'comment_approved'     => 0,
        'comment_meta'         => ['rating' => $rating],
    ]);
    if (!$comment_id || is_wp_error($comment_id)) {
        return new WP_Error('abaya_review_failed', 'خطأ في الخدمة. يرجى المحاولة لاحقاً.', ['status' => 500]);
    }

    // 5) L'avis ne s'affichera qu'après validation manuelle (comment_approved => 1).
    return rest_ensure_response([
        'success'    => true,
        'moderation' => true,
        'message'    => 'شكراً لك! تم استلام تقييمك وسيُنشر بعد المراجعة.',
    ]);
}

/* ---------- Enregistrement des routes REST (méthode POST) ---------- */
add_action('rest_api_init', function () {
    register_rest_route('landing/v1', '/create-order', [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'abaya_handle_create_order',
        'permission_callback' => '__return_true',
    ]);
    register_rest_route('landing/v1', '/submit-review', [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'abaya_handle_submit_review',
        'permission_callback' => '__return_true',
    ]);
});

/* ---------- Création automatique des pages (une seule fois, sans écraser l'existant) ---------- */
function abaya_ensure_pages() {
    if (get_option('abaya_pages_version') === ABAYA_PAGES_VERSION) return;

    if (!get_option('permalink_structure')) {
        update_option('permalink_structure', '/%postname%/');
    }

    foreach (abaya_pages() as $slug => $p) {
        if (get_page_by_path($slug)) continue; // ne jamais écraser une page existante
        $file = get_template_directory() . '/pages/' . $slug . '.html';
        wp_insert_post([
            'post_type'    => 'page',
            'post_status'  => 'publish',
            'post_name'    => $slug,
            'post_title'   => $p[0],
            'post_content' => file_exists($file) ? file_get_contents($file) : '',
        ]);
    }

    update_option('abaya_pages_version', ABAYA_PAGES_VERSION);
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'abaya_ensure_pages');
add_action('admin_init', 'abaya_ensure_pages'); // couvre le cas d'une mise à jour du thème déjà actif
