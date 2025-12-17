<?php
class Feedback {
    private $id;
    private $email;
    private $description;
    private $created_at;

    public function __construct($id = null, $email = null, $description = null, $created_at = null) {
        $this->id = $id;
        $this->email = $email;
        $this->description = $description;
        $this->created_at = $created_at;
    }

    public function getId() { return $this->id; }
    public function getEmail() { return $this->email; }
    public function getDescription() { return $this->description; }
    public function getCreatedAt() { return $this->created_at; }

    public function setId($id) { $this->id = $id; }
    public function setEmail($email) { $this->email = $email; }
    public function setDescription($description) { $this->description = $description; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }
}
?>
