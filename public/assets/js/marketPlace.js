// SLIDER
let current = 0;
const total = 3;
let timer;

function goTo(n, fromUser = true) {
    const slides = document.querySelectorAll(".slide");
    const dots = document.querySelectorAll(".dot-btn");

    slides[current].classList.remove("active");

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

document.addEventListener("keydown", (e) => {
    if (e.key === "ArrowRight") next();
    if (e.key === "ArrowLeft") prev();
});

// NAVBAR SCROLL
window.addEventListener("scroll", () => {
    document
        .getElementById("mainNav")
        ?.classList.toggle("scrolled", window.scrollY > 50);
});

//HEART TOGGLE

const heartButtons = document.querySelectorAll(".heart-btn");

heartButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
        btn.classList.toggle("active");

        if (btn.classList.contains("active")) {
            btn.innerHTML = "♥";
        } else {
            btn.innerHTML = "♡";
        }
    });
});
// PROMO BAND PAUSE
const band = document.querySelector(".promo-items");

if (band) {
    band.addEventListener("mouseenter", () => {
        band.style.animationPlayState = "paused";
    });

    band.addEventListener("mouseleave", () => {
        band.style.animationPlayState = "running";
    });
}

/// LIVE SEARCH

const searchInput = document.getElementById("searchInput");
const allBooks = document.querySelectorAll(".book-card");

//IMAGE NO RESULT DYNAMIQUE

let dynamicNoResult = document.getElementById("dynamic-no-result");

if (!dynamicNoResult) {
    dynamicNoResult = document.createElement("div");

    dynamicNoResult.id = "dynamic-no-result";

    dynamicNoResult.style.cssText = `
    display:none;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    width:100%;
    min-height:500px;
    grid-column:1/-1;
    text-align:center;
`;

    dynamicNoResult.innerHTML = `
        <img
            src="/images/notfound.png"
            alt="Aucun résultat"
            style="max-width:380px; width:90%; opacity:0.9;"
        />
    `;

    document.querySelector(".cards-container")?.appendChild(dynamicNoResult);
}

// LIVE SEARCH FUNCTION

function liveSearchBooks() {
    const query = searchInput.value.trim().toLowerCase();

    let visible = 0;

    allBooks.forEach((book) => {
        const titre =
            book.querySelector(".nombook")?.textContent.toLowerCase() || "";

        const auteur =
            book.querySelector(".card-body p")?.textContent.toLowerCase() || "";

        const genre =
            book.querySelector(".booktype")?.textContent.toLowerCase() || "";

        const match =
            query === "" ||
            titre.includes(query) ||
            auteur.includes(query) ||
            genre.includes(query);

        if (match) {
            book.style.display = "";
            visible++;
        } else {
            book.style.display = "none";
        }
    });

    dynamicNoResult.style.display = visible === 0 ? "flex" : "none";

    const countNum = document.getElementById("countNum");
    if (countNum) countNum.textContent = visible;
}

if (searchInput) {
    searchInput.addEventListener("input", liveSearchBooks);

    searchInput.addEventListener("keydown", (e) => {
        if (e.key === "Enter") e.preventDefault();
    });
}
