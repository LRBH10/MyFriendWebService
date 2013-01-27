<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author bibouh
 */
class OwerUser {

    /**
     * @var String
     */
    private $pseudo;

    /**
     * @var String
     */
    private $firstName;

    /**
     * @var String
     */
    private $lastName;

    /**
     * @var String
     */
    private $password;

    /**
     * @var String
     */
    private $token;

    /**
     * @var String
     */
    private $publictoken;
    private $age;
    private $imagelink;
    private $city;
    
    private $number;

    /**
     * GETTERs and SETTERS 
     */
    public function setFirstName($val) {
        $this->firstName = $val;
    }

    public function setAge($val) {
        $this->age = $val;
    }

    public function setCity($val) {
        $this->city = $val;
    }

    public function setImageLink($val) {
        $this->imagelink = $val;
    }

    public function setLastName($val) {
        $this->lastName = $val;
    }

    public function setNumber($val) {
        $this->number = $val;
    }

    public function getNumber() {
        return $this->number;
    }

    public function getAge() {
        return $this->age;
    }

    public function getCity() {
        return $this->city;
    }

    public function getImageLink() {
        return $this->imagelink;
    }

    public function getPseudo() {
        return $this->pseudo;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getToken() {
        return $this->token;
    }

    public function getPublicToken() {
        return $this->publictoken;
    }

    /**
     *  to create user Object
     * @param string $pseudo
     * @param string $keyword
     */
    public function __construct($pseudo, $keyword) {
        $this->pseudo = $pseudo;
        $this->password = $keyword;
        $this->gT();
    }

    private function gT() {
        $this->token = md5($this->pseudo . "_" . $this->password);
        $this->publictoken = sha1($this->pseudo . "_" . $this->password);
    }

    public static function generateToken($ps, $pass) {
        return md5($ps . "_" . $pass);
    }

    public static function generateTokenFriend($ps, $pass) {
        return sha1($ps . "_" . $pass);
    }

    /**
     * made it persistante (database );
     */
    public function save() {
        $req = "insert into  user values ('$this->token','$this->publictoken','$this->pseudo','$this->firstName','$this->lastName','$this->password','$this->age','$this->city','$this->imagelink','$this->number')";
        Connection::getDbMapper()->execStatement($req);

        $date = date("l d-F-o (H:i:s) -e-");
        $req1 = "insert into  usergeo values ('$this->token','0.0','0.0','$date','TRUE')";
        Connection::getDbMapper()->execStatement($req1);
    }

    /**
     * 
     * @param string $token
     * @return OwerUser
     */
    public static function get($token) {
        $req = "select * from user where token='$token'";
        $res = Connection::getDbMapper()->execStatement($req);
        $user = null;
        while (($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) != NULL) {
            $user = new OwerUser($row['pseudo'], $row['password']);
            $user->firstName = $row['firstname'];
            $user->lastName = $row['lastname'];
            $user->age = $row['age'];
            $user->imagelink = $row['imagelink'];
            $user->city = $row['city'];
            $user->number = $row['number'];
            
        }
        mysqli_free_result($res);
        return $user;
    }

    
    /**
     * check if the user exist in the system
     * @param type $token
     * @return boolean
     */
    public static function existPseudo($pseudo) {
        $req = "select * from user where pseudo='$pseudo'";
        $res = Connection::getDbMapper()->execStatement($req);
        $userexist = false;
        while (($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) != NULL) {
            $userexist = true;
        }
        mysqli_free_result($res);
        return $userexist;
    }
    /**
     * check if the user exist in the system
     * @param type $token
     * @return boolean
     */
    public static function existUSER($token) {
        $req = "select * from user where publictoken='$token'";
        $res = Connection::getDbMapper()->execStatement($req);
        $userexist = false;
        while (($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) != NULL) {
            $userexist = true;
        }
        mysqli_free_result($res);
        return $userexist;
    }

    /**
     *  add Friend  to USER
     * @param string $token_user
     * @param string $token_friend
     * @return Boolean
     */
    public function addfriend($token_friend) {
        if (OwerUser::existUSER($token_friend)) {
            /** @var string */
            $req = "insert into friends values('$this->token','$token_friend')";
            Connection::getDbMapper()->execStatement($req);
            return TRUE;
        } else {
            Alert::information("l'un des 2 token n'existe pas ");
            return FALSE;
        }
    }

    /**
     * check if the token given is a friend of this user 
     * @param string $token_friend
     * @return boolean
     */
    public function existfriend($token_friend) {
        /** @var string */
        $req = "select * from friends where id_user='$this->token' and id_user_f='$token_friend'";
        $res = Connection::getDbMapper()->execStatement($req);
        $userexist = false;
        while (($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) != NULL) {
            $userexist = true;
        }
        mysqli_free_result($res);
        return $userexist;
    }

    /**
     * to remove A friend
     * @param string $token_friend
     * @return boolean
     */
    public function removefriend($token_friend) {
        if ($this->existfriend($token_friend)) {
            /** @var string */
            $req = "delete from friends where id_user = '$this->token' and id_user_f ='$token_friend'";
            Connection::getDbMapper()->execStatement($req);
            return TRUE;
        } else {
            Alert::information("l'un des 2 token n'existe pas ");
            return FALSE;
        }
    }

    /**
     * 
     * @param Double $longitude
     * @param Double $latitude
     */
    public function updateTo($longitude, $latitude) {
        $date = date("l d-F-o (H:i:s) -e-");
        $req = "update usergeo set log ='$longitude', lat='$latitude',time='$date' where token_user='$this->token'";
        Connection::getDbMapper()->execStatement($req);
    }
    
     /**
     * 
     * @param Double $longitude
     * @param Double $latitude
     */
    public function updateInfotmations($city, $age, $imagelink, $number) {
        $req = "update user set age ='$age', city='$city',imagelink='$imagelink', number='$number' where token='$this->token'";
        Connection::getDbMapper()->execStatement($req);
    }

    /**
     * 
     * @param Boolean $visible
     */
    public function updateVisibleTo($visible) {
        $req = "update usergeo set visible ='$visible' where token_user='$this->token'";
        Connection::getDbMapper()->execStatement($req);
    }

    /**
     * to delete user difinitvely from the system
     */
    public function delete() {
        /** @var string */
        $req = "delete from usergeo where token_user='$this->token'";
        Connection::getDbMapper()->execStatement($req);

        $req1 = "delete from friends where id_user='$this->token' or id_user_f = '$this->publictoken'";
        Connection::getDbMapper()->execStatement($req1);

        $req2 = "delete from user where token='$this->token'";
        Connection::getDbMapper()->execStatement($req2);
    }

    /**
     * To get friends information (public token, first name , last name , pseudo )
     */
    public function getfriendsInformation() {
        $req = "select  f.id_user_f as publictoken, u.pseudo , u.firstname as firstName, u.lastname as lastName, u.age, u.city, u.imagelink, u.number, g.log, g.lat,g.time
                    from friends f, user u, usergeo g 
                    where f.id_user='$this->token' 
                    and f.id_user_f=u.publictoken 
                    and g.token_user = (select u1.token from user u1  where publictoken = f.id_user_f)
                    and g.visible = 'TRUE'";

        $res = Connection::getDbMapper()->execStatement($req);
        $ret = array();
        while (($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) != NULL) {
            $ret[] = $row;
        }
        mysqli_free_result($res);
        return $ret;
    }

    /**
     * To get friends information (public token, first name , last name , pseudo )
     */
    public function getlocation($id_friend) {
        $req = "select  g.lat, g.log as lon, g.time   
                    from friends f, user u, usergeo g 
                    where f.id_user='$this->token' and f.id_user_f='$id_friend' and f.id_user_f=u.publictoken and u.token = g.token_user";

        $res = Connection::getDbMapper()->execStatement($req);
        $ret = array();
        while (($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) != NULL) {
            $ret[] = $row;
        }
        mysqli_free_result($res);
        return $ret;
    }

    public function searchFor($search) {
        $req = "SELECT pseudo, firstname, lastname, publictoken, u.age, u.city, u.imagelink,u.number

                FROM user
                WHERE pseudo like '%$search%' OR firstname like '%$search%' OR lastname like '%$search%' OR publictoken='$search'";

        $res = Connection::getDbMapper()->execStatement($req);
        $ret = array();
        while (($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) != NULL) {
            $ret[] = $row;
        }
        mysqli_free_result($res);
        return $ret;
    }

}

?>
