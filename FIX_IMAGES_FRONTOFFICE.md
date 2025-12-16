# Correction de l'affichage des images dans le Front Office

## Problème identifié

Les images des produits n'étaient pas affichées correctement dans le front office (`view/front office/index.php` et `detailsfront.php`). Même lorsque les photos existaient dans le dossier `uploads/`, le logo par défaut était affiché à la place.

## Cause du problème

1. **Chemin incorrect** : La logique de construction du chemin des images était incorrecte
   - Les fichiers PHP sont situés dans `view/front office/`
   - Les images sont stockées dans `uploads/` à la racine du projet
   - Le chemin relatif correct est donc `../../uploads/`

2. **Fallback incorrect** : Lorsqu'une image n'était pas trouvée, le code utilisait `onerror="this.src='logo.png'"` qui pointait vers un fichier inexistant dans le contexte du front office

## Solution appliquée

### Fichiers modifiés
- `view/front office/index.php` (lignes 322-333)
- `view/front office/detailsfront.php` (lignes 276-286)

### Logique de construction du chemin

```php
// Construire le chemin de l'image
if (!empty($produit['photo'])) {
    $photo = htmlspecialchars($produit['photo']);
    
    // Si le chemin commence par 'uploads/', ajouter ../../ pour remonter depuis view/front office/
    if (strpos($photo, 'uploads/') === 0) {
        $photo = '../../' . $photo;
    }
    // Si le chemin ne commence pas par http ou ../../, l'ajouter
    elseif (strpos($photo, 'http') !== 0 && strpos($photo, '../../') !== 0) {
        $photo = '../../uploads/' . $photo;
    }
} else {
    // Image par défaut
    $photo = '../../view/back office/logo.png';
}
```

### Correction du fallback

```html
<img src="<?= $photo ?>" alt="<?= htmlspecialchars($produit['title']) ?>" class="product-img"
     onerror="this.src='../../view/back office/logo.png'">
```

## Résultat

✅ Les images des produits s'affichent correctement depuis le dossier `uploads/`
✅ Si une image n'existe pas, le logo SparkMind est affiché comme fallback
✅ Le chemin est construit correctement quel que soit le format stocké en base de données

## Structure des chemins

```
c:/xampp/htdocs/produit/
├── uploads/                          # Dossier des images uploadées
│   ├── dguj4jb-0f8d291b-....jpg
│   ├── logo.jpg
│   └── logo_gamer.png
├── view/
│   ├── back office/
│   │   └── logo.png                  # Logo par défaut
│   └── front office/
│       ├── index.php                 # Page d'accueil (../../uploads/)
│       └── detailsfront.php          # Page de détails (../../uploads/)
```

## Test

Pour tester que la correction fonctionne :
1. Accédez à `http://localhost/produit/view/front office/index.php`
2. Vérifiez que les images des produits s'affichent correctement
3. Si un produit n'a pas d'image, le logo SparkMind doit s'afficher
