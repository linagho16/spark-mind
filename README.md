<<<<<<< HEAD
# spark-mind
=======
# 🎭 Système de Gestion d'Événements & Réservations

Application web complète pour gérer des événements et leurs réservations.

## 📋 Fonctionnalités

### Gestion des Événements
- ✅ Créer, modifier et supprimer des événements
- 📅 Définir titre, description, lieu, prix et date
- 📊 Vue d'ensemble de tous les événements

### Gestion des Réservations
- ➕ Créer des réservations pour les événements
- 👤 Enregistrer les informations des clients (nom, email, téléphone)
- 💳 Choisir la méthode de paiement
- 📈 Statuts de réservation : En attente, Confirmée, Annulée
- 🔢 Calcul automatique du montant total
- 🎫 Génération automatique de référence unique
- 🪑 Gestion des places disponibles (limite de 100 places par événement)

### Dashboard
- 📊 Statistiques en temps réel
- 📈 Vue d'ensemble des événements et réservations
- 💰 Suivi des revenus

## 🛠️ Installation

### Prérequis
- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur web (Apache/Nginx) ou XAMPP/WAMP

### Étapes d'installation

1. **Cloner/Copier le projet**
   ```bash
   # Copier les fichiers dans votre répertoire htdocs ou www
   ```

2. **Créer la base de données**
   - Ouvrir phpMyAdmin ou un client MySQL
   - Créer une nouvelle base de données nommée `evenement`
   - Importer le fichier `database.sql`

   Ou via ligne de commande :
   ```bash
   mysql -u root -p < database.sql
   ```

3. **Configurer la connexion**
   - Ouvrir `config/config.php`
   - Modifier si nécessaire :
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'evenement');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     ```

4. **Accéder à l'application**
   - Ouvrir votre navigateur
   - Aller sur : `http://localhost/evennement/evennement/`

## 📂 Structure du projet

```
evennement/
├── assets/
│   ├── css/
│   │   └── style.css          # Styles CSS
│   └── js/
│       └── main.js            # Scripts JavaScript
├── config/
│   ├── config.php             # Configuration principale
│   └── database.php           # Classe de connexion DB (legacy)
├── controllers/
│   ├── EventController.php    # Contrôleur des événements
│   └── ReservationController.php # Contrôleur des réservations
├── models/
│   ├── EventModel.php         # Modèle des événements
│   └── Reservation.php        # Modèle des réservations
├── views/
│   ├── dashboard.php          # Tableau de bord
│   ├── layout.php             # Layout principal
│   ├── events/
│   │   ├── create.php         # Formulaire de création
│   │   ├── edit.php           # Formulaire d'édition
│   │   ├── index.php          # Liste des événements
│   │   └── show.php           # Détails d'un événement
│   └── reservations/
│       ├── create.php         # Formulaire de réservation
│       └── index.php          # Liste des réservations
├── database.sql               # Script SQL de création
├── index.php                  # Point d'entrée principal
├── process_event.php          # Traitement des événements
└── process_reservation.php    # Traitement des réservations
```

## 🎯 Utilisation

### Créer un événement
1. Cliquer sur "🎭 Nouvel Événement" dans le menu
2. Remplir le formulaire (titre, description, lieu, prix, date)
3. Cliquer sur "✅ Créer l'Événement"

### Créer une réservation
1. Cliquer sur "➕ Nouvelle Réservation" dans le menu
2. Remplir les informations du client
3. Sélectionner un événement
4. Indiquer le nombre de places
5. Le montant se calcule automatiquement
6. Choisir la méthode de paiement
7. Cliquer sur "✅ Créer la réservation"

### Gérer les réservations
- ✅ **Confirmer** : Passer une réservation "en attente" à "confirmée"
- ❌ **Annuler** : Annuler une réservation
- 🗑️ **Supprimer** : Supprimer définitivement une réservation

## 🔒 Sécurité

- ✅ Requêtes préparées (PDO) pour prévenir les injections SQL
- ✅ Validation des données côté serveur
- ✅ Échappement HTML avec `htmlspecialchars()`
- ✅ Gestion des erreurs
- ⚠️ **À améliorer** : Ajouter authentification admin, protection CSRF

## 🐛 Résolution de problèmes

### Erreur de connexion à la base de données
- Vérifier que MySQL est démarré
- Vérifier les identifiants dans `config/config.php`
- Vérifier que la base `evenement` existe

### Les événements ne s'affichent pas
- Vérifier que la table `events` contient des données
- Consulter les logs d'erreur PHP

### Les réservations ne se créent pas
- Vérifier que la table `reservations` existe
- Vérifier qu'il y a au moins un événement créé

## 📝 Base de données

### Table `events`
| Champ | Type | Description |
|-------|------|-------------|
| id | INT | Identifiant unique |
| titre | VARCHAR(255) | Titre de l'événement |
| description | TEXT | Description détaillée |
| lieu | VARCHAR(255) | Lieu de l'événement |
| prix | DECIMAL(10,2) | Prix par place |
| date_event | DATE | Date de l'événement |

### Table `reservations`
| Champ | Type | Description |
|-------|------|-------------|
| id | INT | Identifiant unique |
| event_id | INT | ID de l'événement (FK) |
| nom_client | VARCHAR(255) | Nom du client |
| email | VARCHAR(255) | Email du client |
| telephone | VARCHAR(20) | Téléphone |
| nombre_places | INT | Nombre de places réservées |
| montant_total | DECIMAL(10,2) | Montant total |
| reference | VARCHAR(50) | Référence unique |
| statut | ENUM | en attente, confirmée, annulée |
| methode_paiement | VARCHAR(50) | Méthode de paiement |
| notes | TEXT | Notes additionnelles |
| date_reservation | TIMESTAMP | Date de création |

## 🚀 Améliorations futures

- [ ] Système d'authentification admin
- [ ] Envoi d'emails de confirmation
- [ ] Export PDF des réservations
- [ ] Statistiques avancées
- [ ] API REST
- [ ] Interface responsive améliorée
- [ ] Gestion multi-utilisateurs
- [ ] Calendrier visuel des événements
- [ ] Paiement en ligne

## 📄 Licence

Projet éducatif - Libre d'utilisation

## 👨‍💻 Support

Pour toute question ou problème, consultez les fichiers de code ou les commentaires intégrés.
>>>>>>> origin/evennement
