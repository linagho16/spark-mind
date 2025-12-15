function toggleLike(postId, button) {
    // Prevent form submission
    if (event) {
        event.preventDefault();
    }
    
    const formData = new FormData();
    formData.append('post_id', postId);
    
    fetch('index.php?action=toggle_like_ajax', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update heart icon
            const icon = button.querySelector('.like-icon');
            if (icon) {
                icon.textContent = data.liked ? '❤️' : '🤍';
            }
            
            // Update liked class
            if (data.liked) {
                button.classList.add('liked');
            } else {
                button.classList.remove('liked');
            }
            
            // Update count
            const form = button.closest('form');
            const likeSection = form ? form.closest('.like-section') : button.closest('.like-section');
            if (!likeSection) return;
            
            let countSpan = likeSection.querySelector('.like-count');
            
            if (data.count > 0) {
                if (!countSpan) {
                    countSpan = document.createElement('span');
                    countSpan.className = 'like-count';
                    likeSection.appendChild(countSpan);
                }
                countSpan.textContent = `${data.count} ${data.count > 1 ? 'personnes aiment' : 'personne aime'}`;
            } else {
                if (countSpan) {
                    countSpan.remove();
                }
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors du like');
    });
    
    return false;
}

/**
 * Toggle like on comment (AJAX - no page reload)
 */
function toggleCommentLike(commentId, postId, button) {
    // Prevent form submission
    if (event) {
        event.preventDefault();
    }
    
    const formData = new FormData();
    formData.append('comment_id', commentId);
    formData.append('post_id', postId);
    
    fetch('index.php?action=toggle_comment_like_ajax', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update heart icon
            const icon = button.querySelector('.like-icon');
            if (icon) {
                icon.textContent = data.liked ? '❤️' : '🤍';
            }
            
            // Update liked class
            if (data.liked) {
                button.classList.add('liked');
            } else {
                button.classList.remove('liked');
            }
            
            // Update count
            const form = button.closest('form');
            const likeSection = form ? form.closest('.like-section') : button.closest('.like-section');
            if (!likeSection) return;
            
            let countSpan = likeSection.querySelector('.like-count');
            
            if (data.count > 0) {
                if (!countSpan) {
                    countSpan = document.createElement('span');
                    countSpan.className = 'like-count';
                    countSpan.style.fontSize = '12px';
                    likeSection.appendChild(countSpan);
                }
                countSpan.textContent = `${data.count} ${data.count > 1 ? 'personnes aiment' : 'personne aime'}`;
            } else {
                if (countSpan) {
                    countSpan.remove();
                }
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors du like');
    });
    
    return false;
}




















































































