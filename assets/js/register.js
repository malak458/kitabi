// IMAGE PREVIEW
const input = document.getElementById("profile_image");
const preview = document.getElementById("previewImg");

if (input) {
    input.addEventListener("change", function () {
        const file = this.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.transform = "scale(1.05)";
                setTimeout(() => {
                    preview.style.transform = "scale(1)";
                }, 200);
            };

            reader.readAsDataURL(file);
        }
    });
}

// INPUT FOCUS ANIMATION
document.querySelectorAll("input, textarea").forEach((el) => {
    el.addEventListener("focus", () => {
        el.parentElement?.classList.add("active");
    });

    el.addEventListener("blur", () => {
        el.parentElement?.classList.remove("active");
    });
});

// BUTTON micro interaction
const btn = document.querySelector(".btn-primary");

if (btn) {
    btn.addEventListener("click", () => {
        btn.style.transform = "scale(0.98)";
        setTimeout(() => {
            btn.style.transform = "scale(1)";
        }, 120);
    });
    let index = 0;
    const slides = document.querySelectorAll(".slide");

    function showSlide() {
        slides.forEach(s => s.classList.remove("active"));
        slides[index].classList.add("active");

        index = (index + 1) % slides.length;
    }

    showSlide();
    setInterval(showSlide, 3000);
}
