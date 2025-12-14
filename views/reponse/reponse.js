// ==========================================
// SPARKMIND - GESTION DES RÉPONSES
// Version intégrée avec back.js
// ==========================================

console.log('🚀 Réponses.js chargé - Version intégrée !');

// Configuration de l'API
const API_BASE = '/SparkMind/controllers/ReponseController.php';
const DEMANDE_API = '/SparkMind/controllers/DemandeController.php';

// Variables globales
let currentPage = 1;
let itemsPerPage = 10;
let allDemandes = [];
let filteredDemandes = [];
let currentDemandeId = null;
let reponseToDelete = null;
let reponsesCount = {};

// ==========================================
// INITIALISATION
// ==========================================

document.addEventListener('DOMContentLoaded', () => {
    console.log('✅ Initialisation de la page réponses...');
    initializeEventListeners();
    loadAllData();
    
    // Actualisation automatique toutes les 30 secondes
    setInterval(() => {
        loadAllData();
    }, 30000);
});

// Initialiser tous les écouteurs d'événements
function initializeEventListeners() {
    // Recherche avec délai
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => handleSearch(), 300);
        });
    }
    
    // Filtres
    const filterStatut = document.getElementById('filterStatut');
    const filterAvecReponse = document.getElementById('filterAvecReponse');
    if (filterStatut) filterStatut.addEventListener('change', applyFilters);
    if (filterAvecReponse) filterAvecReponse.addEventListener('change', applyFilters);
    
    // Boutons
    const btnReset = document.getElementById('btnReset');
    const btnRefresh = document.getElementById('btnRefresh');
    const btnPrev = document.getElementById('btnPrev');
    const btnNext = document.getElementById('btnNext');
    
    if (btnReset) btnReset.addEventListener('click', resetFilters);
    if (btnRefresh) btnRefresh.addEventListener('click', () => loadAllData());
    if (btnPrev) btnPrev.addEventListener('click', () => changePage(-1));
    if (btnNext) btnNext.addEventListener('click', () => changePage(1));
    
    // Formulaire d'ajout de réponse
    const formAddReponse = document.getElementById('formAddReponse');
    if (formAddReponse) {
        formAddReponse.addEventListener('submit', handleAddReponse);
    }
    
    // Confirmation de suppression
    const confirmDeleteBtn = document.getElementById('confirmDeleteReponse');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', handleDeleteReponse);
    }
}

// ==========================================
// CHARGEMENT DES DONNÉES
// ==========================================

async function loadAllData() {
    console.log('📊 Chargement des données...');
    await Promise.all([
        loadReponsesCount(),
        loadDemandes(),
        loadStatistics()
    ]);
}

// Charger le compteur de réponses pour chaque demande
async function loadReponsesCount() {
    try {
        const response = await fetch(`${API_BASE}?action=getAll`);
        const data = await response.json();
        
        if (data.success && data.reponses) {
            reponsesCount = {};
            data.reponses.forEach(reponse => {
                if (!reponsesCount[reponse.demande_id]) {
                    reponsesCount[reponse.demande_id] = 0;
                }
                reponsesCount[reponse.demande_id]++;
            });
            
            console.log('✅ Compteur de réponses chargé:', Object.keys(reponsesCount).length);
        }
    } catch (error) {
        console.error('❌ Erreur chargement compteur réponses:', error);
    }
}

// Charger toutes les demandes
async function loadDemandes() {
    try {
        const response = await fetch(`${DEMANDE_API}?action=getAll`);
        const data = await response.json();
        
        if (data.success) {
            allDemandes = data.demandes.map(demande => ({
                ...demande,
                nb_reponses: reponsesCount[demande.id] || 0
            }));
            
            filteredDemandes = [...allDemandes];
            console.log('✅ Demandes chargées:', allDemandes.length);
            applyFilters();
        } else {
            showNotification('Erreur lors du chargement des demandes', 'error');
        }
    } catch (error) {
        console.error('❌ Erreur chargement demandes:', error);
        showNotification('Erreur de connexion au serveur', 'error');
    }
}

// Charger les statistiques
async function loadStatistics() {
    try {
        const response = await fetch(`${API_BASE}?action=getStatistics`);
        const data = await response.json();
        
        if (data.success) {
            const stats = data.statistics;
            document.getElementById('totalReponses').textContent = stats.totalReponses || 0;
            document.getElementById('demandesSansReponse').textContent = stats.demandesSansReponse || 0;
            document.getElementById('demandesAvecReponse').textContent = stats.demandesAvecReponse || 0;
            document.getElementById('reponsesDuJour').textContent = stats.reponsesDuJour || 0;
            
            console.log('✅ Statistiques mises à jour');
        }
    } catch (error) {
        console.error('❌ Erreur chargement statistiques:', error);
    }
}

// ==========================================
// RECHERCHE ET FILTRES
// ==========================================

function handleSearch() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    
    filteredDemandes = allDemandes.filter(demande => {
        return (
            demande.id.toString().includes(searchTerm) ||
            (demande.nom && demande.nom.toLowerCase().includes(searchTerm)) ||
            (demande.email && demande.email.toLowerCase().includes(searchTerm)) ||
            (demande.gouvernorat && demande.gouvernorat.toLowerCase().includes(searchTerm))
        );
    });
    
    currentPage = 1;
    displayDemandes();
}

function applyFilters() {
    const statutFilter = document.getElementById('filterStatut').value;
    const reponseFilter = document.getElementById('filterAvecReponse').value;
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    
    filteredDemandes = allDemandes.filter(demande => {
        const matchSearch = !searchTerm || 
            demande.id.toString().includes(searchTerm) ||
            (demande.nom && demande.nom.toLowerCase().includes(searchTerm)) ||
            (demande.email && demande.email.toLowerCase().includes(searchTerm)) ||
            (demande.gouvernorat && demande.gouvernorat.toLowerCase().includes(searchTerm));
        
        const matchStatut = !statutFilter || demande.statut === statutFilter;
        
        let matchReponse = true;
        if (reponseFilter === 'avec') {
            matchReponse = demande.nb_reponses > 0;
        } else if (reponseFilter === 'sans') {
            matchReponse = demande.nb_reponses === 0;
        }
        
        return matchSearch && matchStatut && matchReponse;
    });
    
    currentPage = 1;
    displayDemandes();
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterStatut').value = '';
    document.getElementById('filterAvecReponse').value = '';
    filteredDemandes = [...allDemandes];
    currentPage = 1;
    displayDemandes();
    showNotification('Filtres réinitialisés', 'info');
}

// ==========================================
// AFFICHAGE DES DEMANDES
// ==========================================

function displayDemandes() {
    const tbody = document.getElementById('tableBody');
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const pageData = filteredDemandes.slice(startIndex, endIndex);
    
    if (pageData.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px;">
                    <div style="font-size: 3em;">📭</div>
                    <p>Aucune demande trouvée</p>
                </td>
            </tr>
        `;
        updatePagination();
        return;
    }
    
    tbody.innerHTML = pageData.map(demande => `
        <tr>
            <td>#${demande.id}</td>
            <td>
                <strong>${demande.anonyme ? 'Anonyme' : demande.nom}</strong><br>
                <small style="color: #666;">${demande.email || 'N/A'}</small>
            </td>
            <td>
                <span class="status-badge ${demande.statut}">${formatStatut(demande.statut)}</span>
            </td>
            <td>
                <span class="urgence-badge ${demande.urgence}">${formatUrgence(demande.urgence)}</span>
            </td>
            <td>${formatDate(demande.date_soumission)}</td>
            <td>
                <span class="reponse-count">
                    💬 ${demande.nb_reponses} réponse(s)
                </span>
            </td>
            <td>
                <div class="actions">
                    <button class="btn-action btn-voir" onclick="viewReponses(${demande.id})" title="Voir les réponses">
                        👁️
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
    
    updatePagination();
}

// ==========================================
// PAGINATION
// ==========================================

function changePage(direction) {
    const totalPages = Math.ceil(filteredDemandes.length / itemsPerPage);
    const newPage = currentPage + direction;
    
    if (newPage >= 1 && newPage <= totalPages) {
        currentPage = newPage;
        displayDemandes();
    }
}

function updatePagination() {
    const totalPages = Math.ceil(filteredDemandes.length / itemsPerPage);
    const pageInfo = document.getElementById('pageInfo');
    const btnPrev = document.getElementById('btnPrev');
    const btnNext = document.getElementById('btnNext');
    
    if (pageInfo) {
        pageInfo.textContent = `Page ${currentPage} sur ${totalPages || 1}`;
    }
    
    if (btnPrev) btnPrev.disabled = currentPage === 1;
    if (btnNext) btnNext.disabled = currentPage === totalPages || totalPages === 0;
}

// ==========================================
// GESTION DES RÉPONSES
// ==========================================

window.viewReponses = async function(demandeId) {
    console.log('👁️ Ouverture des réponses pour la demande:', demandeId);
    currentDemandeId = demandeId;
    
    try {
        const demandeResponse = await fetch(`${DEMANDE_API}?action=getOne&id=${demandeId}`);
        const demandeData = await demandeResponse.json();
        
        if (!demandeData.success) {
            showNotification('Erreur lors du chargement de la demande', 'error');
            return;
        }
        
        const demande = demandeData.demande;
        
        document.getElementById('modalDemandeId').textContent = demande.id;
        document.getElementById('infoNom').textContent = demande.anonyme ? 'Anonyme' : demande.nom;
        document.getElementById('infoEmail').textContent = demande.email || 'Non fourni';
        document.getElementById('infoTelephone').textContent = demande.telephone || 'Non fourni';
        document.getElementById('infoUrgence').innerHTML = `<span class="urgence-badge ${demande.urgence}">${formatUrgence(demande.urgence)}</span>`;
        document.getElementById('infoDescription').textContent = demande.description_situation || 'Non renseignée';
        
        document.getElementById('reponseDemandeId').value = demandeId;
        document.getElementById('reponseAdmin').value = '';
        document.getElementById('reponseMessage').value = '';
        document.getElementById('reponseStatut').value = '';
        
        await loadReponses(demandeId);
        openModal('modalReponses');
    } catch (error) {
        console.error('❌ Erreur:', error);
        showNotification('Erreur de connexion', 'error');
    }
};

async function loadReponses(demandeId) {
    console.log('📥 Chargement des réponses pour la demande:', demandeId);
    
    try {
        const response = await fetch(`${API_BASE}?action=getByDemande&demande_id=${demandeId}`);
        const data = await response.json();
        
        if (data.success) {
            const reponses = data.reponses;
            document.getElementById('nbReponses').textContent = reponses.length;
            
            const container = document.getElementById('reponsesContainer');
            
            if (reponses.length === 0) {
                container.innerHTML = '<div class="no-reponses">📭 Aucune réponse pour le moment</div>';
            } else {
                container.innerHTML = reponses.map(reponse => `
                    <div class="reponse-item">
                        <div class="reponse-header">
                            <span class="reponse-admin">👤 ${reponse.administrateur}</span>
                            <span class="reponse-date">📅 ${formatDate(reponse.date_reponse)}</span>
                        </div>
                        <div class="reponse-message">${reponse.message}</div>
                        <div class="reponse-actions">
                            <button class="btn-delete-reponse" onclick="confirmDeleteReponse(${reponse.id})">
                                🗑️ Supprimer
                            </button>
                        </div>
                    </div>
                `).join('');
            }
        }
    } catch (error) {
        console.error('❌ Erreur chargement réponses:', error);
    }
}

async function handleAddReponse(e) {
    e.preventDefault();
    
    const demandeId = document.getElementById('reponseDemandeId').value;
    const admin = document.getElementById('reponseAdmin').value.trim();
    const message = document.getElementById('reponseMessage').value.trim();
    const nouveauStatut = document.getElementById('reponseStatut').value;
    
    if (!admin || !message) {
        showNotification('Veuillez remplir tous les champs obligatoires', 'error');
        return;
    }
    
    try {
        const formData = new FormData();
        formData.append('demande_id', demandeId);
        formData.append('administrateur', admin);
        formData.append('message', message);
        
        const response = await fetch(`${API_BASE}?action=create`, {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('✅ Réponse ajoutée avec succès', 'success');
            
            if (nouveauStatut) {
                await updateDemandeStatut(demandeId, nouveauStatut);
            }
            
            await loadReponses(demandeId);
            
            document.getElementById('reponseAdmin').value = '';
            document.getElementById('reponseMessage').value = '';
            document.getElementById('reponseStatut').value = '';
            
            await loadAllData();
        } else {
            showNotification(data.message || 'Erreur lors de l\'ajout de la réponse', 'error');
        }
    } catch (error) {
        console.error('❌ Erreur:', error);
        showNotification('Erreur de connexion', 'error');
    }
}

async function updateDemandeStatut(demandeId, nouveauStatut) {
    try {
        const response = await fetch(`${DEMANDE_API}?action=updateStatus`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: demandeId, statut: nouveauStatut })
        });
        
        const data = await response.json();
        console.log('✅ Statut mis à jour:', data);
    } catch (error) {
        console.error('❌ Erreur mise à jour statut:', error);
    }
}

window.confirmDeleteReponse = function(reponseId) {
    reponseToDelete = reponseId;
    openModal('modalDeleteReponse');
};

async function handleDeleteReponse() {
    if (!reponseToDelete) return;
    
    console.log('🗑️ Suppression de la réponse:', reponseToDelete);
    
    try {
        const response = await fetch(`${API_BASE}?action=delete&id=${reponseToDelete}`, {
            method: 'DELETE'
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('✅ Réponse supprimée avec succès', 'success');
            closeModal('modalDeleteReponse');
            await loadReponses(currentDemandeId);
            await loadAllData();
        } else {
            showNotification(data.message || 'Erreur lors de la suppression', 'error');
        }
    } catch (error) {
        console.error('❌ Erreur:', error);
        showNotification('Erreur de connexion', 'error');
    }
    
    reponseToDelete = null;
}

// ==========================================
// UTILITAIRES MODAL
// ==========================================

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = 'auto';
    }
};

// Fermer les modals en cliquant en dehors
window.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal')) {
        closeModal(e.target.id);
    }
});

// ==========================================
// NOTIFICATION
// ==========================================

function showNotification(message, type = 'info') {
    const notification = document.getElementById('notification');
    if (notification) {
        notification.textContent = message;
        notification.className = `notification ${type} show`;
        
        setTimeout(() => {
            notification.classList.remove('show');
        }, 3000);
    }
}

// ==========================================
// FONCTIONS DE FORMATAGE
// ==========================================

function formatStatut(statut) {
    const statuts = {
        'nouveau': 'Nouveau',
        'en-cours': 'En cours',
        'traite': 'Traité',
        'refuse': 'Refusé'
    };
    return statuts[statut] || statut;
}

function formatUrgence(urgence) {
    const urgences = {
        'tres-urgent': '🔴 Très urgent',
        'urgent': '🟠 Urgent',
        'important': '🟡 Important',
        'peut-attendre': '🟢 Peut attendre'
    };
    return urgences[urgence] || urgence;
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString('fr-FR', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    } catch (e) {
        return dateString;
    }
}

console.log('✅ Gestion des Réponses initialisée - Version Intégrée !');