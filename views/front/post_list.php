<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>SparkMind - forum de Donations</title>
    <link rel="stylesheet" href="assets/css/sty.css" />
    <link rel="stylesheet" href="assets/css/reactions.css" />
    <style>
        /* Notification Bell Styles */
        .notification-bell {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: #fef8f3;
            border: 2px solid #e8d5c4;
            border-radius: 12px;
            text-decoration: none;
            color: #2c5f5d;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .notification-bell:hover {
            background: #fdf3ea;
            transform: translateY(-2px);
        }
        
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        /* Like Button Styles */
        .like-section {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
            margin-top: 12px;
        }
        
        .like-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: transform 0.2s ease;
        }
        
        .like-btn:hover {
            transform: scale(1.15);
        }
        
        .like-btn.liked {
            background: #fff5f5;
            border-color: #ef4444;
            color: #ef4444;
        }
        
        .like-icon {
            font-size: 20px;
            line-height: 1;
        }
        
        .like-btn.liked .like-icon {
            animation: heartbeat 0.4s ease;
        }
        
        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.3); }
        }
        
        .like-count {
            font-size: 13px;
            color: #718096;
            font-weight: 500;
        }
        
        /* Top bar updates */
        .toppage {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .top-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }
    </style>
  </head>
  <body>
    <header class="toppage">
      <div class="logo-title">
        <img src="assets/img/Logo__1_-removebg-preview.png" alt="SparkMind logo" />
        <div class="title-block">
          <h1>SparkMind</h1>
          <p class="subtitle">Forum de messageries</p>
        </div>
      </div>
      
      <!-- Notification Bell -->
      <div class="top-actions">
        <a href="index.php?action=notifications" class="notification-bell">
            🔔 Notifications
            <?php 
            require_once __DIR__ . '/../../models/Notification.php';
            $notifModel = new Notification();
            $unreadCount = $notifModel->getUnreadCount($_SESSION['user_id'] ?? 1);
            if ($unreadCount > 0): 
            ?>
                <span class="notification-badge"><?= $unreadCount ?></span>
            <?php endif; ?>
        </a>
      </div>
    </header>

    <main class="wrap">
      <!--sidebar filtres -->
      <aside class="filters-section">
        <h2>📋 Sujet</h2>
        <div class="filters">
          <a href="index.php" class="filter-btn <?= !isset($_GET['type']) ? 'active' : '' ?>">
            🌟 Tous
          </a>
          <?php foreach($donation_types as $type): ?>
            <a href="index.php?type=<?= $type['id'] ?>"
            class="filter-btn <?= (isset($_GET['type']) && $_GET['type'] == $type['id']) ? 'active' : '' ?>"
            style="<?= (isset($_GET['type']) && $_GET['type'] == $type['id']) ? '' : 'border-color: '.$type['color'].'; color: '.$type['color'] ?>">
            <?= $type['icon'] ?> <?= htmlspecialchars($type['name']) ?>
          </a>
          <?php endforeach; ?>
          </div>
          <div class="post" style="margin-top: 30px;">
            <h2>✨ Nouveau post</h2>
            <?php if (!empty($errors)): ?>
              <div class="alert alert-error">
                <?php foreach ($errors as $e): ?>
              <span>⚠️</span>
              <p><?= htmlspecialchars($e) ?></p>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
          <div class="alert alert-success">
              <span>✅</span>
              <p><?= htmlspecialchars($success) ?></p>
          </div>
          <?php endif; ?>
            <form id="postFormFront" method="post" enctype="multipart/form-data" action="index.php?action=store_front">
              <div class="form-group">
                <label>Type de sujet *</label>
                <select name="donation_type_id" id="donationType">
                  <option value="">sélectionner un type</option>
                  <?php foreach($donation_types as $type): ?>
                    <option value="<?= $type['id'] ?>">
                      <?= $type['icon'] ?> <?= htmlspecialchars($type['name']) ?>
                  </option>
                  <?php endforeach; ?>
                  </select>
                  <div id="errorType" class="error-msg"></div>
                  </div>

                  <div class="form-group">
                    <label>Titre (optionnel)</label>
                    <input type="text" name="titre" placeholder="Ex: titre..." />
                  </div>
                  <div class="form-group">
                    <label>Message *</label>
                    <textarea name="contenu" id="contenuFront" rows="4" placeholder="écrivez votre message..."></textarea>
                    <div id="errorMessage" class="error-msg"></div>
                    <small class="charCount" data-max="280">0 / 280</small>
                  </div>
                  <div class="form-group">
                    <div class="file-input-wrapper">
                      <input type="file" name="image" id ="imageInput" accept="image/*">
                      <label for="imageInput" class="file-input-label">
                        📷 Ajouter une image
                  </label>
                  </div>
                  </div>

                  <button type="submit">Publier</button>
                  </form>
                  </div>
                  </aside>
                  <!-- liste des posts -->
                   <section class="post-list">
                    <h2>📢 Derniers posts</h2>
                    <?php if (empty($posts)): ?>
                      <div class="post-item">
                        <p style="text-align: center; color: #718096;">Aucun post pour le moment. Soyer le premier à publier ! 🎉</p>
                    </div>
                    <?php else: ?>
                      <?php 
                      require_once __DIR__ . '/../../models/Like.php';
                      $likeModel = new Like();
                      $currentUserId = $_SESSION['user_id'] ?? 1;
                      
                      foreach ($posts as $p): 
                        $likesCount = $likeModel->getLikesCount($p['id']);
                        $hasLiked = $likeModel->hasUserLiked($p['id'], $currentUserId);
                      ?>
                        <article class="post-item">
                          <div class="post-header">
                            <span class="donation-badge" style="background-color: <?= $p['color'] ?? '#667eea' ?>">
                              <?= $p['icon'] ?? '🎁' ?> <?= htmlspecialchars($p['type_name'] ?? 'Autre') ?>
                            </span>
                            <div class="menu-container">
                              <button class="menu-btn" onclick="toggleMenu(this)">⋮</button>
                              <div class="menu-options">
                                <a href="index.php?action=edit&id=<?= $p['id'] ?>">✏️ Modifier</a>
                                <form method="post" action="index.php?action=delete_front" onsubmit="return confirm('Supprimer ce post ?');"> 
                                  <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                  <button type="submit" class="delete-link">🗑️ Supprimer</button>
                      </form>
                      </div>
                      </div>
                      </div>
                      <?php if (!empty($p['image'])): ?>
                        <img src="<?= htmlspecialchars($p['image']) ?>" alt="Image du post" />
                        <?php endif; ?>
                        <?php if (!empty($p['titre'])): ?>
                          <h3><?= htmlspecialchars($p['titre']) ?></h3>
                          <?php endif; ?>
                          <p><?= nl2br(htmlspecialchars($p['contenu'])) ?></p>
                          
                          <!-- REACTIONS SECTION -->
                          <?php
                          require_once __DIR__ . '/../../models/Reaction.php';
                          $reactionModel = new Reaction();
                          $currentUserId = $_SESSION['user_id'] ?? 1;
                          $userReaction = $reactionModel->getUserReaction($currentUserId, $p['id']);
                          $reactionCounts = $reactionModel->getReactionCounts($p['id']);
                          ?>
                          
                          <div class="reaction-section" data-post-id="<?= $p['id'] ?>">
                              <button type="button" class="reaction-btn" onclick="toggleReactionPicker(this)">
                                  <span class="emoji"><?= $userReaction ? Reaction::REACTIONS[$userReaction] : '😊' ?></span>
                                  <span>Réagir</span>
                              </button>
                              
                              <div class="reaction-picker">
                                  <?php foreach(Reaction::REACTIONS as $type => $emoji): ?>
                                      <button type="button" onclick="addReaction('<?= $type ?>', <?= $p['id'] ?>, null, this)" title="<?= ucfirst($type) ?>">
                                          <?= $emoji ?>
                                      </button>
                                  <?php endforeach; ?>
                              </div>
                              
                              <div class="reaction-display" style="<?= empty($reactionCounts) ? 'display: none;' : '' ?>">
                                  <?php if (!empty($reactionCounts)): 
                                      arsort($reactionCounts);
                                      foreach($reactionCounts as $type => $count): 
                                          $emoji = Reaction::REACTIONS[$type] ?? '👍';
                                  ?>
                                      <span class="reaction-item"><?= $emoji ?> <?= $count ?></span>
                                  <?php endforeach; endif; ?>
                              </div>
                          </div>
                          
                          <div class="post-footer">
                          <span class="date">📅 <?= date('d/m/Y à H:i', strtotime($p['created_at'])) ?></span>
                          <div class="post-actions">
                            <a href="index.php?action=show&id=<?= $p['id'] ?>" class="btn-comment">
                              💬 Commentaires
                                </a>
                                <a href="index.php?action=show&id=<?= $p['id'] ?>" class="btn-view">
                                    👁️ Voir
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

      </main>
      <script src="assets/js/validationPost.js"></script>
      <script>
        function toggleMenu(button) {
          const menu = button.nextElementSibling;
          const allMenus = document.querySelectorAll('.menu-options');

          //fermer tous les autres menus
          allMenus.forEach(m =>{
            if (m !== menu) m.classList.remove('show');
          });
          menu.classList.toggle('show');
        }
        //fermer les menus si on clique ailleurs
        document.addEventListener('click', (e) => {
          if (!e.target.classList.contains('menu-btn')) {
            document.querySelectorAll('.menu-options').forEach(m =>{
              m.classList.remove('show');
            });
          }
        });
        document.getElementById('imageInput').addEventListener('change', function(e) {
          const label = this.nextElementSibling;
          if (this.files && this.files[0]) {
            label.textContent = '✅ ' + this.files[0].name;
          }
        });
        </script>
      

        <!-- Floating Chatbot Button -->
        <div id="chatbotButton">💬</div>

        <!-- Chatbot Window -->
        <div id="chatbotBox" class="hidden">
            <div class="chat-header">
                Assistant IA
                <span id="closeChatbot">×</span>
            </div>

            <div id="chatWindow" class="chat-window"></div>

            <div class="chat-input">
                <input type="text" id="userMessage" placeholder="Écris un message…">
                <button onclick="sendMessage()">Envoyer</button>
            </div>
        </div>

        <script src="assets/js/chatbot.js"></script>
        <script src="assets/js/reactions.js"></script>
        <script>
function toggleLike(postId, button) {
    if (event) {
        event.preventDefault();
    }
    
    var formData = new FormData();
    formData.append('post_id', postId);
    
    fetch('index.php?action=toggle_like_ajax', {
        method: 'POST',
        body: formData
    })
    .then(function(response) { return response.json(); })
    .then(function(data) {
        console.log('Response:', data);
        if (data.success) {
            var icon = button.querySelector('.like-icon');
            if (icon) {
                icon.textContent = data.liked ? '❤️' : '🤍';
            }
            
            if (data.liked) {
                button.classList.add('liked');
            } else {
                button.classList.remove('liked');
            }
            
            var form = button.closest('form');
            var likeSection = form ? form.closest('.like-section') : button.closest('.like-section');
            if (!likeSection) return;
            
            var countSpan = likeSection.querySelector('.like-count');
            
            if (data.count > 0) {
                if (!countSpan) {
                    countSpan = document.createElement('span');
                    countSpan.className = 'like-count';
                    likeSection.appendChild(countSpan);
                }
                countSpan.textContent = data.count + ' ' + (data.count > 1 ? 'personnes aiment' : 'personne aime');
            } else {
                if (countSpan) {
                    countSpan.remove();
                }
            }
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
        alert('Erreur: ' + error);
    });
    
    return false;
}

function toggleCommentLike(commentId, postId, button) {
    if (event) {
        event.preventDefault();
    }
    
    var formData = new FormData();
    formData.append('comment_id', commentId);
    formData.append('post_id', postId);
    
    fetch('index.php?action=toggle_comment_like_ajax', {
        method: 'POST',
        body: formData
    })
    .then(function(response) { return response.json(); })
    .then(function(data) {
        console.log('Response:', data);
        if (data.success) {
            var icon = button.querySelector('.like-icon');
            if (icon) {
                icon.textContent = data.liked ? '❤️' : '🤍';
            }
            
            if (data.liked) {
                button.classList.add('liked');
            } else {
                button.classList.remove('liked');
            }
            
            var form = button.closest('form');
            var likeSection = form ? form.closest('.like-section') : button.closest('.like-section');
            if (!likeSection) return;
            
            var countSpan = likeSection.querySelector('.like-count');
            
            if (data.count > 0) {
                if (!countSpan) {
                    countSpan = document.createElement('span');
                    countSpan.className = 'like-count';
                    countSpan.style.fontSize = '12px';
                    likeSection.appendChild(countSpan);
                }
                countSpan.textContent = data.count + ' ' + (data.count > 1 ? 'personnes aiment' : 'personne aime');
            } else {
                if (countSpan) {
                    countSpan.remove();
                }
            }
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
        alert('Erreur: ' + error);
    });
    
    return false;
}
</script>
        </body>
        </html>




























































