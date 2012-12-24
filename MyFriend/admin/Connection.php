<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Connection
 *
 * @author bibouh
 */
class Connection {

    /**
     * @var Connection
     */
    private static $connection = null;

    /**
     * @var mysqli
     */
    private $db = null;

    private function __construct($databasename) {
        $this->db = mysqli_connect('localhost', 'root', '', $databasename);
        /* Vérification de la connexion */
        if ($this->db->connect_errno) {
            printf("Échec de la connexion : %s\n", $this->db->connect_error);
            exit();
        }
    }

    /**
     *  get Singloton of database
     * @param string $databasename
     * @return Connection
     */
    public static function getDbMapper($databasename = "") {
        if (Connection::$connection != null) {
            return Connection::$connection;
        } else {
            Connection::$connection = new Connection($databasename);
            return Connection::$connection;
        }
    }

    /**
     * 
     * @param strinf $req
     * @return mixed
     */
    public function execStatement($req) {
        $result = mysqli_query($this->db, $req);
        if ($result) {
           return $result;
        } else {
            echo '=====================================<br/> REQ : '.$req.'<br/>';
            echo $this->db->error.'<br/>=====================================<br/>';
            return null;
        }
    }

    public function saveObject($obj) {
        $obj->save();
    }

    
    public function test() {
       
    }
}

?>
