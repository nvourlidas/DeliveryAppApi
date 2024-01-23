<?php 
Class Order {

    private $conn;
    private $table = 'public."Orders"';
    private $table2 = 'users';

    public $orderid;
    public $address;
    public $price; 
    public $texta;
    public $userid;
    public $odate;
    public $otime;
    public $state;
    public $ptime;
    public $sname;
    public $region;

    public function __construct($db) {
        $this->conn = $db;
    }

    

    public function createorder(){

                $state = 1;

                $query = "INSERT INTO $this->table (address, price, texta, state, userid, odate, otime, region) 
                VALUES (:address, :price, :texta, :state, :userid, :odate, :otime, :region)";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':address',$this->address);
                $stmt->bindParam(':price',$this->price);
                $stmt->bindParam(':texta',$this->texta);
                $stmt->bindParam(':state',$state);
                $stmt->bindParam(':userid',$this->userid);
                $stmt->bindParam(':odate',$this->odate);
                $stmt->bindParam(':otime',$this->otime);
                $stmt->bindParam(':region',$this->region);

                $stmt->execute();

                $query2="SELECT * FROM $this->table ORDER BY orderid ASC";
                $stmt2 = $this->conn->prepare($query2);
                $stmt2->execute();

                while($row = $stmt2->fetch()){
                    $data=array('address'=>$row['address'], 'price'=>$row['price'], 
                    'texta'=>$row['texta'], 'state'=>$row['state'], 'userid'=>$row['userid'], 
                    'odate'=>$row['odate'],'otime'=>$row['otime'], 'region' => $row['region']);
                }

                return json_encode($data);
    }

    public function readorder() {

        $query="SELECT * FROM $this->table  ORDER BY orderid DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $data=array();
        while($row = $stmt->fetch()){
            $id=$row['useraccept'];
            if($row['useraccept'] === null){
                $ord=null;
            }else{
            $query2="SELECT * FROM users WHERE userid=$id";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->execute();


            $ord=array();
            
        

                while($row2 = $stmt2->fetch()){
                    $ord[]=array('userid'=>$row2['userid'],'username'=>$row2['username'],'name'=>$row2['name'],
                    'surname'=>$row2['surname'],'usertype'=>$row2['usertype']);
                    
                }
            }

            if($row['userid'] == null){
                $query4="SELECT * FROM temp ";
                $stmt4 = $this->conn->prepare($query4);
                $stmt4->execute();

                while ($row4=$stmt4->fetch()) {
                    if($row4['orderid'] == $row['orderid']){
                        $nameq= $row4['name'];
                    }
                }

            }else{
                $query4="SELECT * FROM $this->table2 ";
                $stmt4 = $this->conn->prepare($query4);
                $stmt4->execute();

                while ($row4=$stmt4->fetch()) {
                    if($row4['userid'] == $row['userid']){
                        $nameq= $row4['name'];
                    }
                }
            }

            
        $data[]=array_merge(array('orderid'=>$row['orderid'],'address'=>$row['address'], 'price'=>$row['price'], 
        'texta'=>$row['texta'], 'state'=>$row['state'], 'userid'=>$row['userid'], 
        'odate'=>$row['odate'], 'otime'=>$row['otime'], 'useraccept'=>$row['useraccept'], 'acctime'=>$row['acctime'], 'ptime'=>$row['ptime'], 
        'oloruser'=> $row['oloruser'],'name' => $nameq, 'region' => $row['region']), array('users'=> $ord));
            
    }

    return json_encode($data);
    }

    public function update(){

        date_default_timezone_set('Africa/Nairobi');
        $this->ptime= date("h:i:sa");

        if($this->state == 2){
        $query="UPDATE $this->table SET useraccept = :userid, state= :state, acctime = :acctime  WHERE orderid = :orderid";
        $stmt = $this->conn->prepare($query);
        

        $stmt->bindParam(':userid', $this->userid);
        $stmt->bindParam(':orderid', $this->orderid);
        $stmt->bindParam(':state', $this->state);
        $stmt->bindParam(':acctime', $this->ptime);
    }else{
        $query="UPDATE $this->table SET oloruser = :userid, state= :state, ptime = :ptime  WHERE orderid = :orderid";
        $stmt = $this->conn->prepare($query);
        

        $stmt->bindParam(':userid', $this->userid);
        $stmt->bindParam(':orderid', $this->orderid);
        $stmt->bindParam(':state', $this->state);
        $stmt->bindParam(':ptime', $this->ptime);
    }

        if($stmt->execute()) {
            return true;
      }


      printf("Error: %s.\n", $stmt->error);

      return false;
    }

    public function acreateorder(){
                    $state = 1;

                    $id = null;
                    $query = "INSERT INTO $this->table (address, price, texta, state, userid, odate, otime, region) 
                    VALUES (:address, :price, :texta, :state, :userid, :odate, :otime, :region)";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(':address',$this->address);
                    $stmt->bindParam(':price',$this->price);
                    $stmt->bindParam(':texta',$this->texta);
                    $stmt->bindParam(':state',$state);
                    $stmt->bindParam(':userid', $id);
                    $stmt->bindParam(':odate',$this->odate);
                    $stmt->bindParam(':otime',$this->otime);
                    $stmt->bindParam(':region',$this->region);

                    $stmt->execute();

                    $query2="SELECT * FROM $this->table ORDER BY orderid ASC";
                    $stmt2 = $this->conn->prepare($query2);
                    $stmt2->execute();

                    $lastInsertId = $this->conn->lastInsertId();

                $query4 = "INSERT INTO temp (name, orderid) VALUES (:name, :orderid)";
                $stmt4 = $this->conn->prepare($query4);
                $stmt4->bindParam(':name',$this->sname);
                $stmt4->bindParam(':orderid',$lastInsertId);
                $stmt4->execute();

                    while($row = $stmt2->fetch()){
                        $data=array('address'=>$row['address'], 'price'=>$row['price'], 
                        'texta'=>$row['texta'], 'state'=>$row['state'], 'userid'=>$row['userid'], 'odate'=>$row['odate'],'otime'=>$row['otime'], 'region' => $row['region'] );
                    }

                    return json_encode($data);

                }

                public function deleteorder(){
                    $query="DELETE FROM $this->table  WHERE orderid = :id";

                    $stmt = $this->conn->prepare($query);
            
                    //$this->orderid = htmlspecialchars(strip_tags($this->orderid));
            
                    $stmt->bindParam(':id', $this->orderid);
            
                    if($stmt->execute()) {
                        return true;
                  }
            
            
                  printf("Error: %s.\n", $stmt->error);
            
                  return false;
                }
}

?>