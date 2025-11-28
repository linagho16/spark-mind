// ==========================================
// SPARKMIND - BACK.JS (VERSION COMPLETE AVEC CRUD)
// Gestion du Back Office + Categories + Products + Albums
// ==========================================

console.log('🚀 Back.js chargé avec CRUD complet !');

// ==========================================
// DONNÉES EN MÉMOIRE (Arrays JavaScript)
// ==========================================

let categories = [
    { id: 1, name: "Tech", description: "Produits technologiques" },
    { id: 2, name: "Design", description: "Outils et ressources de design" },
    { id: 3, name: "Santé", description: "Produits liés à la santé" }
];

let products = [
    { id: 1, name: "Laptop Dell", date: "2025-01-15", categoryId: 1 },
    { id: 2, name: "Logo Pack", date: "2025-01-20", categoryId: 2 },
    { id: 3, name: "Kit Premiers Secours", date: "2025-01-18", categoryId: 3 }
];

let albums = [
    { id: 1, name: "Vacances 2024", date: "2024-08-01", details: "Photos de vacances en famille" },
    { id: 2, name: "Projet SparkMind", date: "2025-01-10", details: "Captures d'écran et mockups" }
];

// Compteurs pour IDs auto-incrémentés
let nextCategoryId = 4;
let nextProductId = 4;
let nextAlbumId = 3;

// Variables globales pour demandes (existantes)
window.allDemandes = [];
let filteredDemandes = [];
let currentPage = 1;
const demandesPerPage = 10;

// ==========================================
// INITIALISATION
// ==========================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ DOM chargé !');
    
    // Charger les demandes (existant)
    loadDemandes();
    
    // Afficher les nouvelles listes
    renderCategories();
    renderProducts();
    renderAlbums();
    
    // Actualisation automatique des demandes
    setInterval(loadDemandes, 30000);
});

// ==========================================
// NAVIGATION ENTRE SECTIONS
// ==========================================

function showSection(sectionName) {
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
}

// ==========================================
// 1. GESTION DES CATEGORIES
// ==========================================

function renderCategories() {
    const tbody = document.getElementById('categoriesTableBody');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    
    if (categories.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" style="text-align: center; padding: 40px; color: #999;">
                    <div style="font-size: 3em;">📁</div>
                    <p>Aucune catégorie</p>
                </td>
            </tr>
        `;
        return;
    }
    
    categories.forEach(category => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>#${category.id}</td>
            <td>${category.name}</td>
            <td>${category.description || 'N/A'}</td>
            <td>
                <div class="action-buttons">
                    <button class="btn-action edit" onclick="editCategory(${category.id})" title="Modifier">✏️</button>
                    <button class="btn-action delete" onclick="deleteCategory(${category.id})" title="Supprimer">🗑️</button>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    });
    
    console.log('✅ Catégories affichées:', categories.length);
}

function showAddCategoryForm() {
    document.getElementById('categoryModalTitle').textContent = 'Ajouter une catégorie';
    document.getElementById('categoryId').value = '';
    document.getElementById('categoryName').value = '';
    document.getElementById('categoryDescription').value = '';
    openModal('modalCategory');
}

function editCategory(id) {
    const category = categories.find(c => c.id === id);
    if (!category) {
        alert('❌ Catégorie introuvable');
        return;
    }
    
    document.getElementById('categoryModalTitle').textContent = 'Modifier la catégorie';
    document.getElementById('categoryId').value = category.id;
    document.getElementById('categoryName').value = category.name;
    document.getElementById('categoryDescription').value = category.description || '';
    openModal('modalCategory');
}

function saveCategory(event) {
    event.preventDefault();
    
    const id = document.getElementById('categoryId').value;
    const name = document.getElementById('categoryName').value.trim();
    const description = document.getElementById('categoryDescription').value.trim();
    
    if (!name) {
        alert('⚠️ Le nom de la catégorie est obligatoire');
        return;
    }
    
    if (id) {
        // UPDATE
        const category = categories.find(c => c.id == id);
        if (category) {
            category.name = name;
            category.description = description;
            showNotification('✅ Catégorie mise à jour', 'success');
        }
    } else {
        // CREATE
        const newCategory = {
            id: nextCategoryId++,
            name: name,
            description: description
        };
        categories.push(newCategory);
        showNotification('✅ Catégorie ajoutée', 'success');
    }
    
    renderCategories();
    fillCategorySelect();
    closeModal('modalCategory');
}

function deleteCategory(id) {
    if (!confirm(`⚠️ Supprimer la catégorie #${id} ?\n\nCette action est irréversible.`)) {
        return;
    }
    
    // Vérifier si des produits utilisent cette catégorie
    const hasProducts = products.some(p => p.categoryId === id);
    if (hasProducts) {
        alert('❌ Impossible de supprimer cette catégorie car elle contient des produits.');
        return;
    }
    
    categories = categories.filter(c => c.id !== id);
    renderCategories();
    showNotification('✅ Catégorie supprimée', 'success');
}

// ==========================================
// 2. GESTION DES PRODUITS (AVEC JOINTURE)
// ==========================================

function renderProducts() {
    const tbody = document.getElementById('productsTableBody');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    
    if (products.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" style="text-align: center; padding: 40px; color: #999;">
                    <div style="font-size: 3em;">📦</div>
                    <p>Aucun produit</p>
                </td>
            </tr>
        `;
        return;
    }
    
    products.forEach(product => {
        // JOINTURE: Trouver le nom de la catégorie
        const category = categories.find(c => c.id === product.categoryId);
        const categoryName = category ? category.name : 'Non définie';
        
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>#${product.id}</td>
            <td>${product.name}</td>
            <td>${formatDate(product.date)}</td>
            <td><span class="type-badge">${categoryName}</span></td>
            <td>
                <div class="action-buttons">
                    <button class="btn-action edit" onclick="editProduct(${product.id})" title="Modifier">✏️</button>
                    <button class="btn-action delete" onclick="deleteProduct(${product.id})" title="Supprimer">🗑️</button>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    });
    
    console.log('✅ Produits affichés:', products.length);
}

function fillCategorySelect() {
    const select = document.getElementById('productCategoryId');
    if (!select) return;
    
    select.innerHTML = '<option value="">Sélectionnez une catégorie</option>';
    categories.forEach(category => {
        const option = document.createElement('option');
        option.value = category.id;
        option.textContent = category.name;
        select.appendChild(option);
    });
}

function showAddProductForm() {
    fillCategorySelect();
    document.getElementById('productModalTitle').textContent = 'Ajouter un produit';
    document.getElementById('productId').value = '';
    document.getElementById('productName').value = '';
    document.getElementById('productDate').value = '';
    document.getElementById('productCategoryId').value = '';
    openModal('modalProduct');
}

function editProduct(id) {
    const product = products.find(p => p.id === id);
    if (!product) {
        alert('❌ Produit introuvable');
        return;
    }
    
    fillCategorySelect();
    document.getElementById('productModalTitle').textContent = 'Modifier le produit';
    document.getElementById('productId').value = product.id;
    document.getElementById('productName').value = product.name;
    document.getElementById('productDate').value = product.date;
    document.getElementById('productCategoryId').value = product.categoryId;
    openModal('modalProduct');
}

function saveProduct(event) {
    event.preventDefault();
    
    const id = document.getElementById('productId').value;
    const name = document.getElementById('productName').value.trim();
    const date = document.getElementById('productDate').value;
    const categoryId = parseInt(document.getElementById('productCategoryId').value);
    
    if (!name || !date || !categoryId) {
        alert('⚠️ Tous les champs sont obligatoires');
        return;
    }
    
    if (id) {
        // UPDATE
        const product = products.find(p => p.id == id);
        if (product) {
            product.name = name;
            product.date = date;
            product.categoryId = categoryId;
            showNotification('✅ Produit mis à jour', 'success');
        }
    } else {
        // CREATE
        const newProduct = {
            id: nextProductId++,
            name: name,
            date: date,
            categoryId: categoryId
        };
        products.push(newProduct);
        showNotification('✅ Produit ajouté', 'success');
    }
    
    renderProducts();
    closeModal('modalProduct');
}

function deleteProduct(id) {
    if (!confirm(`⚠️ Supprimer le produit #${id} ?\n\nCette action est irréversible.`)) {
        return;
    }
    
    products = products.filter(p => p.id !== id);
    renderProducts();
    showNotification('✅ Produit supprimé', 'success');
}

// ==========================================
// 3. GESTION DES ALBUMS
// ==========================================

function renderAlbums() {
    const tbody = document.getElementById('albumsTableBody');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    
    if (albums.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" style="text-align: center; padding: 40px; color: #999;">
                    <div style="font-size: 3em;">📸</div>
                    <p>Aucun album</p>
                </td>
            </tr>
        `;
        return;
    }
    
    albums.forEach(album => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>#${album.id}</td>
            <td>${album.name}</td>
            <td>${formatDate(album.date)}</td>
            <td>${album.details || 'N/A'}</td>
            <td>
                <div class="action-buttons">
                    <button class="btn-action edit" onclick="editAlbum(${album.id})" title="Modifier">✏️</button>
                    <button class="btn-action delete" onclick="deleteAlbum(${album.id})" title="Supprimer">🗑️</button>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    });
    
    console.log('✅ Albums affichés:', albums.length);
}

function showAddAlbumForm() {
    document.getElementById('albumModalTitle').textContent = 'Ajouter un album';
    document.getElementById('albumId').value = '';
    document.getElementById('albumName').value = '';
    document.getElementById('albumDate').value = '';
    document.getElementById('albumDetails').value = '';
    openModal('modalAlbum');
}

function editAlbum(id) {
    const album = albums.find(a => a.id === id);
    if (!album) {
        alert('❌ Album introuvable');
        return;
    }
    
    document.getElementById('albumModalTitle').textContent = 'Modifier l\'album';
    document.getElementById('albumId').value = album.id;
    document.getElementById('albumName').value = album.name;
    document.getElementById('albumDate').value = album.date;
    document.getElementById('albumDetails').value = album.details || '';
    openModal('modalAlbum');
}

function saveAlbum(event) {
    event.preventDefault();
    
    const id = document.getElementById('albumId').value;
    const name = document.getElementById('albumName').value.trim();
    const date = document.getElementById('albumDate').value;
    const details = document.getElementById('albumDetails').value.trim();
    
    if (!name || !date) {
        alert('⚠️ Le nom et la date sont obligatoires');
        return;
    }
    
    if (id) {
        // UPDATE
        const album = albums.find(a => a.id == id);
        if (album) {
            album.name = name;
            album.date = date;
            album.details = details;
            showNotification('✅ Album mis à jour', 'success');
        }
    } else {
        // CREATE
        const newAlbum = {
            id: nextAlbumId++,
            name: name,
            date: date,
            details: details
        };
        albums.push(newAlbum);
        showNotification('✅ Album ajouté', 'success');
    }
    
    renderAlbums();
    closeModal('modalAlbum');
}

function deleteAlbum(id) {
    if (!confirm(`⚠️ Supprimer l'album #${id} ?\n\nCette action est irréversible.`)) {
        return;
    }
    
    albums = albums.filter(a => a.id !== id);
    renderAlbums();
    showNotification('✅ Album supprimé', 'success');
}

// ==========================================
// UTILITAIRES
// ==========================================

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR');
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
}

// Fermer modal en cliquant en dehors
window.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
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
// CODE EXISTANT POUR DEMANDES (INCHANGÉ)
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
                displayDemandes();
                updateStatistics();
            }
        })
        .catch(error => {
            console.error('❌ Erreur:', error);
        });
}

function displayDemandes() {
    const tbody = document.querySelector('.demandes-table tbody');
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
        const row = createDemandeRow(demande);
        tbody.appendChild(row);
    });
}

function createDemandeRow(demande) {
    const tr = document.createElement('tr');
    const date = new Date(demande.date_soumission);
    const dateStr = date.toLocaleDateString('fr-FR');
    
    tr.innerHTML = `
        <td>#${demande.id}</td>
        <td>${dateStr}</td>
        <td>${demande.anonyme ? 'Anonyme' : demande.nom}</td>
        <td>${demande.gouvernorat}</td>
        <td><span class="type-badge">${demande.categories_aide}</span></td>
        <td><span class="urgence-badge ${demande.urgence}">${demande.urgence}</span></td>
        <td><span class="status-badge ${demande.statut}">${demande.statut}</span></td>
        <td>
            <div class="action-buttons">
                <button class="btn-action view" onclick="viewDemande(${demande.id})">👁️</button>
                <button class="btn-action edit" onclick="editDemande(${demande.id})">✏️</button>
                <button class="btn-action delete" onclick="deleteDemande(${demande.id})">🗑️</button>
            </div>
        </td>
    `;
    return tr;
}

function updateStatistics() {
    const urgentes = window.allDemandes.filter(d => d.urgence === 'tres-urgent').length;
    const enAttente = window.allDemandes.filter(d => d.statut === 'nouveau').length;
    const traitees = window.allDemandes.filter(d => d.statut === 'traite').length;
    const total = window.allDemandes.length;
    
    const statCards = document.querySelectorAll('.stat-card');
    if (statCards[0]) statCards[0].querySelector('h3').textContent = urgentes;
    if (statCards[1]) statCards[1].querySelector('h3').textContent = enAttente;
    if (statCards[2]) statCards[2].querySelector('h3').textContent = traitees;
    if (statCards[3]) statCards[3].querySelector('h3').textContent = total;
}

window.viewDemande = function(id) {
    console.log('👁️ Voir demande:', id);
};

window.editDemande = function(id) {
    alert('✏️ Modification de la demande #' + id);
};

window.deleteDemande = function(id) {
    if (confirm('⚠️ Supprimer la demande #' + id + ' ?')) {
        showNotification('✅ Demande supprimée', 'success');
    }
};

console.log('✅ Back Office complet initialisé !');