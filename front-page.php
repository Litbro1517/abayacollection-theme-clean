<?php
if (!defined('ABSPATH')) exit;
$u = esc_url(get_template_directory_uri());

// Visuel bordeaux de la section « couleurs » : cache-busting automatique.
// La version = date de modification du fichier : toute mise à jour de l'image
// change l'URL (?v=...) et force navigateurs / CDN / LiteSpeed à servir
// la nouvelle version au lieu de l'ancienne en cache.
$visuel_bordeaux = __DIR__ . '/uploads/Robe_comfy_Robe_chemise_avec_un_col_officier_et_deux_poche_tr_s_pratique_et_confortable_pour_tt___6_.webp';
$visuel_ver = 2; // fallback statique si filemtime indisponible
if (is_file($visuel_bordeaux) && ($mt = filemtime($visuel_bordeaux))) {
        $visuel_ver = $mt;
}
get_header();
?>
<main>
      <section class="hero wrap" aria-labelledby="product-title">
        <div class="gallery">
          <div class="main-photo-wrap product-image-wrapper">
            <img id="main-photo" class="main-photo" src="<?php echo esc_url(abaya_lcp_image_src()); ?>" width="1170" height="1560" fetchpriority="high" decoding="async" alt="العباية باللون البيج" />
            <span class="photo-tag">اختيار يومي أنيق</span>
            <button class="gallery-arrow gallery-prev" type="button" aria-label="الصورة السابقة">‹</button>
            <button class="gallery-arrow gallery-next" type="button" aria-label="الصورة التالية">›</button>
          </div>
          <div class="thumbnail-row" aria-label="صور العباية">
            <button class="thumbnail" type="button" data-color="أسود" data-src="<?= $u ?>/uploads/frame_021.webp" data-alt="العباية باللون الأسود" aria-label="عرض اللون الأسود" aria-pressed="false">
              <img src="<?= $u ?>/uploads/thumbs/frame_021.webp" width="151" height="170" loading="lazy" decoding="async" alt="" />
            </button>
            <button class="thumbnail is-current" type="button" data-color="بيج" data-src="<?php echo esc_url(abaya_lcp_image_src()); ?>" data-alt="العباية باللون البيج" aria-label="عرض اللون البيج" aria-pressed="true">
              <img src="<?= $u ?>/uploads/thumbs/Robe_comfy_Robe_chemise_avec_un_col_officier_et_deux_poche_tr_s_pratique_et_confortable_pour_tt___12_.webp" width="151" height="170" loading="lazy" decoding="async" alt="" />
            </button>
            <button class="thumbnail" type="button" data-color="كاكي" data-src="<?= $u ?>/uploads/frame_007.webp" data-alt="العباية باللون الكاكي" aria-label="عرض اللون الكاكي" aria-pressed="false">
              <img src="<?= $u ?>/uploads/thumbs/frame_007.webp" width="151" height="170" loading="lazy" decoding="async" alt="" />
            </button>
            <button class="thumbnail" type="button" data-color="أبيض" data-src="<?= $u ?>/uploads/frame_029-2.webp" data-alt="العباية باللون الأبيض" aria-label="عرض اللون الأبيض" aria-pressed="false">
              <img src="<?= $u ?>/uploads/thumbs/frame_029-2.webp" width="151" height="170" loading="lazy" decoding="async" alt="" />
            </button>
            <button class="thumbnail" type="button" data-color="أزرق داكن" data-src="<?= $u ?>/uploads/Robe_comfy_Robe_chemise_avec_un_col_officier_et_deux_poche_tr_s_pratique_et_confortable_pour_tt___9_.webp" data-alt="العباية باللون الأزرق الداكن" aria-label="عرض اللون الأزرق الداكن" aria-pressed="false">
              <img src="<?= $u ?>/uploads/thumbs/Robe_comfy_Robe_chemise_avec_un_col_officier_et_deux_poche_tr_s_pratique_et_confortable_pour_tt___9_.webp" width="151" height="170" loading="lazy" decoding="async" alt="" />
            </button>
            <button class="thumbnail" type="button" data-color="بني" data-src="<?= $u ?>/uploads/Robe_comfy_Robe_chemise_avec_un_col_officier_et_deux_poche_tr_s_pratique_et_confortable_pour_tt___13_.webp" data-alt="العباية باللون البني" aria-label="عرض اللون البني" aria-pressed="false">
              <img src="<?= $u ?>/uploads/thumbs/Robe_comfy_Robe_chemise_avec_un_col_officier_et_deux_poche_tr_s_pratique_et_confortable_pour_tt___13_.webp" width="151" height="170" loading="lazy" decoding="async" alt="" />
            </button>
            <button class="thumbnail" type="button" data-color="وردي ترابي" data-src="<?= $u ?>/uploads/Robe_comfy_Robe_chemise_avec_un_col_officier_et_deux_poche_tr_s_pratique_et_confortable_pour_tt___11_.webp" data-alt="العباية باللون الوردي الترابي" aria-label="عرض اللون الوردي الترابي" aria-pressed="false">
              <img src="<?= $u ?>/uploads/thumbs/Robe_comfy_Robe_chemise_avec_un_col_officier_et_deux_poche_tr_s_pratique_et_confortable_pour_tt___11_.webp" width="151" height="170" loading="lazy" decoding="async" alt="" />
            </button>
            <button class="thumbnail" type="button" data-color="عنابي" data-src="<?= $u ?>/uploads/Robe_comfy_Robe_chemise_avec_un_col_officier_et_deux_poche_tr_s_pratique_et_confortable_pour_tt___6_.webp" data-alt="العباية باللون العنابي" aria-label="عرض اللون العنابي" aria-pressed="false">
              <img src="<?= $u ?>/uploads/thumbs/Robe_comfy_Robe_chemise_avec_un_col_officier_et_deux_poche_tr_s_pratique_et_confortable_pour_tt___6_.webp" width="151" height="170" loading="lazy" decoding="async" alt="" />
            </button>
          </div>
        </div>

        <div class="product-info">
          <p class="eyebrow"><span class="eyebrow-line"></span> ABAYA COLLECTION</p>
          <h1 id="product-title">عباية خريفية من توب لولان – أنيقة وانسيابية</h1>
          <p class="product-intro">عباية خريفية أنيقة وانسيابية، بتوب مميز وفينيسيون نقية وخياطة مضوبلة من لداخل ومن برا، بجودة عالية لراحتك في الاستخدام اليومي.</p>

          <div class="price-row">
            <strong id="current-price" class="price">299 <small>درهم</small></strong>
            <del id="old-price" class="old-price">349 درهم</del>
            <span class="discount-pill">توفير 50 درهم</span>
          </div>

          <fieldset class="choice-group">
            <legend>اللون <span id="color-value" class="choice-value">بيج</span></legend>
            <div class="swatches">
              <button class="swatch" type="button" data-color="أسود" aria-label="أسود" title="أسود" aria-pressed="false"><span style="--swatch-color:#292827"></span></button>
              <button class="swatch is-selected" type="button" data-color="بيج" aria-label="بيج" title="بيج" aria-pressed="true"><span style="--swatch-color:#d6c5a8"></span></button>
              <button class="swatch" type="button" data-color="كاكي" aria-label="كاكي" title="كاكي" aria-pressed="false"><span style="--swatch-color:#69765b"></span></button>
              <button class="swatch" type="button" data-color="أبيض" aria-label="أبيض" title="أبيض" aria-pressed="false"><span style="--swatch-color:#f0eee7"></span></button>
              <button class="swatch" type="button" data-color="أزرق داكن" aria-label="أزرق داكن" title="أزرق داكن" aria-pressed="false"><span style="--swatch-color:#26384d"></span></button>
              <button class="swatch" type="button" data-color="بني" aria-label="بني" title="بني" aria-pressed="false"><span style="--swatch-color:#75594e"></span></button>
              <button class="swatch" type="button" data-color="وردي ترابي" aria-label="وردي ترابي" title="وردي ترابي" aria-pressed="false"><span style="--swatch-color:#b58e91"></span></button>
              <button class="swatch" type="button" data-color="عنابي" aria-label="عنابي" title="عنابي" aria-pressed="false"><span style="--swatch-color:#792f3c"></span></button>
            </div>
          </fieldset>

          <fieldset class="choice-group size-group">
            <legend>المقاس <span id="size-value" class="choice-value">M</span></legend>
            <div class="sizes" role="group" aria-label="اختيار المقاس">
              <button type="button" class="size-option" data-size="S">S</button>
              <button type="button" class="size-option is-selected" data-size="M">M</button>
              <button type="button" class="size-option" data-size="L">L</button>
              <button type="button" class="size-option" data-size="XL">XL</button>
              <button type="button" class="size-option" data-size="XXL">XXL</button>
            </div>
            <button class="text-link size-guide-link" type="button" data-modal="size-guide-dialog" aria-haspopup="dialog" aria-controls="size-guide-dialog">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 7.5 16.5 21l5-5L8 2.5l-5 5Z"/><path d="m12 7 2-2M15 10l2-2m-8 5 2-2m-5 5 2-2"/></svg>
              جدول المقاسات
              <span aria-hidden="true">⌄</span>
            </button>
          </fieldset>

          <section class="order-section hero-order" id="formulaire-commande" aria-labelledby="order-title">
            <div class="order-card" id="order-form-section">
              <div class="order-heading">
                <h2 id="order-title">معلومات الزبون</h2>
              </div>
              <div class="order-summary" aria-live="polite">
                <div class="summary-copy">
                  <span>اختيارك:</span>
                  <strong id="summary-choice">عباية بيج - مقاس M - قطعة واحدة</strong>
                </div>
                <strong id="summary-price" class="summary-price">299 درهم</strong>
              </div>
              <form class="order-form-grid" id="order-form" novalidate>
                <label class="form-field">
                  <span>الاسم الكامل</span>
                  <input name="name" autocomplete="name" placeholder="الاسم الكامل" required />
                  <small class="field-error"></small>
                </label>
                <label class="form-field">
                  <span>الهاتف</span>
                  <input name="phone" type="tel" inputmode="tel" autocomplete="tel" placeholder="الهاتف" required />
                  <small class="field-error"></small>
                </label>
                <label class="form-field">
                  <span>المدينة</span>
                  <input name="city" autocomplete="address-level2" placeholder="المدينة" required />
                  <small class="field-error"></small>
                </label>
                <label class="form-field">
                  <span>العنوان</span>
                  <input name="address" autocomplete="street-address" placeholder="العنوان" required />
                  <small class="field-error"></small>
                </label>
                <div class="hp-field" aria-hidden="true">
                  <label>Ne pas remplir <input type="text" name="extra_note" tabindex="-1" autocomplete="off" /></label>
                </div>
                <button class="primary-button form-submit" type="submit">اضغطي هنا للطلب</button>
              </form>
              <div class="form-result" id="form-result" role="status" hidden></div>
            </div>
          </section>
          <p class="secure-note"><span aria-hidden="true">✓</span> الدفع عند الاستلام · توصيل مجاني</p>
        </div>
      </section>

      <section class="bundles-section wrap" aria-labelledby="bundles-title">
        <div class="section-heading">
          <h2 id="bundles-title">عروض خاصة</h2>
        </div>
        <div class="bundle-grid" role="group" aria-label="اختاري العرض">
          <button class="bundle-card offer-card is-selected" type="button" data-quantity="1" aria-pressed="true">
            <span class="bundle-badge">قطعة واحدة</span>
            <strong class="bundle-price">299 <small>درهم</small></strong>
            <span class="bundle-saving">توفير 50 درهم</span>
          </button>
          <button class="bundle-card offer-card" type="button" data-quantity="2" aria-pressed="false">
            <span class="bundle-badge">قطعتان</span>
            <strong class="bundle-price">499 <small>درهم</small></strong>
            <span class="bundle-saving">توفير 99 درهم</span>
          </button>
          <button class="bundle-card offer-card" type="button" data-quantity="3" aria-pressed="false">
            <span class="bundle-badge">3 قطع</span>
            <strong class="bundle-price">699 <small>درهم</small></strong>
            <span class="bundle-saving">توفير 198 درهم</span>
          </button>
        </div>
      </section>

      <section class="visual-section visual-section-colors wrap" aria-labelledby="visual-colors-title">
        <div class="visual-row image-left">
          <figure class="visual-media">
            <img src="<?= $u ?>/uploads/Robe_comfy_Robe_chemise_avec_un_col_officier_et_deux_poche_tr_s_pratique_et_confortable_pour_tt___6_.webp?v=<?= $visuel_ver ?>" alt="العباية باللون العنابي بقصة واسعة مناسبة للخروج والعمل" loading="lazy" />
          </figure>
          <div class="visual-copy color-copy">
            <h2 class="visual-title" id="visual-colors-title">لماذا هذه العباية هي الخيار المثالي؟</h2>
            <ul class="visual-check-list" aria-label="مميزات العباية">
              <li class="is-centered">
                <span class="checkmark-badge" aria-hidden="true">✓</span>
                <span>عباية ذات جودة عالية مناسبة لجميع الخرجات والعمل</span>
              </li>
              <li class="is-centered">
                <span class="checkmark-badge" aria-hidden="true">✓</span>
                <span>متوفرة في 8 ألوان كي تختاري لونك المفضل بكل حرية.</span>
              </li>
              <li>
                <span class="checkmark-badge" aria-hidden="true">✓</span>
                <span><strong>خياطة مزدوجة متينة:</strong> تضمن استدامة العباية وتألقها حتى مع الغسيل المتكرر والاستعمال اليومي.</span>
              </li>
              <li>
                <span class="checkmark-badge" aria-hidden="true">✓</span>
                <span><strong>تخصيص القياسات:</strong> نظرًا لأننا نقوم بالخياطة المباشرة للموديل، يمكننا تعديل وتخصيص العباية من حيث (الطول والعرض/المقاس) حسب طولك ووزنك الخاص، مع الحفاظ التام على القصة والتصميم الأصلي.</span>
              </li>
            </ul>
          </div>
        </div>
      </section>

      <section class="visual-section visual-section-specs wrap" aria-labelledby="product-specs-title">
        <div class="visual-row">
          <figure class="visual-media">
            <img src="<?= $u ?>/uploads/Robe_comfy_Robe_chemise_avec_un_col_officier_et_deux_poche_tr_s_pratique_et_confortable_pour_tt___9_.webp" alt="قصة العباية الواسعة وانسياب قماش الكريب" loading="lazy" />
          </figure>
          <div class="visual-copy">
            <h2 class="visual-title" id="product-specs-title">معلومات إضافية وتفاصيل عن المنتج:</h2>
            <ul class="product-spec-list visual-check-list">
              <li><span class="checkmark-badge" aria-hidden="true">✓</span><span>نوع التوب: توب لولان مناسب للموسم الخريفي.</span></li>
              <li><span class="checkmark-badge" aria-hidden="true">✓</span><span>قصة العباية انسيابية وفضفاضة باش تعطيك راحة فالحركة واللبس.</span></li>
              <li><span class="checkmark-badge" aria-hidden="true">✓</span><span>العباية مصممة بتفاصيل بسيطة وأنيقة كتخليها سهلة التنسيق مع مختلف الإطلالات.</span></li>
              <li><span class="checkmark-badge" aria-hidden="true">✓</span><span>متوفرة بمقاسات مختلفة باش تختاري المقاس المناسب ليك.</span></li>
              <li><span class="checkmark-badge" aria-hidden="true">✓</span><span>العباية مصممة بكول أوفيسيي مع جيوب جانبية مدفونة مع الخياطة، غير بارزة للحفاظ على جمالية وأناقة الجوانب.</span></li>
            </ul>
            <a class="primary-button description-cta" href="#formulaire-commande">اضغطي هنا للطلب</a>
          </div>
        </div>
      </section>

      <section class="visual-section visual-section-inspection wrap" aria-labelledby="inspection-title">
        <div class="visual-row image-left">
          <figure class="visual-media">
            <img src="<?= $u ?>/uploads/frame_029.webp" alt="إظهار ملمس قماش العباية البيضاء وجودة خياطتها" loading="lazy" />
          </figure>
          <div class="visual-copy">
            <h2 class="visual-title" id="inspection-title">معاينة الطلبية والحق في الفحص</h2>
            <p class="visual-lead">يمكنك فتح الطرد وفحص العباية قبل الدفع.</p>
            <p class="inspection-note">يحق لك فتح الطرد وفحص القماش وجودة الخياطة أمام الموصل قبل إتمام الدفع.</p>
          </div>
        </div>
      </section>

      <section class="trust-strip" id="delivery" aria-label="مميزات التوصيل والاستبدال">
        <div class="trust-grid wrap">
          <article class="trust-item">
            <span class="trust-icon"><svg viewBox="0 0 32 32" aria-hidden="true"><path d="M5 11.5 16 5l11 6.5v13L16 31 5 24.5v-13Z"/><path d="m5.5 11.5 10.5 6 10.5-6M16 18v12M11 8l11 6"/></svg></span>
            <div id="payment"><h2>الدفع عند الاستلام</h2><p>تأكدي من الطلب قبل الدفع</p></div>
          </article>
          <article class="trust-item">
            <span class="trust-icon"><svg viewBox="0 0 32 32" aria-hidden="true"><path d="M3 9h17v15H3zM20 14h5l4 5v5h-9z"/><circle cx="9" cy="25" r="3"/><circle cx="24" cy="25" r="3"/><path d="M6 13h8m-8 4h5"/></svg></span>
            <div><h2>توصيل سريع</h2><p>التوصيل خلال 24 إلى 72 ساعة هي المدة المعتمدة عموماً. طلب خياطة المنتج يستغرق من 5 إلى 6 أيام.</p></div>
          </article>
          <article class="trust-item">
            <span class="trust-icon"><svg viewBox="0 0 32 32" aria-hidden="true"><path d="M26 10V4l-5 5a12 12 0 0 0-18 9m3 4v6l5-5a12 12 0 0 0 18-9"/><path d="M10 14h12m-12 4h8"/></svg></span>
            <div><h2>استبدال المقاس</h2><p>يمكن استبدال المقاس حسب رغبتك، بشرط تحمل مصاريف التوصيل فقط.</p></div>
          </article>
        </div>
      </section>

      <section class="order-guide wrap" aria-labelledby="order-guide-title">
        <div class="section-heading">
          <h2 id="order-guide-title">كيف يمكنني تقديم الطلب؟</h2>
        </div>
        <ol class="order-steps">
          <li>
            <span class="order-step-number">01</span>
            <h3>الخطوة الأولى: ملء استمارة الطلب</h3>
            <p>اختاري اللون والمقاس المناسبين، ثم قومي بملء معلوماتك الشخصية (الاسم الكامل، رقم الهاتف، المدينة، والعنوان) واضغطي على زر «اضغطي هنا للطلب».</p>
          </li>
          <li>
            <span class="order-step-number">02</span>
            <h3>الخطوة الثانية: التأكيد الهاتفي</h3>
            <p>بعد وصولك إلى صفحة الشكر والتأكد من صحة الاختيارات، ستتلقين مكالمة هاتفية من فريقنا لتأكيد المقاس، اللون، الكمية المطلوبة، وتأكيد عنوان التسليم.</p>
          </li>
          <li>
            <span class="order-step-number">03</span>
            <h3>الخطوة الثالثة: الشحن والدفع عند الاستلام</h3>
            <p>سيتم شحن طلبيتك مباشرة إلى باب منزلك. يحق لك فتح الطرد ومعاينة العباية والتأكد من جودة الثوب والخياطة قبل تسليم المبلغ للموصل.</p>
          </li>
        </ol>
      </section>

      <section class="social-section wrap" id="avis-clients">
        <div class="section-heading">
          <h2>مراجعة الزبناء</h2>
        </div>
        <button class="review-button" type="button" data-modal="review-dialog" aria-haspopup="dialog" aria-controls="review-dialog">إضافة تقييمك</button>
        <p class="review-feedback" id="reviews-feedback" role="status" hidden></p>
        <div class="reviews-list-container" aria-live="polite">
<?php
/* Correctif C2 : rendu serveur des avis approuvés (persistance MySQL via wp_insert_comment,
 * type « review », statut « en attente » à la soumission). Plus aucune volatilité au rafraîchissement. */
$abaya_reviews_args   = ['status' => 'approve', 'type' => 'review', 'number' => 20];
$abaya_reviews_target = abaya_reviews_target_post_id();
if ($abaya_reviews_target) $abaya_reviews_args['post_id'] = $abaya_reviews_target;
foreach (get_comments($abaya_reviews_args) as $abaya_review) {
    abaya_render_review_card($abaya_review);
}
?>
        </div>
      </section>

      <section class="faq-section" id="faq">
        <div class="wrap faq-inner">
          <div class="section-heading">
            <h2>الأسئلة الشائعة:</h2>
          </div>
          <ul class="faq-list">
            <li>
              <p class="faq-question">شحال المدة باش توصل الطلبية؟</p>
              <p class="faq-answer">التوصيل خلال 24 إلى 72 ساعة هي المدة المعتمدة عموماً، أما طلب خياطة المنتج يستغرق من 5 إلى 6 أيام.</p>
            </li>
            <li>
              <p class="faq-question">شحال ثمن التوصيل ؟</p>
              <p class="faq-answer">التوصيل فابور على جميع المنتجات ديالنا.</p>
            </li>
            <li>
              <p class="faq-question">واش إيلا لقيت فالطلبية شي مشكل نقدر نرجع فلوسي ؟</p>
              <p class="faq-answer">يحق لك تقديم طلب الاستبدال أو الإرجاع خلال 72 ساعة من استلام الطرد وفق الشروط الموضحة في سياسة الاستبدال والاسترجاع.</p>
            </li>
            <li>
              <p class="faq-question">واش نقدر نبدل القياس؟</p>
              <p class="faq-answer">نعم، كنديرو خدمة تغيير القياس، يمكن لك تبدل القياس حسب رغبتك بشرط تخلص مصاريف التوصيل فقط.</p>
            </li>
          </ul>
        </div>
      </section>

      <section class="contact-section" id="contact-section">
        <h2>من أجل أي استفسار، تواصلوا معنا عبر الواتساب:</h2>
        <p><a class="ltr-phone whatsapp-link" href="https://wa.me/212698738664" dir="ltr"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="11" fill="#25D366"/><path fill="#fff" d="M16.7 14.4c-.26-.13-1.54-.76-1.78-.85-.24-.09-.41-.13-.58.13-.17.26-.67.85-.82 1.02-.15.17-.3.19-.56.06-.26-.13-1.1-.41-2.1-1.3-.78-.7-1.3-1.57-1.45-1.83-.15-.26-.02-.4.11-.53.12-.12.26-.3.39-.45.13-.15.17-.26.26-.43.09-.17.04-.32-.02-.45-.06-.13-.58-1.39-.8-1.9-.21-.5-.42-.43-.58-.44h-.5c-.17 0-.45.06-.69.32-.24.26-.9.88-.9 2.14s.92 2.48 1.05 2.65c.13.17 1.8 2.75 4.36 3.85.61.26 1.08.42 1.45.54.61.19 1.17.16 1.61.1.49-.07 1.54-.63 1.76-1.24.21-.61.21-1.13.15-1.24-.06-.11-.24-.17-.5-.3z"/></svg><span>+212 698-738664</span></a></p>
      </section>
    </main>

<dialog class="info-modal size-guide-modal" id="size-guide-dialog" aria-labelledby="measurements-title">
      <button class="modal-close" type="button" aria-label="إغلاق">×</button>
      <h2 id="measurements-title">جدول المقاسات</h2>
      <div class="size-table-wrap">
        <table>
          <thead><tr><th>المقاس</th><th>القامة / الطول</th><th>الوزن</th></tr></thead>
          <tbody>
            <tr><th>S</th><td>150–158 cm</td><td>50–60 kg</td></tr>
            <tr><th>M</th><td>158–164 cm</td><td>60–70 kg</td></tr>
            <tr><th>L</th><td>164–170 cm</td><td>70–80 kg</td></tr>
            <tr><th>XL</th><td>170–175 cm</td><td>80–90 kg</td></tr>
            <tr><th>XXL</th><td>175–180 cm</td><td>90–100 kg</td></tr>
          </tbody>
        </table>
      </div>
    </dialog>

    <dialog class="info-modal review-modal" id="review-dialog" aria-labelledby="review-title">
      <button class="modal-close" type="button" aria-label="إغلاق">×</button>
      <h2 id="review-title">إضافة تقييمك</h2>
      <form id="review-form">
        <label class="review-field">
          <span>تقييمك</span>
          <select id="review-rating" name="rating" required aria-label="عدد النجوم">
            <option value="5">★★★★★ — ممتاز</option>
            <option value="4">★★★★☆ — جيد جداً</option>
            <option value="3">★★★☆☆ — جيد</option>
            <option value="2">★★☆☆☆ — مقبول</option>
            <option value="1">★☆☆☆☆ — ضعيف</option>
          </select>
        </label>
        <label class="review-field">
          <span>رأيك في المنتج</span>
          <textarea id="review-comment" name="review" rows="4" placeholder="اكتبي تقييمك هنا" required></textarea>
        </label>
        <button class="primary-button review-submit" type="submit">إرسال التقييم</button>
      </form>
    </dialog>

<?php get_footer(); ?>
