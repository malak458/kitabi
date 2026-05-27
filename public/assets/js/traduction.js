const navTranslations = {
  en: {
    // ===== NAVBAR =====
    nav_marketplace: '<span class="material-icons">local_mall</span> Marketplace',
    nav_messages: '<span class="material-icons">chat_bubble</span> Messages',
    nav_exchanges: '<span class="material-icons">swap_horiz</span> Exchanges',
    nav_favorites: '<span class="material-icons">favorite</span> Favorites',
    nav_reading_rooms: '<span class="material-icons">import_contacts</span> Reading Rooms',
    nav_dashboard: '<span class="material-icons">dashboard</span> Dashboard',
    nav_leaderboard: '<span class="material-icons">emoji_events</span> Leaderboard',
    nav_profile: '<span class="material-icons">account_circle</span> Profile',
    nav_logout: '<span class="material-icons">logout</span> Log out',

    // ===== COMMON =====
    mode_dark: "Dark",
    mode_light: "Light",
    screen_full: "Fullscreen",
    screen_exit: "Exit Fullscreen",

    // ===== FAVORITES =====
    fav_title: "My Favorites",
    fav_subtitle: "Your curated collection of beloved books",
    empty_fav_title: "Your list is empty",
    empty_fav_text: "You haven't added any books to your favorites yet.",
    empty_fav_btn: "Explore Marketplace",

    // ===== STATISTICS =====
    stat_total: "Total",
    stat_value: "Value",
    stat_exchange: "Exchange",
    stat_genres: "Genres",

    // ===== CARD BUTTONS & LABELS =====
    card_details: '<i class="bi bi-book"></i> Details',
    card_chat: '<i class="bi bi-chat-left"></i> Chat',
    badge_exchange: "Exchange",
    cond_new: "New",
    cond_good: "Good",
    cond_used: "Used",
    author_unknown: "Unknown Author",
    user_anonymous: "Anonymous"
  },

  fr: {
    // ===== NAVBAR =====
    nav_marketplace: '<span class="material-icons">local_mall</span> Marché',
    nav_messages: '<span class="material-icons">chat_bubble</span> Messages',
    nav_exchanges: '<span class="material-icons">swap_horiz</span> Échanges',
    nav_favorites: '<span class="material-icons">favorite</span> Favoris',
    nav_reading_rooms: '<span class="material-icons">import_contacts</span> Salons de lecture',
    nav_dashboard: '<span class="material-icons">dashboard</span> Tableau de bord',
    nav_leaderboard: '<span class="material-icons">emoji_events</span> Classement',
    nav_profile: '<span class="material-icons">account_circle</span> Profil',
    nav_logout: '<span class="material-icons">logout</span> Déconnexion',

    // ===== COMMON =====
    mode_dark: "Sombre",
    mode_light: "Clair",
    screen_full: "Plein écran",
    screen_exit: "Quitter plein écran",

    // ===== FAVORITES =====
    fav_title: "Mes Favoris",
    fav_subtitle: "Votre collection de livres adorés",
    empty_fav_title: "Votre liste est vide",
    empty_fav_text: "Vous n'avez pas encore ajouté de livres à vos favoris.",
    empty_fav_btn: "Explorer le Marketplace",

    // ===== STATISTICS =====
    stat_total: "Total",
    stat_value: "Valeur",
    stat_exchange: "Échange",
    stat_genres: "Genres",

    // ===== CARD BUTTONS & LABELS =====
    card_details: '<i class="bi bi-book"></i> Détails',
    card_chat: '<i class="bi bi-chat-left"></i> Discussion',
    badge_exchange: "Échange",
    cond_new: "Neuf",
    cond_good: "Bon état",
    cond_used: "Abîmé",
    author_unknown: "Auteur inconnu",
    user_anonymous: "Anonyme"
  },

  ar: {
    // ===== NAVBAR =====
    nav_marketplace: '<span class="material-icons">local_mall</span> السوق',
    nav_messages: '<span class="material-icons">chat_bubble</span> الرسائل',
    nav_exchanges: '<span class="material-icons">swap_horiz</span> التبادلات',
    nav_favorites: '<span class="material-icons">favorite</span> المفضلة',
    nav_reading_rooms: '<span class="material-icons">import_contacts</span> غرف القراءة',
    nav_dashboard: '<span class="material-icons">dashboard</span> لوحة التحكم',
    nav_leaderboard: '<span class="material-icons">emoji_events</span> المتصدرون',
    nav_profile: '<span class="material-icons">account_circle</span> الملف الشخصي',
    nav_logout: '<span class="material-icons">logout</span> تسجيل الخروج',

    // ===== COMMON =====
    mode_dark: "داكن",
    mode_light: "فاتح",
    screen_full: "ملء الشاشة",
    screen_exit: "إلغاء ملء الشاشة",

    // ===== FAVORITES =====
    fav_title: "مفضلتي",
    fav_subtitle: "مجموعتك المنسقة من الكتب المحبوبة",
    empty_fav_title: "قائمتك فارغة",
    empty_fav_text: "لم تقم بإضافة أي كتب إلى المفضلة بعد.",
    empty_fav_btn: "استكشاف المتجر",

    // ===== STATISTICS =====
    stat_total: "الإجمالي",
    stat_value: "القيمة",
    stat_exchange: "التبادلات",
    stat_genres: "التصنيفات",

    // ===== CARD BUTTONS & LABELS =====
    card_details: '<i class="bi bi-book"></i> التفاصيل',
    card_chat: '<i class="bi bi-chat-left"></i> الدردشة',
    badge_exchange: "تبادل",
    cond_new: "جديد",
    cond_good: "حالة جيدة",
    cond_used: "مستعمل",
    author_unknown: "مؤلف مجهول",
    user_anonymous: "مجهول"
  },
};

function setEl(id, value, html = true) {
  const el = document.getElementById(id);
  if (!el) return;
  if (html) { el.innerHTML = value; } else { el.textContent = value; }
}

window.setLang = function (lang) {
  if (!navTranslations[lang]) return;

  const t = navTranslations[lang];
  window.currentTranslations = t;

  // 1. TRADUCTION DES ELEMENTS STATIQUES (Navbar, Titres, Stats)
  Object.entries(t).forEach(([id, val]) => {
    if (id.startsWith("nav_") || id.startsWith("fav_") || id.startsWith("stat_")) {
      setEl(id, val);
    }
  });

  // 2. TRADUCTION DES BOUTONS DE CARTES (Ciblage direct par classe globale)
  document.querySelectorAll('.details').forEach(el => el.innerHTML = t.card_details);
  document.querySelectorAll('.chat').forEach(el => el.innerHTML = t.card_chat);
  document.querySelectorAll('.exchange').forEach(el => el.textContent = t.badge_exchange);

  // 3. TRADUCTION DES BADGES DE CONDITION
  document.querySelectorAll('.condition').forEach(el => {
    if (el.classList.contains('new')) el.textContent = t.cond_new;
    if (el.classList.contains('good')) el.textContent = t.cond_good;
    if (el.classList.contains('used')) el.textContent = t.cond_used;
  });

  // 4. TRADUCTION DYNAMIQUE DES TEXTES DE REPLI (Auteur Inconnu & Anonyme)
  document.querySelectorAll('.book-left p').forEach(el => {
    const txt = el.textContent.trim();
    if (txt === "Unknown Author" || txt === "Auteur inconnu" || txt === "مؤلف مجهول") {
      el.textContent = t.author_unknown;
    }
  });

  document.querySelectorAll('.user span').forEach(el => {
    const txt = el.textContent.trim();
    if (txt === "Anonymous" || txt === "Anonyme" || txt === "مجهول") {
      el.textContent = t.user_anonymous;
    }
  });

  // 5. TRADUCTION DU BOUTON DE LISTE VIDE
  const emptyFavBtn = document.getElementById("empty_fav_btn");
  if (emptyFavBtn) {
    emptyFavBtn.innerHTML = `<span class="material-icons">local_mall</span> ${t.empty_fav_btn}`;
  }

  // 6. GESTION DU MODE RTL POUR L'ARABE
  document.body.classList.toggle("rtl", lang === "ar");
  document.documentElement.setAttribute("dir", lang === "ar" ? "rtl" : "ltr");

  // 7. BOUTON DE LANGUE ACTIF
  document.querySelectorAll(".lang-btn").forEach((btn) => btn.classList.remove("lang-active"));
  const activeBtn = document.getElementById("btn-" + lang);
  if (activeBtn) activeBtn.add ? activeBtn.classList.add("lang-active") : activeBtn.classList.add("lang-active");

  localStorage.setItem("kitabi_lang", lang);
};

// ================= INITIALISATION =================
document.addEventListener("DOMContentLoaded", function () {
  document.getElementById("btn-en")?.addEventListener("click", () => window.setLang("en"));
  document.getElementById("btn-fr")?.addEventListener("click", () => window.setLang("fr"));
  document.getElementById("btn-ar")?.addEventListener("click", () => window.setLang("ar"));

  // Applique la langue sauvegardée ou le français par défaut
  window.setLang(localStorage.getItem("kitabi_lang") || "fr");
});