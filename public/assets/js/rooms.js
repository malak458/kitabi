

    // Votre code JavaScript pour la recherche et les onglets reste inchangé ici...
    document.querySelector(".search-bar").addEventListener("input", function () {
        const query = this.value.trim().toLowerCase();
        const cards = document.querySelectorAll(".room-card");
        let found = 0;
        cards.forEach(card => {
            const title = card.querySelector("h3").textContent.toLowerCase();
            const author = card.querySelector(".room-author").textContent.toLowerCase();
            const show = query === "" || title.includes(query) || author.includes(query);
            card.style.display = show ? "flex" : "none";
            if (show) found++;
        });
        document.getElementById("no-result").style.display = found === 0 && query !== "" ? "flex" : "none";
        document.getElementById("search-query").textContent = query;
    });

    document.querySelectorAll(".nav-tab").forEach(tab => {
        tab.addEventListener("click", function (e) {
            e.preventDefault();
            document.querySelectorAll(".nav-tab").forEach(t => t.classList.remove("active"));
            this.classList.add("active");
            document.getElementById("live-section").style.display = "none";
            document.getElementById("scheduled-section").style.display = "none";
            document.getElementById("myrooms-section").style.display = "none";

            if (this.id === "tab_live") document.getElementById("live-section").style.display = "block";
            if (this.id === "tab_scheduled") document.getElementById("scheduled-section").style.display = "block";
            if (this.id === "tab_myrooms") document.getElementById("myrooms-section").style.display = "flex";
            if (this.id === "tab_discover") {
                document.getElementById("live-section").style.display = "block";
                document.getElementById("scheduled-section").style.display = "block";
            }
        });
    });
