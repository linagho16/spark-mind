// ==========================================
// SPARKMIND - BACK.JS (VERSION DEBUG)
// Gestion du Back Office
// ==========================================

console.log('🚀 Back.js chargé !');

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ DOM chargé !');
    
    // Variables globales
    let allDemandes = [];
    let filteredDemandes = [];
    let currentPage = 1;
    const demandesPerPage = 10;
    
    // ==========================================
    // 1. CHARGEMENT DES DEMANDES
    // ==========================================
    
    function loadDemandes() {
        console.log('📡 Chargement des demandes...');
        
        const url = '/SparkMind/controllers/DemandeController.php?action=getAll';
        console.log('URL appelée:', url);
        
        fetch(url)
            .then(response => {
                console.log('📥 Réponse reçue:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('📊 Données reçues:', data);
                
                if (data.success) {
                    allDemandes = data.demandes || [];
                    filteredDemandes = [...allDemandes];
                    console.log('✅ Nombre de demandes:', allDemandes.length);
                    displayDemandes();
                    updateStatistics();
                } else {
                    console.error('❌ Erreur dans les données:', data.message);
                    alert('Erreur: ' + data.message);
                }
            })
            .catch(error => {
                console.error('❌ Erreur fetch:', error);
                alert('Erreur de connexion au serveur: ' + error.message);
            });
    }
    
    // ==========================================
    // 2. AFFICHAGE DES DEMANDES
    // ==========================================
    
    function displayDemandes() {
        console.log('🖼️ Affichage des demandes...');
        
        const tbody = document.querySelector('.demandes-table tbody');
        if (!tbody) {
            console.error('❌ Table tbody non trouvée !');
            return;
        }
        
        // Calculer les indices pour la pagination
        const startIndex = (currentPage - 1) * demandesPerPage;
        const endIndex = startIndex + demandesPerPage;
        const demandesPage = filteredDemandes.slice(startIndex, endIndex);
        
        console.log('📄 Affichage de', demandesPage.length, 'demandes');
        
        // Vider le tableau
        tbody.innerHTML = '';
        
        if (demandesPage.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: #999;">
                        <div style="font-size: 3em;">📭</div>
                        <p style="margin-top: 10px;">Aucune demande trouvée</p>
                    </td>
                </tr>
            `;
            return;
        }
        
        // Afficher les demandes
        demandesPage.forEach(demande => {
            const row = createDemandeRow(demande);
            tbody.appendChild(row);
        });
        
        console.log('✅ Demandes affichées !');
        updatePagination();
    }
    
    function createDemandeRow(demande) {
        const tr = document.createElement('tr');
        
        // Format de la date
        const date = new Date(demande.date_soumission);
        const dateStr = date.toLocaleDateString('fr-FR');
        
        // Type d'aide
        const typeAide = Array.isArray(demande.categories_aide) ? demande.categories_aide[0] : demande.categories_aide;
        const typeLabel = getTypeLabel(typeAide);
        
        // Urgence
        const urgenceLabel = getUrgenceLabel(demande.urgence);
        
        // Statut
        const statutLabel = getStatutLabel(demande.statut);
        
        tr.innerHTML = `
            <td>#${demande.id}</td>
            <td>${dateStr}</td>
            <td>${demande.anonyme ? 'Anonyme' : demande.nom}</td>
            <td>${demande.gouvernorat}</td>
            <td><span class="type-badge ${typeAide}">${typeLabel}</span></td>
            <td><span class="urgence-badge ${demande.urgence}">${urgenceLabel}</span></td>
            <td><span class="status-badge ${demande.statut}">${statutLabel}</span></td>
            <td>
                <div class="action-buttons">
                    <button class="btn-action view" title="Voir détails" onclick="viewDemande(${demande.id})">👁️</button>
                    <button class="btn-action edit" title="Modifier" onclick="editDemande(${demande.id})">✏️</button>
                    <button class="btn-action delete" title="Supprimer" onclick="deleteDemande(${demande.id})">🗑️</button>
                </div>
            </td>
        `;
        return tr;
    }
    
    function getTypeLabel(type) {
        const labels = {
            'alimentaire': '🍽️ Alimentaire',
            'scolaire': '📚 Scolaire',
            'vestimentaire': '👕 Vestimentaire',
            'medicale': '🏥 Médicale',
            'financiere': '💰 Financière',
            'logement': '🏠 Logement',
            'professionnelle': '💼 Professionnelle',
            'psychologique': '💬 Psychologique',
            'autre': '🔧 Autre'
        };
        return labels[type] || type;
    }
    
    function getUrgenceLabel(urgence) {
        const labels = {
            'tres-urgent': '🔴 Très urgent',
            'urgent': '🟠 Urgent',
            'important': '🟡 Important',
            'peut-attendre': '🟢 Peut attendre'
        };
        return labels[urgence] || urgence;
    }
    
    function getStatutLabel(statut) {
        const labels = {
            'nouveau': 'Nouveau',
            'en-cours': 'En cours',
            'traite': 'Traité',
            'refuse': 'Refusé'
        };
        return labels[statut] || statut;
    }
    
    // ==========================================
    // 3. STATISTIQUES
    // ==========================================
    
    function updateStatistics() {
        console.log('📊 Mise à jour des statistiques...');
        
        const urgentes = allDemandes.filter(d => d.urgence === 'tres-urgent').length;
        const enAttente = allDemandes.filter(d => d.statut === 'nouveau').length;
        const traitees = allDemandes.filter(d => d.statut === 'traite').length;
        const total = allDemandes.length;
        
        console.log('Stats:', { urgentes, enAttente, traitees, total });
        
        // Mettre à jour les cartes
        const statCards = document.querySelectorAll('.stat-card');
        if (statCards[0]) statCards[0].querySelector('h3').textContent = urgentes;
        if (statCards[1]) statCards[1].querySelector('h3').textContent = enAttente;
        if (statCards[2]) statCards[2].querySelector('h3').textContent = traitees;
        if (statCards[3]) statCards[3].querySelector('h3').textContent = total;
        
        // Mettre à jour le badge
        const badge = document.querySelector('.nav-item .badge');
        if (badge) badge.textContent = enAttente;
    }
    
    // ==========================================
    // 4. PAGINATION
    // ==========================================
    
    function updatePagination() {
        const totalPages = Math.ceil(filteredDemandes.length / demandesPerPage);
        const pagination = document.querySelector('.pagination');
        if (!pagination) return;
        
        pagination.innerHTML = '';
        
        // Bouton Précédent
        const prevBtn = document.createElement('button');
        prevBtn.className = 'page-btn';
        prevBtn.textContent = '« Précédent';
        prevBtn.disabled = currentPage === 1;
        prevBtn.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                displayDemandes();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
        pagination.appendChild(prevBtn);
        
        // Numéros de pages
        for (let i = 1; i <= totalPages; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.className = 'page-btn';
            if (i === currentPage) pageBtn.classList.add('active');
            pageBtn.textContent = i;
            pageBtn.addEventListener('click', () => {
                currentPage = i;
                displayDemandes();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
            pagination.appendChild(pageBtn);
        }
        
        // Bouton Suivant
        const nextBtn = document.createElement('button');
        nextBtn.className = 'page-btn';
        nextBtn.textContent = 'Suivant »';
        nextBtn.disabled = currentPage === totalPages || totalPages === 0;
        nextBtn.addEventListener('click', () => {
            if (currentPage < totalPages) {
                currentPage++;
                displayDemandes();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
        pagination.appendChild(nextBtn);
    }
    
    // ==========================================
    // 5. ACTIONS
    // ==========================================
    
    window.viewDemande = function(id) {
        const demande = allDemandes.find(d => d.id == id);
        if (!demande) {
            alert('❌ Demande introuvable');
            return;
        }
        
        alert('📋 Détails de la demande #' + id + '\n\n' +
              'Nom: ' + demande.nom + '\n' +
              'Âge: ' + demande.age + '\n' +
              'Gouvernorat: ' + demande.gouvernorat + '\n' +
              'Urgence: ' + demande.urgence + '\n' +
              'Statut: ' + demande.statut + '\n\n' +
              'Description: ' + demande.description_situation);
    };
    
    window.editDemande = function(id) {
        alert('✏️ Modification de la demande #' + id + '\n(Fonctionnalité à implémenter)');
    };
    
    window.deleteDemande = function(id) {
        if (!confirm('⚠️ Supprimer la demande #' + id + ' ?')) return;
        
        fetch(`../../controllers/DemandeController.php?action=delete&id=${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ Demande supprimée !');
                loadDemandes();
            } else {
                alert('❌ Erreur: ' + data.message);
            }
        })
        .catch(error => {
            alert('❌ Erreur: ' + error.message);
        });
    };
    
    // ==========================================
    // 6. RECHERCHE
    // ==========================================
    
    const searchInput = document.querySelector('.search-box input');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performSearch(e.target.value);
            }, 300);
        });
    }
    
    function performSearch(query) {
        console.log('🔍 Recherche:', query);
        
        if (!query || query.trim() === '') {
            filteredDemandes = [...allDemandes];
        } else {
            const searchTerm = query.toLowerCase();
            filteredDemandes = allDemandes.filter(demande => {
                return (
                    demande.id.toString().includes(searchTerm) ||
                    demande.nom.toLowerCase().includes(searchTerm) ||
                    demande.gouvernorat.toLowerCase().includes(searchTerm) ||
                    demande.ville.toLowerCase().includes(searchTerm)
                );
            });
        }
        
        currentPage = 1;
        displayDemandes();
    }
    
    // ==========================================
    // 7. FILTRES
    // ==========================================
    
    const btnFilter = document.querySelector('.btn-filter');
    if (btnFilter) {
        btnFilter.addEventListener('click', applyFilters);
    }
    
    const btnReset = document.querySelector('.btn-reset');
    if (btnReset) {
        btnReset.addEventListener('click', resetFilters);
    }
    
    function applyFilters() {
        console.log('🔧 Application des filtres...');
        
        const selects = document.querySelectorAll('.filter-select');
        const filters = {};
        
        selects.forEach(select => {
            if (select.value) {
                filters[select.name] = select.value;
            }
        });
        
        console.log('Filtres:', filters);
        
        filteredDemandes = allDemandes.filter(demande => {
            let match = true;
            
            if (filters.statut && demande.statut !== filters.statut) match = false;
            if (filters.urgence && demande.urgence !== filters.urgence) match = false;
            if (filters.gouvernorat && demande.gouvernorat !== filters.gouvernorat) match = false;
            
            return match;
        });
        
        currentPage = 1;
        displayDemandes();
        alert('✅ ' + filteredDemandes.length + ' demande(s) trouvée(s)');
    }
    
    function resetFilters() {
        document.querySelectorAll('.filter-select').forEach(select => {
            select.value = '';
        });
        
        filteredDemandes = [...allDemandes];
        currentPage = 1;
        displayDemandes();
        alert('✅ Filtres réinitialisés');
    }
    
    // ==========================================
    // 8. INITIALISATION
    // ==========================================
    
    console.log('🎬 Initialisation...');
    loadDemandes();
    
    // Rafraîchir toutes les 30 secondes
    setInterval(loadDemandes, 30000);
    
    console.log('✅ Back Office initialisé !');
});