<?php
if (!defined('ABSPATH')) exit;

// slug => [titre H1, <title> du navigateur, meta description]
function abaya_pages() {
    return [
        'about' => ['عن المتجر', 'عن المتجر | Abaya Collection', 'تعرفوا على Abaya Collection، علامة مغربية متخصصة في العبايات.'],
        'modes-paiement' => ['طرق الدفع', 'طرق الدفع | Abaya Collection', 'طريقة الدفع عند الاستلام من Abaya Collection.'],
        'livraison' => ['الشحن والتسليم 🚚', 'الشحن والتسليم | Abaya Collection', 'معلومات الشحن والتسليم لدى Abaya Collection.'],
        'conditions-utilisation' => ['شروط الاستخدام 📜', 'شروط الاستخدام | Abaya Collection', 'شروط استخدام موقع Abaya Collection.'],
        'conditions-retour' => ['سياسة الاستبدال والاسترجاع 🔄', 'سياسة الاستبدال والاسترجاع | Abaya Collection', 'سياسة الاستبدال والاسترجاع لدى Abaya Collection.'],
        'politique-confidentialite' => ['سياسة الخصوصية (الخاصة بنطاق Abaya Collection) 🔒', 'سياسة الخصوصية | Abaya Collection', 'سياسة الخصوصية الخاصة بنطاق Abaya Collection.'],
        'contact' => ['اتصل بنا 💬', 'اتصل بنا | Abaya Collection', 'تواصلوا مع فريق Abaya Collection.'],
        'merci' => ['شكراً لك! تم استلام طلبك', 'شكراً لك | Abaya Collection', ''],
    ];
}
