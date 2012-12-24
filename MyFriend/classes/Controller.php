<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Controller
 *
 * @author bibouh
 */
class Controller {

    /**
     * 
     * @param string $action
     * @return string 
     */
    public static function dispatch() {

        if (isset($_GET['action'])) {
            $controller = new Controller();
            $action = $_GET['action'];
            if (method_exists($controller, $action)) {
                $controller->$action();
            } else {
                $error = array();
                $error['what'] = 'error';
                $error['type'] = 'action not found';
                $error['actionNotFounded'] = $_GET['action'];
                $result = json_encode($error, JSON_PRETTY_PRINT);
                echo $result;
            }
        } else {
            include 'help/help.html';
        }
    }

    /*     * ************************************************************* Action = createuser
     * Create User Action
     */

    public function createuser() {
        if (isset($_GET['pseudo']) && isset($_GET['password'])) {
            $ps = isset($_GET['pseudo']);
            $pass = isset($_GET['password']);
            if ($this->checkuser($ps, $pass)) {
                $this->createIt($ps, $pass);
            }

            /*             * *****        No pseudo and password Error */
        } else {
            $error = array();
            $error['what'] = 'error';
            $error['type'] = 'missing field';
            $error['info'] = "fields 'pseudo' and 'password' are required";
            $result = json_encode($error, JSON_PRETTY_PRINT);
            echo $result;
            return FALSE;
        }
    }

    /**
     * Existing User Check
     */
    private function checkuser($ps, $pass) {
        /*         * ****** Error when the User is existing before ************************* */
        if (OwerUser::get(OwerUser::generateToken($ps, $pass))) {
            $error = array();
            $error['what'] = 'error';
            $error['type'] = 'user exist';
            $result = json_encode($error, JSON_PRETTY_PRINT);
            echo $result;
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Create User
     */
    private function createIt($ps, $pass) {
        if (isset($_GET['firstname']) && isset($_GET['lastname'])) {
            $fn = $_GET['firstname'];
            $ln = $_GET['lastname'];
            $user = new OwerUser($ps, $pass);
            $user->setFirstName($fn);
            $user->setLastName($ln);
            $user->save();
        } else {
            $user = new OwerUser($ps, $pass);
            $user->save();
        }
        $error = array();
        $error['what'] = 'succes';
        $error['type'] = 'user have been created';
        $error['token'] = $user->getToken();

        $result = json_encode($error, JSON_PRETTY_PRINT);
        echo $result;
    }

    /*     * ******************************************************** Action = deleteuser *****************************
     * to delete user from the system
     */

    public function deleteuser() {
        if (isset($_GET['token'])) {
            $to = $_GET['token'];
            $user =OwerUser::get($to); 
            if ($user != null) {
                $user->delete();
                $error = array();
                $error['what'] = 'succes';
                $error['type'] = 'user deleted';
                $result = json_encode($error, JSON_PRETTY_PRINT);
                echo $result;
                
            } else {
                //************************* USER DOES NOT EXIST ************************************/
                $error = array();
                $error['what'] = 'error';
                $error['type'] = 'user does not exist';
                $error['info'] = "user with token '$to' does no exist in system";
                $result = json_encode($error, JSON_PRETTY_PRINT);
                echo $result;
            }
            /*             * *****        No token Error */
        } else {
            $error = array();
            $error['what'] = 'error';
            $error['type'] = 'missing field';
            $error['info'] = "fields 'token' is required";
            $result = json_encode($error, JSON_PRETTY_PRINT);
            echo $result;
        }
    }

    
    
    /************************************************************** Action = update  **************************/
}

?>
