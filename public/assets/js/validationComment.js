document.addEventListener('DOMContentLoaded', () => {
    // Sélectionner tous les formulaires de commentaire
    const commentForms = document.querySelectorAll('.comment-form');
    
    commentForms.forEach(form => {
        const textarea = form.querySelector('textarea[name="content"]');
        const submitBtn = form.querySelector('button[type="submit"]');
        
        if (!textarea || !submitBtn) return;
        
        // Créer le span d'erreur s'il n'existe pas
        let errorSpan = form.querySelector('.comment-error');
        if (!errorSpan) {
            errorSpan = document.createElement('span');
            errorSpan.className = 'comment-error';
            errorSpan.style.color = '#e74c3c';
            errorSpan.style.fontSize = '13px';
            errorSpan.style.marginTop = '6px';
            errorSpan.style.display = 'block';
            textarea.parentNode.appendChild(errorSpan);
        }
        
        // Validation au submit
        form.addEventListener('submit', (e) => {
            const content = textarea.value.trim();
            
            // Reset erreur
            errorSpan.textContent = '';
            textarea.style.borderColor = '#e8d5c4';
            
            // Vérifier si vide
            if (content === '') {
                errorSpan.textContent = 'Veuillez remplir ce champ.';
                textarea.style.borderColor = '#e74c3c';
                e.preventDefault();
                textarea.focus();
            }
        });
        
        // Effacer l'erreur quand l'utilisateur tape
        textarea.addEventListener('input', () => {
            const errorSpan = form.querySelector('.comment-error');
            if (errorSpan) {
                errorSpan.textContent = '';
                textarea.style.borderColor = '#e8d5c4';
            }
        });
    });
});




































































































































































































