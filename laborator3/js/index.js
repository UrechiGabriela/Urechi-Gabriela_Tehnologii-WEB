function renderTable(tableId, dataObject) {
    const tbody = document.querySelector(`#${tableId} tbody`);
    tbody.innerHTML = '';

    const entries = Object.entries(dataObject || {});
    if (!entries.length) {
        const tr = document.createElement('tr');
        const td = document.createElement('td');
        td.colSpan = 2;
        td.textContent = 'Nu există date';
        tr.appendChild(td);
        tbody.appendChild(tr);
        return;
    }

    entries.forEach(([key, value]) => {
        const tr = document.createElement('tr');
        const tdKey = document.createElement('td');
        const tdValue = document.createElement('td');

        tdKey.textContent = String(key);
        tdValue.textContent =
            typeof value === 'object' ? JSON.stringify(value) : String(value);

        tr.appendChild(tdKey);
        tr.appendChild(tdValue);
        tbody.appendChild(tr);
    });
}

function applyTheme() {
    const theme = CookieManager.get('theme') === 'dark' ? 'dark' : 'light';
    document.body.classList.remove('light', 'dark');
    document.body.classList.add(theme);
}

function renderAllTables() {
    renderTable('cookiesTable', CookieManager.getAll());
    renderTable('localTable', StorageManager.getAllLocal());
    renderTable('sessionTable', StorageManager.getAllSession());
}

function initPage() {
    const username = CookieManager.get('username') || 'Vizitator';
    document.getElementById('welcomeMessage').textContent = `Bun venit, ${username}!`;

    const visits = Number(CookieManager.get('visits') || 0) + 1;
    CookieManager.set('visits', String(visits), 365);
    document.getElementById('visitsMessage').textContent = `Număr vizite: ${visits}`;

    applyTheme();
    renderAllTables();
}

document.getElementById('clearAllBtn').addEventListener('click', () => {
    CookieManager.deleteAll();
    StorageManager.clearLocal();
    StorageManager.clearSession();

    document.getElementById('welcomeMessage').textContent = 'Bun venit, Vizitator!';
    document.getElementById('visitsMessage').textContent = 'Număr vizite: 0';

    document.body.classList.remove('dark');
    document.body.classList.add('light');

    renderAllTables();
});

initPage();