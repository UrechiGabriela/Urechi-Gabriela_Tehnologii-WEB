class CalculatorModel {
    constructor() {
        this.reset();
    }
    reset() {
        this.operand1 = '';
        this.operand2 = '';
        this.operator = null;
        this.displayValue = '0';
    }
    addDigit(digit) {
        if (this.displayValue === 'Eroare') this.reset();
        if (this.operator === null) {
            this.operand1 += digit;
            this.displayValue = this.operand1;
        } else {
            this.operand2 += digit;
            this.displayValue = `${this.operand1} ${this.operator} ${this.operand2}`;
        }
    }
    
    chooseOperation(op) {
        if (this.displayValue === 'Eroare' || this.operand1 === '') return;
        if (this.operand2 !== '') 
        {
            this.calculate();
        }
        this.operator = op;
        this.displayValue = `${this.operand1} ${this.operator}`;
    }
    calculate() {
        if (!this.operand1 || !this.operand2 || !this.operator) return;
        const num1 = parseFloat(this.operand1);
        const num2 = parseFloat(this.operand2);
        let result = 0;

        switch (this.operator) {
            case '+': result = num1 + num2; break;
            case '-': result = num1 - num2; break;
            case '*': result = num1 * num2; break;
            case '/':
                if (num2 === 0) {
                    this.displayValue = 'Eroare'; 
                    this.operand1 = '';
                    this.operand2 = '';
                    this.operator = null;
                    return;
                }
                result = num1 / num2;
                break;
        }
        this.operand1 = result.toString();
        this.operand2 = '';
        this.operator = null;
        this.displayValue = this.operand1;
    }
}