<?php
    class Database {
        private $host='localhost';
        private $db = 'deliveryapp';
        private $username = 'postgres';
        private $password = '123456';
        private $conn;

        public function connect(){
            $this->conn = null;

            try{
                $this->conn = new PDO("pgsql:host=$this->host;port=5432;dbname=$this->db;user=$this->username;password=$this->password");
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                
                
            }catch(PDOException $e){
                echo 'Connection Error: '.$e->getMessage();
            }
            return $this->conn;
        }

        
    }
    
?> 