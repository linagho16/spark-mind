<?php 
require_once __DIR__ . "/../models/config.php";
require_once __DIR__ . "/../models/Feedback.php";


class FeedbackController {
    
    // Get all feedbacks with user information
    public function getAllFeedbacks() {
        $sql = "SELECT 
                    Feedbacks.id,
                    Feedbacks.email,
                    Feedbacks.description,
                    Feedbacks.created_at,
                    IFNULL(Users.username, Feedbacks.email) AS username
                FROM Feedbacks
                LEFT JOIN Users ON Users.email = Feedbacks.email
                ORDER BY Feedbacks.created_at DESC";
        
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll();
        } catch (Exception $e) {
            error_log("Error in getAllFeedbacks: " . $e->getMessage());
            return [];
        }
    }

    // Generate unique ID for varchar(100) IDs
    private function generateId($prefix = 'feedback') {
        // Generate a unique ID: prefix_timestamp_randomhex (max ~50 chars, fits in varchar(100))
        return $prefix . '_' . time() . '_' . bin2hex(random_bytes(12));
    }
    
    // Add a new feedback
    public function addFeedback($feedback) {
        // Generate a unique ID for the feedback (varchar(100))
        $feedbackId = $this->generateId('feedback');
        
        $sql = "INSERT INTO Feedbacks (id, email, description, created_at) 
                VALUES (:id, :email, :description, NOW())";
        
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $feedbackId);
            $query->bindValue(':email', $feedback->getEmail());
            $query->bindValue(':description', $feedback->getDescription());
            $query->execute();
            
            return true;
        } catch (Exception $e) {
            error_log("Error in addFeedback: " . $e->getMessage());
            return false;
        }
    }

    // Update a feedback
    public function updateFeedback($feedback, $id) {
        $sql = "UPDATE Feedbacks 
                SET description = :description 
                WHERE id = :id";
        
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $id,
                'description' => $feedback->getDescription()
            ]);
            
            return $query->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Error in updateFeedback: " . $e->getMessage());
            return false;
        }
    }

    // Delete a feedback
    public function deleteFeedback($id) {
        $sql = "DELETE FROM Feedbacks WHERE id = :id";
        
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
            
            return $query->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Error in deleteFeedback: " . $e->getMessage());
            return false;
        }
    }

    // Get a single feedback by ID
    public function showFeedback($id) {
        $sql = "SELECT 
                    Feedbacks.id,
                    Feedbacks.email,
                    Feedbacks.description,
                    Feedbacks.created_at,
                    IFNULL(Users.username, Feedbacks.email) AS username
                FROM Feedbacks
                LEFT JOIN Users ON Users.email = Feedbacks.email
                WHERE Feedbacks.id = :id";
        
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
            return $query->fetch();
        } catch (Exception $e) {
            error_log("Error in showFeedback: " . $e->getMessage());
            return null;
        }
    }
}


?>
