document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // 1. GESTION DE LA MODALE (MARKETPLACE)
    // ==========================================
    const exchangeModal = document.getElementById('exchangeModal');
    const exchangeForm = document.getElementById('exchangeForm');
    
    if (exchangeModal && exchangeForm) {
        const existingBookBlock = document.getElementById('existingBookBlock');
        const newBookBlock = document.getElementById('newBookBlock');
        const offeredBookSelect = document.getElementById('offeredBookSelect');
        const newBookTitle = document.getElementById('newBookTitle');
        const newBookAuthor = document.getElementById('newBookAuthor');
        const bookMode = document.getElementById('bookMode');

        // Capturer l'ouverture de la modale
        exchangeModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const requestedBookId = button.getAttribute('data-book-id');
            
            // Met à jour l'action du formulaire vers ton contrôleur Symfony
            exchangeForm.action = `/exchange/create/${requestedBookId}`;

            // Réinitialisation par défaut vers l'état "Livre existant"
            existingBookBlock.classList.remove('d-none');
            newBookBlock.classList.add('d-none');
            bookMode.value = 'existing';
            
            offeredBookSelect.value = "";
            newBookTitle.value = "";
            newBookAuthor.value = "";
            
            newBookTitle.removeAttribute('required');
            offeredBookSelect.setAttribute('required', 'required');
        });

        // Gestion de la bascule vers le mode "Nouveau livre"
        document.getElementById('btnShowNewBook').addEventListener('click', function() {
            existingBookBlock.classList.add('d-none');
            newBookBlock.classList.remove('d-none');
            bookMode.value = 'new';
            offeredBookSelect.removeAttribute('required');
            newBookTitle.setAttribute('required', 'required');
        });

        // Retour vers le mode "Livre existant"
        document.getElementById('btnShowExistingBook').addEventListener('click', function() {
            newBookBlock.classList.add('d-none');
            existingBookBlock.classList.remove('d-none');
            bookMode.value = 'existing';
            newBookTitle.removeAttribute('required');
            offeredBookSelect.setAttribute('required', 'required');
        });
    }

    // ==========================================
    // 2. GESTION DES FILTRES DE VISIBILITÉ (TABLEAU DE BORD)
    // ==========================================
    const activeBtn    = document.getElementById('activeBtn');
    const completedBtn = document.getElementById('completedBtn');
    const allBtn       = document.getElementById('allBtn');

    const exchangeAccepted    = document.getElementById('exchange-Accepted');
    const pendingExchanges    = document.getElementById('pending-Exchanges');
    const completedExchanges  = document.getElementById('completed-Exchanges');
    const refusedExchanges    = document.getElementById('refused-Exchanges');
    const inProgressExchanges = document.getElementById('in-progress-Exchanges');

    const allSections = [exchangeAccepted, pendingExchanges, completedExchanges, refusedExchanges, inProgressExchanges];

    function updateVisibility() {
        allSections.forEach(sec => { if (sec) sec.style.display = 'none'; });

        if (activeBtn && activeBtn.checked) {
            [exchangeAccepted, pendingExchanges, inProgressExchanges].forEach(sec => {
                if (sec) sec.style.display = 'block';
            });
        } else if (completedBtn && completedBtn.checked) {
            if (completedExchanges) completedExchanges.style.display = 'block';
        } else if (allBtn && allBtn.checked) {
            allSections.forEach(sec => { if (sec) sec.style.display = 'block'; });
        }
    }

    [activeBtn, completedBtn, allBtn].forEach(btn => {
        if (btn) btn.addEventListener('change', updateVisibility);
    });
    updateVisibility();

    // ==========================================
    // 3. ACTIONS EN DIRECT (ACCEPT / DECLINE)
    // ==========================================
    document.addEventListener('click', function (e) {
        const isAccept = e.target.classList.contains('accept-btn');
        const isDecline = e.target.classList.contains('decline-btn');

        if (isAccept || isDecline) {
            e.preventDefault();
            const container = e.target.closest('.pending-card');
            if (!container) return;
            
            const exchangeId = container.dataset.exchangeId;
            const actionUrl = isAccept ? `/exchange/${exchangeId}/accept` : `/exchange/${exchangeId}/decline`;
            const confirmMessage = isAccept ? 'Voulez-vous accepter cette demande ?' : 'Voulez-vous refuser cette demande ?';

            if (confirm(confirmMessage)) {
                e.target.disabled = true;
                e.target.textContent = 'Chargement...';

                // Envoi direct vers les routes de ton ExchangeController
                fetch(actionUrl, { method: 'POST' })
                .then(response => {
                    if (response.ok) {
                        showNotification(isAccept ? 'Échange accepté !' : 'Échange refusé.', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification('Une erreur est survenue.', 'error');
                        e.target.disabled = false;
                        e.target.textContent = isAccept ? 'Accepter' : 'Refuser';
                    }
                })
                .catch(() => {
                    showNotification('Erreur réseau.', 'error');
                    e.target.disabled = false;
                });
            }
        }
    });
});

function showNotification(message, type) {
    const colors = { success: '#0e6b5e', error: '#dc3545', info: '#17a2b8' };
    const notification = document.createElement('div');
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 12px 24px;
        background-color: ${colors[type] || colors.info};
        color: white;
        border-radius: 8px;
        font-weight: 500;
        z-index: 10000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: opacity 0.3s ease;
    `;
    document.body.appendChild(notification);
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 2500);
}