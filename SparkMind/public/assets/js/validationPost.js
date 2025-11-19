document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('postFormFront');
  const textarea = document.getElementById('contenuFront');
  if (!form || !textarea) return;

  const charCount = form.querySelector('.charCount');
  const max = parseInt(charCount.getAttribute('data-max') || '280', 10);

  textarea.addEventListener('input', () => {
    const len = textarea.value.length;
    charCount.textContent = len + ' / ' + max;
    charCount.style.color = len > max ? 'red' : '#6F6F6F';
  });

  form.addEventListener('submit', (e) => {
    const value = textarea.value.trim();
    if (value.length < 5) {
      e.preventDefault();
      alert("Le message doit contenir au moins 5 caractères.");
    } else if (value.length > max) {
      e.preventDefault();
      alert("Le message dépasse " + max + " caractères.");
    }
  });
});
