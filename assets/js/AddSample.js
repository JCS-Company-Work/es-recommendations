class AddSample {

    constructor() {
        // Get all quantity-wrapper elements
        this.wrappers = document.querySelectorAll('.quantity-wrapper');
        this.init();
    }

    init() {

        // Loop through wrappers
        this.wrappers.forEach(wrapper => {

            // Get "Add to sample" button
            const sampleBtn = wrapper.querySelector('.add_to_sample');

            // On click, grab hidden input and add to storage
            sampleBtn.addEventListener("click", () => {
                const hiddenInput =  wrapper.querySelector("input[name='p-cart']");
                addToStoragePT('cart', hiddenInput);
            })

        });

    }

}

// Run after DOM loads: set up sample buttons
document.addEventListener('DOMContentLoaded', () => {
    new AddSample();
});