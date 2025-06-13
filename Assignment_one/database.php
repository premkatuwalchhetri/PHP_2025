<?php 
    class database{
        private $host = '172.31.22.43';
        private $username = 'Bahadur200617098';
        private $password = 'F40LddhVn2';
        private $database = 'Bahadur200617098';
        protected $conn;
        public function __construct(){
            if(!isset($this->conn)){
                $this->conn = new mysqli($this->host, $this->username,
                $this->password, $this->database);
                if(!$this->conn){
                    echo "<p>Cannot connect to the server</p>";
                    exit;
                }
            }
            return $this->conn;
        }

    }
?>