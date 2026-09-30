const mainPhoto = document.querySelector("#main-photo");
const thumbnails = [...document.querySelectorAll(".thumbnail")];
const swatches = [...document.querySelectorAll(".swatch")];
const colorImages = new Map(
  thumbnails.map((thumbnail) => [
    thumbnail.dataset.color,
    { src: thumbnail.dataset.src, alt: thumbnail.dataset.alt },
  ]),
);
const sizeOptions = [...document.querySelectorAll(".size-option")];
const bundleOptions = [...document.querySelectorAll(".bundle-card")];
// P0-3 : plus aucune présélection — la cliente DOIT choisir couleur et taille.
const state = { color: null, size: null, quantity: 1 };
// P0-1 : la grille tarifaire des offres vient du serveur
// (window.LANDING.catalog, injectée depuis abaya_catalog()) — le front n'est
// qu'un affichage ; repli local identique si l'injection manquerait.
const LANDING = window.LANDING || {};
const bundlePrices = (LANDING.catalog && LANDING.catalog.bundles) || { 1: 299, 2: 499, 3: 699 };
const quantityLabels = { 1: "قطعة واحدة", 2: "قطعتان", 3: "3 قطع" };
let photoRequestId = 0;
// Mandat 4P : erreurs ciblées par bloc (couleur / taille). Plus aucun message
// global près du bouton : le groupe manquant est encadré en rouge avec une
// micro-secousse, et un petit texte discret (même style que les .field-error
// du formulaire) apparaît au-dessus du bloc, nettoyé dès la sélection.
const choiceBlocks = {
  color: document.querySelector('.choice-group[data-attribute="color"]'),
  size: document.querySelector('.choice-group[data-attribute="size"]'),
};
const choiceErrorSlots = {
  color: document.querySelector('[data-choice-error="color"]'),
  size: document.querySelector('[data-choice-error="size"]'),
};
const CHOICE_ERROR_MESSAGES = {
  color: "يرجى اختيار اللون.",
  size: "يرجى اختيار المقاس.",
};

function setChoiceError(attribute, hasError) {
  const slot = choiceErrorSlots[attribute];
  if (slot) {
    slot.textContent = hasError ? CHOICE_ERROR_MESSAGES[attribute] : "";
    slot.hidden = !hasError;
  }
  const block = choiceBlocks[attribute];
  if (block) block.classList.toggle("attribute-error-box", hasError);
}

function flagChoiceError(attribute) {
  setChoiceError(attribute, true);
  // Relance de la micro-secousse à chaque tentative de soumission
  // (prefers-reduced-motion neutralise l'animation côté CSS).
  const block = choiceBlocks[attribute];
  if (block) {
    block.classList.remove("attribute-error-box");
    void block.offsetWidth; // reflow : réinitialise l'animation CSS
    block.classList.add("attribute-error-box");
  }
}

function updatePhoto(src, alt) {
  const requestId = ++photoRequestId;
  mainPhoto.classList.add("is-changing");
  const nextImage = new Image();
  nextImage.onload = () => {
    if (requestId !== photoRequestId) return;
    mainPhoto.src = src;
    mainPhoto.alt = alt;
    mainPhoto.classList.remove("is-changing");
  };
  nextImage.onerror = () => {
    if (requestId === photoRequestId) mainPhoto.classList.remove("is-changing");
  };
  nextImage.src = src;
}

function selectColor(color) {
  const image = colorImages.get(color);
  if (!image) return;

  state.color = color;
  swatches.forEach((swatch) => {
    const selected = swatch.dataset.color === color;
    swatch.classList.toggle("is-selected", selected);
    swatch.setAttribute("aria-pressed", String(selected));
  });
  thumbnails.forEach((thumbnail) => {
    const selected = thumbnail.dataset.color === color;
    thumbnail.classList.toggle("is-current", selected);
    thumbnail.setAttribute("aria-pressed", String(selected));
  });
  updatePhoto(image.src, image.alt);
  document.querySelector("#color-value").textContent = color;
  setChoiceError("color", false);
  updateSummary();
}

function updateSummary() {
  const price = bundlePrices[state.quantity];
  const quantityLabel = quantityLabels[state.quantity];
  // P0-3 : gère l'état « non choisi » (color/size à null).
  const choiceText = state.color && state.size
    ? `عباية ${state.color} - مقاس ${state.size} - ${quantityLabel}`
    : `اختاري اللون والمقاس - ${quantityLabel}`;
  document.querySelector("#summary-choice").textContent = choiceText;
  document.querySelector("#summary-price").textContent = `${price} درهم`;
  bundleOptions.forEach((option) => {
    const selected = Number(option.dataset.quantity) === state.quantity;
    option.classList.toggle("is-selected", selected);
    option.setAttribute("aria-pressed", String(selected));
  });
}

swatches.forEach((swatch) => {
  swatch.addEventListener("click", () => {
    selectColor(swatch.dataset.color);
  });
});

sizeOptions.forEach((option) => {
  option.addEventListener("click", () => {
    state.size = option.dataset.size;
    sizeOptions.forEach((item) => item.classList.toggle("is-selected", item === option));
    document.querySelector("#size-value").textContent = state.size;
    setChoiceError("size", false);
    updateSummary();
  });
});

bundleOptions.forEach((option) => {
  option.addEventListener("click", () => {
    state.quantity = Number(option.dataset.quantity);
    updateSummary();
  });
});

thumbnails.forEach((thumbnail) => {
  thumbnail.addEventListener("click", () => {
    selectColor(thumbnail.dataset.color);
    thumbnail.scrollIntoView({ behavior: "smooth", block: "nearest", inline: "nearest" });
  });
});

document.querySelector(".gallery-prev").addEventListener("click", () => {
  const current = thumbnails.findIndex((item) => item.classList.contains("is-current"));
  const previous = (current - 1 + thumbnails.length) % thumbnails.length;
  thumbnails[previous].click();
});
document.querySelector(".gallery-next").addEventListener("click", () => {
  const current = thumbnails.findIndex((item) => item.classList.contains("is-current"));
  const next = (current + 1) % thumbnails.length;
  thumbnails[next].click();
});

document.querySelectorAll(".product-image-wrapper").forEach((wrapper) => {
  const image = wrapper.querySelector("img");
  wrapper.addEventListener("pointermove", (event) => {
    if (event.pointerType !== "mouse") return;
    const rect = wrapper.getBoundingClientRect();
    const x = ((event.clientX - rect.left) / rect.width) * 100;
    const y = ((event.clientY - rect.top) / rect.height) * 100;
    image.style.transformOrigin = `${x}% ${y}%`;
  });
  wrapper.addEventListener("pointerleave", () => {
    image.style.transformOrigin = "center center";
  });
});

const orderTarget = document.querySelector("#order-form-section");
document.querySelectorAll(".offer-card, .promo-option, [data-offer]").forEach((card) => {
  card.addEventListener("click", () => {
    if (!orderTarget) return;
    const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    orderTarget.scrollIntoView({
      behavior: reduceMotion ? "auto" : "smooth",
      block: "start",
    });
  });
});

document.querySelectorAll("[data-modal]").forEach((trigger) => {
  trigger.addEventListener("click", () => {
    document.getElementById(trigger.dataset.modal)?.showModal();
  });
});
document.querySelectorAll(".info-modal").forEach((modal) => {
  modal.querySelector(".modal-close")?.addEventListener("click", () => modal.close());
  modal.addEventListener("click", (event) => {
    if (event.target === modal) modal.close();
  });
});

const orderForm = document.querySelector("#order-form");
const formResult = document.querySelector("#form-result");
const reviewForm = document.querySelector("#review-form");
const reviewDialog = document.querySelector("#review-dialog");
const reviewFeedback = document.querySelector("#reviews-feedback");
const reviewSubmitButton = reviewForm.querySelector(".review-submit");

function showReviewFeedback(message, isError = false) {
  reviewFeedback.textContent = message;
  reviewFeedback.classList.toggle("is-error", Boolean(isError));
  reviewFeedback.hidden = false;
}

reviewForm.addEventListener("submit", async (event) => {
  event.preventDefault();
  if (reviewSubmitButton?.disabled) return;
  if (!reviewForm.reportValidity()) return;

  const reviewEndpoint = (window.LANDING || {}).review_endpoint;
  if (!reviewEndpoint) {
    showReviewFeedback("شكراً لمشاركة تقييمك.");
    reviewForm.reset();
    reviewDialog.close();
    return;
  }

  // Correctif C2 : l'avis est désormais envoyé au serveur (wp_insert_comment, modération) au lieu d'être injecté dans le DOM
  const reviewPayload = {
    rating: Number(reviewForm.elements.rating.value),
    comment: reviewForm.elements.review.value.trim(),
  };

  reviewSubmitButton.disabled = true;
  try {
    const response = await fetch(reviewEndpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      credentials: "same-origin",
      body: JSON.stringify(reviewPayload),
    });
    const out = await response.json().catch(() => ({}));
    reviewDialog.close();
    if (out.success) {
      // Enregistré en base avec statut « en attente » : publié après validation manuelle
      showReviewFeedback(out.message || "شكراً لك! تم استلام تقييمك وسيُنشر بعد المراجعة.");
      reviewForm.reset();
    } else {
      showReviewFeedback(out.message || "حدث خطأ، يرجى المحاولة مرة أخرى.", true);
    }
  } catch (_) {
    reviewDialog.close();
    showReviewFeedback("خطأ في الشبكة. يرجى المحاولة مرة أخرى.", true);
  }
  reviewSubmitButton.disabled = false;
});

function setFieldError(field, message) {
  const wrapper = field.closest(".form-field");
  wrapper.querySelector(".field-error").textContent = message;
  field.setAttribute("aria-invalid", message ? "true" : "false");
}

orderForm.querySelectorAll(".form-field input, .form-field select").forEach((field) => {
  field.addEventListener("input", () => setFieldError(field, ""));
  field.addEventListener("change", () => setFieldError(field, ""));
});

const LANDING_ENDPOINT = LANDING.endpoint;
const submitButton = orderForm.querySelector(".form-submit");
const submitLabel = submitButton.textContent;
// Un seul event_id par chargement de page : un nouvel essai ne crée jamais de doublon
const pageEventId = "order_" + Date.now() + "_" + Math.random().toString(36).slice(2, 9);
const pageParams = new URLSearchParams(location.search);
let checkoutTracked = false;

function track(...args) {
  try { if (window.fbq) window.fbq(...args); } catch (_) {}
}

function showFormError(message) {
  formResult.textContent = message;
  formResult.classList.add("is-error");
  formResult.hidden = false;
}

orderForm.addEventListener("focusin", () => {
  if (checkoutTracked) return;
  checkoutTracked = true;
  track("track", "InitiateCheckout", { currency: "MAD", value: bundlePrices[state.quantity] });
});

orderForm.addEventListener("submit", async (event) => {
  event.preventDefault();
  if (submitButton.disabled) return;
  formResult.hidden = true;
  formResult.classList.remove("is-error");

  const name = orderForm.elements.name;
  const phone = orderForm.elements.phone;
  const city = orderForm.elements.city;
  const address = orderForm.elements.address;
  const normalizedPhone = phone.value.replace(/[\s().-]/g, "");
  const moroccanPhone = /^(?:(?:\+|00)212)?0?[67]\d{8}$/.test(normalizedPhone);
  const validName = name.value.trim().length >= 3;
  const validCity = city.value.trim().length >= 2;
  const validAddress = address.value.trim().length >= 4;
  // P0-3 : couleur et taille obligatoires, aucune valeur par défaut.
  const validColor = Boolean(state.color);
  const validSize = Boolean(state.size);

  setFieldError(name, validName ? "" : "يرجى إدخال الاسم الكامل.");
  setFieldError(phone, moroccanPhone ? "" : "أدخلي رقم هاتف مغربي صحيحاً.");
  setFieldError(city, validCity ? "" : "يرجى اختيار المدينة.");
  setFieldError(address, validAddress ? "" : "يرجى إدخال العنوان الكامل.");

  // Mandat 4P : chaque groupe manquant est signalé indépendamment (contour
  // rouge + micro-secousse + micro-texte au-dessus du bloc) — plus aucun
  // message global près du bouton. Si aucun choix n'a été fait, les deux
  // blocs sont encadrés simultanément ; le défilement va au premier manquant.
  let missingChoice = null;
  if (!validColor) {
    flagChoiceError("color");
    missingChoice = choiceBlocks.color;
  } else {
    setChoiceError("color", false);
  }
  if (!validSize) {
    flagChoiceError("size");
    if (!missingChoice) missingChoice = choiceBlocks.size;
  } else {
    setChoiceError("size", false);
  }

  if (!validName || !moroccanPhone || !validCity || !validAddress || !validColor || !validSize) {
    if (missingChoice) {
      const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
      missingChoice.scrollIntoView({ behavior: reduceMotion ? "auto" : "smooth", block: "center" });
    } else {
      const firstInvalid = orderForm.querySelector('[aria-invalid="true"]');
      firstInvalid?.focus();
    }
    return;
  }

  if (!LANDING_ENDPOINT) {
    showFormError("خطأ في الإعداد. يرجى الاتصال بنا هاتفياً.");
    return;
  }

  submitButton.disabled = true;
  submitButton.textContent = "جارٍ إرسال الطلب...";

  const payload = {
    full_name: name.value.trim(),
    phone: phone.value.trim(),
    city: city.value.trim(),
    address: address.value.trim(),
    color: state.color,
    size: state.size,
    quantity: state.quantity,
    website_hp: orderForm.elements.extra_note ? orderForm.elements.extra_note.value : "",
    event_id: pageEventId,
    page_url: location.href.split("#")[0],
  };
  ["utm_source", "utm_medium", "utm_campaign", "utm_content", "utm_term"].forEach((key) => {
    if (pageParams.get(key)) payload[key] = pageParams.get(key);
  });

  try {
    const response = await fetch(LANDING.endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      credentials: "same-origin", // envoie les cookies _fbp / _fbc au serveur
      body: JSON.stringify(payload),
    });
    const out = await response.json().catch(() => ({}));

    if (out.success) {
      // Même event_id que le serveur + valeur réelle calculée côté serveur
      track("track", "Purchase", {
        currency: "MAD",
        value: out.value,
        content_type: "product",
        content_ids: [String(out.product_id)],
      }, { eventID: out.event_id });
      // Bouton laissé désactivé : redirection vers la page de remerciement.
      // P1-1 : passage de order_id + order_key pour afficher le
      // récapitulatif dynamique sécurisé (sans clé valide, /merci/ reste générique).
      setTimeout(() => {
        const thanksBase = LANDING.thanks || "/";
        const sep = thanksBase.includes("?") ? "&" : "?";
        const orderQuery = out.order_key
          ? `${sep}order=${encodeURIComponent(out.order_id)}&key=${encodeURIComponent(out.order_key)}`
          : "";
        location.href = thanksBase + orderQuery;
      }, 500);
      return;
    }
    showFormError(out.message || "حدث خطأ، يرجى المحاولة مرة أخرى.");
  } catch (_) {
    showFormError("خطأ في الشبكة. يرجى المحاولة مرة أخرى.");
  }
  submitButton.disabled = false;
  submitButton.textContent = submitLabel;
});

updateSummary();
