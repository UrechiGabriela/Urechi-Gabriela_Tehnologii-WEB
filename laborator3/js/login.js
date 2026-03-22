const USERS_KEY = 'users';
const REMEMBERED_USER_KEY = 'rememberedUsername';
const SESSION_KEY = 'session';

const defaultUsers = [
    { id: 1, username: 'admin', password: 'password', email: 'admin@example.com' },
    { id: 2, username: 'student', password: 'student123', email: 'student@example.com' }
];

function generateSessionId() {
    if (window.crypto && crypto.randomUUID) return crypto.randomUUID();
    return 'sess-' + Math.random().toString(36).slice(2) + Date.now();
}

function showMessage(type, text) {
    const box = document.getElementById('messageBox');
    const cls = type === 'error' ? 'alert-error' : 'alert-success';
    box.innerHTML = `<div class="${cls}">${text}</div>`;
}
function ensureDefaultUsers() {
    const users = StorageManager.getLocal(USERS_KEY);
    if (!Array.isArray(users) || users.length === 0) {
        StorageManager.setLocal(USERS_KEY, defaultUsers);
    }
}

function preloadRememberedUsername() {
    const remembered = StorageManager.getLocal(REMEMBERED_USER_KEY);
    if (remembered) {
        document.getElementById('username').value = remembered;
        document.getElementById('rememberMe').checked = true;
    }
}
document.getElementById('loginForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    const rememberMe = document.getElementById('rememberMe').checked;
    const users = StorageManager.getLocal(USERS_KEY) || [];
    const user = users.find(u => u.username === username && u.password === password);
    if (!user) {
        showMessage('error', 'Username sau parola incorecta');
        return;
    }
    if (rememberMe) {
        StorageManager.setLocal(REMEMBERED_USER_KEY, username);
    } else {
        StorageManager.removeLocal(REMEMBERED_USER_KEY);
    }
    const sessionData = {
    userId: user.id,
    username: user.username,
    email: user.email,
    loginTime: new Date().toISOString(),
    sessionId: generateSessionId()
    };

StorageManager.setSession(SESSION_KEY, sessionData);
showMessage('success', 'Autentificare reușită. Redirecționare...');
setTimeout(() => {
    window.location.href = 'dashboard.html';
}, 800);
});
ensureDefaultUsers();
preloadRememberedUsername();