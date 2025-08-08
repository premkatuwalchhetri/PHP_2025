<?php
class User {
    private $conn;
    private $table = "user";

    public function __construct($db) {
        $this->conn = $db;
    }
    public function exists($username) {
        $sql = "SELECT id FROM {$this->table} WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }
    public function emailExists($email) {
        $sql = "SELECT id FROM {$this->table} WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }
    public function register($username, $password, $email, $image) {
        if ($this->exists($username)) {
            return ['success' => false, 'error' => 'Username already taken'];
        }
        if ($this->emailExists($email)) {
            return ['success' => false, 'error' => 'Email already registered'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'Invalid email format'];
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $sql = "INSERT INTO {$this->table} (username, password, email, image) 
                    VALUES (:username, :password, :email, :image)";
            $stmt = $this->conn->prepare($sql);
            $success = $stmt->execute([
                ':username' => $username,
                ':password' => $hashedPassword,
                ':email'    => $email,
                ':image'    => $image
            ]);
            if ($success) {
                return ['success' => true];
            } else {
                return ['success' => false, 'error' => 'Database error during registration'];
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                return ['success' => false, 'error' => 'Email or username already exists'];
            }
            throw $e; // other DB errors
        }
    }
    public function login($username, $password) {
        $sql = "SELECT * FROM {$this->table} WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }
    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>


