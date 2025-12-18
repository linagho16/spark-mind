<?php
require_once __DIR__ . '/../models/Reaction.php';
require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../models/Like.php';

class ReactionController {
    
    /**
     * Toggle reaction (AJAX)
     */
    public function toggleReaction() {
        header('Content-Type: application/json');
        
        $user_id = $_SESSION['user_id'] ?? 1;
        $reaction_type = $_POST['reaction_type'] ?? null;
        $post_id = $_POST['post_id'] ?? null;
        $comment_id = $_POST['comment_id'] ?? null;
        
        if (!$reaction_type) {
            echo json_encode(['success' => false, 'error' => 'Missing reaction_type']);
            exit;
        }
        
        $reactionModel = new Reaction();
        $notificationModel = new Notification();
        $likeModel = new Like();
        
        // Vérifier si l'utilisateur a déjà cette réaction
        $currentReaction = $reactionModel->getUserReaction($user_id, $post_id, $comment_id);
        
        if ($currentReaction === $reaction_type) {
            // Même réaction = supprimer
            $reactionModel->removeReaction($user_id, $post_id, $comment_id);
            $hasReaction = false;
        } else {
            // Nouvelle réaction ou changement
            $reactionModel->addReaction($user_id, $reaction_type, $post_id, $comment_id);
            $hasReaction = true;
            
            // Notifier le propriétaire
            if ($post_id) {
                $owner = $likeModel->getPostOwner($post_id);
                if ($owner && $owner != $user_id) {
                    $emoji = Reaction::REACTIONS[$reaction_type] ?? '👍';
                    $notificationModel->create(
                        $owner,
                        $user_id,
                        $post_id,
                        'reaction',
                        "Quelqu'un a réagi $emoji à votre post"
                    );
                }
            } elseif ($comment_id) {
                $owner = $likeModel->getCommentOwner($comment_id);
                if ($owner && $owner != $user_id) {
                    $emoji = Reaction::REACTIONS[$reaction_type] ?? '👍';
                    $notificationModel->create(
                        $owner,
                        $user_id,
                        null,
                        'reaction',
                        "Quelqu'un a réagi $emoji à votre commentaire",
                        $comment_id
                    );
                }
            }
        }
        
        $counts = $reactionModel->getReactionCounts($post_id, $comment_id);
        $total = $reactionModel->getTotalReactions($post_id, $comment_id);
        
        echo json_encode([
            'success' => true,
            'hasReaction' => $hasReaction,
            'currentReaction' => $hasReaction ? $reaction_type : null,
            'counts' => $counts,
            'total' => $total
        ]);
        exit;
    }
}






















































































