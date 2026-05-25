document.addEventListener("DOMContentLoaded", function () {

  document.body.addEventListener("click", function (e) {
    const btn = e.target.closest(".heart-btn");
    if (!btn) return;

    e.preventDefault();

    const url = btn.getAttribute("data-url");
    const isPageFavoris = btn.getAttribute("data-page-favoris") === "true";
    const wasActive = btn.classList.contains("active");

    // ===== ANIMATION =====
    btn.classList.remove("pulse", "burst");
    void btn.offsetWidth; // reset animation
    btn.classList.add("pulse", "burst");
    btn.addEventListener("animationend", () => {
      btn.classList.remove("pulse", "burst");
    }, { once: true });

    // ===== REQUÊTE AJAX =====
    fetch(url, {
      method: "POST",
      headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(response => {
      if (!response.ok) throw new Error("Erreur serveur");
      return response.json();
    })
    .then(data => {
      if (data.success) {

        if (data.isFavorite) {
          // ✅ Ajouté aux favoris
          btn.classList.add("active");
          btn.textContent = "♥";
          btn.setAttribute("aria-label", "Retirer des favoris");

        } else {
          // ❌ Retiré des favoris
          btn.classList.remove("active");
          btn.textContent = "♡";
          btn.setAttribute("aria-label", "Ajouter aux favoris");

          // Sur la page favoris → on fait disparaître la carte
          if (isPageFavoris) {
            const card = btn.closest(".favorite-card") || btn.closest(".book-card");
            if (card) {
              card.style.transition = "all 0.4s ease";
              card.style.opacity = "0";
              card.style.transform = "scale(0.85)";
              setTimeout(() => card.remove(), 400);
            }
          }
        }

      } else {
        // Serveur a refusé → on annule visuellement
        alert("Une erreur est survenue.");
        btn.classList.toggle("active", wasActive);
        btn.textContent = wasActive ? "♥" : "♡";
      }
    })
    .catch(error => {
      console.error("Erreur favoris:", error);
      btn.classList.toggle("active", wasActive);
      btn.textContent = wasActive ? "♥" : "♡";
    });
  });
});