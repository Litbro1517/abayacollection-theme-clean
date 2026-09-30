<?php
/**
 * BLOC DE CONFIGURATION META PIXEL & CAPI - ABAYA COLLECTION
 * ----------------------------------------------------------
 * Ce fichier est un MODELE de référence : il ne s'exécute pas tout seul.
 *
 * ACTION REQUISE : copier le bloc ci-dessous dans le fichier wp-config.php
 * de votre hébergement WordPress, JUSTE AVANT la ligne :
 *   /* That's all, stop editing! Happy publishing. * /
 *
 * Après insertion :
 *   1. Remplacez 'VOTRE_PIXEL_ID'   par l'ID numérique de votre Pixel Meta.
 *   2. Remplacez 'VOTRE_CAPI_TOKEN' par le jeton d'accès Conversions API
 *      (Business Manager > Paramètres > Conversions API > Générer un jeton).
 *   3. Remplacez 'TESTxxxx' par votre code d'événements de test, puis
 *      SUPPRIMEZ la ligne META_TEST_EVENT_CODE avant le lancement officiel
 *      des campagnes.
 *   4. LANDING_PRODUCT_ID (15) correspond à l'ID du produit WooCommerce réellement en ligne.
 *
 * Le thème lit ces constantes automatiquement :
 *   - META_PIXEL_ID      : active le Pixel navigateur (PageView, ViewContent).
 *   - META_CAPI_TOKEN    : active l'envoi serveur Purchase (Conversions API),
 *                          dédupliqué avec le Pixel via le même event_id.
 *   - META_TEST_EVENT_CODE : route les événements vers l'outil de test Meta.
 *   - LANDING_PRODUCT_ID : identifiant produit envoyé dans les événements.
 */

/** Configuration Meta Pixel & CAPI - Abaya Collection */
define('LANDING_PRODUCT_ID', 15);   // ID du produit WooCommerce réellement en ligne (correctif D2)
define('META_PIXEL_ID', 'VOTRE_PIXEL_ID');
define('META_CAPI_TOKEN', 'VOTRE_CAPI_TOKEN');
define('META_TEST_EVENT_CODE', 'TESTxxxx'); // À retirer avant le lancement officiel des campagnes

/* Fin du bloc modèle - ne rien exécuter si ce fichier est appelé directement. */
if (!defined('ABSPATH')) {
    http_response_code(403);
    exit('Ce fichier est un modèle de configuration : copiez son contenu dans wp-config.php.');
}
