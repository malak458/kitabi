document.addEventListener("DOMContentLoaded", function () {

  const sons = [
    {
      emoji: "🌧️",
      nom: "Pluie",
      url: "https://cdn.pixabay.com/audio/2025/01/30/audio_699b9e2768.mp3",
    },
    {
      emoji: "☕",
      nom: "Café",
      url: "https://cdn.pixabay.com/audio/2024/09/04/audio_68f32f8bbe.mp3",
    },
    {
      emoji: "🎵",
      nom: "Musique",
      url: "https://cdn.pixabay.com/audio/2025/04/17/audio_6d677e0fb3.mp3",
    },
  ];

  const wrapper = document.createElement("div");
  wrapper.id = "ambiance-wrapper";

  const stats = document.querySelector(".stats-container");
  if (stats) {
    stats.insertAdjacentElement("afterend", wrapper);
  }

  const style = document.createElement("style");
  style.textContent = `

    #ambiance-wrapper {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      gap: 10px;
      margin-bottom: 10px;
    }

    #ambiance-toggle {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 12px 24px;
      border-radius: 15px;
      border: 1px solid var(--border);
      background: var(--white);
      color: var(--amber);
      font-family: 'DM Sans', sans-serif;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(26,18,8,.08);
      transition: all 0.3s ease;
    }

    #ambiance-toggle:hover {
      background: var(--amber);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(200,134,10,.22);
    }

    #ambiance-toggle.actif {
      background: var(--amber);
      color: white;
      border-color: var(--amber);
    }

    #ambiance-menu {
      display: none;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
      justify-content: flex-end;
    }

    #ambiance-menu.ouvert {
      display: flex;
    }

    .son-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 10px 18px;
      border-radius: 50px;
      border: 1px solid var(--border);
      background: var(--white);
      color: var(--ink);
      font-family: 'DM Sans', sans-serif;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 2px 8px rgba(26,18,8,.06);
    }

    .son-btn:hover {
      background: var(--ink);
      color: white;
      transform: translateY(-2px);
    }

    .son-btn.actif {
      background: var(--ink);
      color: white;
      border-color: var(--amber);
    }

    #volume-bar {
      display: none;
      align-items: center;
      gap: 8px;
      padding: 8px 16px;
      background: var(--white);
      border-radius: 50px;
      border: 1px solid var(--border);
      box-shadow: 0 2px 8px rgba(26,18,8,.08);
    }

    #volume-bar.visible {
      display: flex;
    }

    #volume-bar input {
      width: 90px;
      accent-color: var(--amber);
      cursor: pointer;
    }

    #volume-bar span {
      font-size: 12px;
      color: var(--amber);
      font-weight: 600;
      font-family: 'DM Sans', sans-serif;
      min-width: 32px;
    }
  `;
  document.head.appendChild(style);

  const toggle = document.createElement("button");
  toggle.id = "ambiance-toggle";
  toggle.innerHTML = `🎵 Ambiance`;
  wrapper.appendChild(toggle);

  const menu = document.createElement("div");
  menu.id = "ambiance-menu";
  wrapper.appendChild(menu);

  const volumeBar = document.createElement("div");
  volumeBar.id = "volume-bar";
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

    const btn = document.createElement("button");
    btn.className = "son-btn";
    btn.innerHTML = `${son.emoji} ${son.nom} ▶`;

    btn.addEventListener("click", function () {

      if (boutonActif === btn) {
        audio.pause();
        audio.currentTime = 0;
        btn.innerHTML = `${son.emoji} ${son.nom} ▶`;
        btn.classList.remove("actif");
        volumeBar.classList.remove("visible");
        audioActif = null;
        boutonActif = null;
        return;
      }

      if (audioActif) {
        audioActif.pause();
        audioActif.currentTime = 0;
        boutonActif.innerHTML = boutonActif.innerHTML.replace("⏸", "▶");
        boutonActif.classList.remove("actif");
      }

      audio.play();
      btn.innerHTML = `${son.emoji} ${son.nom} ⏸`;
      btn.classList.add("actif");
      volumeBar.classList.add("visible");
      audioActif = audio;
      boutonActif = btn;
    });

    menu.appendChild(btn);
  });

  const slider = document.getElementById("volume-slider");
  const label = document.getElementById("volume-label");

  slider.addEventListener("input", function () {
    if (audioActif) audioActif.volume = slider.value;
    label.textContent = Math.round(slider.value * 100) + "%";
  });

  let menuOuvert = false;

  toggle.addEventListener("click", function () {
    if (menuOuvert) {
      menu.classList.remove("ouvert");
      toggle.innerHTML = `🎵 Ambiance`;
      toggle.classList.remove("actif");
      menuOuvert = false;
    } else {
      menu.classList.add("ouvert");
      toggle.innerHTML = `✕ Fermer`;
      toggle.classList.add("actif");
      menuOuvert = true;
    }
  });

});