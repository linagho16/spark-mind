<?php
class User {
    private $id;
    private $username;
    private $email;
    private $password_hash;
    private $created_at;

    public function __construct($id = null, $username = null, $email = null, $password_hash = null, $created_at = null) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password_hash = $password_hash;
        $this->created_at = $created_at;
    }

    public function getId() { return $this->id; }
    public function getUsername() { return $this->username; }
    public function getEmail() { return $this->email; }
    public function getPasswordHash() { return $this->password_hash; }
    public function getCreatedAt() { return $this->created_at; }

    public function setId($id) { $this->id = $id; }
    public function setUsername($username) { $this->username = $username; }
    public function setEmail($email) { $this->email = $email; }
    public function setPasswordHash($password_hash) { $this->password_hash = $password_hash; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }
}
?>

