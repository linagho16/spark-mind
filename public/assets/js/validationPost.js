document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('postFormFront');
    const textarea = document.getElementById('contenuFront');
    const select = document.getElementById('donationType');
    
    if (!form || !textarea || !select) return;

    const charCount = document.querySelector('.charCount');
    const max = charCount ? parseInt(charCount.getAttribute('data-max') || '280', 10) : 280;

    // Error displays
    const errorMessage = document.getElementById('errorMessage');
    const errorType = document.getElementById('errorType');

    // === LIVE CHARACTER COUNTER ===
    if (charCount) {
        textarea.addEventListener('input', () => {
            const len = textarea.value.length;
            charCount.textContent = `${len} / ${max}`;
            charCount.style.color = len > max ? 'red' : '#6F6F6F';
        });
    }

    // === FORM VALIDATION ===
    form.addEventListener('submit', (e) => {
        let valid = true;

        // Reset errors
        errorMessage.textContent = "";
        errorType.textContent = "";
        textarea.classList.remove("input-error");
        select.classList.remove("input-error");

        const message = textarea.value.trim();
        const type = select.value;

        // Validate category
        if (!type) {
            errorType.textContent = "Veuillez sélectionner un type.";
            select.classList.add("input-error");
            valid = false;
        }

        // Validate message length
        if (message.length < 5) {
            errorMessage.textContent = "Le message doit contenir au moins 5 caractéres.";
            textarea.classList.add("input-error");
            valid = false;
        }

        if (message.length > max) {
            errorMessage.textContent = "Le message dépasse " + max + " caractéres.";
            textarea.classList.add("input-error");
            valid = false;
        }

        // Block form if invalid
        if (!valid) e.preventDefault();
    });
});



