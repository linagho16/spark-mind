<?php
class Comment {
    private $id;
    private $feedback_id;
    private $user_id;
    private $content;
    private $created_at;

    public function __construct($id = null, $feedback_id = null, $user_id = null, $content = null, $created_at = null) {
        $this->id = $id;
        $this->feedback_id = $feedback_id;
        $this->user_id = $user_id;
        $this->content = $content;
        $this->created_at = $created_at;
    }

    public function getId() { return $this->id; }
    public function getFeedbackId() { return $this->feedback_id; }
    public function getUserId() { return $this->user_id; }
    public function getContent() { return $this->content; }
    public function getCreatedAt() { return $this->created_at; }

    public function setId($id) { $this->id = $id; }
    public function setFeedbackId($feedback_id) { $this->feedback_id = $feedback_id; }
    public function setUserId($user_id) { $this->user_id = $user_id; }
    public function setContent($content) { $this->content = $content; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }
}
?>

