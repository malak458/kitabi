// 🌿 Login UI interactions

document.addEventListener("DOMContentLoaded", function () {

    const inputs = document.querySelectorAll("input");

    // Soft focus animation effect
    inputs.forEach(input => {
        input.addEventListener("focus", () => {
            input.style.transform = "scale(1.02)";
        });

        input.addEventListener("blur", () => {
            input.style.transform = "scale(1)";
        });
    });

    // Button loading effect (fake UX polish)
    const button = document.querySelector("button");

    if (button) {
        button.addEventListener("click", function () {
            button.innerText = "Connexion...";
            button.style.opacity = "0.8";

            setTimeout(() => {
                button.innerText = "Login";
                button.style.opacity = "1";
            }, 1500);
        });
    }

});
