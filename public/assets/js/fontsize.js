document.addEventListener("DOMContentLoaded", function () {

  let taille = 15;
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
  }

  const style = document.createElement("style");
  style.textContent = `
    #fontsize-wrapper {
      display: flex;
      align-items: center;
      gap: 8px;
      margin: 10px 0 16px;
    }

    #btn-aplus,
    #btn-amoins {
      padding: 8px 18px;
      border-radius: 20px;
      border: 1px solid var(--border);
      background: var(--white);
      color: var(--amber);
      font-family: 'DM Sans', sans-serif;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.2s ease;
      box-shadow: 0 2px 8px rgba(26,18,8,.08);
    }

    #btn-aplus  { font-size: 16px; }
    #btn-amoins { font-size: 13px; }

    #btn-aplus:hover,
    #btn-amoins:hover {
      background: var(--amber);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(200,134,10,.25);
    }

    #btn-aplus:disabled,
    #btn-amoins:disabled {
      opacity: 0.35;
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
    }
  `;
  document.head.appendChild(style);

  function appliquerTaille() {
    const elements = document.querySelectorAll(`
      .room-content h3,
      .room-author,
      .progress-text,
      .host-name,
      .tag,
      .room-action-btn
    `);

    elements.forEach(function (el) {
      el.style.fontSize = taille + "px";
    });

    btnPlus.disabled = taille >= tailleMax;
    btnMoins.disabled = taille <= tailleMin;
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
});