<?php
// app/models/AccountModel.php
class AccountModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAccountByUsername($username) {
        $query = "SELECT * FROM accounts WHERE username = :username LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function save($username, $fullName, $password, $role) {
        $query = "INSERT INTO accounts (username, full_name, password, role) 
                  VALUES (:username, :full_name, :password, :role)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':full_name', $fullName);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);
        return $stmt->execute();
    }
}