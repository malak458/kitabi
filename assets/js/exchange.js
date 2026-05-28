document.addEventListener('DOMContentLoaded', function () {

    // ========== FILTRES ==========
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

    // ========== ACCEPT / DECLINE (délégation sur tous les boutons) ==========
    // Utilise la délégation d'événements pour gérer plusieurs pending exchanges

    document.addEventListener('click', function (e) {

        // --- ACCEPT ---
        if (e.target.classList.contains('accept-btn')) {
            e.preventDefault();
            const container = e.target.closest('.pending-card');
            if (!container) return;
            const exchangeId = container.dataset.exchangeId;

            if (confirm('Are you sure you want to accept this exchange request?')) {
                updateStatus(exchangeId, 'accepted', e.target);
            }
        }

        // --- DECLINE ---
        if (e.target.classList.contains('decline-btn')) {
            e.preventDefault();
            const container = e.target.closest('.pending-card');
            if (!container) return;
            const exchangeId = container.dataset.exchangeId;

            if (confirm('Are you sure you want to decline this exchange request?')) {
                updateStatus(exchangeId, 'refused', e.target);
            }
        }
    });

    function updateStatus(exchangeId, status, btn) {
        // Désactiver le bouton pendant la requête
        btn.disabled = true;
        btn.textContent = 'Loading...';

        fetch('update_exchange_status.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ exchangeId: exchangeId, status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(
                    status === 'accepted' ? 'Exchange accepted!' : 'Exchange declined.',
                    'success'
                );
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification(data.error || 'Operation failed.', 'error');
                btn.disabled = false;
                btn.textContent = status === 'accepted' ? 'Accept' : 'Decline';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Network error. Please try again.', 'error');
            btn.disabled = false;
            btn.textContent = status === 'accepted' ? 'Accept' : 'Decline';
        });
    }
});

// ========== NOTIFICATION ==========
function showNotification(message, type) {
    const colors = { success: '#28a745', error: '#dc3545', info: '#17a2b8' };
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
    }, 2700);
}