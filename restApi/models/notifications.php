<?php 
Class Notification {

    

    private $conn;
    private $table = 'public."Orders"';
    private $table2 = 'tokens';

    public $token;
    public $utype;
    public $userid;
    public $sname;
    public $region;


    public function __construct($db) {
        $this->conn = $db;
    }


    public function notifications(){

        date_default_timezone_set('Africa/Nairobi');

        $fiveSecondsAgo = date('H:i:s', strtotime('-11 seconds'));
        $mera = date('Y-m-d');

        $query="SELECT * FROM $this->table WHERE odate = :mera AND otime >= :fiveSecondsAgo";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':fiveSecondsAgo', $fiveSecondsAgo);
        $stmt->bindParam(':mera', $mera);
        $stmt->execute();

        $data=array();
        while($row = $stmt->fetch()) {
            $data[]=array('orderid'=>$row['orderid'],'address'=>$row['address'], 'price'=>$row['price'], 
            'texta'=>$row['texta'], 'state'=>$row['state'], 'userid'=>$row['userid'], 
            'odate'=>$row['odate'], 'otime'=>$row['otime'], 'useraccept'=>$row['useraccept'], 'ora' => $fiveSecondsAgo, 'mera' => $mera);
        }

        return json_encode($data);
    }

    public function fbtoken(){

                $query = "SELECT COUNT(*) FROM " . $this->table2 . " WHERE token = :token";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':token', $this->token);
                
                $stmt->execute();

                $count = $stmt->fetchColumn();

                
                $query3 ="UPDATE $this->table2 SET utype = :utype, userid = :userid  WHERE token = :token";
                $stmt3 = $this->conn->prepare($query3);
                $stmt3->bindParam(':token', $this->token);
                $stmt3->bindParam(':utype', $this->utype);
                $stmt3->bindParam(':userid', $this->userid);
                $stmt3->execute();

                if ($count > 0) {
                    // Token already exists, handle this situation (e.g., return an error message)

                    echo json_encode(array("message" => "Token already exists"));
                } else {
                    // Token doesn't exist, proceed to insert the new record
                    $insertQuery = "INSERT INTO " . $this->table2 . " (token, utype,userid, region) 
                    VALUES (:token, :utype, :userid, :region)";
                    $stmt = $this->conn->prepare($insertQuery);
                    $stmt->bindParam(':token', $this->token);
                    $stmt->bindParam(':utype', $this->utype);
                    $stmt->bindParam(':userid', $this->userid);
                    $stmt->bindParam(':region', $this->region);
                    $stmt->execute();
                }

                $query2="SELECT * FROM $this->table2 ORDER BY tokenid ASC";
                $stmt2 = $this->conn->prepare($query2);
                $stmt2->execute();

                while($row = $stmt2->fetch()){
                    $data=array('token'=>$row['token'], 'utype'=>$row['utype']);
                }

                return json_encode($data);
    }

    public function tokens(){
                $query="SELECT * FROM $this->table2";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();

                $data = array();
                if($this->utype == 2){
                    while($row = $stmt->fetch()){
                        if($row['utype'] != 2 && $row['region'] == $this->region){
                        $data[]=$row['token'];
                        }
                    }
                }else{
                while($row = $stmt->fetch()){
                    if($row['utype'] == 2 && $row['region'] == $this->region){
                    $data[]=$row['token'];
                    }
                }
            }
                return $data;
    }
    
    public function shop(){
                if($this->utype == 1){
                    $data = $this->sname;
                }else{
                    $query="SELECT * FROM users";
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute();

                    while($row = $stmt->fetch()){
                        if($row['userid'] == $this -> userid){
                        $data=$row['name'];
                        }
                    }
                }
                return $data;
    }

    public function singletoken(){
        $query = "SELECT * FROM tokens WHERE userid = :userid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userid', $this->userid);
        $stmt->execute();

        while($row = $stmt->fetch()){
            $token = $row['token'];
        }

        return $token;
    }

    public function checkmin(){
        date_default_timezone_set('Africa/Nairobi');
        $currentTime= date("h:i:sa");

        $query="SELECT * FROM $this->table WHERE useraccept = :userid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userid', $this->userid);
        $stmt->execute();

        while($row = $stmt->fetch()){
            $time = $row['acctime'];
        

            $timeinsec = strtotime($time);
            $currentTimeinsec = strtotime($currentTime);

            $timeDifference = $currentTimeinsec - $timeinsec;

            if ($timeDifference > 1200 && $timeDifference < 1257 && $row['oloruser'] == null) {
                $id = $row['orderid'];
            }
        }

        return $id;
    }

    public function deletetoken(){
        $query="DELETE FROM $this->table2  WHERE userid = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->userid);

        if($stmt->execute()) {
            return true;
      }


      printf("Error: %s.\n", $stmt->error);

      return false;
    }

    public function readtokens(){
        $query="SELECT * FROM tokens";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        while($row = $stmt->fetch()){
            $data[]=array('tokenid'=> $row['tokenid'], 'token'=> $row['token'], 'utype' => $row['utype'],
             'userid'=> $row['userid'], 'region'=> $row['region']);
        }

        return json_encode($data);
    }
   
}
?>