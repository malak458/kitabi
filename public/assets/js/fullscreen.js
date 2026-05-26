// 1. Fonction globale placée en haut pour être accessible instantanément par traduction.js
window.updateFullscreenButton = function () {
  const bouton = document.getElementById("fullscreen-btn");
  if (!bouton) return; // Sécurité si le bouton n'est pas encore créé au DOM

  const t = window.currentTranslations || {
    screen_full: "Plein Écran",
    screen_exit: "Quitter Plein Écran",
  };

  // On vérifie l'état réel via la classe présente sur le body
  const estPleinEcran = document.body.classList.contains("fullscreen");

  if (estPleinEcran) {
    bouton.innerHTML = `✕ ${t.screen_exit}`;
  } else {
    bouton.innerHTML = `⛶ ${t.screen_full}`;
  }
};

document.addEventListener("DOMContentLoaded", function () {
  const bouton = document.createElement("button");
  bouton.id = "fullscreen-btn";

  // Applique la traduction directe dès la création de l'élément
  const t = window.currentTranslations || {
    screen_full: "Plein Écran",
    screen_exit: "Quitter Plein Écran",
  };
  bouton.innerHTML = `⛶ ${t.screen_full}`;

  document.body.appendChild(bouton);

  const style = document.createElement("style");
  style.textContent = `
    #fullscreen-btn {
      position: fixed;
      bottom: 28px;
      left: 20px;
      z-index: 9999;
      padding: 10px 20px;
      border-radius: 50px;
      border: 1px solid var(--border);
      background: var(--white);
      color: var(--amber);
      font-family: 'DM Sans', sans-serif;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(26,18,8,.10);
      transition: all 0.3s ease;
    }

    #fullscreen-btn:hover {
      background: var(--amber);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(200,134,10,.25);
    }

    /* Cacher la sidebar en plein écran */
    body.fullscreen .sidebar {
      display: none;
    }

    /* Le contenu prend toute la largeur */
    body.fullscreen .content {
      margin-left: 0 !important;
      width: 100% !important;
    }

    /* Bouton se déplace à gauche quand sidebar cachée */
    body.fullscreen #fullscreen-btn {
      left: 20px;
    }
  `;
  document.head.appendChild(style);

  // Un seul écouteur de clic propre
  bouton.addEventListener("click", function () {
    const estPleinEcranActuel = document.body.classList.contains("fullscreen");

    if (estPleinEcranActuel) {
      if (document.exitFullscreen) {
        document.exitFullscreen();
      }
      document.body.classList.remove("fullscreen");
    } else {
      if (document.documentElement.requestFullscreen) {
        document.documentElement.requestFullscreen();
      }
      document.body.classList.add("fullscreen");
    }

    // Met à jour le texte du bouton immédiatement après le changement d'état
    window.updateFullscreenButton();
  });

  // Gère aussi le retour à la normale via la touche Échap du clavier
  document.addEventListener("fullscreenchange", function () {
    if (!document.fullscreenElement) {
      document.body.classList.remove("fullscreen");
      window.updateFullscreenButton();
    }
  });
});
