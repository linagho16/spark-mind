// Configuration de l'API
const API_BASE = '../../controllers/ReponseController.php';
const DEMANDE_API = '../../controllers/DemandeController.php';

// Variables globales
let currentPage = 1;
let itemsPerPage = 10;
let allDemandes = [];
let filteredDemandes = [];
let currentDemandeId = null;
let reponseToDelete = null;
let reponsesCount = {}; // Stocke le nombre de réponses par demande

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    console.log('🚀 Initialisation de la page réponses...');
    initializeEventListeners();
    loadAllData();
    
    // Actualisation automatique toutes les 30 secondes
    setInterval(() => {
        loadAllData();
    }, 30000);
});

// Initialiser tous les écouteurs d'événements
function initializeEventListeners() {
    // Recherche
    document.getElementById('searchInput').addEventListener('input', handleSearch);
    
    // Filtres
    document.getElementById('filterStatut').addEventListener('change', applyFilters);
    document.getElementById('filterAvecReponse').addEventListener('change', applyFilters);
    
    // Boutons
    document.getElementById('btnReset').addEventListener('click', resetFilters);
    document.getElementById('btnRefresh').addEventListener('click', () => {
        loadAllData();
    });
    document.getElementById('btnPrev').addEventListener('click', () => changePage(-1));
    document.getElementById('btnNext').addEventListener('click', () => changePage(1));
    
    // Formulaire d'ajout de réponse
    document.getElementById('formAddReponse').addEventListener('submit', handleAddReponse);
    
    // Confirmation de suppression
    document.getElementById('confirmDeleteReponse').addEventListener('click', handleDeleteReponse);
}

// Charger toutes les données (demandes + réponses)
async function loadAllData() {
    console.log('📊 Chargement des données...');
    await Promise.all([
        loadDemandes(),
        loadReponsesCount(),
        loadStatistics()
    ]);
}

// Charger le compteur de réponses pour chaque demande
async function loadReponsesCount() {
    try {
        const response = await fetch(`${API_BASE}?action=getAll`);
        const data = await response.json();
        
        if (data.success && data.reponses) {
            // Compter les réponses par demande
            reponsesCount = {};
            data.reponses.forEach(reponse => {
                if (!reponsesCount[reponse.demande_id]) {
                    reponsesCount[reponse.demande_id] = 0;
                }
                reponsesCount[reponse.demande_id]++;
            });
            
            console.log('✅ Compteur de réponses chargé:', reponsesCount);
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
            // Ajouter le nombre de réponses à chaque demande
            allDemandes = data.demandes.map(demande => {
                return {
                    ...demande,
                    nb_reponses: reponsesCount[demande.id] || 0
                };
            });
            
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
        
        console.log('📊 Statistiques reçues:', data);
        
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

// Gérer la recherche
function handleSearch() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    
    filteredDemandes = allDemandes.filter(demande => {
        return (
            demande.id.toString().includes(searchTerm) ||
            demande.nom.toLowerCase().includes(searchTerm) ||
            demande.email.toLowerCase().includes(searchTerm) ||
            demande.gouvernorat.toLowerCase().includes(searchTerm)
        );
    });
    
    currentPage = 1;
    displayDemandes();
}

// Appliquer les filtres
function applyFilters() {
    const statutFilter = document.getElementById('filterStatut').value;
    const reponseFilter = document.getElementById('filterAvecReponse').value;
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    
    filteredDemandes = allDemandes.filter(demande => {
        // Filtre de recherche
        const matchSearch = !searchTerm || 
            demande.id.toString().includes(searchTerm) ||
            demande.nom.toLowerCase().includes(searchTerm) ||
            demande.email.toLowerCase().includes(searchTerm) ||
            demande.gouvernorat.toLowerCase().includes(searchTerm);
        
        // Filtre de statut
        const matchStatut = !statutFilter || demande.statut === statutFilter;
        
        // Filtre avec/sans réponse
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

// Réinitialiser les filtres
function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterStatut').value = '';
    document.getElementById('filterAvecReponse').value = '';
    filteredDemandes = [...allDemandes];
    currentPage = 1;
    displayDemandes();
    showNotification('Filtres réinitialisés', 'info');
}

// Afficher les demandes
function displayDemandes() {
    const tbody = document.getElementById('tableBody');
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const pageData = filteredDemandes.slice(startIndex, endIndex);
    
    console.log('📋 Affichage des demandes:', pageData.length);
    
    if (pageData.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 40px;">Aucune demande trouvée</td></tr>';
        updatePagination();
        return;
    }
    
    tbody.innerHTML = pageData.map(demande => `
        <tr>
            <td>#${demande.id}</td>
            <td>
                <strong>${demande.nom}</strong><br>
                <small>${demande.email}</small>
            </td>
            <td>
                <span class="badge badge-${demande.statut.replace('_', '-')}">${formatStatut(demande.statut)}</span>
            </td>
            <td>
                <span class="badge badge-${getUrgenceClass(demande.urgence)}">${formatUrgence(demande.urgence)}</span>
            </td>
            <td>${formatDate(demande.date_soumission)}</td>
            <td>
                <span class="reponse-count">
                    💬 ${demande.nb_reponses} réponse(s)
                </span>
            </td>
            <td>
                <div class="actions">
                    <button class="btn-action btn-voir" onclick="viewReponses(${demande.id})">
                        👁️ Voir
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
    
    updatePagination();
}

// Changer de page
function changePage(direction) {
    const totalPages = Math.ceil(filteredDemandes.length / itemsPerPage);
    const newPage = currentPage + direction;
    
    if (newPage >= 1 && newPage <= totalPages) {
        currentPage = newPage;
        displayDemandes();
    }
}

// Mettre à jour la pagination
function updatePagination() {
    const totalPages = Math.ceil(filteredDemandes.length / itemsPerPage);
    document.getElementById('pageInfo').textContent = `Page ${currentPage} sur ${totalPages || 1}`;
    document.getElementById('btnPrev').disabled = currentPage === 1;
    document.getElementById('btnNext').disabled = currentPage === totalPages || totalPages === 0;
}

// Voir les réponses d'une demande
async function viewReponses(demandeId) {
    console.log('👁️ Ouverture des réponses pour la demande:', demandeId);
    currentDemandeId = demandeId;
    
    try {
        // Charger les informations de la demande
        const demandeResponse = await fetch(`${DEMANDE_API}?action=getOne&id=${demandeId}`);
        const demandeData = await demandeResponse.json();
        
        if (!demandeData.success) {
            showNotification('Erreur lors du chargement de la demande', 'error');
            return;
        }
        
        const demande = demandeData.demande;
        
        // Remplir les informations de la demande
        document.getElementById('modalDemandeId').textContent = demande.id;
        document.getElementById('infoNom').textContent = demande.nom;
        document.getElementById('infoEmail').textContent = demande.email;
        document.getElementById('infoTelephone').textContent = demande.telephone;
        document.getElementById('infoUrgence').innerHTML = `<span class="badge badge-${getUrgenceClass(demande.urgence)}">${formatUrgence(demande.urgence)}</span>`;
        document.getElementById('infoDescription').textContent = demande.description_situation;
        
        // Préparer le formulaire
        document.getElementById('reponseDemandeId').value = demandeId;
        document.getElementById('reponseAdmin').value = '';
        document.getElementById('reponseMessage').value = '';
        document.getElementById('reponseStatut').value = '';
        
        // Charger les réponses
        await loadReponses(demandeId);
        
        // Ouvrir le modal
        openModal('modalReponses');
    } catch (error) {
        console.error('❌ Erreur:', error);
        showNotification('Erreur de connexion', 'error');
    }
}

// Charger les réponses d'une demande
async function loadReponses(demandeId) {
    console.log('📥 Chargement des réponses pour la demande:', demandeId);
    
    try {
        const response = await fetch(`${API_BASE}?action=getByDemande&demande_id=${demandeId}`);
        const data = await response.json();
        
        console.log('📨 Réponses reçues:', data);
        
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

// Gérer l'ajout d'une réponse
async function handleAddReponse(e) {
    e.preventDefault();
    
    const demandeId = document.getElementById('reponseDemandeId').value;
    const admin = document.getElementById('reponseAdmin').value.trim();
    const message = document.getElementById('reponseMessage').value.trim();
    const nouveauStatut = document.getElementById('reponseStatut').value;
    
    console.log('📤 Envoi de la réponse:', {demandeId, admin, message, nouveauStatut});
    
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
        console.log('✉️ Résultat création réponse:', data);
        
        if (data.success) {
            showNotification('✅ Réponse ajoutée avec succès', 'success');
            
            // Mettre à jour le statut si nécessaire
            if (nouveauStatut) {
                await updateDemandeStatut(demandeId, nouveauStatut);
            }
            
            // Recharger les réponses
            await loadReponses(demandeId);
            
            // Réinitialiser le formulaire
            document.getElementById('reponseAdmin').value = '';
            document.getElementById('reponseMessage').value = '';
            document.getElementById('reponseStatut').value = '';
            
            // Recharger toutes les données
            await loadAllData();
        } else {
            showNotification(data.message || 'Erreur lors de l\'ajout de la réponse', 'error');
        }
    } catch (error) {
        console.error('❌ Erreur:', error);
        showNotification('Erreur de connexion', 'error');
    }
}

// Mettre à jour le statut d'une demande
async function updateDemandeStatut(demandeId, nouveauStatut) {
    try {
        const formData = new FormData();
        formData.append('id', demandeId);
        formData.append('statut', nouveauStatut);
        
        await fetch(`${DEMANDE_API}?action=updateStatus`, {
            method: 'POST',
            body: formData
        });
        
        console.log('✅ Statut mis à jour');
    } catch (error) {
        console.error('❌ Erreur mise à jour statut:', error);
    }
}

// Confirmer la suppression d'une réponse
function confirmDeleteReponse(reponseId) {
    reponseToDelete = reponseId;
    openModal('modalDeleteReponse');
}

// Supprimer une réponse
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
            
            // Recharger les réponses
            await loadReponses(currentDemandeId);
            
            // Recharger toutes les données
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

// Ouvrir un modal
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

// Fermer un modal
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.remove('show');
    document.body.style.overflow = 'auto';
}

// Fermer les modals en cliquant en dehors
window.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal')) {
        closeModal(e.target.id);
    }
});

// Afficher une notification
function showNotification(message, type = 'info') {
    const notification = document.getElementById('notification');
    notification.textContent = message;
    notification.className = `notification ${type} show`;
    
    setTimeout(() => {
        notification.classList.remove('show');
    }, 3000);
}

// Fonctions utilitaires de formatage
function formatStatut(statut) {
    const statuts = {
        'en_attente': 'En attente',
        'en_cours': 'En cours',
        'traite': 'Traité',
        'rejete': 'Rejeté'
    };
    return statuts[statut] || statut;
}

function formatUrgence(urgence) {
    const urgences = {
        'tres_urgent': 'Très urgent',
        'urgent': 'Urgent',
        'moyen': 'Moyen',
        'faible': 'Faible'
    };
    return urgences[urgence] || urgence;
}

function getUrgenceClass(urgence) {
    const classes = {
        'tres_urgent': 'tres-urgent',
        'urgent': 'urgent',
        'moyen': 'moyen',
        'faible': 'faible'
    };
    return classes[urgence] || 'faible';
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}