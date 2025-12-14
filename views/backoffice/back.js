// ==========================================
// SPARKMIND - BACK.JS COMPLET
// Version finale avec Export et Carte
// ==========================================

console.log('🚀 Back.js chargé - Version complète !');

// Variables globales
window.allDemandes = [];
let filteredDemandes = [];
let currentPage = 1;
const demandesPerPage = 10;
let selectedStatus = null;
let currentDemandeId = null;
let exportHistory = [];

// Coordonnées approximatives des gouvernorats tunisiens (en pourcentage)
const gouvernoratCoords = {
    'Tunis': { x: 64, y: 11 },
    'Ariana': { x: 62, y: 9 },
    'Ben Arous': { x: 65, y: 13 },
    'Manouba': { x: 60, y: 12 },
    'Nabeul': { x: 74, y: 12 },
    'Zaghouan': { x: 62, y: 16 },
    'Bizerte': { x: 60, y: 4 },
    'Béja': { x: 57, y: 11 },
    'Jendouba': { x: 52, y: 7 },
    'Le Kef': { x: 55, y: 14 },
    'Siliana': { x: 59, y: 17 },
    'Kairouan': { x: 62, y: 24 },
    'Kasserine': { x: 52, y: 27 },
    'Sidi Bouzid': { x: 60, y: 31 },
    'Sousse': { x: 65, y: 21 },
    'Monastir': { x: 68, y: 22 },
    'Mahdia': { x: 69, y: 26 },
    'Sfax': { x: 71, y: 38 },
    'Gafsa': { x: 54, y: 37 },
    'Tozeur': { x: 49, y: 39 },
    'Kébili': { x: 54, y: 47 },
    'Gabès': { x: 63, y: 50 },
    'Médenine': { x: 66, y: 53  },
    'Tataouine': { x: 64, y: 57 }
};

// ==========================================
// INITIALISATION
// ==========================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ DOM chargé !');
    
    loadDemandes();
    loadExportHistory();
    setInterval(loadDemandes, 30000);
    
    const searchInput = document.getElementById('searchDemandes');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => performSearch(e.target.value), 300);
        });
    }

    const periodeSelect = document.getElementById('exportPeriode');
    if (periodeSelect) {
        periodeSelect.addEventListener('change', function() {
            const customDateRange = document.getElementById('customDateRange');
            if (customDateRange) {
                if (this.value === 'custom') {
                    customDateRange.style.display = 'grid';
                } else {
                    customDateRange.style.display = 'none';
                }
            }
        });
    }
});

// ==========================================
// NAVIGATION
// ==========================================

function showSection(event, sectionName) {
    event.preventDefault();
    
    document.querySelectorAll('.content-section').forEach(section => {
        section.classList.remove('active');
    });
    
    document.querySelectorAll('.menu-item').forEach(item => {
        item.classList.remove('active');
    });
    
    const targetSection = document.getElementById('section-' + sectionName);
    if (targetSection) {
        targetSection.classList.add('active');
    }
    
    event.target.closest('.menu-item').classList.add('active');
    
    if (sectionName === 'categories') {
        renderCategoriesStats();
    } else if (sectionName === 'gouvernorats') {
        renderGouvernoratsStats();
    } else if (sectionName === 'carte') {
        renderCarte();
    } else if (sectionName === 'export') {
        fillExportFilters();
    }
}

// ==========================================
// CHARGEMENT DES DEMANDES
// ==========================================

function loadDemandes() {
    console.log('📡 Chargement des demandes...');
    
    const url = '/SparkMind/controllers/DemandeController.php?action=getAll';
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.allDemandes = data.demandes || [];
                filteredDemandes = [...window.allDemandes];
                console.log('✅ Nombre de demandes:', window.allDemandes.length);
                
                displayDemandes();
                updateStatistics();
                fillGouvernoratFilter();
            } else {
                showNotification('Erreur: ' + data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Erreur de connexion', 'error');
        });
}

// ==========================================
// AFFICHAGE DES DEMANDES
// ==========================================

function displayDemandes() {
    const tbody = document.getElementById('demandesTableBody');
    if (!tbody) return;
    
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
// STATISTIQUES
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
                categories[cat] = { total: 0, urgentes: 0, enAttente: 0, traitees: 0 };
            }
            categories[cat].total++;
            if (demande.urgence === 'tres-urgent') categories[cat].urgentes++;
            if (demande.statut === 'nouveau') categories[cat].enAttente++;
            if (demande.statut === 'traite') categories[cat].traitees++;
        });
    });
    
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
        'alimentaire': '🍽️', 'scolaire': '📚', 'vestimentaire': '👕',
        'medicale': '🏥', 'financiere': '💰', 'logement': '🏠',
        'professionnelle': '💼', 'psychologique': '💬', 'autre': '🔧'
    };
    return icons[cat] || '📁';
}

function renderGouvernoratsStats() {
    const gouvernorats = {};
    
    window.allDemandes.forEach(demande => {
        const gouv = demande.gouvernorat;
        if (!gouvernorats[gouv]) {
            gouvernorats[gouv] = { total: 0, urgentes: 0, enAttente: 0, traitees: 0 };
        }
        gouvernorats[gouv].total++;
        if (demande.urgence === 'tres-urgent') gouvernorats[gouv].urgentes++;
        if (demande.statut === 'nouveau') gouvernorats[gouv].enAttente++;
        if (demande.statut === 'traite') gouvernorats[gouv].traitees++;
    });
    
    const tbody = document.getElementById('gouvernoratsTableBody');
    tbody.innerHTML = '';
    
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

// ==========================================
// CARTE TUNISIE
// ==========================================

function renderCarte() {
    const gouvernorats = {};
    
    window.allDemandes.forEach(demande => {
        const gouv = demande.gouvernorat;
        gouvernorats[gouv] = (gouvernorats[gouv] || 0) + 1;
    });
    
    // Markers sur la carte
    const markersContainer = document.getElementById('carteMarkers');
    markersContainer.innerHTML = '';
    
    Object.entries(gouvernorats).forEach(([gouv, count]) => {
        const coords = gouvernoratCoords[gouv];
        if (!coords) return;
        
        const marker = document.createElement('div');
        marker.className = 'carte-marker ' + getMarkerClass(count);
        marker.style.left = coords.x + '%';
        marker.style.top = coords.y + '%';
        marker.innerHTML = `
            ${count}
            <div class="marker-tooltip">${gouv}: ${count} demandes</div>
        `;
        marker.onclick = () => filterByGouvernorat(gouv);
        markersContainer.appendChild(marker);
    });
    
    // Top 5
    const topDiv = document.getElementById('topGouvernorats');
    const sorted = Object.entries(gouvernorats).sort((a, b) => b[1] - a[1]).slice(0, 5);
    const maxCount = sorted[0] ? sorted[0][1] : 1;
    
    topDiv.innerHTML = '';
    sorted.forEach(([gouv, count]) => {
        const item = document.createElement('div');
        item.className = 'top-gouvernorat-item';
        item.innerHTML = `
            <div class="top-gouvernorat-header">
                <span class="gouvernorat-name">${gouv}</span>
                <span class="gouvernorat-count">${count}</span>
            </div>
            <div class="gouvernorat-bar">
                <div class="gouvernorat-bar-fill" style="width: ${(count / maxCount) * 100}%"></div>
            </div>
        `;
        topDiv.appendChild(item);
    });
    
    // Grille détaillée
    const gridDiv = document.getElementById('gouvernoratGrid');
    gridDiv.innerHTML = '';
    
    Object.entries(gouvernorats).sort((a, b) => b[1] - a[1]).forEach(([gouv, count]) => {
        const card = document.createElement('div');
        card.className = 'gouvernorat-card';
        card.innerHTML = `
            <div class="gouvernorat-card-header">${gouv}</div>
            <div class="gouvernorat-card-count">${count}</div>
            <div class="gouvernorat-card-label">demandes</div>
        `;
        card.onclick = () => filterByGouvernorat(gouv);
        gridDiv.appendChild(card);
    });
}

function getMarkerClass(count) {
    if (count > 20) return 'hot';
    if (count > 10) return 'warm';
    if (count > 5) return 'cool';
    return 'cold';
}

// ==========================================
// EXPORT DONNÉES
// ==========================================

function exportToExcel(type) {
    const data = type === 'filtered' ? filteredDemandes : window.allDemandes;
    
    if (data.length === 0) {
        showNotification('Aucune donnée à exporter', 'error');
        return;
    }
    
    const ws_data = [
        ['ID', 'Date', 'Nom', 'Âge', 'Gouvernorat', 'Ville', 'Type d\'aide', 'Urgence', 'Statut', 'Téléphone', 'Email']
    ];
    
    data.forEach(d => {
        ws_data.push([
            d.id,
            d.date_soumission,
            d.anonyme ? 'Anonyme' : d.nom,
            d.age,
            d.gouvernorat,
            d.ville,
            d.categories_aide,
            d.urgence,
            d.statut,
            d.telephone,
            d.email || ''
        ]);
    });
    
    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.aoa_to_sheet(ws_data);
    XLSX.utils.book_append_sheet(wb, ws, 'Demandes');
    
    const filename = `sparkmind_demandes_${type}_${new Date().toISOString().split('T')[0]}.xlsx`;
    XLSX.writeFile(wb, filename);
    
    addExportHistory('Excel', filename, data.length);
    showNotification(`✅ Export Excel réussi (${data.length} demandes)`, 'success');
}

function exportToCSV(type) {
    const data = type === 'filtered' ? filteredDemandes : window.allDemandes;
    
    if (data.length === 0) {
        showNotification('Aucune donnée à exporter', 'error');
        return;
    }
    
    let csv = 'ID,Date,Nom,Âge,Gouvernorat,Ville,Type,Urgence,Statut,Téléphone,Email\n';
    
    data.forEach(d => {
        csv += `${d.id},"${d.date_soumission}","${d.anonyme ? 'Anonyme' : d.nom}",${d.age},"${d.gouvernorat}","${d.ville}","${d.categories_aide}","${d.urgence}","${d.statut}","${d.telephone}","${d.email || ''}"\n`;
    });
    
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    const filename = `sparkmind_demandes_${type}_${new Date().toISOString().split('T')[0]}.csv`;
    
    link.setAttribute('href', url);
    link.setAttribute('download', filename);
    link.click();
    
    addExportHistory('CSV', filename, data.length);
    showNotification(`✅ Export CSV réussi (${data.length} demandes)`, 'success');
}

function exportToPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(20);
    doc.text('SparkMind - Rapport des Demandes', 20, 20);
    
    doc.setFont('helvetica', 'normal');
    doc.setFontSize(12);
    doc.text(`Date: ${new Date().toLocaleDateString('fr-FR')}`, 20, 30);
    doc.text(`Total demandes: ${window.allDemandes.length}`, 20, 40);
    
    const urgentes = window.allDemandes.filter(d => d.urgence === 'tres-urgent').length;
    const enAttente = window.allDemandes.filter(d => d.statut === 'nouveau').length;
    const traitees = window.allDemandes.filter(d => d.statut === 'traite').length;
    
    doc.text(`Urgentes: ${urgentes}`, 20, 50);
    doc.text(`En attente: ${enAttente}`, 20, 60);
    doc.text(`Traitées: ${traitees}`, 20, 70);
    
    const filename = `sparkmind_rapport_${new Date().toISOString().split('T')[0]}.pdf`;
    doc.save(filename);
    
    addExportHistory('PDF', filename, window.allDemandes.length);
    showNotification('✅ Rapport PDF généré', 'success');
}

function exportStatistics() {
    const stats = {
        total: window.allDemandes.length,
        urgentes: window.allDemandes.filter(d => d.urgence === 'tres-urgent').length,
        enAttente: window.allDemandes.filter(d => d.statut === 'nouveau').length,
        traitees: window.allDemandes.filter(d => d.statut === 'traite').length,
        parGouvernorat: {},
        parCategorie: {}
    };
    
    window.allDemandes.forEach(d => {
        stats.parGouvernorat[d.gouvernorat] = (stats.parGouvernorat[d.gouvernorat] || 0) + 1;
    });
    
    const json = JSON.stringify(stats, null, 2);
    const blob = new Blob([json], { type: 'application/json' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    const filename = `sparkmind_stats_${new Date().toISOString().split('T')[0]}.json`;
    
    link.setAttribute('href', url);
    link.setAttribute('download', filename);
    link.click();
    
    addExportHistory('Statistiques', filename, window.allDemandes.length);
    showNotification('✅ Statistiques exportées', 'success');
}

function exportCustom() {
    const periode = document.getElementById('exportPeriode').value;
    const statut = document.getElementById('exportStatut').value;
    const urgence = document.getElementById('exportUrgence').value;
    const gouvernorat = document.getElementById('exportGouvernorat').value;
    
    let filtered = [...window.allDemandes];
    
    // Filtrer par période
    if (periode !== 'all') {
        const now = new Date();
        let startDate;
        
        if (periode === 'today') {
            startDate = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        } else if (periode === 'week') {
            startDate = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
        } else if (periode === 'month') {
            startDate = new Date(now.getFullYear(), now.getMonth(), 1);
        } else if (periode === 'year') {
            startDate = new Date(now.getFullYear(), 0, 1);
        }
        
        if (startDate) {
            filtered = filtered.filter(d => new Date(d.date_soumission) >= startDate);
        }
    }
    
    // Autres filtres
    if (statut !== 'all') filtered = filtered.filter(d => d.statut === statut);
    if (urgence !== 'all') filtered = filtered.filter(d => d.urgence === urgence);
    if (gouvernorat !== 'all') filtered = filtered.filter(d => d.gouvernorat === gouvernorat);
    
    if (filtered.length === 0) {
        showNotification('Aucune donnée correspondant aux filtres', 'error');
        return;
    }
    
    filteredDemandes = filtered;
    exportToExcel('filtered');
}

function fillExportFilters() {
    const gouvSelect = document.getElementById('exportGouvernorat');
    const gouvernorats = [...new Set(window.allDemandes.map(d => d.gouvernorat))].sort();
    
    gouvSelect.innerHTML = '<option value="all">Tous</option>';
    gouvernorats.forEach(gouv => {
        const option = document.createElement('option');
        option.value = gouv;
        option.textContent = gouv;
        gouvSelect.appendChild(option);
    });
}

function addExportHistory(type, filename, count) {
    const history = {
        type: type,
        filename: filename,
        count: count,
        date: new Date().toLocaleString('fr-FR')
    };
    
    exportHistory.unshift(history);
    if (exportHistory.length > 10) exportHistory.pop();
    
    localStorage.setItem('exportHistory', JSON.stringify(exportHistory));
}

function loadExportHistory() {
    const saved = localStorage.getItem('exportHistory');
    if (saved) {
        exportHistory = JSON.parse(saved);
    }
}

// ==========================================
// FILTRES ET RECHERCHE
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
    document.getElementById('searchDemandes').value = '';
    
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

function filterByCategory(category) {
    document.getElementById('section-categories').classList.remove('active');
    document.getElementById('section-demandes').classList.add('active');
    
    document.querySelectorAll('.menu-item').forEach(item => item.classList.remove('active'));
    document.querySelector('.menu-item[href="#demandes"]').classList.add('active');
    
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

function filterByGouvernorat(gouvernorat) {
    const activeSection = document.querySelector('.content-section.active');
    if (activeSection.id !== 'section-demandes') {
        document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
        document.getElementById('section-demandes').classList.add('active');
        
        document.querySelectorAll('.menu-item').forEach(item => item.classList.remove('active'));
        document.querySelector('.menu-item[href="#demandes"]').classList.add('active');
    }
    
    filteredDemandes = window.allDemandes.filter(d => d.gouvernorat === gouvernorat);
    currentPage = 1;
    displayDemandes();
    showNotification(`Filtré par: ${gouvernorat}`, 'info');
}

// ==========================================
// PAGINATION
// ==========================================

function updatePagination() {
    const totalPages = Math.ceil(filteredDemandes.length / demandesPerPage);
    const pagination = document.getElementById('paginationDemandes');
    
    if (!pagination) return;
    
    pagination.innerHTML = '';
    
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
// ACTIONS SUR LES DEMANDES
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
// UTILITAIRES MODAL
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
        animation: slideIn 0.3s ease;
    `;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

console.log('✅ Back Office SparkMind initialisé - Version Complète !');