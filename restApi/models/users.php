<?php 
Class User {

    

    private $conn;
    private $table = 'users';
    private $table2= 'public."Orders"';

    public $userid;
    public $username;
    public $password;
    public $name;
    public $surname;
    public $usertype;
    public $status;
    public $region;


    public function __construct($db) {
        $this->conn = $db;
    }


    public function createUser(){

        $query3="SELECT * FROM $this->table ORDER BY userid ASC";
        $stmt3 = $this->conn->prepare($query3);
        $stmt3->execute();

        while($row3 = $stmt3->fetch()){
            $id = $row3['userid'];
        }
        $id = $id+1;

        $query = "INSERT INTO $this->table (userid, username, password, name, surname, usertype, region) 
        VALUES (:userid, :username, :password, :name, :surname, :usertype, :region)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userid',$id);
        $stmt->bindParam(':username',$this->username);
        $stmt->bindParam(':password',$this->password);
        $stmt->bindParam(':name',$this->name);
        $stmt->bindParam(':surname',$this->surname);
        $stmt->bindParam(':usertype',$this->usertype);
        $stmt->bindParam(':region',$this->region);
        

        $stmt->execute();

        $query2="SELECT * FROM $this->table ORDER BY userid ASC";
        $stmt2 = $this->conn->prepare($query2);
        $stmt2->execute();

        while($row = $stmt2->fetch()){
            $data=array('userid'=>$row['userid'], 'username'=>$row['username'], 
            'password'=>$row['password'], 'name'=>$row['name'], 'surname'=>$row['surname'],
             'usertype'=>$row['usertype'], 'region'=>$row['region'] );
        }

        return json_encode($data);
    }

    public function readusers(){

        $query="SELECT * FROM $this->table  ORDER BY userid  DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        while($row = $stmt->fetch()){
            if($row['usertype'] == 2){
            $data[]=array('userid'=>$row['userid'],'username'=>$row['username'],'name'=>$row['name'],
            'surname'=>$row['surname'],'usertype'=>$row['usertype'], 'online'=> $row['online'], 'region'=>$row['region']);
            }
        }

        return json_encode($data);
    }

    public function readallusers(){

        $query="SELECT * FROM $this->table  ORDER BY userid  DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        while($row = $stmt->fetch()){
            
            $data[]=array('userid'=>$row['userid'],'username'=>$row['username'],'password'=>$row['password'],'name'=>$row['name'],
            'surname'=>$row['surname'],'usertype'=>$row['usertype'], 'online' => $row['online'], 'region'=>$row['region']);
            
        }

        return json_encode($data);
    }

    public function sumorders(){
        $query="SELECT * FROM $this->table ORDER BY userid ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        
        while($row = $stmt->fetch()){
            $query2="SELECT * FROM $this->table2";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->execute();

            $count= 0;
            $count2=0;
            $sum=0;
            
            while($row2 = $stmt2->fetch()){
                if($row['userid'] == $row2['useraccept'] && $row2['oloruser'] == null){
                    $count = $count + 1;
                }
                if($row['userid'] == $row2['oloruser']){
                    $count2 = $count2 +1;
                    $sum=$sum+$row2['price'];
                }
                $sunolo=number_format($sum,2);

            }
                if($row['usertype'] == 2){
                $data[]=array('userid' => $row['userid'], 'name' => $row['name'],
                'surname'=>$row['surname'], 'accorders' => $count, 'olorders'=> $count2, 'tziros'=>$sunolo, 'region'=> $row['region'],);
                }
        }

       return json_encode($data);
    }

    public function sumordershop(){
        $query="SELECT * FROM $this->table ORDER BY userid ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        while($row = $stmt->fetch()){
            $query2="SELECT * FROM $this->table2";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->execute();

            $count= 0;
            $count2=0;
            $count3=0;

            while($row2 = $stmt2->fetch()){
                if($row['userid'] == $row2['userid'] && $row2['state'] == 3){
                    $count += 1;
                    $count3 += $row2['price'];
                }elseif($row['userid'] == $row2['userid'] && $row2['state'] == 1){
                    $count2 +=1;
                }
                $sunolo = number_format($count3, 2);

            }
            if($row['usertype'] == 3){
            $data[]=array('userid' => $row['userid'], 'name' => $row['name'],
                'surname'=>$row['surname'], 'openorders' => $count2, 'olorders'=> $count, 'sunolo'=>$sunolo, 'region'=> $row['region'],);
            }
        }

        return json_encode($data);
    }

    public function update(){

        if($this->username != ''){
            $query="UPDATE $this->table SET username = :username WHERE userid = :userid";
            $stmt = $this->conn->prepare($query);
            

            $stmt->bindParam(':userid', $this->userid);
            $stmt->bindParam(':username', $this->username);
        }elseif($this->password != ''){
            $query="UPDATE $this->table SET password = :password WHERE userid = :userid";
            $stmt = $this->conn->prepare($query);
        

            $stmt->bindParam(':userid', $this->userid);
            $stmt->bindParam(':password', $this->password);
        }elseif($this->name != ''){
            $query="UPDATE $this->table SET name = :name WHERE userid = :userid";
            $stmt = $this->conn->prepare($query);
        

            $stmt->bindParam(':userid', $this->userid);
            $stmt->bindParam(':name', $this->name);
        }elseif($this->surname != ''){
            $query="UPDATE $this->table SET surname = :surname WHERE userid = :userid";
            $stmt = $this->conn->prepare($query);
        

            $stmt->bindParam(':userid', $this->userid);
            $stmt->bindParam(':surname', $this->surname);
        }

        if($stmt->execute()) {
            return true;
        }


        printf("Error: %s.\n", $stmt->error);

        return false;
    }

    public function deleteuser(){
        $query="DELETE FROM $this->table  WHERE userid = :id";

        $stmt = $this->conn->prepare($query);

        //$this->orderid = htmlspecialchars(strip_tags($this->orderid));

        $stmt->bindParam(':id', $this->userid);

        if($stmt->execute()) {
            return true;
      }


      printf("Error: %s.\n", $stmt->error);

      return false;
    }
    
   public function currentsum(){
        $query="SELECT * FROM $this->table ORDER BY userid ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        
        while($row = $stmt->fetch()){
            $currentMonth = date("F");
            $currentYear = date("Y");

            $query2="SELECT * FROM $this->table2";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->execute();

            $count= 0;
            $count2=0;
            $sum=0;
            $sunolo =0;
            while($row2 = $stmt2->fetch()){
                $dateString = $row2['odate'];
                $timestamp = strtotime($dateString);
                $monthString = date("F", $timestamp);
                $dateArray = explode("-", $dateString);
                $year = $dateArray[0];

                if($monthString == $currentMonth && $year == $currentYear){
                    if($row['userid'] == $row2['useraccept'] && $row2['oloruser'] == null){
                        $count = $count + 1;
                    }
                    if($row['userid'] == $row2['oloruser']){
                        $count2 = $count2 +1;
                        $sum=$sum+$row2['price'];
                    }
                    $sunolo=number_format($sum,2);
                }

            }
                if($row['usertype'] == 2){
                $data[]=array('userid' => $row['userid'], 'name' => $row['name'],
                'surname'=>$row['surname'], 'accorders' => $count, 'olorders'=> $count2, 'tziros'=>$sunolo, 'region'=> $row['region'],);
                }
        }

       return json_encode($data);
    }

    public function online(){
        if($this->status == 0){
            $st=1;
            $query="UPDATE $this->table SET online = :online WHERE userid = :userid";
            $stmt = $this->conn->prepare($query);
            

            $stmt->bindParam(':userid', $this->userid);
            $stmt->bindParam(':online', $st);
        }else{
            $st=0;
            $query="UPDATE $this->table SET online = :online WHERE userid = :userid";
            $stmt = $this->conn->prepare($query);
            

            $stmt->bindParam(':userid', $this->userid);
            $stmt->bindParam(':online', $st);
        }

        if($stmt->execute()) {
            return true;
        }


        printf("Error: %s.\n", $stmt->error);

        return false;
    }

    public function todaysum(){
        $query="SELECT * FROM $this->table ORDER BY userid ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        while($row = $stmt->fetch()){
            date_default_timezone_set('Africa/Nairobi');
            $cdate = date('Y-m-d');

            $query2="SELECT * FROM $this->table2";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->execute();

            $count= 0;
            $count2=0;
            $sum=0;
            $sunolo =0;
            while($row2 = $stmt2->fetch()){
                if($row2['odate'] == $cdate){
                    if($row['userid'] == $row2['useraccept'] && $row2['oloruser'] == null){
                        $count = $count + 1;
                    }

                    if($row['userid'] == $row2['oloruser']){
                        $count2 = $count2 +1;
                        $sum=$sum+$row2['price'];
                    }

                    $sunolo=number_format($sum,2);
                }
            }

            if($row['usertype'] == 2){
                $data[]=array('userid' => $row['userid'], 'name' => $row['name'],
                'surname'=>$row['surname'], 'accorders' => $count, 'region'=> $row['region'], 'olorders'=> $count2, 'tziros'=>$sunolo);
                }
        }

        return json_encode($data);
    }
}
