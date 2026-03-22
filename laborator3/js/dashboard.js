const SESSION_KEY = 'session';
let timerId = null;

function formatDuration(ms) {
    const totalSeconds = Math.max(0, Math.floor(ms / 1000));
    const h = Math.floor(totalSeconds / 3600);
    const m = Math.floor((totalSeconds % 3600) / 60);
    const s = totalSeconds % 60;
    return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
}

function getAllSessionRaw() {
    const data = {};
    for (let i = 0; i < sessionStorage.length; i++) {
        const key = sessionStorage.key(i);
        try {
            data[key] = JSON.parse(sessionStorage.getItem(key));
        } catch {
            data[key] = sessionStorage.getItem(key);
        }
    }
    return data;
}
function applyTheme() {
    const theme = CookieManager.get('theme');
    document.body.classList.remove('light', 'dark');
    document.body.classList.add(theme === 'dark' ? 'dark' : 'light');
}
function renderRawSession() {
    document.getElementById('rawSession').textContent =
        JSON.stringify(getAllSessionRaw(), null, 2) || '{}';
}

function renderSessionInfo(sessionData) {
    const loginDate = new Date(sessionData.loginTime);
    const validLoginDate = !Number.isNaN(loginDate.getTime());
    const box = document.getElementById('sessionInfo');
    box.innerHTML = `
        <p><strong>ID Sesiune:</strong> ${sessionData.sessionId || '-'}</p>
        <p><strong>Username:</strong> ${sessionData.username || '-'}</p>
        <p><strong>Email:</strong> ${sessionData.email || '-'}</p>
        <p><strong>Ora autentificării:</strong> ${validLoginDate ? loginDate.toLocaleString('ro-RO') : '-'}</p>
        <p><strong>Durata sesiunii:</strong> <span id="sessionDuration">00:00:00</span></p>
    `;
    const durationEl = document.getElementById('sessionDuration');
    const start = validLoginDate ? loginDate.getTime() : Date.now();
    const tick = () => {
        durationEl.textContent = formatDuration(Date.now() - start);
    };
    tick();
    timerId = setInterval(tick, 1000);
}

function initDashboard() {
    const sessionData = StorageManager.getSession(SESSION_KEY);
    if (!sessionData || !sessionData.sessionId) {
        window.location.href = 'login.html';
        return;
    }
    renderSessionInfo(sessionData);
    renderRawSession();
}

document.getElementById('logoutBtn').addEventListener('click', function () {
    if (timerId) clearInterval(timerId);
    StorageManager.removeSession(SESSION_KEY);
    window.location.href = 'login.html';
});

initDashboard();
applyTheme();
