// ==========================================
// SPARKMIND - BACK.JS (VERSION FINALE)
// Gestion du Back Office
// ==========================================

console.log('🚀 Back.js chargé !');

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ DOM chargé !');
    
    // Variables globales
    window.allDemandes = [];
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
                } else {
                    console.error('❌ Erreur dans les données:', data.message);
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
        
        const tbody = document.querySelector('.demandes-table tbody');
        if (!tbody) {
            console.error('❌ Table tbody non trouvée !');
            return;
        }
        
        // Calculer pagination
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
        
        const date = new Date(demande.date_soumission);
        const dateStr = date.toLocaleDateString('fr-FR');
        
        const typeAide = Array.isArray(demande.categories_aide) ? demande.categories_aide[0] : demande.categories_aide;
        const typeLabel = getTypeLabel(typeAide);
        const urgenceLabel = getUrgenceLabel(demande.urgence);
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
        
        const urgentes = window.allDemandes.filter(d => d.urgence === 'tres-urgent').length;
        const enAttente = window.allDemandes.filter(d => d.statut === 'nouveau').length;
        const traitees = window.allDemandes.filter(d => d.statut === 'traite').length;
        const total = window.allDemandes.length;
        
        console.log('Stats:', { urgentes, enAttente, traitees, total });
        
        const statCards = document.querySelectorAll('.stat-card');
        if (statCards[0]) statCards[0].querySelector('h3').textContent = urgentes;
        if (statCards[1]) statCards[1].querySelector('h3').textContent = enAttente;
        if (statCards[2]) statCards[2].querySelector('h3').textContent = traitees;
        if (statCards[3]) statCards[3].querySelector('h3').textContent = total;
        
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
        
        const prevBtn = document.createElement('button');
        prevBtn.className = 'page-btn';
        prevBtn.textContent = '« Précédent';
        prevBtn.disabled = currentPage === 1;
        prevBtn.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                displayDemandes();
            }
        });
        pagination.appendChild(prevBtn);
        
        for (let i = 1; i <= totalPages; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.className = 'page-btn';
            if (i === currentPage) pageBtn.classList.add('active');
            pageBtn.textContent = i;
            pageBtn.addEventListener('click', () => {
                currentPage = i;
                displayDemandes();
            });
            pagination.appendChild(pageBtn);
        }
        
        const nextBtn = document.createElement('button');
        nextBtn.className = 'page-btn';
        nextBtn.textContent = 'Suivant »';
        nextBtn.disabled = currentPage === totalPages || totalPages === 0;
        nextBtn.addEventListener('click', () => {
            if (currentPage < totalPages) {
                currentPage++;
                displayDemandes();
            }
        });
        pagination.appendChild(nextBtn);
    }
    
    // ==========================================
    // 5. RECHERCHE
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
        console.log('🔧 Application des filtres...');
        
        const statutSelect = document.querySelector('select[name="statut"]');
        const urgenceSelect = document.querySelector('select[name="urgence"]');
        const typeSelect = document.querySelector('select[name="type"]');
        const gouvernoratSelect = document.querySelector('select[name="gouvernorat"]');
        
        const filters = {
            statut: statutSelect ? statutSelect.value : '',
            urgence: urgenceSelect ? urgenceSelect.value : '',
            type: typeSelect ? typeSelect.value : '',
            gouvernorat: gouvernoratSelect ? gouvernoratSelect.value : ''
        };
        
        console.log('Filtres appliqués:', filters);
        
        filteredDemandes = window.allDemandes.filter(demande => {
            let match = true;
            
            if (filters.statut && demande.statut !== filters.statut) match = false;
            if (filters.urgence && demande.urgence !== filters.urgence) match = false;
            if (filters.gouvernorat && demande.gouvernorat !== filters.gouvernorat) match = false;
            
            if (filters.type) {
                const categories = Array.isArray(demande.categories_aide) ? demande.categories_aide : [demande.categories_aide];
                if (!categories.includes(filters.type)) match = false;
            }
            
            return match;
        });
        
        currentPage = 1;
        displayDemandes();
        showNotification(filteredDemandes.length + ' demande(s) trouvée(s)', 'info');
    }
    
    function resetFilters() {
        document.querySelectorAll('.filter-select').forEach(select => {
            select.value = '';
        });
        
        filteredDemandes = [...window.allDemandes];
        currentPage = 1;
        displayDemandes();
        showNotification('Filtres réinitialisés', 'info');
    }
    
    // ==========================================
    // 7. NOTIFICATIONS
    // ==========================================
    
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
            animation: slideIn 0.3s ease;
        `;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
    
    // ==========================================
    // 8. INITIALISATION
    // ==========================================
    
    console.log('🎬 Initialisation...');
    loadDemandes();
    setInterval(loadDemandes, 30000);
    console.log('✅ Back Office initialisé !');
});

// ==========================================
// ACTIONS GLOBALES
// ==========================================

window.viewDemande = function(id) {
    console.log('👁️ Voir demande:', id);
    
    const demande = window.allDemandes.find(d => d.id == id);
    if (!demande) {
        alert('❌ Demande introuvable');
        return;
    }
    
    // Récupérer la modal
    const modal = document.getElementById('detailsModal');
    if (!modal) {
        console.error('❌ Modal non trouvée !');
        return;
    }
    
    // Remplir le modal
    modal.querySelector('.modal-header h2').textContent = `Détails de la Demande #${demande.id}`;
    
    const modalBody = modal.querySelector('.modal-body');
    modalBody.innerHTML = `
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
                    <span class="urgence-badge ${demande.urgence}">${demande.urgence}</span>
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
                <button class="btn-contact" onclick="window.location.href='tel:${demande.telephone}'">📞 Contacter</button>
            </div>
        </div>
    `;
    
    // Afficher la modal
    modal.style.display = 'flex';
};

window.editDemande = function(id) {
    console.log('✏️ Modifier demande:', id);
    alert(`✏️ Modification de la demande #${id}\n\nCette fonctionnalité sera implémentée prochainement.`);
};

window.deleteDemande = function(id) {
    console.log('🗑️ Supprimer demande:', id);
    
    if (!confirm(`⚠️ Supprimer la demande #${id} ?\n\nCette action est irréversible.`)) return;
    
    fetch(`/SparkMind/controllers/DemandeController.php?action=delete&id=${id}`, {
        method: 'GET'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ Demande supprimée !');
            location.reload();
        } else {
            alert('❌ Erreur: ' + data.message);
        }
    })
    .catch(error => {
        alert('❌ Erreur: ' + error.message);
    });
};

window.updateStatus = function(id) {
    const statusSelect = document.getElementById('statusSelect');
    if (!statusSelect) return;
    
    const newStatus = statusSelect.value;
    
    fetch('/SparkMind/controllers/DemandeController.php?action=updateStatus', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id, statut: newStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ Statut mis à jour !');
            document.getElementById('detailsModal').style.display = 'none';
            location.reload();
        } else {
            alert('❌ Erreur: ' + data.message);
        }
    })
    .catch(error => {
        alert('❌ Erreur: ' + error.message);
    });
};

// Fermer la modal
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('detailsModal');
    if (modal) {
        const closeBtn = modal.querySelector('.modal-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                modal.style.display = 'none';
            });
        }
        
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    }
});