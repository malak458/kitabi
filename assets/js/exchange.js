document.addEventListener('DOMContentLoaded', function () {

    // ===== 1. GESTION DES ONGLETS / FILTRES =====
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
        // On cache toutes les sections d'abord
        allSections.forEach(sec => { 
            if (sec) sec.style.setProperty('display', 'none', 'important'); 
        });

        // On affiche les sections selon le bouton coché
        if (activeBtn && activeBtn.checked) {
            [exchangeAccepted, pendingExchanges, inProgressExchanges].forEach(sec => {
                if (sec) sec.style.setProperty('display', 'flex', 'important'); 
            });
        } else if (completedBtn && completedBtn.checked) {
            if (completedExchanges) completedExchanges.style.setProperty('display', 'flex', 'important');
        } else if (allBtn && allBtn.checked) {
            allSections.forEach(sec => { 
                if (sec) sec.style.setProperty('display', 'flex', 'important'); 
            });
        }
    }

    // Écouter les clics sur les boutons filtres
    [activeBtn, completedBtn, allBtn].forEach(btn => {
        if (btn) btn.addEventListener('change', updateVisibility);
    });
    
    // Activer les filtres au chargement de la page
    updateVisibility();


    // ===== 2. GESTION DES BOUTONS ACCEPTER / REFUSER =====
    document.addEventListener('click', function (e) {
        
        // Si on clique sur "Accept"
        if (e.target.classList.contains('accept-btn')) {
            e.preventDefault();
            const container = e.target.closest('.pending-card');
            if (!container) return;
            const exchangeId = container.dataset.exchangeId;

            if (confirm('Are you sure you want to accept this exchange request?')) {
                sendStatusToSymfony(exchangeId, 'accepted', e.target);
            }
        }

        // Si on clique sur "Decline"
        if (e.target.classList.contains('decline-btn')) {
            e.preventDefault();
            const container = e.target.closest('.pending-card');
            if (!container) return;
            const exchangeId = container.dataset.exchangeId;

            if (confirm('Are you sure you want to decline this exchange request?')) {
                sendStatusToSymfony(exchangeId, 'refused', e.target);
            }
        }
    });

    function sendStatusToSymfony(exchangeId, status, buttonClicked) {
        // On récupère la route Symfony qu'on a cachée dans le HTML à l'étape 2
        const mainContainer = document.querySelector('.exchanges');
        const url = mainContainer ? mainContainer.dataset.updateUrl : null;

        if (!url) {
            alert("Erreur : L'adresse de mise à jour est introuvable.");
            return;
        }

        // On bloque le bouton pendant le chargement
        buttonClicked.classList.add('loading-state');
        const originalText = buttonClicked.textContent;
        buttonClicked.textContent = 'Loading...';

        // Envoi de la demande à Symfony
        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ exchangeId: exchangeId, status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(status === 'accepted' ? 'Exchange accepted!' : 'Exchange declined.');
                location.reload(); // Recharge la page pour voir les changements
            } else {
                alert(data.error || 'Operation failed.');
                buttonClicked.classList.remove('loading-state');
                buttonClicked.textContent = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error. Please try again.');
            buttonClicked.classList.remove('loading-state');
            buttonClicked.textContent = originalText;
        });
    }
});