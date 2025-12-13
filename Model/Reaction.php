<?php
class Reaction {
    private $id;
    private $feedback_id;
    private $user_id;
    private $type;
    private $created_at;

    public function __construct($id = null, $feedback_id = null, $user_id = null, $type = 'heart', $created_at = null) {
        $this->id = $id;
        $this->feedback_id = $feedback_id;
        $this->user_id = $user_id;
        $this->type = $type;
        $this->created_at = $created_at;
    }

    public function getId() { return $this->id; }
    public function getFeedbackId() { return $this->feedback_id; }
    public function getUserId() { return $this->user_id; }
    public function getType() { return $this->type; }
    public function getCreatedAt() { return $this->created_at; }

    public function setId($id) { $this->id = $id; }
    public function setFeedbackId($feedback_id) { $this->feedback_id = $feedback_id; }
    public function setUserId($user_id) { $this->user_id = $user_id; }
    public function setType($type) { $this->type = $type; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }
}
?>

