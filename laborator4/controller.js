class CalculatorController {
    constructor(model, view) {
        this.model = model;
        this.view = view;
        this.view.listenForClicks(this.handleButtonClick);
        this.view.updateDisplay(this.model.displayValue);
    }

    handleButtonClick = (type, value) => {
        if (type === 'digit') {
            this.model.addDigit(value);
        } else if (type === 'operator') {
            this.model.chooseOperation(value);
        } else if (type === 'action') {
            if (value === 'C') 
                {
                this.model.reset();
                } else if (value === '=') {
                this.model.calculate();
            }
        }
        this.view.updateDisplay(this.model.displayValue);
    }
}
document.addEventListener('DOMContentLoaded', () => {
    const model = new CalculatorModel();
    const view = new CalculatorView();
    const app = new CalculatorController(model, view);
});