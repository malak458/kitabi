

let currentRsvpBtn = null;
 
let currentRsvpCard = null;



function createRsvpPopup() {
  const popup = document.createElement('div');
  popup.id = 'rsvpPopup';

  popup.innerHTML = `
    <div class="rsvp-box" id="rsvpBox">
      
      <div class="rsvp-header">
        <div class="rsvp-icon">📅</div>
        <div>
          <h3 class="rsvp-title">Réserver ma place</h3>
          <p class="rsvp-subtitle" id="rsvpBookName">Titre du livre</p>
        </div>
        <button class="rsvp-close" id="rsvpClose">✕</button>
      </div>

      <hr class="rsvp-divider">
      
      <div class="rsvp-form">

        <div class="rsvp-field">
          <label>Nom complet</label>
          <input type="text" id="rsvpName" placeholder="ex : Sarah Johnson" />
          <span class="rsvp-error" id="errName">Merci d'entrer votre nom.</span>
        </div>

        <div class="rsvp-field">
          <label>Adresse e-mail</label>
          <input type="email" id="rsvpEmail" placeholder="ex : sarah@mail.com" />
          <span class="rsvp-error" id="errEmail">Entrez un e-mail valide.</span>
        </div>

      </div>

      
      <div class="rsvp-actions">
        <button class="rsvp-btn-cancel" id="rsvpCancel">Annuler</button>
        <button class="rsvp-btn-confirm" id="rsvpConfirm">Confirmer</button>
      </div>

    </div>
  `;

  
  document.body.appendChild(popup);

  
  const style = document.createElement('style');
  style.textContent = `

    
    #rsvpPopup {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.45);
      backdrop-filter: blur(4px);
      z-index: 1000;
      align-items: center;
      justify-content: center;
    }
    #rsvpPopup.open { display: flex; }
   
    .rsvp-box {
      background: #fff;
      width: 100%;
      max-width: 400px;
      border-radius: 20px;
      padding: 28px 28px 24px;
      box-shadow: 0 24px 60px rgba(0,0,0,0.18);
      animation: rsvpSlide 0.3s ease;
      font-family: 'DM Sans', sans-serif;
    }

    @keyframes rsvpSlide {
      from { opacity:0; transform: translateY(24px) scale(0.97); }
      to   { opacity:1; transform: translateY(0)   scale(1); }
    }
   
    .rsvp-header {
      display: flex;
      align-items: flex-start;
      gap: 12px;
    }
    .rsvp-icon {
      font-size: 26px;
      line-height: 1;
      margin-top: 2px;
    }
    .rsvp-title {
      font-size: 17px;
      font-weight: 700;
      color: #1e2d27;
      margin: 0 0 3px;
      font-family: 'Playfair Display', serif;
    }
    .rsvp-subtitle {
      font-size: 13px;
      color: #888;
      margin: 0;
    }
    .rsvp-close {
      margin-left: auto;
      background: none;
      border: none;
      font-size: 17px;
      color: #aaa;
      cursor: pointer;
      padding: 4px 6px;
      border-radius: 6px;
      transition: background 0.2s, color 0.2s;
      line-height: 1;
    }
    .rsvp-close:hover { background: #f4f4f4; color: #333; }
    
    .rsvp-divider {
      border: none;
      border-top: 1.5px solid #f0f0f0;
      margin: 18px 0 20px;
    }

    
    .rsvp-form { display: flex; flex-direction: column; gap: 14px; }

    .rsvp-field { display: flex; flex-direction: column; gap: 5px; }

    .rsvp-field label {
      font-size: 12px;
      font-weight: 600;
      color: #555;
      letter-spacing: 0.5px;
      text-transform: uppercase;
    }

    .rsvp-field input {
      padding: 11px 14px;
      border: 1.5px solid #e5e5e5;
      border-radius: 10px;
      font-size: 14px;
      font-family: 'DM Sans', sans-serif;
      color: #333;
      outline: none;
      transition: border-color 0.25s, box-shadow 0.25s;
    }
    .rsvp-field input:focus {
      border-color: #3b5d50;
      box-shadow: 0 0 0 3px rgba(59,93,80,0.12);
    }
    .rsvp-field input.invalid {
      border-color: #e53e3e;
      box-shadow: 0 0 0 3px rgba(229,62,62,0.12);
      animation: rsvpShake 0.3s ease;
    }

    @keyframes rsvpShake {
      0%,100% { transform: translateX(0); }
      25%      { transform: translateX(-5px); }
      75%      { transform: translateX(5px); }
    }

    
    .rsvp-error {
      font-size: 11.5px;
      color: #e53e3e;
      display: none;
      padding-left: 2px;
    }
    .rsvp-error.show { display: block; }

    
    .rsvp-actions {
      display: flex;
      gap: 10px;
      margin-top: 22px;
    }
    .rsvp-btn-cancel {
      flex: 1;
      padding: 11px;
      background: #f5f5f5;
      border: none;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 600;
      color: #666;
      cursor: pointer;
      transition: background 0.2s;
      font-family: 'DM Sans', sans-serif;
    }
    .rsvp-btn-cancel:hover { background: #ebebeb; }

    .rsvp-btn-confirm {
      flex: 2;
      padding: 11px;
      background: #3b5d50;
      border: none;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 600;
      color: white;
      cursor: pointer;
      transition: background 0.2s, transform 0.15s;
      font-family: 'DM Sans', sans-serif;
    }
    .rsvp-btn-confirm:hover   { background: #2e4a3e; }
    .rsvp-btn-confirm:active  { transform: scale(0.97); }

    #rsvpToast {
      display: none;
      position: fixed;
      bottom: 32px;
      left: 50%;
      transform: translateX(-50%);
      background: #3b5d50;
      color: #fff;
      padding: 14px 26px;
      border-radius: 12px;
      font-size: 14px;
      font-weight: 500;
      box-shadow: 0 8px 24px rgba(0,0,0,0.18);
      z-index: 1100;
      white-space: nowrap;
      animation: toastIn 0.4s ease;
    }
    #rsvpToast.show { display: block; }

    @keyframes toastIn {
      from { opacity:0; transform: translateX(-50%) translateY(10px); }
      to   { opacity:1; transform: translateX(-50%) translateY(0); }
    }

    /* Bouton RSVP après confirmation */
    .rsvp-btn.done {
      background: #e8ede5 !important;
      color: #3b5d50 !important;
      border-color: #3b5d50 !important;
      cursor: default !important;
      pointer-events: none;
    }
  `;
  document.head.appendChild(style);

 
  const toast = document.createElement('div');
  toast.id = 'rsvpToast';
  document.body.appendChild(toast);
}




function openRsvpPopup(btn) {
  currentRsvpBtn  = btn;
  currentRsvpCard = btn.closest('.room-card');

 
  const bookTitle = currentRsvpCard.querySelector('h3').textContent;

  document.getElementById('rsvpBookName').textContent = bookTitle;

  
  document.getElementById('rsvpName').value  = '';
  document.getElementById('rsvpEmail').value = '';
  clearErrors();

  
  document.getElementById('rsvpPopup').classList.add('open');
}



function closeRsvpPopup() {
  document.getElementById('rsvpPopup').classList.remove('open');
  currentRsvpBtn  = null;
  currentRsvpCard = null;
}



function clearErrors() {
  ['rsvpName','rsvpEmail'].forEach(id => {
    document.getElementById(id).classList.remove('invalid');
  });
  ['errName','errEmail'].forEach(id => {
    document.getElementById(id).classList.remove('show');
  });
}

function validateRsvp() {
  clearErrors();
  let ok = true;

  const name  = document.getElementById('rsvpName').value.trim();
  const email = document.getElementById('rsvpEmail').value.trim();
 

  if (!name) {
    document.getElementById('rsvpName').classList.add('invalid');
    document.getElementById('errName').classList.add('show');
    ok = false;
  }

  if (!email.includes("@") || !email.includes(".")) {
    document.getElementById('rsvpEmail').classList.add('invalid');
    document.getElementById('errEmail').classList.add('show');
    ok = false;
  }

  return ok;
}



function confirmRsvp() {
  if (!validateRsvp()) return;  

  const name = document.getElementById('rsvpName').value.trim();


  currentRsvpBtn.textContent    = '✓ Réservé';
  currentRsvpBtn.classList.add('done');
  
  closeRsvpPopup();

  
  showRsvpToast(`Réservation confirmée, ${name} ! À bientôt. `);
}






function showRsvpToast(message) {
  const toast = document.getElementById('rsvpToast');
  toast.textContent = message;
  toast.classList.add('show');

  setTimeout(() => {
    toast.classList.remove('show');
  }, 3500);
}



function attachRsvpButtons() {
  document.querySelectorAll('.rsvp-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      openRsvpPopup(this);
    });
  });
}




document.addEventListener('DOMContentLoaded', function () {
  
  createRsvpPopup();
  
  attachRsvpButtons();

  
  document.getElementById('rsvpConfirm').addEventListener('click', confirmRsvp);
  document.getElementById('rsvpCancel').addEventListener('click', closeRsvpPopup);
  document.getElementById('rsvpClose').addEventListener('click', closeRsvpPopup);

 
  document.getElementById('rsvpPopup').addEventListener('click', function (e) {
    if (e.target === this) closeRsvpPopup();
  });

});