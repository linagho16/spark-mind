// Changement de section via la sidebar
const links = document.querySelectorAll('.menu .item');
const sections = document.querySelectorAll('.section');
links.forEach(l => {
  l.addEventListener('click', () => {
    links.forEach(i => i.classList.remove('active'));
    l.classList.add('active');
    const id = l.getAttribute('data-section');
    sections.forEach(s => s.classList.toggle('show', s.id === id));
    // Scroll top à chaque changement
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
});

// Modales
function wireModal(openAttr, modalId){
  document.querySelectorAll(`[data-open="${openAttr}"]`).forEach(btn=>{
    btn.addEventListener('click', ()=> document.getElementById(modalId).showModal());
  });
}
wireModal('userModal','userModal');
wireModal('contentModal','contentModal');

// Fermer modales sur "Esc" (natif <dialog>), rien à ajouter.
// Petite démo KPI dynamique (optionnel)
document.getElementById('searchBtn')?.addEventListener('click', ()=>{
  const val = document.getElementById('globalSearch').value.trim();
  if(val) alert(`Recherche globale : "${val}"`);
});

// Thème clair/sépia doux (reste dans la palette)
const themeBtn = document.getElementById('themeToggle');
let sepia = false;
themeBtn?.addEventListener('click', ()=>{
  sepia = !sepia;
  document.documentElement.style.setProperty('--bg', sepia ? '#FAE8CC' : '#FBEDD7');
  document.documentElement.style.setProperty('--panel', sepia ? '#fff1dc' : '#fff7ec');
  document.documentElement.style.setProperty('--panel-2', sepia ? '#ffe3c7' : '#ffe9d8');
});
