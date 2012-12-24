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
     * GETTERs and SETTERS 
     */
    public function setFirstName($val){
        $this->firstName = $val;
    }
    public function setLastName($val){
        $this->lastName = $val;
    }
    
    public function getPseudo(){
        return $this->pseudo;
    }
    
    public function getLastName(){
        return $this->lastName;
    }
    
    public function getFirstName(){
        return $this->firstName;
    }
    
    public function getToken(){
        return $this->token;
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
    }

    public static function generateToken($ps, $pass) {
        return md5($ps . "_" . $pass);
    }

    public function save() {
        $req = "insert into  user values ('$this->token','$this->pseudo','$this->firstName','$this->lastName','$this->password')";
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
        }
        mysqli_free_result($res);
        return $user;
    }

    /**
     * 
     * @param string $token_user
     * @param string $token_friend
     * @return Boolean
     */
    public static function makefriends($token_user, $token_friend) {
        if (OwerUser::get($token_user) != null && OwerUser::get($token_friend) != null) {
            /** @var string */
            $req = "insert into friends values('$token_user','$token_friend')";
            Connection::getDbMapper()->execStatement($req);

            /** @var string */
            $req1 = "insert into friends values('$token_friend','$token_user')";
            Connection::getDbMapper()->execStatement($req1);
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
     * @param Boolean $visible
     */
    public function updateVisibleTo($visible) {
        $req = "update usergeo set visible ='$visible' where token_user='$this->token'";
        Connection::getDbMapper()->execStatement($req);
    }

    public function delete() {
        /** @var string */
        $req = "delete from usergeo where token_user='$this->token'";
        Connection::getDbMapper()->execStatement($req);

        $req1 = "delete from friends where id_user='$this->token' or id_user_f = '$this->token'";
        Connection::getDbMapper()->execStatement($req1);

        $req2 = "delete from user where token='$this->token'";
        Connection::getDbMapper()->execStatement($req2);
    }

}

?>
