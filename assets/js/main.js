/* =========================
   ✅ FONCTIONS GLOBALES
   ========================= */

// Like AJAX
function toggleLike(e, postId, button) {
  if (e) e.preventDefault();

  const formData = new FormData();
  formData.append('post_id', postId);

  fetch('index.php?page=toggle_like_ajax', {
    method: 'POST',
    body: formData
  })
    .then(r => r.text())
    .then(text => {
      let data;
      try { data = JSON.parse(text); }
      catch (err) {
        console.error("toggleLike: réponse non JSON:", text);
        throw err;
      }

      if (!data.ok) {
        alert(data.error || "Erreur");
        return;
      }

      // UI update
      if (button) {
        button.classList.toggle('active', data.liked === true);
        // si ton bouton contient un texte simple
        button.childNodes[0].textContent = (data.liked ? '✅ Réagi ' : '😊 Réagir ');
        const countSpan = button.querySelector('.like-count');
        if (countSpan) countSpan.textContent = data.count;
      }
    })
    .catch(err => {
      console.error(err);
      alert("Erreur réseau");
    });

  return false;
}


// Réactions AJAX (posts)
function addReaction(type, postId, commentId = null, button = null) {
  const fd = new FormData();

  // ✅ IMPORTANT : ton PHP attend reaction_type
  fd.append('reaction_type', type);

  if (postId) fd.append('post_id', postId);
  if (commentId) fd.append('comment_id', commentId);

  fetch('index.php?page=add_reaction_ajax', { method: 'POST', body: fd })
    .then(async (r) => {
      const text = await r.text();
      try { return JSON.parse(text); }
      catch (e) {
        console.log("Réponse serveur (pas JSON):", text);
        throw e;
      }
    })
    .then(data => {
      if (!data.ok) {
        alert(data.error || 'Erreur');
        return;
      }

      // On cible la bonne section (post ou commentaire)
      let section = null;

      if (button && button.closest) {
        section = button.closest('.reaction-section');
      }

      if (!section && postId) {
        section = document.querySelector(`.reaction-section[data-post-id="${postId}"]`);
      }

      if (!section && commentId) {
        section = document.querySelector(`.reaction-section[data-comment-id="${commentId}"]`);
      }

      if (!section) return;

      // ✅ met à jour l'emoji du bouton principal (si présent)
      const emojiSpan = section.querySelector('.reaction-btn .emoji');
      if (emojiSpan) emojiSpan.textContent = data.userEmoji || '😊';

      // ✅ met à jour l'affichage des compteurs
      const display = section.querySelector('.reaction-display');
      if (!display) return;

      display.innerHTML = '';

      const counts = data.counts || {};
      const emojis = data.emojis || {};
      const entries = Object.entries(counts);

      if (entries.length === 0) {
        display.style.display = 'none';
      } else {
        display.style.display = 'flex';

        // tri desc
        entries.sort((a, b) => (b[1] || 0) - (a[1] || 0)).forEach(([k, v]) => {
          const el = document.createElement('span');
          el.className = 'reaction-item'; // sinon remplace par 'btn-pill'
          el.textContent = `${emojis[k] || '👍'} ${v}`;
          display.appendChild(el);
        });
      }

      // ✅ ferme le picker si présent
      const picker = section.querySelector('.reaction-picker');
      if (picker) picker.style.display = 'none';
    })
    .catch(err => {
      console.error(err);
      alert("Erreur réseau");
    });
}


/* =========================
   ✅ INIT DOM (DOMContentLoaded)
   ========================= */

document.addEventListener('DOMContentLoaded', function () {

  // Gestion des alertes - Auto-hide après 5 secondes
  const alerts = document.querySelectorAll('.alert');
  alerts.forEach(alertBox => {
    setTimeout(() => {
      alertBox.style.transition = 'opacity 0.5s ease-out';
      alertBox.style.opacity = '0';
      setTimeout(() => alertBox.remove(), 500);
    }, 5000);
  });

  // Validation des formulaires
  const forms = document.querySelectorAll('form');
  forms.forEach(form => {
    form.addEventListener('submit', function (e) {
      const requiredFields = form.querySelectorAll('[required]');
      let isValid = true;

      requiredFields.forEach(field => {
        if (!field.value.trim()) {
          isValid = false;
          field.style.borderColor = '#ef4444';

          field.addEventListener('input', function () {
            this.style.borderColor = '';
          }, { once: true });
        }
      });

      if (!isValid) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
      }
    });
  });

  // Animation d'entrée pour les cartes
  const cards = document.querySelectorAll('.event-card');
  if (cards.length > 0) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
        }
      });
    }, { threshold: 0.1 });

    cards.forEach(card => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      card.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
      observer.observe(card);
    });
  }

  // Confirmation de suppression améliorée
  const deleteButtons = document.querySelectorAll('.btn-danger');
  deleteButtons.forEach(button => {
    button.addEventListener('click', function (e) {
      if (!confirm('Êtes-vous sûr de vouloir supprimer cet événement ? Cette action est irréversible.')) {
        e.preventDefault();
      }
    });
  });

  // Formatage automatique du prix
  const priceInputs = document.querySelectorAll('input[name="prix"]');
  priceInputs.forEach(input => {
    input.addEventListener('blur', function () {
      if (this.value && !isNaN(this.value)) {
        this.value = parseFloat(this.value).toFixed(2);
      }
    });
  });

  // Validation de la date (ne pas permettre les dates passées pour création)
  const dateInputs = document.querySelectorAll('input[type="date"]');
  dateInputs.forEach(input => {
    if (window.location.href.includes('action=create')) {
      const today = new Date().toISOString().split('T')[0];
      input.setAttribute('min', today);
    }
  });

});
