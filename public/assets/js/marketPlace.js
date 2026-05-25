// ===== VARIABLES GLOBALES =====
const selectCondition = document.getElementById("constionselect");
const allBooks = document.querySelectorAll(".book-card");
const genreselect = document.getElementById("genreselect");
const searchnav = document.querySelector(".nav-search");
const noResult = document.getElementById("no-result");

// ===== FONCTION CENTRALE : filtre recherche + condition + genre =====
function filtrerbooks() {
  const selected1 = selectCondition.value.toLowerCase();
  const selected2 = genreselect.value.toLowerCase();
  const query = searchnav.value.trim().toLowerCase();

  allBooks.forEach((book) => {
    let condition = book
      .querySelector(".conditionbook")
      .textContent.toLowerCase()
      .replace("-", " ");
    let typeb = book.querySelector(".booktype").textContent.toLowerCase();
    let titre = book.querySelector(".nombook")?.textContent.toLowerCase() || "";
    let auteur =
      book.querySelector(".card-body p")?.textContent.toLowerCase() || "";

    const condOk = selected1 === "all condition" || condition === selected1;
    const genreOk = selected2 === "all genres" || selected2 === typeb;
    const searchOk =
      query === "" ||
      titre.includes(query) ||
      auteur.includes(query) ||
      typeb.includes(query);

    book.style.display = condOk && genreOk && searchOk ? "" : "none";
  });

  // Affiche image si aucun résultat
  const visible = [...allBooks].filter(
    (b) => b.style.display !== "none"
  ).length;
  if (noResult) noResult.style.display = visible === 0 ? "flex" : "none";

  // Met à jour le compteur
  const counter = document.getElementById("bookCount");
  document.getElementById("countNum").textContent = visible;
}

// Écoute filtres + recherche live
selectCondition.addEventListener("change", filtrerbooks);
genreselect.addEventListener("change", filtrerbooks);
searchnav.addEventListener("input", filtrerbooks);

// ===== TRI : prix / A-Z =====
const bookscontainer = document.querySelector(".cards-container");
let tabbooks = Array.from(allBooks);
const priceAZselct = document.getElementById("priceAZselct");

function updatebooksorder() {
  for (const element of tabbooks) {
    bookscontainer.appendChild(element);
  }
}

priceAZselct.addEventListener("click", () => {
  const optionn = priceAZselct.value;
  switch (optionn) {
    case "low to height":
      tabbooks.sort(
        (a, b) =>
          parseFloat(a.querySelector(".price").textContent) -
          parseFloat(b.querySelector(".price").textContent)
      );
      break;
    case "height to low":
      tabbooks.sort(
        (a, b) =>
          parseFloat(b.querySelector(".price").textContent) -
          parseFloat(a.querySelector(".price").textContent)
      );
      break;
    case "title : A-Z":
      tabbooks.sort((a, b) =>
        a
          .querySelector(".nombook")
          .textContent.toLocaleLowerCase()
          .localeCompare(
            b.querySelector(".nombook").textContent.toLocaleLowerCase()
          )
      );
      break;
  }
  updatebooksorder();
});

// ===== SLIDER =====
let current = 0;
const total = 3;
let timer;

function goTo(n, fromUser = true) {
  const slides = document.querySelectorAll(".slide");
  const dots = document.querySelectorAll(".dot-btn");
  slides[current].classList.remove("active");
  slides[current].classList.add("prev");
  setTimeout(() => slides[current].classList.remove("prev"), 5000);
  current = (n + total) % total;
  slides[current].classList.add("active");
  dots.forEach((d) => d.classList.remove("active"));
  dots[current].classList.add("active");
  document.getElementById("slideCounter").textContent =
    String(current + 1).padStart(2, "0") +
    " / " +
    String(total).padStart(2, "0");
  if (fromUser) {
    clearInterval(timer);
    startAuto();
  }
}

function next() {
  goTo(current + 1);
}
function prev() {
  goTo(current - 1);
}
function startAuto() {
  timer = setInterval(() => goTo(current + 1, false), 5000);
}
startAuto();

// Keyboard nav slider
document.addEventListener("keydown", (e) => {
  if (e.key === "ArrowRight") next();
  if (e.key === "ArrowLeft") prev();
});

// ===== NAV SCROLL =====
window.addEventListener("scroll", () => {
  document
    .getElementById("mainNav")
    ?.classList.toggle("scrolled", window.scrollY > 50);
});

// ===== HEART TOGGLE =====
document.querySelectorAll(".heart-btn").forEach((btn) => {
  btn.addEventListener("click", function () {
    this.textContent = this.textContent === "♡" ? "♥" : "♡";
    this.style.background = this.textContent === "♥" ? "#c8860a" : "";
    this.style.color = this.textContent === "♥" ? "#fff" : "";
  });
});

// ===== PROMO BAND PAUSE =====
const band = document.querySelector(".promo-items");
band.addEventListener("mouseenter", () => {
  band.style.animationPlayState = "paused";
});
band.addEventListener("mouseleave", () => {
  band.style.animationPlayState = "running";
});

document.addEventListener('DOMContentLoaded', function () {
    // On sélectionne toutes les cases à cocher (les cœurs)
    const heartCheckboxes = document.querySelectorAll('.heart input[type="checkbox"]');

    heartCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const isChecked = this.checked;
            // On récupère l'URL générée par Twig
            const url = this.getAttribute('data-url'); 

            if (!url) {
                console.error("Erreur : l'attribut data-url est manquant sur le coeur.");
                return;
            }

            // Envoi de la requête AJAX à Symfony
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau ou serveur');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    console.log(data.message);
                    this.checked = data.isFavorite; // Force l'état selon le serveur
                } else {
                    alert('Une erreur est survenue.');
                    this.checked = !isChecked;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                this.checked = !isChecked; // En cas de bug, on remet le coeur à son état initial
            });
        });
    });
});