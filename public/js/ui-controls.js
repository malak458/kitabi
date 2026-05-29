
document.addEventListener("DOMContentLoaded", function () {

    
    function initAmbiance() {
        if (document.getElementById("ambiance-wrapper")) return;

        const sons = [
            { emoji: "🌧️", nom: "Pluie", url: "https://cdn.pixabay.com/audio/2025/01/30/audio_699b9e2768.mp3" },
            { emoji: "☕", nom: "Café", url: "https://cdn.pixabay.com/audio/2024/09/04/audio_68f32f8bbe.mp3" },
            { emoji: "🎵", nom: "Musique", url: "https://cdn.pixabay.com/audio/2025/04/17/audio_6d677e0fb3.mp3" },
            { emoji: "🔥", nom: "Cheminée", url: "https://cdn.pixabay.com/audio/2024/05/15/audio_1234567890.mp3" },
            { emoji: "📖", nom: "Bibliothèque", url: "https://cdn.pixabay.com/audio/2024/03/10/audio_0987654321.mp3" }
        ];

        const wrapper = document.createElement("div");
        wrapper.id = "ambiance-wrapper";

        
        const stats = document.querySelector(".stats-container");
        if (stats) {
            stats.insertAdjacentElement("afterend", wrapper);
        }

        
        wrapper.style.cssText = `
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
            margin-bottom: 10px;
        `;

        
        const toggle = document.createElement("button");
        toggle.id = "ambiance-toggle";
        toggle.innerHTML = `🎵 Ambiance`;
        toggle.style.cssText = `
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 15px;
            border: 1px solid #e2d9c8;
            background: #fffdf8;
            color: #c8860a;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(26,18,8,.08);
            transition: all 0.3s ease;
        `;
        wrapper.appendChild(toggle);

        
        const menu = document.createElement("div");
        menu.id = "ambiance-menu";
        menu.style.cssText = `
            display: none;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        `;
        wrapper.appendChild(menu);

       
        const volumeBar = document.createElement("div");
        volumeBar.id = "volume-bar";
        volumeBar.style.cssText = `
            display: none;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: #fffdf8;
            border-radius: 50px;
            border: 1px solid #e2d9c8;
            box-shadow: 0 2px 8px rgba(26,18,8,.08);
        `;
        volumeBar.innerHTML = `
            🔊
            <input type="range" id="volume-slider" min="0" max="1" step="0.1" value="0.5">
            <span id="volume-label">50%</span>
        `;
        wrapper.appendChild(volumeBar);

        let audioActif = null;
        let boutonActif = null;

       
        sons.forEach(function (son) {
            const audio = new Audio(son.url);
            audio.loop = true;
            audio.volume = 0.5;
            audio.preload = 'auto';

            const btn = document.createElement("button");
            btn.className = "son-btn";
            btn.innerHTML = `${son.emoji} ${son.nom} ▶`;
            btn.style.cssText = `
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 10px 18px;
                border-radius: 50px;
                border: 1px solid #e2d9c8;
                background: #fffdf8;
                color: #1a1208;
                font-family: 'DM Sans', sans-serif;
                font-size: 13px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 2px 8px rgba(26,18,8,.06);
            `;

            btn.addEventListener("mouseenter", function () {
                if (!btn.classList.contains("actif")) {
                    btn.style.background = "#1a1208";
                    btn.style.color = "white";
                }
            });
            btn.addEventListener("mouseleave", function () {
                if (!btn.classList.contains("actif")) {
                    btn.style.background = "#fffdf8";
                    btn.style.color = "#1a1208";
                }
            });

            btn.addEventListener("click", function () {
                if (boutonActif === btn) {
                    // Arrêter le son
                    audio.pause();
                    audio.currentTime = 0;
                    btn.innerHTML = `${son.emoji} ${son.nom} ▶`;
                    btn.classList.remove("actif");
                    btn.style.background = "#fffdf8";
                    btn.style.color = "#1a1208";
                    volumeBar.style.display = "none";
                    audioActif = null;
                    boutonActif = null;
                    return;
                }

                
                if (audioActif) {
                    audioActif.pause();
                    audioActif.currentTime = 0;
                    boutonActif.innerHTML = boutonActif.innerHTML.replace("⏸", "▶");
                    boutonActif.classList.remove("actif");
                    boutonActif.style.background = "#fffdf8";
                    boutonActif.style.color = "#1a1208";
                }

                
                audio.play().catch(error => {
                    console.warn("Erreur audio:", error);
                });
                btn.innerHTML = `${son.emoji} ${son.nom} ⏸`;
                btn.classList.add("actif");
                btn.style.background = "#c8860a";
                btn.style.color = "white";
                volumeBar.style.display = "flex";
                audioActif = audio;
                boutonActif = btn;
            });

            menu.appendChild(btn);
        });

        
        const slider = document.getElementById("volume-slider");
        const label = document.getElementById("volume-label");

        if (slider && label) {
            slider.addEventListener("input", function () {
                if (audioActif) audioActif.volume = slider.value;
                label.textContent = Math.round(slider.value * 100) + "%";
            });
        }

       
        let menuOuvert = false;
        toggle.addEventListener("click", function () {
            if (menuOuvert) {
                menu.style.display = "none";
                toggle.innerHTML = `🎵 Ambiance`;
                toggle.classList.remove("actif");
                toggle.style.background = "#fffdf8";
                toggle.style.color = "#c8860a";
                menuOuvert = false;
            } else {
                menu.style.display = "flex";
                toggle.innerHTML = `✕ Fermer`;
                toggle.classList.add("actif");
                toggle.style.background = "#c8860a";
                toggle.style.color = "white";
                menuOuvert = true;
            }
        });
    }

    
    function initDarkMode() {
        if (document.getElementById("mode-toggle-btn")) return;
        
        const bouton = document.createElement("button");
        bouton.id = "mode-toggle-btn";
        document.body.appendChild(bouton);

        bouton.style.cssText = `
            position: fixed;
            bottom: 28px;
            right: 28px;
            z-index: 9999;
            padding: 12px 24px;
            border-radius: 50px;
            border: 1px solid #e2d9c8;
            background: #fffdf8;
            color: #c8860a;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(26,18,8,.15);
            transition: all 0.25s ease;
        `;

        const stylesDark = `
            body, .page-body, .page-wrapper {
                background-color: #16110b !important;
                color: #f5f0e8 !important;
            }
            #mainNav, nav {
                background-color: #12100a !important;
            }
            .room-card, .stat-card {
                background-color: #241a11 !important;
                border-color: #3a2a1b !important;
                color: #f5f0e8 !important;
            }
            .room-content h3, .room-author {
                color: #f5f0e8 !important;
            }
            .tag {
                background: rgba(255,255,255,.05) !important;
                color: #f9c85b !important;
            }
            .progress-bar-bg {
                background: #3a2a1b !important;
            }
            #fontsize-wrapper button, #fullscreen-btn, #mode-toggle-btn, #ambiance-toggle, .son-btn {
                background: #241a11 !important;
                color: #f9c85b !important;
                border-color: #3a2a1b !important;
            }
            .search-area {
                background-color: #1a1208 !important;
            }
            .search-bar {
                background: rgba(255,255,255,.08) !important;
                color: #f9efd0 !important;
            }
            #ambiance-wrapper {
                background: transparent !important;
            }
            #volume-bar {
                background: #241a11 !important;
                border-color: #3a2a1b !important;
            }
        `;

        let baliseStyle = document.getElementById("dark-mode-style");
        if (!baliseStyle) {
            baliseStyle = document.createElement("style");
            baliseStyle.id = "dark-mode-style";
            document.head.appendChild(baliseStyle);
        }

        let modeEstSombre = localStorage.getItem("kitabi_theme") === "dark";

        function updateModeButton() {
            if (modeEstSombre) {
                bouton.textContent = `☀️ Light Mode`;
            } else {
                bouton.textContent = `🌙 Dark Mode`;
            }
        }

        function applyTheme() {
            if (modeEstSombre) {
                baliseStyle.textContent = stylesDark;
                bouton.style.background = "#1a1208";
                bouton.style.color = "#e8a82a";
                localStorage.setItem("kitabi_theme", "dark");
            } else {
                baliseStyle.textContent = "";
                bouton.style.background = "#fffdf8";
                bouton.style.color = "#c8860a";
                localStorage.setItem("kitabi_theme", "light");
            }
            updateModeButton();
        }

        applyTheme();

        bouton.addEventListener("click", function () {
            modeEstSombre = !modeEstSombre;
            applyTheme();
        });

        bouton.addEventListener("mouseenter", () => {
            bouton.style.background = "#c8860a";
            bouton.style.color = "white";
            bouton.style.transform = "translateY(-2px)";
        });

        bouton.addEventListener("mouseleave", () => {
            applyTheme();
            bouton.style.transform = "translateY(0)";
        });
    }

   
    function initFullscreen() {
        if (document.getElementById("fullscreen-btn")) return;
        
        const bouton = document.createElement("button");
        bouton.id = "fullscreen-btn";
        document.body.appendChild(bouton);

        function updateFullscreenButton() {
            const estPleinEcran = document.body.classList.contains("fullscreen");
            if (estPleinEcran) {
                bouton.innerHTML = `✕ Exit Full Screen`;
            } else {
                bouton.innerHTML = `⛶ Full Screen`;
            }
        }

        bouton.style.cssText = `
            position: fixed;
            bottom: 28px;
            left: 20px;
            z-index: 9999;
            padding: 12px 24px;
            border-radius: 50px;
            border: 1px solid #e2d9c8;
            background: #fffdf8;
            color: #c8860a;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(26,18,8,.10);
            transition: all 0.3s ease;
        `;

        updateFullscreenButton();

        bouton.addEventListener("click", function () {
            const estPleinEcranActuel = document.body.classList.contains("fullscreen");
            if (estPleinEcranActuel) {
                if (document.exitFullscreen) document.exitFullscreen();
                document.body.classList.remove("fullscreen");
            } else {
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen();
                }
                document.body.classList.add("fullscreen");
            }
            updateFullscreenButton();
        });

        document.addEventListener("fullscreenchange", function () {
            if (!document.fullscreenElement) {
                document.body.classList.remove("fullscreen");
                updateFullscreenButton();
            }
        });
    }
    function initFontSize() {
        if (document.getElementById("fontsize-wrapper")) return;
        
        let taille = parseInt(localStorage.getItem("kitabi_fontsize")) || 15;
        const tailleMin = 12;
        const tailleMax = 22;

        const wrapper = document.createElement("div");
        wrapper.id = "fontsize-wrapper";

        const btnPlus = document.createElement("button");
        btnPlus.id = "btn-aplus";
        btnPlus.innerHTML = "A+";

        const btnMoins = document.createElement("button");
        btnMoins.id = "btn-amoins";
        btnMoins.innerHTML = "A−";

        wrapper.appendChild(btnMoins);
        wrapper.appendChild(btnPlus);

        const navTabs = document.querySelector(".nav-tabs");
        if (navTabs) {
            navTabs.insertAdjacentElement("afterend", wrapper);
        } else {
            const stats = document.querySelector(".stats-container");
            if (stats) {
                stats.insertAdjacentElement("afterend", wrapper);
            }
        }

        wrapper.style.cssText = `
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 10px 0 16px;
            justify-content: flex-start;
        `;

        btnPlus.style.cssText = `
            padding: 8px 18px;
            border-radius: 20px;
            border: 1px solid #e2d9c8;
            background: #fffdf8;
            color: #c8860a;
            font-family: 'DM Sans', sans-serif;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 16px;
        `;

        btnMoins.style.cssText = `
            padding: 8px 18px;
            border-radius: 20px;
            border: 1px solid #e2d9c8;
            background: #fffdf8;
            color: #c8860a;
            font-family: 'DM Sans', sans-serif;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 13px;
        `;

        function appliquerTaille() {
            const elements = document.querySelectorAll(`
                .room-content h3,
                .room-author,
                .progress-text,
                .tag,
                .room-action-btn,
                .stat-title,
                .stat-number
            `);
            elements.forEach(function (el) {
                el.style.fontSize = taille + "px";
            });
            btnPlus.disabled = taille >= tailleMax;
            btnMoins.disabled = taille <= tailleMin;
            localStorage.setItem("kitabi_fontsize", taille);
        }

        btnPlus.addEventListener("click", function () {
            if (taille < tailleMax) {
                taille += 1;
                appliquerTaille();
            }
        });

        btnMoins.addEventListener("click", function () {
            if (taille > tailleMin) {
                taille -= 1;
                appliquerTaille();
            }
        });

        appliquerTaille();
    }

    
    setTimeout(() => {
        initAmbiance();
        initDarkMode();
        initFullscreen();
        initFontSize();
    }, 100);
});