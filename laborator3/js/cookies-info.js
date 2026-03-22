function renderCookies() {
    document.getElementById('rawCookies').textContent = document.cookie || '(gol)';
    const tbody = document.querySelector('#cookiesTable tbody');
    tbody.innerHTML = '';
    const cookies = CookieManager.getAll();
    const list = Object.entries(cookies);
    if (list.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4">Nu exista cookies</td></tr>';
        return;
    }
    list.forEach(([name, value]) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `<td>${name}</td><td>${value}</td><td>${String(value).length}</td>`;
        const tdBtn = document.createElement('td');
        const btn = document.createElement('button');
        btn.textContent = 'Șterge';
        btn.className = 'btn-danger';
        btn.onclick = () => {
            CookieManager.delete(name);
            refreshAll();
        };
        tdBtn.appendChild(btn);
        tr.appendChild(tdBtn);
        tbody.appendChild(tr);
    });
}

function renderLocal() {
    const data = StorageManager.getAllLocal();
    document.getElementById('localJson').textContent = JSON.stringify(data, null, 2);
    const tbody = document.querySelector('#localTable tbody');
    tbody.innerHTML = '';
    const list = Object.entries(data);
    if (list.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4">localStorage este gol</td></tr>';
        return;
    }
    list.forEach(([key, value]) => {
        const text = typeof value === 'object' ? JSON.stringify(value) : String(value);
        const tr = document.createElement('tr');
        tr.innerHTML = `<td>${key}</td><td>${text}</td><td>${text.length} B</td>`;
        const tdBtn = document.createElement('td');
        const btn = document.createElement('button');
        btn.textContent = 'Șterge';
        btn.className = 'btn-danger';
        btn.onclick = () => {
            StorageManager.removeLocal(key);
            refreshAll();
        };
        tdBtn.appendChild(btn);
        tr.appendChild(tdBtn);
        tbody.appendChild(tr);
    });
}

function renderSession() {
    const data = StorageManager.getAllSession();
    document.getElementById('sessionJson').textContent = JSON.stringify(data, null, 2);
    const tbody = document.querySelector('#sessionTable tbody');
    tbody.innerHTML = '';
    const list = Object.entries(data);
    if (list.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4">sessionStorage este gol</td></tr>';
        return;
    }
    list.forEach(([key, value]) => {
        const text = typeof value === 'object' ? JSON.stringify(value) : String(value);
        const tr = document.createElement('tr');
        tr.innerHTML = `<td>${key}</td><td>${text}</td><td>${text.length} B</td>`;
        const tdBtn = document.createElement('td');
        const btn = document.createElement('button');
        btn.textContent = 'Șterge';
        btn.className = 'btn-danger';
        btn.onclick = () => {
            StorageManager.removeSession(key);
            refreshAll();
        };
        tdBtn.appendChild(btn);
        tr.appendChild(tdBtn);
        tbody.appendChild(tr);
    });
}
function refreshAll() {
    renderCookies();
    renderLocal();
    renderSession();
}

refreshAll();