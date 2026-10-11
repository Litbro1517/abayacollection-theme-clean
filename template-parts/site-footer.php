<?php if (!defined('ABSPATH')) exit;
$u = esc_url(get_template_directory_uri());
?>
<footer class="site-footer">
      <div class="footer-inner wrap">
        <div class="footer-logo-wrapper">
          <a class="footer-wordmark" href="/" aria-label="Accueil Abaya Collection">
            <img src="<?= $u ?>/assets/images/logo-footer-white.png" alt="Abaya Collection" class="footer-logo-svg" width="138" height="104" loading="lazy" decoding="async" />
          </a>
        </div>
        <nav class="footer-columns" aria-label="روابط المتجر">
          <section>
            <h2>عن المتجر</h2>
            <a href="/about/">عن المتجر</a>
            <a href="/modes-paiement/">طرق الدفع</a>
            <a href="/livraison/">الشحن والتسليم</a>
          </section>
          <section>
            <h2>الشروط والسياسات</h2>
            <a href="/conditions-utilisation/">شروط الاستخدام</a>
            <a href="/conditions-retour/">سياسة الاستبدال والاسترجاع</a>
            <a href="/politique-confidentialite/">سياسة الخصوصية</a>
          </section>
          <section>
            <h2>اتصل بنا</h2>
            <a href="/contact/">اتصل بنا</a>
            <a href="#faq">الأسئلة المتكررة</a>
            <a class="ltr-phone whatsapp-link" href="https://wa.me/212698738664" dir="ltr"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="11" fill="#25D366"/><path fill="#fff" d="M16.7 14.4c-.26-.13-1.54-.76-1.78-.85-.24-.09-.41-.13-.58.13-.17.26-.67.85-.82 1.02-.15.17-.3.19-.56.06-.26-.13-1.1-.41-2.1-1.3-.78-.7-1.3-1.57-1.45-1.83-.15-.26-.02-.4.11-.53.12-.12.26-.3.39-.45.13-.15.17-.26.26-.43.09-.17.04-.32-.02-.45-.06-.13-.58-1.39-.8-1.9-.21-.5-.42-.43-.58-.44h-.5c-.17 0-.45.06-.69.32-.24.26-.9.88-.9 2.14s.92 2.48 1.05 2.65c.13.17 1.8 2.75 4.36 3.85.61.26 1.08.42 1.45.54.61.19 1.17.16 1.61.1.49-.07 1.54-.63 1.76-1.24.21-.61.21-1.13.15-1.24-.06-.11-.24-.17-.5-.3z"/></svg><span>+212 698-738664</span></a>
          </section>
        </nav>
        <p class="copyright">© 2026 Abaya Collection. جميع الحقوق محفوظة.</p>
      </div>
    </footer>
