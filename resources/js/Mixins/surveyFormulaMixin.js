export default {
    methods: {
        result(quantity, customValue) {
            let numberInputs = JSON.parse(quantity);
            let result = 0;
            numberInputs.forEach((input) => {
                if (input.operator) {
                    this.javascriptExecuter(
                        `result = result ${
                            input.operator
                        } ${+this.calculateResult(input, customValue)}`
                    );
                } else {
                    result = +this.calculateResult(input, customValue);
                }
            });
            return result;
        },
        calculateResult(numberInput, customValue) {
            let result = 0;
            if (!numberInput.parenthesis.length) {
                let foundOption = {};
                for (let i = 0; i < this.questions.length; i++) {
                    foundOption =
                        Object.values(this.allOptions).find(
                            (op) => op.uuid === numberInput.id
                        ) ?? {};
                    if (Object.keys(foundOption).length > 0) {
                        break;
                    }
                }
                if (customValue) {
                    result = +(customValue ?? numberInput.value);
                } else result = +(foundOption?.value ?? numberInput.value);
            } else {
                let foundOption = {};
                for (let i = 0; i < this.questions.length; i++) {
                    foundOption =
                        Object.values(this.allOptions).find(
                            (op) => op.uuid === numberInput.id
                        ) ?? {};
                    if (Object.keys(foundOption).length > 0) {
                        break;
                    }
                }

                result = +(foundOption?.value ?? numberInput.value);
                numberInput.parenthesis.forEach((input) => {
                    // result = this.calculateResult(input);
                    this.javascriptExecuter(
                        `result = result ${
                            input.operator
                        } ${this.calculateResult(input, customValue)}`
                    );
                });
            }
            return result;
        },
        isJson(value) {
            try {
                return typeof JSON.parse(value) === "object";
            } catch (e) {
                return false;
            }
        },
        checkForFormula(formula) {
            if (this.isJson(formula)) return false;
            try {
                return parseInt(formula) ? false : true;
            } catch (e) {
                return false;
            }
        },
        executeFormula(item, customValue) {
            if (!item.quantity) return 1;
            if (this.checkForFormula(item.quantity)) {
                let matches = item.quantity.match(
                    /(?<=\$).+?(?=\+|\*|\-|\/|\%|\^|\)|$)/g
                );
                let quantity = item.quantity;
                matches.forEach((match) => {
                    let foundOption = {};
                    for (let i = 0; i < this.questions.length; i++) {
                        foundOption =
                            Object.values(this.allOptions).find(
                                (op) => op.uuid === match
                            ) ?? {};
                        if (Object.keys(foundOption).length > 0) {
                            break;
                        }
                    }
                    quantity = quantity.replace(
                        "$" + match,
                        "$" + foundOption?.title.toLowerCase()
                    );
                    if (customValue) {
                        quantity = quantity.replace(
                            "$" + foundOption?.title.toLowerCase(),
                            +customValue
                        );
                    } else {
                        quantity = quantity.replace(
                            "$" + foundOption?.title.toLowerCase(),
                            +foundOption.value
                        );
                    }
                });
                try {
                    quantity = this.javascriptExecuter(quantity.slice(1));
                } catch (e) {
                    console.log(e);
                    quantity = 1;
                }
                return quantity;
            } else {
                return item.quantity;
            }
        },
    },
};
