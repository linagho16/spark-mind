// ==========================================
// SPARKMIND - BACK.JS (VERSION AMÉLIORÉE)
// Gestion du Back Office avec statistiques SparkMind
// ==========================================

console.log('🚀 Back.js chargé - Version améliorée !');

// Variables globales
window.allDemandes = [];
let filteredDemandes = [];
let currentPage = 1;
const demandesPerPage = 10;
let selectedStatus = null;
let currentDemandeId = null;

// ==========================================
// INITIALISATION
// ==========================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ DOM chargé !');
    
    // Charger les demandes
    loadDemandes();
    
    // Actualisation automatique
    setInterval(loadDemandes, 30000);
    
    // Event listeners pour la recherche
    const searchInput = document.getElementById('searchDemandes');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performSearch(e.target.value);
            }, 300);
        });
    }
});

// ==========================================
// NAVIGATION ENTRE SECTIONS
// ==========================================

function showSection(event, sectionName) {
    event.preventDefault();
    
    // Masquer toutes les sections
    document.querySelectorAll('.content-section').forEach(section => {
        section.classList.remove('active');
    });
    
    // Désactiver tous les nav-items
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });
    
    // Afficher la section demandée
    const targetSection = document.getElementById('section-' + sectionName);
    if (targetSection) {
        targetSection.classList.add('active');
    }
    
    // Activer le nav-item correspondant
    event.target.closest('.nav-item').classList.add('active');
    
    // Charger les données selon la section
    if (sectionName === 'categories') {
        renderCategoriesStats();
    } else if (sectionName === 'gouvernorats') {
        renderGouvernoratsStats();
    } else if (sectionName === 'evolution') {
        renderEvolutionStats();
    }
}

// ==========================================
// 1. CHARGEMENT DES DEMANDES
// ==========================================

function loadDemandes() {
    console.log('📡 Chargement des demandes...');
    
    const url = '/SparkMind/controllers/DemandeController.php?action=getAll';
    
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur HTTP: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('📊 Données reçues:', data);
            
            if (data.success) {
                window.allDemandes = data.demandes || [];
                filteredDemandes = [...window.allDemandes];
                console.log('✅ Nombre de demandes:', window.allDemandes.length);
                
                displayDemandes();
                updateStatistics();
                fillGouvernoratFilter();
            } else {
                console.error('❌ Erreur:', data.message);
                showNotification('Erreur: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('❌ Erreur fetch:', error);
            showNotification('Erreur de connexion au serveur', 'error');
        });
}

// ==========================================
// 2. AFFICHAGE DES DEMANDES
// ==========================================

function displayDemandes() {
    console.log('🖼️ Affichage des demandes...');
    
    const tbody = document.getElementById('demandesTableBody');
    if (!tbody) return;
    
    // Pagination
    const startIndex = (currentPage - 1) * demandesPerPage;
    const endIndex = startIndex + demandesPerPage;
    const demandesPage = filteredDemandes.slice(startIndex, endIndex);
    
    tbody.innerHTML = '';
    
    if (demandesPage.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px;">
                    <div style="font-size: 3em;">📭</div>
                    <p>Aucune demande trouvée</p>
                </td>
            </tr>
        `;
        return;
    }
    
    demandesPage.forEach(demande => {
        const tr = createDemandeRow(demande);
        tbody.appendChild(tr);
    });
    
    updatePagination();
}

function createDemandeRow(demande) {
    const tr = document.createElement('tr');
    
    const date = new Date(demande.date_soumission);
    const dateStr = date.toLocaleDateString('fr-FR');
    
    // Gérer les catégories d'aide (peut être un array ou une string)
    let categories = demande.categories_aide;
    if (typeof categories === 'string') {
        try {
            categories = JSON.parse(categories);
        } catch (e) {
            categories = [categories];
        }
    }
    if (!Array.isArray(categories)) {
        categories = [categories];
    }
    const typeAide = categories[0] || 'N/A';
    
    tr.innerHTML = `
        <td>#${demande.id}</td>
        <td>${dateStr}</td>
        <td>${demande.anonyme ? 'Anonyme' : demande.nom}</td>
        <td>${demande.gouvernorat}</td>
        <td><span class="type-badge ${typeAide}">${getTypeLabel(typeAide)}</span></td>
        <td><span class="urgence-badge ${demande.urgence}">${getUrgenceLabel(demande.urgence)}</span></td>
        <td><span class="status-badge ${demande.statut}">${getStatutLabel(demande.statut)}</span></td>
        <td>
            <div class="action-buttons">
                <button class="btn-action view" title="Voir détails" onclick="viewDemande(${demande.id})">👁️</button>
                <button class="btn-action edit" title="Modifier statut" onclick="editDemandeStatus(${demande.id})">✏️</button>
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
// 3. STATISTIQUES PRINCIPALES
// ==========================================

function updateStatistics() {
    const urgentes = window.allDemandes.filter(d => d.urgence === 'tres-urgent').length;
    const enAttente = window.allDemandes.filter(d => d.statut === 'nouveau').length;
    const traitees = window.allDemandes.filter(d => d.statut === 'traite').length;
    const total = window.allDemandes.length;
    
    document.getElementById('statUrgentes').textContent = urgentes;
    document.getElementById('statEnAttente').textContent = enAttente;
    document.getElementById('statTraitees').textContent = traitees;
    document.getElementById('statTotal').textContent = total;
    document.getElementById('badgeEnAttente').textContent = enAttente;
}

// ==========================================
// 4. STATISTIQUES PAR CATÉGORIE
// ==========================================

function renderCategoriesStats() {
    const categories = {};
    
    window.allDemandes.forEach(demande => {
        let cats = demande.categories_aide;
        if (typeof cats === 'string') {
            try {
                cats = JSON.parse(cats);
            } catch (e) {
                cats = [cats];
            }
        }
        if (!Array.isArray(cats)) cats = [cats];
        
        cats.forEach(cat => {
            if (!categories[cat]) {
                categories[cat] = {
                    total: 0,
                    urgentes: 0,
                    enAttente: 0,
                    traitees: 0
                };
            }
            
            categories[cat].total++;
            if (demande.urgence === 'tres-urgent') categories[cat].urgentes++;
            if (demande.statut === 'nouveau') categories[cat].enAttente++;
            if (demande.statut === 'traite') categories[cat].traitees++;
        });
    });
    
    // Afficher les cartes statistiques
    const statsContainer = document.getElementById('categoriesStats');
    statsContainer.innerHTML = '';
    
    Object.entries(categories).forEach(([cat, stats]) => {
        const card = document.createElement('div');
        card.className = 'stat-card';
        card.innerHTML = `
            <div class="stat-icon">${getIconForCategory(cat)}</div>
            <div class="stat-info">
                <h3>${stats.total}</h3>
                <p>${getTypeLabel(cat)}</p>
            </div>
        `;
        statsContainer.appendChild(card);
    });
    
    // Afficher le tableau
    const tbody = document.getElementById('categoriesTableBody');
    tbody.innerHTML = '';
    
    Object.entries(categories).forEach(([cat, stats]) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><span class="type-badge ${cat}">${getTypeLabel(cat)}</span></td>
            <td><strong>${stats.total}</strong></td>
            <td>${stats.urgentes}</td>
            <td>${stats.enAttente}</td>
            <td>${stats.traitees}</td>
            <td>
                <button class="btn-action view" onclick="filterByCategory('${cat}')">👁️ Voir</button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function getIconForCategory(cat) {
    const icons = {
        'alimentaire': '🍽️',
        'scolaire': '📚',
        'vestimentaire': '👕',
        'medicale': '🏥',
        'financiere': '💰',
        'logement': '🏠',
        'professionnelle': '💼',
        'psychologique': '💬',
        'autre': '🔧'
    };
    return icons[cat] || '📁';
}

function filterByCategory(category) {
    document.getElementById('section-categories').classList.remove('active');
    document.getElementById('section-demandes').classList.add('active');
    
    document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
    document.querySelector('.nav-item[href="#demandes"]').classList.add('active');
    
    filteredDemandes = window.allDemandes.filter(d => {
        let cats = d.categories_aide;
        if (typeof cats === 'string') {
            try {
                cats = JSON.parse(cats);
            } catch (e) {
                return cats === category;
            }
        }
        return Array.isArray(cats) && cats.includes(category);
    });
    
    currentPage = 1;
    displayDemandes();
    showNotification(`Filtré par: ${getTypeLabel(category)}`, 'info');
}

// ==========================================
// 5. STATISTIQUES PAR GOUVERNORAT
// ==========================================

function renderGouvernoratsStats() {
    const gouvernorats = {};
    
    window.allDemandes.forEach(demande => {
        const gouv = demande.gouvernorat;
        if (!gouvernorats[gouv]) {
            gouvernorats[gouv] = {
                total: 0,
                urgentes: 0,
                enAttente: 0,
                traitees: 0
            };
        }
        
        gouvernorats[gouv].total++;
        if (demande.urgence === 'tres-urgent') gouvernorats[gouv].urgentes++;
        if (demande.statut === 'nouveau') gouvernorats[gouv].enAttente++;
        if (demande.statut === 'traite') gouvernorats[gouv].traitees++;
    });
    
    const tbody = document.getElementById('gouvernoratsTableBody');
    tbody.innerHTML = '';
    
    // Trier par nombre total décroissant
    const sorted = Object.entries(gouvernorats).sort((a, b) => b[1].total - a[1].total);
    
    sorted.forEach(([gouv, stats]) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><strong>🗺️ ${gouv}</strong></td>
            <td><strong>${stats.total}</strong></td>
            <td>${stats.urgentes}</td>
            <td>${stats.enAttente}</td>
            <td>${stats.traitees}</td>
            <td>
                <button class="btn-action view" onclick="filterByGouvernorat('${gouv}')">👁️ Voir</button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function filterByGouvernorat(gouvernorat) {
    document.getElementById('section-gouvernorats').classList.remove('active');
    document.getElementById('section-demandes').classList.add('active');
    
    document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
    document.querySelector('.nav-item[href="#demandes"]').classList.add('active');
    
    filteredDemandes = window.allDemandes.filter(d => d.gouvernorat === gouvernorat);
    currentPage = 1;
    displayDemandes();
    showNotification(`Filtré par: ${gouvernorat}`, 'info');
}

// ==========================================
// 6. ÉVOLUTION DANS LE TEMPS
// ==========================================

function renderEvolutionStats() {
    const now = new Date();
    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
    const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
    const monthAgo = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());
    
    let aujourdhui = 0;
    let semaine = 0;
    let mois = 0;
    
    window.allDemandes.forEach(demande => {
        const demandeDate = new Date(demande.date_soumission);
        
        if (demandeDate >= today) aujourdhui++;
        if (demandeDate >= weekAgo) semaine++;
        if (demandeDate >= monthAgo) mois++;
    });
    
    const moyenne = window.allDemandes.length > 0 
        ? Math.round(window.allDemandes.length / 30) 
        : 0;
    
    document.getElementById('statAujourdhui').textContent = aujourdhui;
    document.getElementById('statSemaine').textContent = semaine;
    document.getElementById('statMois').textContent = mois;
    document.getElementById('statMoyenne').textContent = moyenne;
    
    // Graphique simple par mois
    renderEvolutionChart();
}

function renderEvolutionChart() {
    const monthsData = {};
    
    window.allDemandes.forEach(demande => {
        const date = new Date(demande.date_soumission);
        const monthKey = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
        monthsData[monthKey] = (monthsData[monthKey] || 0) + 1;
    });
    
    // Prendre les 6 derniers mois
    const sortedMonths = Object.entries(monthsData)
        .sort((a, b) => b[0].localeCompare(a[0]))
        .slice(0, 6)
        .reverse();
    
    const chartDiv = document.getElementById('evolutionChart');
    chartDiv.innerHTML = '';
    
    if (sortedMonths.length === 0) {
        chartDiv.innerHTML = '<p style="text-align: center; color: #999;">Aucune donnée disponible</p>';
        return;
    }
    
    const maxValue = Math.max(...sortedMonths.map(m => m[1]));
    
    sortedMonths.forEach(([month, count]) => {
        const bar = document.createElement('div');
        bar.className = 'chart-bar';
        const height = (count / maxValue) * 200;
        bar.style.height = height + 'px';
        bar.innerHTML = `
            <div class="bar-value">${count}</div>
            <div class="bar-label">${formatMonth(month)}</div>
        `;
        chartDiv.appendChild(bar);
    });
}

function formatMonth(monthKey) {
    const [year, month] = monthKey.split('-');
    const months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
    return `${months[parseInt(month) - 1]} ${year}`;
}

// ==========================================
// 7. FILTRES ET RECHERCHE
// ==========================================

function applyFilters() {
    const statut = document.getElementById('filterStatut').value;
    const urgence = document.getElementById('filterUrgence').value;
    const gouvernorat = document.getElementById('filterGouvernorat').value;
    
    filteredDemandes = window.allDemandes.filter(demande => {
        let match = true;
        
        if (statut && demande.statut !== statut) match = false;
        if (urgence && demande.urgence !== urgence) match = false;
        if (gouvernorat && demande.gouvernorat !== gouvernorat) match = false;
        
        return match;
    });
    
    currentPage = 1;
    displayDemandes();
    showNotification(`${filteredDemandes.length} demande(s) trouvée(s)`, 'info');
}

function resetFilters() {
    document.getElementById('filterStatut').value = '';
    document.getElementById('filterUrgence').value = '';
    document.getElementById('filterGouvernorat').value = '';
    
    filteredDemandes = [...window.allDemandes];
    currentPage = 1;
    displayDemandes();
    showNotification('Filtres réinitialisés', 'info');
}

function fillGouvernoratFilter() {
    const select = document.getElementById('filterGouvernorat');
    const gouvernorats = [...new Set(window.allDemandes.map(d => d.gouvernorat))].sort();
    
    select.innerHTML = '<option value="">Tous</option>';
    gouvernorats.forEach(gouv => {
        const option = document.createElement('option');
        option.value = gouv;
        option.textContent = gouv;
        select.appendChild(option);
    });
}

function performSearch(query) {
    if (!query || query.trim() === '') {
        filteredDemandes = [...window.allDemandes];
    } else {
        const searchTerm = query.toLowerCase();
        filteredDemandes = window.allDemandes.filter(demande => {
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
// 8. PAGINATION
// ==========================================

function updatePagination() {
    const totalPages = Math.ceil(filteredDemandes.length / demandesPerPage);
    const pagination = document.getElementById('paginationDemandes');
    
    if (!pagination) return;
    
    pagination.innerHTML = '';
    
    // Bouton précédent
    const prevBtn = document.createElement('button');
    prevBtn.className = 'page-btn';
    prevBtn.textContent = '« Précédent';
    prevBtn.disabled = currentPage === 1;
    prevBtn.onclick = () => {
        if (currentPage > 1) {
            currentPage--;
            displayDemandes();
        }
    };
    pagination.appendChild(prevBtn);
    
    // Boutons de pages
    for (let i = 1; i <= totalPages; i++) {
        const pageBtn = document.createElement('button');
        pageBtn.className = 'page-btn' + (i === currentPage ? ' active' : '');
        pageBtn.textContent = i;
        pageBtn.onclick = () => {
            currentPage = i;
            displayDemandes();
        };
        pagination.appendChild(pageBtn);
    }
    
    // Bouton suivant
    const nextBtn = document.createElement('button');
    nextBtn.className = 'page-btn';
    nextBtn.textContent = 'Suivant »';
    nextBtn.disabled = currentPage === totalPages || totalPages === 0;
    nextBtn.onclick = () => {
        if (currentPage < totalPages) {
            currentPage++;
            displayDemandes();
        }
    };
    pagination.appendChild(nextBtn);
}

// ==========================================
// 9. ACTIONS SUR LES DEMANDES
// ==========================================

window.viewDemande = function(id) {
    const demande = window.allDemandes.find(d => d.id == id);
    if (!demande) {
        alert('❌ Demande introuvable');
        return;
    }
    
    let categories = demande.categories_aide;
    if (typeof categories === 'string') {
        try {
            categories = JSON.parse(categories);
        } catch (e) {
            categories = [categories];
        }
    }
    
    let horaires = demande.horaires_disponibles;
    if (typeof horaires === 'string') {
        try {
            horaires = JSON.parse(horaires);
        } catch (e) {
            horaires = [horaires];
        }
    }
    
    document.getElementById('modalTitle').textContent = `Détails de la Demande #${demande.id}`;
    document.getElementById('modalBody').innerHTML = `
        <div class="detail-section">
            <h3>Informations Personnelles</h3>
            <div class="detail-grid">
                <div class="detail-item"><strong>Nom:</strong> <span>${demande.anonyme ? 'Anonyme' : demande.nom}</span></div>
                <div class="detail-item"><strong>Âge:</strong> <span>${demande.age} ans</span></div>
                <div class="detail-item"><strong>Gouvernorat:</strong> <span>${demande.gouvernorat}</span></div>
                <div class="detail-item"><strong>Ville:</strong> <span>${demande.ville}</span></div>
            </div>
        </div>
        
        <div class="detail-section">
            <h3>Type d'Aide</h3>
            <div class="detail-grid">
                <div class="detail-item"><strong>Catégories:</strong> <span>${Array.isArray(categories) ? categories.map(c => getTypeLabel(c)).join(', ') : categories}</span></div>
                <div class="detail-item"><strong>Urgence:</strong> <span class="urgence-badge ${demande.urgence}">${getUrgenceLabel(demande.urgence)}</span></div>
            </div>
        </div>
        
        <div class="detail-section">
            <h3>Description</h3>
            <p><strong>Situation:</strong> ${demande.description_situation}</p>
            <p><strong>Demande exacte:</strong> ${demande.demande_exacte}</p>
        </div>
        
        <div class="detail-section">
            <h3>Contact</h3>
            <div class="detail-grid">
                <div class="detail-item"><strong>Téléphone:</strong> <span>${demande.telephone}</span></div>
                <div class="detail-item"><strong>Email:</strong> <span>${demande.email || 'Non fourni'}</span></div>
                <div class="detail-item"><strong>Préférence:</strong> <span>${demande.preference_contact}</span></div>
                <div class="detail-item"><strong>Disponibilité:</strong> <span>${Array.isArray(horaires) ? horaires.join(', ') : horaires}</span></div>
            </div>
        </div>
    `;
    
    openModal('detailsModal');
};

// ==========================================
// 10. MODAL MODIFICATION STATUT - NOUVEAU
// ==========================================

window.editDemandeStatus = function(id) {
    currentDemandeId = id;
    selectedStatus = null;
    
    document.getElementById('currentDemandeId').textContent = id;
    document.querySelectorAll('.status-option').forEach(opt => opt.classList.remove('selected'));
    document.getElementById('confirmStatusBtn').disabled = true;
    
    openModal('statusModal');
};

window.selectStatus = function(element, status) {
    document.querySelectorAll('.status-option').forEach(opt => {
        opt.classList.remove('selected');
    });
    
    element.classList.add('selected');
    selectedStatus = status;
    
    document.getElementById('confirmStatusBtn').disabled = false;
};

window.confirmStatusChange = function() {
    if (!selectedStatus || !currentDemandeId) return;
    
    fetch('/SparkMind/controllers/DemandeController.php?action=updateStatus', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: currentDemandeId, statut: selectedStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('✅ Statut mis à jour avec succès', 'success');
            closeModal('statusModal');
            loadDemandes();
        } else {
            showNotification('❌ Erreur: ' + data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('❌ Erreur: ' + error.message, 'error');
    });
};

window.deleteDemande = function(id) {
    if (!confirm(`⚠️ Supprimer la demande #${id} ?\n\nCette action est irréversible.`)) return;
    
    fetch(`/SparkMind/controllers/DemandeController.php?action=delete&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('✅ Demande supprimée', 'success');
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
// 11. UTILITAIRES MODAL
// ==========================================

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.style.display = 'flex';
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.style.display = 'none';
}

// Fermer le modal en cliquant en dehors
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
        e.target.style.display = 'none';
    }
});

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#4caf50' : type === 'error' ? '#f44336' : '#2196F3'};
        color: white;
        padding: 15px 25px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        z-index: 10000;
        font-weight: 600;
    `;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => notification.remove(), 3000);
}

console.log('✅ Back Office initialisé !');