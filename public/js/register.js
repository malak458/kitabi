document.addEventListener("DOMContentLoaded", function () {

    /* =====================
       IMAGE PREVIEW
    ===================== */
    const input = document.querySelector("#profile_image");
    const preview = document.querySelector("#previewImg");

    if (input && preview) {
        input.addEventListener("change", function () {
            const file = this.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result;
                };

                reader.readAsDataURL(file);
            }
        });
    }

    /* =====================
       SLIDER RIGHT SIDE
    ===================== */
    const slides = document.querySelectorAll(".auth-right img");
    let index = 0;

    if (slides.length > 0) {
        slides[index].classList.add("active");

        setInterval(() => {
            slides[index].classList.remove("active");

            index = (index + 1) % slides.length;

            slides[index].classList.add("active");

        }, 3500);
    }

});
