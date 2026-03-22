function esc(text) {
    return String(text)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}
function bytesLength(value) {
    return new TextEncoder().encode(String(value)).length;
}
function applyTheme() {
    const theme = CookieManager.get('theme');
    document.body.classList.remove('light', 'dark');
    document.body.classList.add(theme === 'dark' ? 'dark' : 'light');
}
function renderCookies() {
    document.getElementById('rawCookies').textContent = document.cookie || '(gol)';
    const data = CookieManager.getAll();
    const tbody = document.querySelector('#cookiesTable tbody');
    tbody.innerHTML = '';
    const entries = Object.entries(data);
    if (!entries.length) {
        tbody.innerHTML = '<tr><td colspan="4">Nu există cookies</td></tr>';
        return;
    }
    entries.forEach(([name, value]) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${esc(name)}</td>
            <td>${esc(value)}</td>
            <td>${value.length}</td>
            <td><button class="btn-danger" data-cookie="${esc(name)}">Șterge</button></td>
        `;
        tbody.appendChild(row);
    });
    tbody.querySelectorAll('button[data-cookie]').forEach(btn => {
        btn.addEventListener('click', () => {
            const cookieName = btn.getAttribute('data-cookie');
            CookieManager.delete(cookieName);
            refreshAll();
        });
    });
}

function renderLocal() {
    const data = StorageManager.getAllLocal();
    document.getElementById('localJson').textContent = JSON.stringify(data, null, 2) || '{}';
    const tbody = document.querySelector('#localTable tbody');
    tbody.innerHTML = '';
    const entries = Object.entries(data);
    if (!entries.length) {
        tbody.innerHTML = '<tr><td colspan="4">localStorage este gol</td></tr>';
        return;
    }
    entries.forEach(([key, value]) => {
        const printable = typeof value === 'object' ? JSON.stringify(value) : String(value);
        const size = bytesLength(printable);
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${esc(key)}</td>
            <td>${esc(printable)}</td>
            <td>${size} B</td>
            <td><button class="btn-danger" data-local="${esc(key)}">Șterge</button></td>
        `;
        tbody.appendChild(row);
    });
    tbody.querySelectorAll('button[data-local]').forEach(btn => {
        btn.addEventListener('click', () => {
            const key = btn.getAttribute('data-local');
            StorageManager.removeLocal(key);
            refreshAll();
        });
    });
}

function renderSession() {
    const data = StorageManager.getAllSession();
    document.getElementById('sessionJson').textContent = JSON.stringify(data, null, 2) || '{}';
    const tbody = document.querySelector('#sessionTable tbody');
    tbody.innerHTML = '';
    const entries = Object.entries(data);
    if (!entries.length) {
        tbody.innerHTML = '<tr><td colspan="4">sessionStorage este gol</td></tr>';
        return;
    }
    entries.forEach(([key, value]) => {
        const printable = typeof value === 'object' ? JSON.stringify(value) : String(value);
        const size = bytesLength(printable);
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${esc(key)}</td>
            <td>${esc(printable)}</td>
            <td>${size} B</td>
            <td><button class="btn-danger" data-session="${esc(key)}">Șterge</button></td>
        `;
        tbody.appendChild(row);
    });
    tbody.querySelectorAll('button[data-session]').forEach(btn => {
        btn.addEventListener('click', () => {
            const key = btn.getAttribute('data-session');
            StorageManager.removeSession(key);
            refreshAll();
        });
    });
}
function refreshAll() {
    applyTheme();
    renderCookies();
    renderLocal();
    renderSession();
}
refreshAll();