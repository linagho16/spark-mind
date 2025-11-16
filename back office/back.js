// ==========================================
// SPARKMIND - BACK.JS
// Gestion du Back Office
// ==========================================

document.addEventListener('DOMContentLoaded', function() {
    
    // Variables globales
    let allDemandes = [];
    let filteredDemandes = [];
    let currentPage = 1;
    const demandesPerPage = 10;
    
    // ==========================================
    // 1. CHARGEMENT DES DEMANDES
    // ==========================================
    
    function loadDemandes() {
        // Afficher un loader
        showLoader();
        
        fetch('../controllers/DemandeController.php?action=getAll')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    allDemandes = data.demandes || [];
                    filteredDemandes = [...allDemandes];
                    displayDemandes();
                    updateStatistics();
                } else {
                    console.error('Erreur:', data.message);
                    showNotification('Erreur lors du chargement des demandes', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showNotification('Erreur de connexion au serveur', 'error');
            })
            .finally(() => {
                hideLoader();
            });
    }
    
    // ==========================================
    // 2. AFFICHAGE DES DEMANDES
    // ==========================================
    
    function displayDemandes() {
        const tbody = document.querySelector('.demandes-table tbody');
        if (!tbody) return;
        
        // Calculer les indices pour la pagination
        const startIndex = (currentPage - 1) * demandesPerPage;
        const endIndex = startIndex + demandesPerPage;
        const demandesPage = filteredDemandes.slice(startIndex, endIndex);
        
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
        
        // Mettre à jour la pagination
        updatePagination();
    }
    
    function createDemandeRow(demande) {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>#${demande.id}</td>
            <td>${formatDate(demande.date_soumission)}</td>
            <td>${demande.anonyme ? 'Anonyme' : demande.nom}</td>
            <td>${demande.gouvernorat}</td>
            <td>${createTypeBadges(demande.categories_aide)}</td>
            <td>${createUrgenceBadge(demande.urgence)}</td>
            <td>${createStatusBadge(demande.statut || 'nouveau')}</td>
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
    
    // ==========================================
    // 3. CRÉATION DES BADGES
    // ==========================================
    
    function createTypeBadges(categories) {
        if (!categories || categories.length === 0) return '-';
        
        const typeIcons = {
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
        
        const typeLabels = {
            'alimentaire': 'Alimentaire',
            'scolaire': 'Scolaire',
            'vestimentaire': 'Vestimentaire',
            'medicale': 'Médicale',
            'financiere': 'Financière',
            'logement': 'Logement',
            'professionnelle': 'Professionnelle',
            'psychologique': 'Psychologique',
            'autre': 'Autre'
        };
        
        // Afficher seulement le premier type avec un compteur si plusieurs
        const firstType = Array.isArray(categories) ? categories[0] : categories;
        const icon = typeIcons[firstType] || '📋';
        const label = typeLabels[firstType] || firstType;
        const count = Array.isArray(categories) ? categories.length : 1;
        const extraText = count > 1 ? ` (+${count - 1})` : '';
        
        return `<span class="type-badge ${firstType}">${icon} ${label}${extraText}</span>`;
    }
    
    function createUrgenceBadge(urgence) {
        const urgenceConfig = {
            'tres-urgent': { icon: '🔴', label: 'Très urgent' },
            'urgent': { icon: '🟠', label: 'Urgent' },
            'important': { icon: '🟡', label: 'Important' },
            'peut-attendre': { icon: '🟢', label: 'Peut attendre' }
        };
        
        const config = urgenceConfig[urgence] || urgenceConfig['important'];
        return `<span class="urgence-badge ${urgence}">${config.icon} ${config.label}</span>`;
    }
    
    function createStatusBadge(statut) {
        const statusConfig = {
            'nouveau': { label: 'Nouveau' },
            'en-cours': { label: 'En cours' },
            'traite': { label: 'Traité' },
            'refuse': { label: 'Refusé' }
        };
        
        const config = statusConfig[statut] || statusConfig['nouveau'];
        return `<span class="status-badge ${statut}">${config.label}</span>`;
    }
    
    // ==========================================
    // 4. MODAL - AFFICHAGE DES DÉTAILS
    // ==========================================
    
    window.viewDemande = function(id) {
        const demande = allDemandes.find(d => d.id == id);
        if (!demande) {
            showNotification('Demande introuvable', 'error');
            return;
        }
        
        const modal = document.getElementById('detailsModal');
        if (!modal) return;
        
        // Remplir le modal avec les données
        modal.querySelector('.modal-header h2').textContent = `Détails de la Demande #${demande.id}`;
        
        // Informations personnelles
        const detailsHTML = `
            <div class="detail-section">
                <h3>Informations Personnelles</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <strong>Nom complet:</strong>
                        <span>${demande.anonyme ? 'Utilisateur anonyme' : demande.nom}</span>
                    </div>
                    <div class="detail-item">
                        <strong>Âge:</strong>
                        <span>${demande.age} ans</span>
                    </div>
                    <div class="detail-item">
                        <strong>Gouvernorat:</strong>
                        <span>${demande.gouvernorat}</span>
                    </div>
                    <div class="detail-item">
                        <strong>Ville:</strong>
                        <span>${demande.ville}</span>
                    </div>
                    <div class="detail-item">
                        <strong>Situation familiale:</strong>
                        <span>${demande.situation || 'Non précisée'}</span>
                    </div>
                </div>
            </div>
            
            <div class="detail-section">
                <h3>Type d'Aide</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <strong>Catégorie(s):</strong>
                        <span>${Array.isArray(demande.categories_aide) ? demande.categories_aide.join(', ') : demande.categories_aide}</span>
                    </div>
                    <div class="detail-item">
                        <strong>Urgence:</strong>
                        <span>${createUrgenceBadge(demande.urgence)}</span>
                    </div>
                </div>
            </div>
            
            <div class="detail-section">
                <h3>Description</h3>
                <div class="detail-description">
                    <p><strong>Situation:</strong></p>
                    <p>${demande.description_situation}</p>
                    
                    <p><strong>Demande exacte:</strong></p>
                    <p>${demande.demande_exacte}</p>
                </div>
            </div>
            
            <div class="detail-section">
                <h3>Contact</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <strong>Téléphone:</strong>
                        <span>${demande.telephone}</span>
                    </div>
                    <div class="detail-item">
                        <strong>Email:</strong>
                        <span>${demande.email || 'Non fourni'}</span>
                    </div>
                    <div class="detail-item">
                        <strong>Préférence:</strong>
                        <span>${demande.preference_contact}</span>
                    </div>
                    <div class="detail-item">
                        <strong>Disponibilité:</strong>
                        <span>${Array.isArray(demande.horaires_disponibles) ? demande.horaires_disponibles.join(', ') : demande.horaires_disponibles}</span>
                    </div>
                </div>
            </div>
            
            <div class="detail-section">
                <h3>Confidentialité</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <strong>Visibilité:</strong>
                        <span>${demande.visibilite}</span>
                    </div>
                    <div class="detail-item">
                        <strong>Anonymat:</strong>
                        <span>${demande.anonyme ? 'Oui' : 'Non'}</span>
                    </div>
                </div>
            </div>
            
            <div class="detail-section">
                <h3>Actions de traitement</h3>
                <div class="modal-actions">
                    <select class="status-select" id="statusSelect">
                        <option value="nouveau" ${demande.statut === 'nouveau' ? 'selected' : ''}>Nouveau</option>
                        <option value="en-cours" ${demande.statut === 'en-cours' ? 'selected' : ''}>En cours</option>
                        <option value="traite" ${demande.statut === 'traite' ? 'selected' : ''}>Traité</option>
                        <option value="refuse" ${demande.statut === 'refuse' ? 'selected' : ''}>Refusé</option>
                    </select>
                    <button class="btn-save" onclick="updateStatus(${demande.id})">💾 Enregistrer le statut</button>
                    <button class="btn-contact" onclick="contactDemandeur('${demande.telephone}')">📞 Contacter</button>
                    <button class="btn-assign" onclick="assignToGroup(${demande.id})">👥 Assigner à un groupe</button>
                </div>
            </div>
        `;
        
        modal.querySelector('.modal-body').innerHTML = detailsHTML;
        
        // Afficher le modal
        modal.style.display = 'flex';
    };
    
    // Fermer le modal
    const modal = document.getElementById('detailsModal');
    if (modal) {
        const closeBtn = modal.querySelector('.modal-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });
        }
        
        // Fermer en cliquant à l'extérieur
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    }
    
    // ==========================================
    // 5. ACTIONS SUR LES DEMANDES
    // ==========================================
    
    window.editDemande = function(id) {
        const demande = allDemandes.find(d => d.id == id);
        if (!demande) return;
        
        // Ouvrir le modal avec possibilité de modification
        viewDemande(id);
        showNotification('Mode édition - Fonctionnalité à implémenter', 'info');
    };
    
    window.deleteDemande = function(id) {
        if (!confirm('⚠️ Êtes-vous sûr de vouloir supprimer cette demande?\n\nCette action est irréversible.')) {
            return;
        }
        
        fetch(`../controllers/DemandeController.php?action=delete&id=${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Demande supprimée avec succès', 'success');
                loadDemandes(); // Recharger la liste
            } else {
                showNotification('Erreur lors de la suppression: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('Erreur de connexion', 'error');
        });
    };
    
    window.updateStatus = function(id) {
        const statusSelect = document.getElementById('statusSelect');
        if (!statusSelect) return;
        
        const newStatus = statusSelect.value;
        
        fetch('../controllers/DemandeController.php?action=updateStatus', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: id,
                statut: newStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Statut mis à jour avec succès', 'success');
                loadDemandes(); // Recharger la liste
                document.getElementById('detailsModal').style.display = 'none';
            } else {
                showNotification('Erreur lors de la mise à jour: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('Erreur de connexion', 'error');
        });
    };
    
    window.contactDemandeur = function(telephone) {
        if (confirm(`📞 Voulez-vous appeler le ${telephone} ?`)) {
            window.location.href = `tel:${telephone}`;
        }
    };
    
    window.assignToGroup = function(id) {
        showNotification('Fonctionnalité "Assigner à un groupe" à implémenter', 'info');
    };
    
    // ==========================================
    // 6. FILTRES
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
        const statutFilter = document.querySelector('select[name="statut"]')?.value || '';
        const urgenceFilter = document.querySelector('select[name="urgence"]')?.value || '';
        const typeFilter = document.querySelector('select[name="type"]')?.value || '';
        const gouvernoratFilter = document.querySelector('select[name="gouvernorat"]')?.value || '';
        
        filteredDemandes = allDemandes.filter(demande => {
            let match = true;
            
            if (statutFilter && demande.statut !== statutFilter) {
                match = false;
            }
            
            if (urgenceFilter && demande.urgence !== urgenceFilter) {
                match = false;
            }
            
            if (typeFilter) {
                const categories = Array.isArray(demande.categories_aide) ? demande.categories_aide : [demande.categories_aide];
                if (!categories.includes(typeFilter)) {
                    match = false;
                }
            }
            
            if (gouvernoratFilter && demande.gouvernorat !== gouvernoratFilter) {
                match = false;
            }
            
            return match;
        });
        
        currentPage = 1;
        displayDemandes();
        showNotification(`${filteredDemandes.length} demande(s) trouvée(s)`, 'info');
    }
    
    function resetFilters() {
        // Réinitialiser tous les selects
        document.querySelectorAll('.filter-select').forEach(select => {
            select.value = '';
        });
        
        filteredDemandes = [...allDemandes];
        currentPage = 1;
        displayDemandes();
        showNotification('Filtres réinitialisés', 'info');
    }
    
    // ==========================================
    // 7. RECHERCHE
    // ==========================================
    
    const searchInput = document.querySelector('.search-box input');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performSearch(e.target.value);
            }, 300); // Attendre 300ms après la dernière frappe
        });
    }
    
    function performSearch(query) {
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
    // 8. PAGINATION
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
            if (i === currentPage) {
                pageBtn.classList.add('active');
            }
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
    // 9. STATISTIQUES
    // ==========================================
    
    function updateStatistics() {
        const urgentes = allDemandes.filter(d => d.urgence === 'tres-urgent').length;
        const enAttente = allDemandes.filter(d => d.statut === 'nouveau').length;
        const traitees = allDemandes.filter(d => d.statut === 'traite').length;
        const total = allDemandes.length;
        
        // Mettre à jour les cartes de statistiques
        const statCards = document.querySelectorAll('.stat-card');
        if (statCards[0]) statCards[0].querySelector('h3').textContent = urgentes;
        if (statCards[1]) statCards[1].querySelector('h3').textContent = enAttente;
        if (statCards[2]) statCards[2].querySelector('h3').textContent = traitees;
        if (statCards[3]) statCards[3].querySelector('h3').textContent = total;
        
        // Mettre à jour le badge dans la sidebar
        const badge = document.querySelector('.nav-item .badge');
        if (badge) {
            badge.textContent = enAttente;
        }
    }
    
    // ==========================================
    // 10. UTILITAIRES
    // ==========================================
    
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }
    
    function showNotification(message, type = 'info') {
        // Créer une notification temporaire
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
            animation: slideIn 0.3s ease;
        `;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
    
    function showLoader() {
        const loader = document.createElement('div');
        loader.id = 'loader';
        loader.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            font-size: 2em;
        `;
        loader.textContent = '⏳ Chargement...';
        document.body.appendChild(loader);
    }
    
    function hideLoader() {
        const loader = document.getElementById('loader');
        if (loader) loader.remove();
    }
    
    // ==========================================
    // 11. INITIALISATION
    // ==========================================
    
    // Charger les demandes au démarrage
    loadDemandes();
    
    // Rafraîchir automatiquement toutes les 30 secondes
    setInterval(loadDemandes, 30000);
    
    console.log('✅ SparkMind Back Office - Initialisé avec succès');
});

// Ajouter les animations CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);