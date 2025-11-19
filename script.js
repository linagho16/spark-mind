const toast = (msg) => {
  const t = document.createElement('div');
  t.textContent = msg;
  Object.assign(t.style,{
    position:'fixed',left:'50%',bottom:'24px',translate:'-50% 0',padding:'12px 16px',
    background:'#111',color:'#fff',borderRadius:'12px',
    boxShadow:'0 5px 15px rgba(0,0,0,.3)',zIndex:9999
  });
  document.body.appendChild(t);
  setTimeout(()=>{ t.style.opacity='0'; t.style.transition='opacity .3s'; }, 1600);
  setTimeout(()=> t.remove(), 2000);
};

document.getElementById('loginBtn').addEventListener('click',()=>toast('Ouvrir la modale Login'));
document.getElementById('signupBtn').addEventListener('click',()=>toast('Ouvrir la modale Inscription'));
document.getElementById('searchBtn').addEventListener('click',()=>toast('Recherche : '+(document.getElementById('search').value||'…')));
document.getElementById('askLink').addEventListener('click',e=>{e.preventDefault();toast('Créer une demande d’aide');});
document.getElementById('offerLink').addEventListener('click',e=>{e.preventDefault();toast('Proposer une offre d’aide');});
document.getElementById('discussBtn').addEventListener('click',()=>toast('Entrer dans les discussions anonymes'));
document.getElementById('helpBtn').addEventListener('click',e=>{e.preventDefault();toast('Besoin d’aide ? Contactez le support.');});
