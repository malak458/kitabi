let currentRoom = {
    title: '',
    author: '',
    button: null,
    card: null
};


document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.join-btn');

    buttons.forEach(function (button) {
        button.addEventListener('click', function () {
            const card = this.closest('.room-card');
            const title = card.querySelector('h3').textContent;
            const author = card.querySelector('.room-author').textContent;
            openJoinPopup(this, title, author);
        });
    });
});

function openJoinPopup(button, title, author) {
    currentRoom = {
        title: title,
        author: author,
        button: button,
        card: button.closest('.room-card')
    };

    document.getElementById('popupTitle').textContent = `Join: ${title}`;
    document.getElementById('popupBook').textContent = `by ${author}`;
    document.getElementById('popupName').value = '';
    document.getElementById('popupEmail').value = '';

    document.getElementById('joinPopup').classList.add('open');
}

function closePopup() {
    document.getElementById('popupName').classList.remove('error');
    document.getElementById('popupEmail').classList.remove('error');
    document.getElementById('errorName').style.display = 'none';
    document.getElementById('errorEmail').style.display = 'none';

    document.getElementById('joinPopup').classList.remove('open');
}


function confirmJoin() {
    const nameInput = document.getElementById('popupName');
    const emailInput = document.getElementById('popupEmail');
    const errorName = document.getElementById('errorName');
    const errorEmail = document.getElementById('errorEmail');

    const name = nameInput.value.trim();
    const email = emailInput.value.trim();

    nameInput.classList.remove('error');
    emailInput.classList.remove('error');
    errorName.style.display = 'none';
    errorEmail.style.display = 'none';

    let valide = true;

    if (!name) {
        nameInput.classList.add('error');
        errorName.style.display = 'block';
        valide = false;
    }

    if (!email || !email.includes('@')) {
        emailInput.classList.add('error');
        errorEmail.style.display = 'block';
        valide = false;
    }

    if (!valide) return;

    closePopup();
    afficherSucces(`Welcome ${name}! You joined "${currentRoom.title}"`);
}


function afficherSucces(message) {
    const box = document.getElementById('successMessage');
    const text = document.getElementById('successText');

    text.textContent = message;
    box.style.display = 'block';
    box.style.animation = 'none';

    void box.offsetWidth;
    box.style.animation = 'slideDown 0.4s ease';

    setTimeout(function () {
        box.style.animation = 'fadeOut 0.5s ease forwards';
        setTimeout(function () {
            box.style.display = 'none';
        }, 500);
    }, 3000);
}





