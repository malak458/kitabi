const navTranslations = {
  en: {
    // ===== NAVBAR =====

    nav_marketplace:
      '<span class="material-icons">local_mall</span> Marketplace',

    nav_messages: '<span class="material-icons">chat_bubble</span> Messages',

    nav_exchanges: '<span class="material-icons">swap_horiz</span> Exchanges',

    nav_favorites: '<span class="material-icons">favorite</span> Favorites',

    nav_reading_rooms:
      '<span class="material-icons">import_contacts</span> Reading Rooms',

    nav_dashboard: '<span class="material-icons">dashboard</span> Dashboard',

    nav_leaderboard:
      '<span class="material-icons">emoji_events</span> Leaderboard',

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
  },

  fr: {
    // ===== NAVBAR =====

    nav_marketplace: '<span class="material-icons">local_mall</span> Marché',

    nav_messages: '<span class="material-icons">chat_bubble</span> Messages',

    nav_exchanges: '<span class="material-icons">swap_horiz</span> Échanges',

    nav_favorites: '<span class="material-icons">favorite</span> Favoris',

    nav_reading_rooms:
      '<span class="material-icons">import_contacts</span> Salons',

    nav_dashboard:
      '<span class="material-icons">dashboard</span> Tableau de bord',

    nav_leaderboard:
      '<span class="material-icons">emoji_events</span> Classement',

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
  },

  ar: {
    // ===== NAVBAR =====

    nav_marketplace: '<span class="material-icons">local_mall</span> السوق',

    nav_messages: '<span class="material-icons">chat_bubble</span> الرسائل',

    nav_exchanges: '<span class="material-icons">swap_horiz</span> التبادلات',

    nav_favorites: '<span class="material-icons">favorite</span> المفضلة',

    nav_reading_rooms:
      '<span class="material-icons">import_contacts</span> غرف القراءة',

    nav_dashboard: '<span class="material-icons">dashboard</span> لوحة التحكم',

    nav_leaderboard:
      '<span class="material-icons">emoji_events</span> المتصدرون',

    nav_profile:
      '<span class="material-icons">account_circle</span> الملف الشخصي',

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
  },
};

// ================= HELPER =================

function setEl(id, value, html = true) {
  const el = document.getElementById(id);

  if (!el) return;

  if (html) {
    el.innerHTML = value;
  } else {
    el.textContent = value;
  }
}

// ================= MAIN FUNCTION =================

window.setLang = function (lang) {
  if (!navTranslations[lang]) return;

  const t = navTranslations[lang];

  // Sauvegarde globale

  window.currentTranslations = t;

  // ===== NAVBAR =====

  Object.entries(t).forEach(([id, val]) => {
    if (id.startsWith("nav_")) {
      setEl(id, val);
    }
  });

  // ===== PAGE TRANSLATIONS =====

  if (window.pageTranslations && window.pageTranslations[lang]) {
    const pt = window.pageTranslations[lang];

    Object.entries(pt).forEach(([id, val]) => {
      const el = document.getElementById(id);

      if (!el) return;

      if (id.endsWith("_html")) {
        el.innerHTML = val;
      } else {
        el.textContent = val;
      }
    });

    // ===== PLACEHOLDERS =====

    if (pt._placeholders) {
      Object.entries(pt._placeholders).forEach(([id, val]) => {
        const el = document.getElementById(id);

        if (el) el.placeholder = val;
      });
    }

    // ===== SELECTORS =====

    if (pt._selectors) {
      Object.entries(pt._selectors).forEach(([selector, val]) => {
        document.querySelectorAll(selector).forEach((el) => {
          el.textContent = val;
        });
      });
    }
  }

  // ===== FAVORITES =====

  setEl("fav_title", t.fav_title, false);

  setEl("fav_subtitle", t.fav_subtitle, false);

  setEl("empty_fav_title", t.empty_fav_title, false);

  setEl("empty_fav_text", t.empty_fav_text, false);

  const emptyFavBtn = document.getElementById("empty_fav_btn");

  if (emptyFavBtn) {
    emptyFavBtn.innerHTML = `
        <span class="material-icons">
          local_mall
        </span>
        ${t.empty_fav_btn}
      `;
  }

  // ===== RTL =====

  document.body.classList.toggle("rtl", lang === "ar");

  document.documentElement.setAttribute("dir", lang === "ar" ? "rtl" : "ltr");

  // ===== ACTIVE BUTTON =====

  document.querySelectorAll(".lang-btn").forEach((btn) => {
    btn.classList.remove("lang-active");
  });

  const activeBtn = document.getElementById("btn-" + lang);

  if (activeBtn) {
    activeBtn.classList.add("lang-active");
  }

  // ===== UPDATE EXTERNAL BUTTONS =====

  if (typeof window.updateModeButton === "function") {
    window.updateModeButton();
  }

  if (typeof window.updateFullscreenButton === "function") {
    window.updateFullscreenButton();
  }

  // ===== SAVE =====

  localStorage.setItem("kitabi_lang", lang);
};

// ================= INIT =================

document.addEventListener("DOMContentLoaded", function () {
  document.getElementById("btn-en")?.addEventListener("click", () => {
    window.setLang("en");
  });

  document.getElementById("btn-fr")?.addEventListener("click", () => {
    window.setLang("fr");
  });

  document.getElementById("btn-ar")?.addEventListener("click", () => {
    window.setLang("ar");
  });

  window.setLang(localStorage.getItem("kitabi_lang") || "fr");
});
