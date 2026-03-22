const USERS_KEY = 'users';

function showMessage(type, text) {
    const cls = type === 'error' ? 'alert-error' : 'alert-success';
    document.getElementById('messageBox').innerHTML = `<div class="${cls}">${text}</div>`;
}

function getUsers() {
    const users = StorageManager.getLocal(USERS_KEY);
    return Array.isArray(users) ? users : [];
}

function isEmailValid(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

document.getElementById('registerForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const username = document.getElementById('username').value.trim();
    const email = document.getElementById('email').value.trim().toLowerCase();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    if (!username || !email || !password || !confirmPassword) {
        showMessage('error', 'Toate câmpurile sunt obligatorii');
        return;
    }
    if (username.length < 3) {
        showMessage('error', 'Username-ul trebuie să aibă minim 3 caractere');
        return;
    }
    if (!isEmailValid(email)) {
        showMessage('error', 'Format email invalid');
        return;
    }

    if (password.length < 6) {
        showMessage('error', 'Parola trebuie să aibă minim 6 caractere');
        return;
    }
    if (password !== confirmPassword) {
        showMessage('error', 'Parolele nu coincid');
        return;
    }
    const users = getUsers();
    const usernameExists = users.some(u => (u.username || '').toLowerCase() === username.toLowerCase());
    if (usernameExists) {
        showMessage('error', 'Username deja existent');
        return;
    }
    const emailExists = users.some(u => (u.email || '').toLowerCase() === email);
    if (emailExists) {
        showMessage('error', 'Email deja existent');
        return;
    }
    const nextId = users.length ? Math.max(...users.map(u => Number(u.id) || 0)) + 1 : 1;
    const newUser = { id: nextId, username, email, password };
    users.push(newUser);
    const ok = StorageManager.setLocal(USERS_KEY, users);
    if (!ok) {
        showMessage('error', 'Eroare la salvarea utilizatorului');
        return;
    }
    showMessage('success', 'Cont creat cu succes. Redirecționare către login...');
    setTimeout(() => {
        window.location.href = 'login.html';
    }, 1500);
});