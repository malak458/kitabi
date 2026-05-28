document.addEventListener('DOMContentLoaded', function() {
    const exchangeModal = document.getElementById('exchangeModal');
    const exchangeForm = document.getElementById('exchangeForm');

    // On écoute le clic sur n'importe quel bouton qui ouvre la modale
    document.querySelectorAll('.open-exchange-modal').forEach(button => {
        button.addEventListener('click', function() {
            // 1. On récupère l'ID du livre ciblé
            const requestedBookId = this.getAttribute('data-book-id');
            
            // 2. On change dynamiquement l'action du formulaire avec le bon ID
            exchangeForm.action = `/exchange/create/${requestedBookId}`;
        });
    });
});
document.addEventListener('DOMContentLoaded', function() {
    const exchangeModal = document.getElementById('exchangeModal');
    const exchangeForm = document.getElementById('exchangeForm');
    
    const existingBookBlock = document.getElementById('existingBookBlock');
    const newBookBlock = document.getElementById('newBookBlock');
    const offeredBookSelect = document.getElementById('offeredBookSelect');
    const newBookTitle = document.getElementById('newBookTitle');
    const newBookAuthor = document.getElementById('newBookAuthor');
    const bookMode = document.getElementById('bookMode');

    // 1. CAPTURER L'OUVERTURE DE LA MODALE (Déclenché par Bootstrap)
    exchangeModal.addEventListener('show.bs.modal', function(event) {
        // Le bouton HTML qui a déclenché l'ouverture de la modale
        const button = event.relatedTarget;
        
        // On récupère l'ID du livre demandé depuis l'attribut du bouton
        const requestedBookId = button.getAttribute('data-book-id');
        
        // On met à jour l'action du formulaire dynamiquement
        exchangeForm.action = `/exchange/create/${requestedBookId}`;

        // FORCE LA RÉINITIALISATION (On revient toujours au mode "Livre existant" à l'ouverture)
        existingBookBlock.classList.remove('d-none');
        newBookBlock.classList.add('d-none');
        bookMode.value = 'existing';
        
        // Reset des champs textes et sélecteurs
        offeredBookSelect.value = "";
        newBookTitle.value = "";
        newBookAuthor.value = "";
        
        // Gestion des attributs obligatoires
        newBookTitle.removeAttribute('required');
        offeredBookSelect.setAttribute('required', 'required');
    });

    // 2. GESTION DES BOUTONS DE BASCULE (À l'intérieur de la modale)
    document.getElementById('btnShowNewBook').addEventListener('click', function() {
        existingBookBlock.classList.add('d-none');
        newBookBlock.classList.remove('d-none');
        
        bookMode.value = 'new';
        offeredBookSelect.removeAttribute('required');
        newBookTitle.setAttribute('required', 'required');
    });

    document.getElementById('btnShowExistingBook').addEventListener('click', function() {
        newBookBlock.classList.add('d-none');
        existingBookBlock.classList.remove('d-none');
        
        bookMode.value = 'existing';
        newBookTitle.removeAttribute('required');
        offeredBookSelect.setAttribute('required', 'required');
    });
});