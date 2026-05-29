(function () {
    "use strict";

    const API_URL = "/chatbot";
    const DETAILS_URL = "/marketplace";
    const PLACEHOLDER =
        "https://via.placeholder.com/60x80/f5f0e8/8b7355?text=📚";

    let isOpen = false;
    let isTyping = false;

    function injectHTML() {
        const html = `
        <!-- Hint bubble -->
        <div id="kb-hint" class="kb-hint" aria-hidden="true">
          Besoin d'aide pour trouver un livre ?
        </div>
  
        <!-- Toggle button -->
        <button id="kb-toggle" class="kb-toggle" aria-label="Ouvrir l'assistant KITAB" aria-expanded="false">
          <span class="kb-toggle-icon kb-icon-open">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
            </svg>
          </span>
          <span class="kb-toggle-icon kb-icon-close" style="display:none">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
          </span>
          <span class="kb-notif" id="kb-notif" aria-label="1 nouveau message">1</span>
        </button>
  
        <!-- Chat panel -->
        <div id="kb-panel" class="kb-panel" role="dialog" aria-label="Assistant KITAB" aria-hidden="true">
          <!-- Header -->
          <div class="kb-header">
            <div class="kb-header-avatar" aria-hidden="true">📚</div>
            <div class="kb-header-info">
              <div class="kb-header-name">Assistant KITAB</div>
              <div class="kb-header-status">
                <span class="kb-status-dot"></span> En ligne
              </div>
            </div>
            <button class="kb-close-btn" id="kb-close" aria-label="Fermer le chat">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
              </svg>
            </button>
          </div>
  
          <!-- Messages -->
          <div id="kb-messages" class="kb-messages" role="log" aria-live="polite" aria-label="Conversation"></div>
  
          <!-- Input -->
          <div class="kb-input-row">
            <input
              type="text"
              id="kb-input"
              class="kb-input"
              placeholder="Ex : roman fantasy sous 40 DT…"
              autocomplete="off"
              maxlength="200"
              aria-label="Votre message"
            />
            <button id="kb-send" class="kb-send-btn" aria-label="Envoyer">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18">
                <line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>
              </svg>
            </button>
          </div>
        </div>
      `;
        const wrapper = document.createElement("div");
        wrapper.id = "kb-root";
        wrapper.innerHTML = html;
        document.body.appendChild(wrapper);
    }

    function toggleChat() {
        isOpen = !isOpen;
        const panel = document.getElementById("kb-panel");
        const toggle = document.getElementById("kb-toggle");
        const notif = document.getElementById("kb-notif");
        const hint = document.getElementById("kb-hint");
        const iconOpen = toggle.querySelector(".kb-icon-open");
        const iconClose = toggle.querySelector(".kb-icon-close");

        panel.classList.toggle("kb-panel--open", isOpen);
        panel.setAttribute("aria-hidden", String(!isOpen));
        toggle.setAttribute("aria-expanded", String(isOpen));

        iconOpen.style.display = isOpen ? "none" : "flex";
        iconClose.style.display = isOpen ? "flex" : "none";
        toggle.classList.toggle("kb-toggle--open", isOpen);

        if (isOpen) {
            notif.style.display = "none";
            hint.style.display = "none";
            document.getElementById("kb-input").focus();
        }
    }

    function appendUserMsg(text) {
        const msgs = document.getElementById("kb-messages");
        const row = document.createElement("div");
        row.className = "kb-msg kb-msg--user";
        row.innerHTML = `
        <div class="kb-bubble kb-bubble--user">${escHtml(text)}</div>
        <div class="kb-avatar kb-avatar--user" aria-hidden="true">Vous</div>
      `;
        msgs.appendChild(row);
        scrollDown();
    }

    function showTyping() {
        if (isTyping) return;
        isTyping = true;
        const msgs = document.getElementById("kb-messages");
        const row = document.createElement("div");
        row.className = "kb-msg";
        row.id = "kb-typing";
        row.innerHTML = `
        <div class="kb-avatar kb-avatar--bot" aria-hidden="true">📚</div>
        <div class="kb-bubble kb-bubble--bot">
          <span class="kb-typing-dot"></span>
          <span class="kb-typing-dot"></span>
          <span class="kb-typing-dot"></span>
        </div>
      `;
        msgs.appendChild(row);
        scrollDown();
    }

    function hideTyping() {
        isTyping = false;
        const el = document.getElementById("kb-typing");
        if (el) el.remove();
    }

    function appendBotResponse(data) {
        const msgs = document.getElementById("kb-messages");
        const row = document.createElement("div");
        row.className = "kb-msg";

        let inner = `
        <div class="kb-avatar kb-avatar--bot" aria-hidden="true">📚</div>
        <div class="kb-bot-content">
          <div class="kb-bubble kb-bubble--bot">${escHtml(data.message)}</div>
      `;

        if (data.books && data.books.length > 0) {
            inner += `<div class="kb-books-list">`;
            data.books.forEach((book) => {
                const img =
                    book.image && book.image.trim() !== ""
                        ? escHtml(book.image)
                        : PLACEHOLDER;
                const exBadge = book.for_exchange
                    ? `<span class="kb-badge kb-badge--exchange">Échange</span>`
                    : "";
                const condClass =
                    "kb-cond--" +
                    (book.condition || "bon").replace(/\s+/g, "-");
                inner += `
            <div class="kb-book-card" role="article" aria-label="${escHtml(
                book.titre
            )}">
              <img src="${img}" alt="${escHtml(book.titre)}" class="kb-book-img"
                   onerror="this.src='${PLACEHOLDER}'" loading="lazy" />
              <div class="kb-book-info">
                <div class="kb-book-title">${escHtml(book.titre)}</div>
                <div class="kb-book-author">${escHtml(book.auteur)}</div>
                <div class="kb-book-meta">
                  <span class="kb-book-price">${escHtml(book.prix)}</span>
                  <span class="kb-cond ${condClass}">${escHtml(
                    book.condition
                )}</span>
                  ${exBadge}
                </div>
              </div>
              <a href="${DETAILS_URL}?id=${
                    book.id
                }" class="kb-details-btn" aria-label="Voir ${escHtml(
                    book.titre
                )}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
                  <polyline points="9 18 15 12 9 6"/>
                </svg>
              </a>
            </div>
          `;
            });
            inner += `</div>`;
        }

        if (data.chips && data.chips.length > 0) {
            inner += `<div class="kb-chips">`;
            data.chips.forEach((chip) => {
                inner += `<button class="kb-chip" data-msg="${escHtml(
                    chip
                )}">${escHtml(chip)}</button>`;
            });
            inner += `</div>`;
        }

        inner += `</div>`;
        row.innerHTML = inner;

        row.querySelectorAll(".kb-chip").forEach((btn) => {
            btn.addEventListener("click", () => sendMessage(btn.dataset.msg));
        });

        msgs.appendChild(row);
        scrollDown();
    }

    function appendError() {
        const msgs = document.getElementById("kb-messages");
        const row = document.createElement("div");
        row.className = "kb-msg";
        row.innerHTML = `
        <div class="kb-avatar kb-avatar--bot" aria-hidden="true">📚</div>
        <div class="kb-bubble kb-bubble--bot kb-bubble--error">
          Une erreur est survenue. Réessayez dans un instant.
        </div>
      `;
        msgs.appendChild(row);
        scrollDown();
    }

    async function sendMessage(text) {
        const input = document.getElementById("kb-input");
        const msg = (text || input.value).trim();
        if (!msg || isTyping) return;

        input.value = "";
        appendUserMsg(msg);
        showTyping();

        try {
            const res = await fetch(API_URL, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ message: msg }),
            });
            if (!res.ok) throw new Error("HTTP " + res.status);
            const data = await res.json();
            hideTyping();
            appendBotResponse(data);
        } catch (e) {
            hideTyping();
            appendError();
            console.error("[KITAB Chatbot]", e);
        }
    }

    function sendWelcome() {
        appendBotResponse({
            type: "text",
            message:
                "Bonjour ! 👋 Je suis l'assistant KITAB.\nDites-moi ce que vous cherchez : un genre, un auteur, un budget…",
            chips: [
                "Fiction",
                "Fantasy",
                "Romance",
                "Sous 50 DT",
                "Livres à échanger",
            ],
        });
    }

    function scrollDown() {
        const msgs = document.getElementById("kb-messages");
        requestAnimationFrame(() => {
            msgs.scrollTop = msgs.scrollHeight;
        });
    }

    function escHtml(str) {
        if (!str) return "";
        return String(str)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#x27;");
    }

    function init() {
        injectHTML();

        document.getElementById("kb-toggle").addEventListener("click", () => {
            toggleChat();
            if (
                isOpen &&
                document.getElementById("kb-messages").children.length === 0
            ) {
                sendWelcome();
            }
        });
        document
            .getElementById("kb-close")
            .addEventListener("click", toggleChat);

        document
            .getElementById("kb-send")
            .addEventListener("click", () => sendMessage());
        document.getElementById("kb-input").addEventListener("keydown", (e) => {
            if (e.key === "Enter") sendMessage();
        });

        setTimeout(() => {
            const hint = document.getElementById("kb-hint");
            if (hint) hint.style.opacity = "0";
        }, 5000);
        setTimeout(() => {
            const hint = document.getElementById("kb-hint");
            if (hint) hint.style.display = "none";
        }, 5500);
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }
})();
