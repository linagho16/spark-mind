<?php
require_once __DIR__ . '/../models/Like.php';
require_once __DIR__ . '/../models/Notification.php';

class LikeController {
    
    /**
     * Toggle like on a post (like if not liked, unlike if already liked)
     */
    public function toggleLike() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $post_id = $_POST['post_id'] ?? null;
            $user_id = $_SESSION['user_id'] ?? 1;
            
            if (!$post_id) {
                header('Location: index.php');
                exit;
            }
            
            $likeModel = new Like();
            $notificationModel = new Notification();
            
            // Check if already liked
            $hasLiked = $likeModel->hasUserLiked($post_id, $user_id);
            
            if ($hasLiked) {
                // Unlike
                $likeModel->removeLike($post_id, $user_id);
            } else {
                // Like
                $likeModel->addLike($post_id, $user_id);
                
                // Notify post owner
                $postOwner = $likeModel->getPostOwner($post_id);
                if ($postOwner && $postOwner != $user_id) {
                    $notificationModel->notifyNewLike($postOwner, $user_id, $post_id);
                }
            }
            
            // Redirect back to the post
            $redirect = $_POST['redirect'] ?? 'index.php';
            header('Location: ' . $redirect);
            exit;
        }
    }
    
    /**
     * Get like count for AJAX (optional)
     */
    public function getLikeCount() {
        header('Content-Type: application/json');
        
        $post_id = $_GET['post_id'] ?? null;
        if (!$post_id) {
            echo json_encode(['error' => 'Missing post_id']);
            exit;
        }
        
        $likeModel = new Like();
        $user_id = $_SESSION['user_id'] ?? 1;
        
        $count = $likeModel->getLikesCount($post_id);
        $hasLiked = $likeModel->hasUserLiked($post_id, $user_id);
        
        echo json_encode([
            'count' => $count,
            'hasLiked' => $hasLiked
        ]);
        exit;
    }
    
    /**
     * Toggle like on a comment
     */
    public function toggleCommentLike() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $comment_id = $_POST['comment_id'] ?? null;
            $post_id = $_POST['post_id'] ?? null;
            $user_id = $_SESSION['user_id'] ?? 1;
            
            if (!$comment_id || !$post_id) {
                header('Location: index.php');
                exit;
            }
            
            $likeModel = new Like();
            $notificationModel = new Notification();
            
            // Check if already liked
            $hasLiked = $likeModel->hasUserLikedComment($comment_id, $user_id);
            
            if ($hasLiked) {
                // Unlike
                $likeModel->removeCommentLike($comment_id, $user_id);
            } else {
                // Like
                $likeModel->addCommentLike($comment_id, $user_id);
                
                // Notify comment owner
                $commentOwner = $likeModel->getCommentOwner($comment_id);
                if ($commentOwner && $commentOwner != $user_id) {
                    $notificationModel->create(
                        $commentOwner,
                        $user_id,
                        $post_id,
                        'comment_like',
                        "Quelqu'un a aimé votre commentaire",
                        $comment_id
                    );
                }
            }
            
            // Redirect back to the post
            header('Location: index.php?action=show&id=' . $post_id);
            exit;
        }
    }
    
    /**
     * Toggle like AJAX (returns JSON) - NO PAGE RELOAD
     */
    public function toggleLikeAjax() {
        header('Content-Type: application/json');
        
        $post_id = $_POST['post_id'] ?? null;
        $user_id = $_SESSION['user_id'] ?? 1;
        
        if (!$post_id) {
            echo json_encode(['success' => false, 'error' => 'Missing post_id']);
            exit;
        }
        
        $likeModel = new Like();
        $notificationModel = new Notification();
        
        // Check if already liked
        $hasLiked = $likeModel->hasUserLiked($post_id, $user_id);
        
        if ($hasLiked) {
            // Unlike
            $likeModel->removeLike($post_id, $user_id);
            $liked = false;
        } else {
            // Like
            $likeModel->addLike($post_id, $user_id);
            $liked = true;
            
            // Notify post owner
            $postOwner = $likeModel->getPostOwner($post_id);
            if ($postOwner && $postOwner != $user_id) {
                $notificationModel->notifyNewLike($postOwner, $user_id, $post_id);
            }
        }
        
        $count = $likeModel->getLikesCount($post_id);
        
        echo json_encode([
            'success' => true,
            'liked' => $liked,
            'count' => $count
        ]);
        exit;
    }
    
    /**
     * Toggle comment like AJAX (returns JSON) - NO PAGE RELOAD
     */
    public function toggleCommentLikeAjax() {
        header('Content-Type: application/json');
        
        $comment_id = $_POST['comment_id'] ?? null;
        $post_id = $_POST['post_id'] ?? null;
        $user_id = $_SESSION['user_id'] ?? 1;
        
        if (!$comment_id) {
            echo json_encode(['success' => false, 'error' => 'Missing comment_id']);
            exit;
        }
        
        $likeModel = new Like();
        $notificationModel = new Notification();
        
        // Check if already liked
        $hasLiked = $likeModel->hasUserLikedComment($comment_id, $user_id);
        
        if ($hasLiked) {
            // Unlike
            $likeModel->removeCommentLike($comment_id, $user_id);
            $liked = false;
        } else {
            // Like
            $likeModel->addCommentLike($comment_id, $user_id);
            $liked = true;
            
            // Notify comment owner
            $commentOwner = $likeModel->getCommentOwner($comment_id);
            if ($commentOwner && $commentOwner != $user_id) {
                $notificationModel->create(
                    $commentOwner,
                    $user_id,
                    $post_id,
                    'comment_like',
                    "Quelqu'un a aimé votre commentaire",
                    $comment_id
                );
            }
        }
        
        $count = $likeModel->getCommentLikesCount($comment_id);
        
        echo json_encode([
            'success' => true,
            'liked' => $liked,
            'count' => $count
        ]);
        exit;
    }
}