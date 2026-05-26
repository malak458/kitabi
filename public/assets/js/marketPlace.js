// ===== SLIDER =====
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

// ===== NAVBAR SCROLL =====
window.addEventListener("scroll", () => {
    document
        .getElementById("mainNav")
        ?.classList.toggle("scrolled", window.scrollY > 50);
});

// ===== HEART TOGGLE =====

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
// ===== PROMO BAND PAUSE =====
const band = document.querySelector(".promo-items");

if (band) {
    band.addEventListener("mouseenter", () => {
        band.style.animationPlayState = "paused";
    });

    band.addEventListener("mouseleave", () => {
        band.style.animationPlayState = "running";
    });
}

// ===== LIVE SEARCH =====
const searchInput = document.getElementById("searchInput");
const searchSuggestions = document.getElementById("searchSuggestions");
const filterForm = document.getElementById("filterForm");

let searchTimeout;

if (searchInput) {
    searchInput.addEventListener("input", () => {
        clearTimeout(searchTimeout);

        const q = searchInput.value.trim();

        if (q.length < 2) {
            searchSuggestions.style.display = "none";
            return;
        }

        searchTimeout = setTimeout(async () => {
            try {
                const res = await fetch(
                    `/marketplace/live-search?q=${encodeURIComponent(q)}`
                );

                const data = await res.json();

                searchSuggestions.innerHTML = "";

                if (data.length === 0) {
                    searchSuggestions.style.display = "none";
                    return;
                }

                data.forEach((book) => {
                    const li = document.createElement("li");

                    li.style.cssText = `
                        padding:8px 16px;
                        cursor:pointer;
                        font-size:13px;
                        color:#1a1208;
                        border-bottom:1px solid #f0ece3;
                        `;

                    li.innerHTML = `
                        <strong>${book.titre}</strong>
                        <span style="color:#888">
                            — ${book.auteur ?? ""}
                        </span>

                        <span style="float:right;color:green;">
                            ${book.prix}dt
                        </span>
                    `;

                    li.addEventListener("click", () => {
                        searchInput.value = book.titre;

                        searchSuggestions.style.display = "none";

                        filterForm.submit();
                    });

                    li.addEventListener("mouseenter", () => {
                        li.style.background = "#f9efd0";
                    });

                    li.addEventListener("mouseleave", () => {
                        li.style.background = "";
                    });

                    searchSuggestions.appendChild(li);
                });

                searchSuggestions.style.display = "block";
            } catch (e) {
                console.error("Live search error:", e);
            }
        }, 350);
    });

    searchInput.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
            searchSuggestions.style.display = "none";

            filterForm.submit();
        }

        if (e.key === "Escape") {
            searchSuggestions.style.display = "none";
        }
    });

    document.addEventListener("click", (e) => {
        if (!searchInput.contains(e.target)) {
            searchSuggestions.style.display = "none";
        }
    });
}
