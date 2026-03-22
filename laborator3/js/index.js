function escapeHtml(text) {
    return String(text)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

function renderTable(tableId, dataObject) {
    const tbody = document.querySelector(`#${tableId} tbody`);
    tbody.innerHTML = '';

    const entries = Object.entries(dataObject || {});
    if (!entries.length) {
        const row = document.createElement('tr');
        row.innerHTML = `<td colspan="2" class="empty">Nu există date</td>`;
        tbody.appendChild(row);
        return;
    }

    entries.forEach(([key, value]) => {
        const row = document.createElement('tr');
        const formattedValue = typeof value === 'object'
            ? JSON.stringify(value) : String(value);

        row.innerHTML = `
            <td>${escapeHtml(key)}</td>
            <td>${escapeHtml(formattedValue)}</td>
        `;
        tbody.appendChild(row);
    });
}

function initPage() {
    const username = CookieManager.get('username') || 'Vizitator';
    document.getElementById('welcomeMessage').textContent = `Bun venit, ${username}!`;

    const currentVisits = parseInt(CookieManager.get('visits') || '0', 10);
    const newVisits = Number.isNaN(currentVisits) ? 1 : currentVisits + 1;
    CookieManager.set('visits', String(newVisits), 365);
    document.getElementById('visitsMessage').textContent = `Număr vizite: ${newVisits}`;

    const theme = CookieManager.get('theme');
    const bodyTheme = (theme === 'dark') ? 'dark' : 'light';
    document.body.classList.remove('light', 'dark');
    document.body.classList.add(bodyTheme);

    renderTable('cookiesTable', CookieManager.getAll());
    renderTable('localTable', StorageManager.getAllLocal());
    renderTable('sessionTable', StorageManager.getAllSession());
}

document.getElementById('clearAllBtn').addEventListener('click', () => {
    CookieManager.deleteAll();
    StorageManager.clearLocal();
    StorageManager.clearSession();

    document.getElementById('welcomeMessage').textContent = 'Bun venit, Vizitator!';
    document.getElementById('visitsMessage').textContent = 'Număr vizite: 0';
    document.body.classList.remove('dark');
    document.body.classList.add('light');

    renderTable('cookiesTable', CookieManager.getAll());
    renderTable('localTable', StorageManager.getAllLocal());
    renderTable('sessionTable', StorageManager.getAllSession());
});

initPage();