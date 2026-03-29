class CalculatorView {
    constructor() {
        this.displayElement = document.getElementById('display');
        this.buttons = document.querySelectorAll('button');
    }

    updateDisplay(value) {
        this.displayElement.innerText = value === '' ? '0' : value;
    }
    
    listenForClicks(handler) {
        this.buttons.forEach(button => {
            button.addEventListener('click', () => {
                const type = button.getAttribute('data-type'); 
                const value = button.getAttribute('data-value');
                handler(type, value);
            });
        });
    }
}