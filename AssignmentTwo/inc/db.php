<?php
class Database {
    private $host = '172.31.22.43';
    private $db_name = 'Bahadur200617098';
    private $username = 'Bahadur200617098';
    private $password = 'F40LddhVn2';
    private $conn;

    public function getConnection() {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO(
                    "mysql:host={$this->host};dbname={$this->db_name}",
                    $this->username,
                    $this->password
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return $this->conn;
    }
}

$database = new Database();
$pdo = $database->getConnection();

?>
