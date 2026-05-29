document.addEventListener('DOMContentLoaded', function () {

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
        allSections.forEach(sec => { 
            if (sec) sec.style.setProperty('display', 'none', 'important'); 
        });
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
    [activeBtn, completedBtn, allBtn].forEach(btn => {
        if (btn) btn.addEventListener('change', updateVisibility);
    });
    
    updateVisibility();
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('accept-btn')) {
            e.preventDefault();
            const container = e.target.closest('.pending-card');
            if (!container) return;
            const exchangeId = container.dataset.exchangeId;

            if (confirm('Are you sure you want to accept this exchange request?')) {
                sendStatusToSymfony(exchangeId, 'accepted', e.target);
            }
        }
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
        const mainContainer = document.querySelector('.exchanges');
        const url = mainContainer ? mainContainer.dataset.updateUrl : null;

        if (!url) {
            alert("Erreur : L'adresse de mise à jour est introuvable.");
            return;
        }
        buttonClicked.classList.add('loading-state');
        const originalText = buttonClicked.textContent;
        buttonClicked.textContent = 'Loading...';
        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ exchangeId: exchangeId, status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(status === 'accepted' ? 'Exchange accepted!' : 'Exchange declined.');
                location.reload(); 
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

const searchInput = document.getElementById('searchInput');

if (searchInput) {
    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase().trim();
        
        const exchangeCards = document.querySelectorAll('.empl.position-relative, .empl.mb-4');
        
        let hasVisibleCards = false;
        
        exchangeCards.forEach(card => {
            const cardText = card.innerText.toLowerCase();
            
            if (searchTerm === '') {
                card.style.display = '';
                card.classList.remove('search-highlight');
            } else if (cardText.includes(searchTerm)) {
                card.style.display = '';
                card.classList.add('search-highlight');
                hasVisibleCards = true;
                setTimeout(() => card.classList.remove('search-highlight'), 2000);
            } else {
                card.style.display = 'none';
            }
        });
        
        const sections = ['exchange-Accepted', 'pending-Exchanges', 'completed-Exchanges', 'refused-Exchanges', 'in-progress-Exchanges'];
        sections.forEach(sectionId => {
            const section = document.getElementById(sectionId);
            if (section) {
                const visibleCards = section.querySelectorAll('.empl.position-relative:not([style*="display: none"]), .empl.mb-4:not([style*="display: none"])');
                let emptyMsg = section.querySelector('.search-empty-msg');
                
                if (visibleCards.length === 0 && searchTerm !== '') {
                    if (!emptyMsg) {
                        emptyMsg = document.createElement('div');
                        emptyMsg.className = 'search-empty-msg';
                        emptyMsg.innerHTML = '<i class="bi bi-search fs-1"></i><p class="mt-2">Aucun résultat trouvé pour "<strong>' + searchTerm + '</strong>"</p>';
                        section.appendChild(emptyMsg);
                    }
                    emptyMsg.style.display = 'block';
                } else if (emptyMsg) {
                    emptyMsg.style.display = 'none';
                }
            }
        });
    });
}

});