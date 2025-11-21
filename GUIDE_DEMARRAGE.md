# 🚀 Guide de Démarrage du Projet Événements

## 📋 Prérequis
- XAMPP installé et démarré
- Apache et MySQL doivent être actifs dans XAMPP

## 🔧 Étapes d'Installation

### Étape 1 : Démarrer XAMPP
1. Ouvrez le **Panneau de Contrôle XAMPP**
2. Démarrez **Apache** (cliquez sur "Start")
3. Démarrez **MySQL** (cliquez sur "Start")

### Étape 2 : Installer la Base de Données
1. Ouvrez votre navigateur web
2. Accédez à : **http://localhost/evenement/install.php**
3. Vous devriez voir des messages de succès (✅) indiquant que :
   - La connexion MySQL est réussie
   - La base de données `projet_groupe3` est créée
   - Les tables sont créées
   - Les données d'exemple sont insérées

### Étape 3 : Accéder à l'Application
Une fois l'installation terminée, accédez à l'application :

**Option 1 (Recommandée) :**
```
http://localhost/evenement/
```
Cela redirigera automatiquement vers l'application.

**Option 2 (Directe) :**
```
http://localhost/evenement/app/public/index.php
```

## 🎯 Fonctionnalités Disponibles

Une fois connecté, vous pouvez :
- ✅ Voir la liste des événements
- ✅ Créer un nouvel événement
- ✅ Modifier un événement existant
- ✅ Supprimer un événement
- ✅ Voir les détails d'un événement
- ✅ Uploader des images pour les événements

## ⚠️ En Cas de Problème

### Erreur de connexion à la base de données
- Vérifiez que MySQL est démarré dans XAMPP
- Vérifiez que le nom de la base de données est `projet_groupe3`
- Vérifiez les identifiants dans `config/db.php` (par défaut : root / mot de passe vide)

### Page blanche ou erreur 500
- Vérifiez les logs d'erreur Apache dans XAMPP
- Vérifiez que PHP est activé dans XAMPP
- Vérifiez que tous les fichiers sont bien présents

### Images ne s'affichent pas
- Vérifiez que le dossier `uplodes/events/` existe et est accessible en écriture
- Vérifiez les permissions du dossier

## 📝 Notes Importantes

- **Sécurité** : Après l'installation, supprimez le fichier `install.php` pour des raisons de sécurité
- **Base de données** : La base de données `projet_groupe3` sera créée automatiquement si elle n'existe pas
- **Données d'exemple** : Des événements et catégories d'exemple sont créés lors de l'installation

