document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.heart-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const url = this.dataset.url;
            const loginUrl = this.dataset.login;
            const isPageFavoris = this.dataset.pageFavoris === 'true';

            if (loginUrl) {
                window.location.href = loginUrl;
                return;
            }

            if (!url) return;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (isPageFavoris) {
                        window.location.reload(); // refresh si page favoris
                    } else {
                        if (data.action === 'added') {
                            this.style.color = 'red';
                            this.textContent = '♥';
                        } else {
                            this.style.color = 'gray';
                            this.textContent = '♡';
                        }
                    }
                }
            })
            .catch(err => console.error('Erreur fetch:', err));
        });
    });
});