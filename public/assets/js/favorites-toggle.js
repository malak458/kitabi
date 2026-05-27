document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.heart-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const url = this.dataset.url;
            console.log('URL appelée:', url);
            if (!url) return;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => {
                console.log('Status:', res.status);
                return res.json();
            })
            .then(data => {
                console.log('Réponse:', data);
                if (data.success) {
                    window.location.reload();
                }
            })
            .catch(err => console.error('Erreur fetch:', err));
        });
    });
});