const REACTIONS = {
    'love': '❤️',
    'haha': '😂',
    'wow': '😮',
    'sad': '😢',
    'angry': '😡',
    'like': '👍',
    'fire': '🔥',
    'clap': '👏'
};

// Liste des stickers disponibles
const STICKERS = {
    'celebration': ['🎉', '🎊', '🎈', '🎆', '✨', '🌟'],
    'love': ['❤️', '💕', '💖', '💗', '💓', '💝'],
    'emotions': ['😊', '😂', '😍', '😭', '😡', '🥺'],
    'gestures': ['👍', '👏', '🙌', '👋', '🤝', '💪'],
    'school': ['📚', '✏️', '📖', '🎓', '📝', '🏫'],
    'support': ['🫂', '🤗', '💐', '🌺', '🌸', '🌻']
};

/**
 * Toggle reaction picker
 */
function toggleReactionPicker(button) {
    var picker = button.nextElementSibling;
    if (!picker || !picker.classList.contains('reaction-picker')) return;
    
    // Fermer les autres pickers
    document.querySelectorAll('.reaction-picker').forEach(function(p) {
        if (p !== picker) p.classList.remove('show');
    });
    
    picker.classList.toggle('show');
}

/**
 * Add reaction
 */
function addReaction(reactionType, postId, commentId, button) {
    var formData = new FormData();
    formData.append('reaction_type', reactionType);
    if (postId) formData.append('post_id', postId);
    if (commentId) formData.append('comment_id', commentId);
    
    fetch('index.php?action=toggle_reaction', {
        method: 'POST',
        body: formData
    })
    .then(function(response) { return response.json(); })
    .then(function(data) {
        if (data.success) {
            updateReactionDisplay(postId, commentId, data);
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
    });
    
    // Fermer le picker
    var picker = button.closest('.reaction-picker');
    if (picker) picker.classList.remove('show');
}

/**
 * Update reaction display
 */
function updateReactionDisplay(postId, commentId, data) {
    var selector = postId 
        ? '[data-post-id="' + postId + '"]' 
        : '[data-comment-id="' + commentId + '"]';
    
    var container = document.querySelector(selector + ' .reaction-display');
    if (!container) return;
    
    // Mettre à jour l'affichage
    var html = '';
    var sortedReactions = Object.entries(data.counts || {}).sort(function(a, b) {
        return b[1] - a[1];
    });
    
    sortedReactions.forEach(function(entry) {
        var type = entry[0];
        var count = entry[1];
        var emoji = REACTIONS[type] || '👍';
        html += '<span class="reaction-item">' + emoji + ' ' + count + '</span>';
    });
    
    if (html) {
        container.innerHTML = html;
        container.style.display = 'flex';
    } else {
        container.style.display = 'none';
    }
}

/**
 * Toggle sticker picker in comment form
 * FIXED: Now correctly positions picker in comment-form-wrapper
 */
function toggleStickerPicker(textarea) {
    // Trouver le wrapper du formulaire (qui a position: relative)
    var wrapper = textarea.closest('.comment-form-wrapper');
    if (!wrapper) {
        console.error('Comment form wrapper not found!');
        return;
    }
    
    // Chercher ou créer le picker
    var picker = wrapper.querySelector('.sticker-picker');
    if (!picker) {
        // Créer le picker s'il n'existe pas
        picker = createStickerPicker();
        
        // IMPORTANT: Ajouter au wrapper, pas au parent du textarea!
        wrapper.appendChild(picker);
    }
    
    // Fermer les autres pickers ouverts
    document.querySelectorAll('.sticker-picker').forEach(function(p) {
        if (p !== picker) p.classList.remove('show');
    });
    
    // Toggle ce picker
    picker.classList.toggle('show');
}

/**
 * Create sticker picker
 */
function createStickerPicker() {
    var picker = document.createElement('div');
    picker.className = 'sticker-picker';
    
    var html = '<div class="sticker-categories">';
    
    Object.keys(STICKERS).forEach(function(category) {
        html += '<div class="sticker-category">';
        html += '<div class="sticker-category-title">' + getCategoryName(category) + '</div>';
        html += '<div class="sticker-grid">';
        
        STICKERS[category].forEach(function(sticker) {
            html += '<button type="button" class="sticker-btn" onclick="insertSticker(\'' + sticker + '\', this)">' + sticker + '</button>';
        });
        
        html += '</div></div>';
    });
    
    html += '</div>';
    picker.innerHTML = html;
    
    return picker;
}

/**
 * Get category display name
 */
function getCategoryName(category) {
    var names = {
        'celebration': '🎉 Célébration',
        'love': '❤️ Amour',
        'emotions': '😊 Émotions',
        'gestures': '👍 Gestes',
        'school': '📚 École',
        'support': '🫂 Soutien'
    };
    return names[category] || category;
}

/**
 * Insert sticker into textarea
 * FIXED: Better logic to find the textarea
 */
function insertSticker(sticker, button) {
    var picker = button.closest('.sticker-picker');
    var wrapper = picker.closest('.comment-form-wrapper');
    var textarea = wrapper.querySelector('textarea');
    
    if (textarea) {
        var cursorPos = textarea.selectionStart;
        var textBefore = textarea.value.substring(0, cursorPos);
        var textAfter = textarea.value.substring(textarea.selectionEnd);
        
        textarea.value = textBefore + sticker + ' ' + textAfter;
        textarea.focus();
        
        // Positionner le curseur après le sticker
        var newPos = cursorPos + sticker.length + 1;
        textarea.setSelectionRange(newPos, newPos);
    }
    
    // Fermer le picker
    picker.classList.remove('show');
}

// Fermer les pickers en cliquant ailleurs
document.addEventListener('click', function(e) {
    // Fermer reaction pickers
    if (!e.target.closest('.reaction-btn') && !e.target.closest('.reaction-picker')) {
        document.querySelectorAll('.reaction-picker').forEach(function(p) {
            p.classList.remove('show');
        });
    }
    
    // Fermer sticker pickers
    if (!e.target.closest('.sticker-picker') && !e.target.closest('.sticker-toggle-btn')) {
        document.querySelectorAll('.sticker-picker').forEach(function(p) {
            p.classList.remove('show');
        });
    }
});




























































































































































































