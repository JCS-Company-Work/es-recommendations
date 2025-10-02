class PricingCalculator {

    constructor() {
        this.wrapperSelector = document.querySelectorAll('.quantity-wrapper');
        this.init();
    }

    init() {
        this.wrapperSelector.forEach(wrapper => {
            const sqmInput = wrapper.querySelector('input[input-type="sqm"]');
            const boxInput = wrapper.querySelector('input[input-type="cartons"]');

            if (!sqmInput || !boxInput) return;

            // Get the related WC cart input (boxes should update this one)
            const wcQtyInput = wrapper.querySelector('.product-buttons .cart .quantity input.qty');

            // Attach +/- controls
            this.bindButtons(sqmInput);
            this.bindButtons(boxInput);

            // Sync sqm <-> boxes
            sqmInput.addEventListener('change', () => {
                this.syncFromSqm(wrapper, sqmInput, boxInput, wcQtyInput);
            });

            boxInput.addEventListener('change', () => {
                this.syncFromBoxes(wrapper, sqmInput, boxInput, wcQtyInput);
            });
        });
    }

    bindButtons(input) {
        const minus = input.parentElement.querySelector('.qty-btns.minus');
        const plus = input.parentElement.querySelector('.qty-btns.plus');

        if (minus) {
            minus.addEventListener('click', e => {
                e.preventDefault();
                this.stepInput(input, -1);
                input.dispatchEvent(new Event('change', { bubbles: true }));
            });
        }

        if (plus) {
            plus.addEventListener('click', e => {
                e.preventDefault();
                this.stepInput(input, 1);
                input.dispatchEvent(new Event('change', { bubbles: true }));
            });
        }
    }

    stepInput(input, dir) {
        
        // Step size taken from input's "step" attribute, default 1
        const step = parseFloat(input.step) || 1;
        
        // Minimum value allowed (defaults to 0 if not set)
        const min = parseFloat(input.min) || 0;
        
        // Maximum value allowed (Infinity if not set)
        const max = parseFloat(input.max) || Infinity;

        
        // Current numeric value from the input (or use min if empty/invalid)
        let value = parseFloat(input.value) || min;
        
        // Preview of the new value (before clamping)
        const newValue = value + dir * step;

        
        // If new value would exceed max, show "max available" message
        if (newValue > max) {

            const wrapper = input.closest('.quantity-wrapper');
            this.maxAvailableMessage(wrapper, "block");

        } else {

            const wrapper = input.closest('.quantity-wrapper');
            this.maxAvailableMessage(wrapper, "none");

        }

        // Actually apply the new value with clamping
        value = value + dir * step;
        value = Math.max(min, Math.min(value, max));

        
        // Update input value with correct decimal precision
        input.value = value.toFixed(this.decimals(step));
    }

    decimals(num) {
        const parts = num.toString().split('.');
        return parts.length > 1 ? parts[1].length : 0;
    }

    syncFromSqm(wrapper, sqmInput, boxInput, wcQtyInput) {
        const sqm = parseFloat(sqmInput.value) || 0;
        const perBox = parseFloat(sqmInput.step) || 2;

        const boxes = Math.max(1, Math.round(sqm / perBox));
        boxInput.value = boxes;

        // Force 2 decimals on sqm input
        sqmInput.value = sqm.toFixed(2);

        // Update WC qty
        if (wcQtyInput) wcQtyInput.value = boxes;

        // Update hidden input
        this.updateHiddenInput(wrapper);
    }

    syncFromBoxes(wrapper, sqmInput, boxInput, wcQtyInput) {
        const boxes = parseInt(boxInput.value, 10) || 1;
        const perBox = parseFloat(sqmInput.step) || 2;

        sqmInput.value = (boxes * perBox).toFixed(2);

        // Update WC qty
        if (wcQtyInput) wcQtyInput.value = boxes;

        // Update hidden input
        this.updateHiddenInput(wrapper);
    }

    updateHiddenInput(wrapper) {

        //get batch hidden input field
        const hiddenInput = wrapper.querySelector("input[name='p-cart']");

        // Get number of boxes selected by user
        const userBoxes = wrapper.querySelector('#user-sqm-cartons');

        // Get square metres selected by user
        const sqm = wrapper.querySelector('#user-sqm');

        // Update price
        this.updatePrice(wrapper, hiddenInput, userBoxes.value);

        // Update quantities (sqm and boxes)
        this.updateQuantities(wrapper, hiddenInput, userBoxes.value, sqm.value);

    }

    updatePrice = (wrapper, hiddenInput, boxesSelected) => {

        // Total value element
        const totalPrice = wrapper.querySelector(".split-total-value");

        // Get single box cost from hidden input
        const singleCartonPrice = hiddenInput.getAttribute('p-single-carton-price');

        // Format cost in UK currency
        const formattedPrice = singleCartonPrice * boxesSelected;

        // Update cost on page
        totalPrice.innerHTML = `£${formattedPrice}`;

        // Update hidden input costs for Cognito Forms without £ symbol
        hiddenInput.setAttribute("p-total-price", formattedPrice); 

    }

    updateQuantities = (wrapper, hiddenInput, boxesSelected, sqm) => {

        // Update hidden input with sqm value required
        hiddenInput.setAttribute("p-quantity-required", sqm); 

        // Update hidden input with number of cartons selected
        hiddenInput.setAttribute("p-cartons-selected", boxesSelected); 

        // Update number of boxes value on page
        const cartonsSelected = wrapper.querySelector(".cartons-selected > .box-qty");

        cartonsSelected.innerHTML = boxesSelected;

    }

    maxAvailableMessage = (wrapper, displayValue) => {

        const maxAvailable = wrapper.querySelector('.max-qty-available');

        if (maxAvailable && maxAvailable.style.display !== displayValue) {

            maxAvailable.style.display = displayValue;

        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new PricingCalculator();
});