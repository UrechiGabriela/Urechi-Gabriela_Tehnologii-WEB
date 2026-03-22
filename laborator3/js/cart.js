const SESSION_KEY = 'session';
const CART_KEY = 'cart';

const products = {
    laptop: { name: 'Laptop', price: 2500 },
    telefon: { name: 'Telefon', price: 1200 },
    tableta: { name: 'Tabletă', price: 800 },
    casti: { name: 'Căști', price: 150 }
};

function showMessage(type, text) {
    const cls = type === 'error' ? 'alert-error' : 'alert-success';
    document.getElementById('messageBox').innerHTML = `<div class="${cls}">${text}</div>`;
}

function ensureAuth() {
    const sessionData = StorageManager.getSession(SESSION_KEY);
    if (!sessionData || !sessionData.sessionId) {
        window.location.href = 'login.html';
        return false;
    }
    return true;
}

function getCart() {
    const cart = StorageManager.getSession(CART_KEY);
    return cart && typeof cart === 'object' ? cart : {};
}

function saveCart(cart) {
    StorageManager.setSession(CART_KEY, cart);
}

function populateProducts() {
    const select = document.getElementById('product');
    select.innerHTML = '';

    Object.keys(products).forEach(key => {
        const option = document.createElement('option');
        option.value = key;
        option.textContent = `${products[key].name} - ${products[key].price} RON`;
        select.appendChild(option);
    });
}

function renderCart() {
    const cart = getCart();
    const tbody = document.querySelector('#cartTable tbody');
    const totalEl = document.getElementById('cartTotal');

    tbody.innerHTML = '';
    let total = 0;

    const entries = Object.entries(cart).filter(([k, q]) => products[k] && Number(q) > 0);

    if (!entries.length) {
        tbody.innerHTML = '<tr><td colspan="4">Coșul este gol.</td></tr>';
        totalEl.textContent = '0';
        return;
    }

    entries.forEach(([key, qty]) => {
        const quantity = Number(qty);
        const item = products[key];
        const linePrice = item.price * quantity;
        total += linePrice;

        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.name}</td>
            <td>${quantity}</td>
            <td>${linePrice} RON</td>
            <td><button class="btn-danger" data-remove="${key}">Șterge</button></td>
        `;
        tbody.appendChild(row);
    });

    totalEl.textContent = String(total);

    tbody.querySelectorAll('button[data-remove]').forEach(btn => {
        btn.addEventListener('click', () => {
            const key = btn.getAttribute('data-remove');
            const cartData = getCart();
            delete cartData[key];
            saveCart(cartData);
            renderCart();
        });
    });
}

document.getElementById('addToCartForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const key = document.getElementById('product').value;
    const quantity = Number(document.getElementById('quantity').value);

    if (!products[key]) {
        showMessage('error', 'Produs invalid.');
        return;
    }

    if (!Number.isInteger(quantity) || quantity < 1 || quantity > 10) {
        showMessage('error', 'Cantitatea trebuie să fie între 1 și 10.');
        return;
    }

    const cart = getCart();
    cart[key] = (Number(cart[key]) || 0) + quantity; 
    saveCart(cart);

    showMessage('success', 'Produs adăugat în coș.');
    this.reset();
    document.getElementById('quantity').value = 1;
    renderCart();
});

document.getElementById('clearCartBtn').addEventListener('click', function () {
    saveCart({});
    showMessage('success', 'Coșul a fost golit.');
    renderCart();
});

if (ensureAuth()) {
    populateProducts();
    renderCart();
}