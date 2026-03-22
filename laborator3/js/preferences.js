function applyTheme(themeValue) {
    const theme = themeValue === 'dark' ? 'dark' : 'light';
    document.body.classList.remove('light', 'dark');
    document.body.classList.add(theme);
}

function applyFontSize(sizeValue) {
    document.body.style.fontSize = sizeValue || '16px';
}

function loadSavedPreferences() {
    const savedUsername = CookieManager.get('username') || '';
    const savedTheme = CookieManager.get('theme') || 'light';
    const savedLanguage = StorageManager.getLocal('language') || 'ro';
    const savedFontSize = StorageManager.getLocal('fontSize') || '16px';

    document.getElementById('username').value = savedUsername;
    document.getElementById('theme').value = (savedTheme === 'dark') ? 'dark' : 'light';
    document.getElementById('language').value = ['ro', 'en', 'fr'].includes(savedLanguage) ? savedLanguage : 'ro';
    document.getElementById('fontSize').value = ['14px', '16px', '18px', '20px'].includes(savedFontSize) ? savedFontSize : '16px';

    applyTheme(savedTheme);
    applyFontSize(savedFontSize);
}

document.getElementById('preferencesForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const username = document.getElementById('username').value.trim() || 'Vizitator';
    const theme = document.getElementById('theme').value;
    const language = document.getElementById('language').value;
    const fontSize = document.getElementById('fontSize').value;

    CookieManager.set('username', username, 365);
    CookieManager.set('theme', theme, 365);
    StorageManager.setLocal('language', language);
    StorageManager.setLocal('fontSize', fontSize);

    applyTheme(theme);
    applyFontSize(fontSize);

    const messageBox = document.getElementById('messageBox');
    messageBox.innerHTML = '<div class="alert-success">Preferințele au fost salvate cu succes. Redirecționare...</div>';

    setTimeout(() => {
        window.location.href = 'index.html';
    }, 1500);
});

loadSavedPreferences();