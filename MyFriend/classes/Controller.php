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
                $error['info'] = "  '" . $_GET['action'] . "' does not supported";
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
            $ps = $_GET['pseudo'];
            $pass = $_GET['password'];
            if ($this->checkuser($ps, $pass)) {
                $this->createIt($ps, $pass);
            }

            /*             * *****        No pseudo and password Error */
        } else {
            $this->renderJSON("error", "missing field", "fields 'pseudo' and 'password' are required");
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


        $this->renderJSON("succes", "user have been created");
    }

    /*     * ******************************************************** Action = deleteuser *****************************
     * to delete user from the system
     */

    public function deleteuser() {
        if (isset($_GET['token'])) {
            $to = $_GET['token'];
            $user = OwerUser::get($to);
            if ($user != null) {
                $user->delete();
                $this->renderJSON("succes", "user deleted");
            } else {
                $this->renderJSON("error", "user does not exist", "user with token '$to' does no exist in system");
            }
            /*             * *****        No token Error */
        } else {
            $this->renderJSON("error", "missing field", "fields 'token' is required");
        }
    }

    /*     * ************************************************************ Action = update  *************************
     * To update user Longitude and latitude
     */

    public function update() {
        if (isset($_GET['token']) && isset($_GET['lon']) && isset($_GET['lat'])) {
            $lon = $_GET['lon'];
            $lat = $_GET['lat'];
            $to = $_GET['token'];
            $user = OwerUser::get($to);
            if ($user != null) {
                $user->updateTo($lon, $lat);

                $error = array();
                $error['what'] = 'succes';
                $error['type'] = 'user update';

                if (isset($_GET['visible'])) {
                    $vi = $_GET['visible'];
                    if ($vi == 'true') {
                        $user->updateVisibleTo(TRUE);
                    } else if ($vi == 'false') {
                        $user->updateVisibleTo(FALSE);
                    } else {
                        $error['warnning'] = " visible must be 'true' or 'false' ";
                    }
                }

                $result = json_encode($error, JSON_PRETTY_PRINT);
                echo $result;
            } else {
                $this->tokendoesnot($to);
            }
        } else {
            $this->renderJSON("error", "missing field", "fields 'token' and 'lat' and 'lon' are required");
        }
    }

    /**     * *********************************************** Action = login *************************************
     *  To login into System
     */
    public function login() {
        if (isset($_GET['pseudo']) && isset($_GET['password'])) {
            $ps = $_GET['pseudo'];
            $pass = $_GET['password'];
            $user = OwerUser::get(OwerUser::generateToken($ps, $pass));
            if ($user != null) {
                $error = array();
                $error['what'] = 'succes';
                $error['type'] = 'user Connected';
                $error['pseudo'] = $user->getPseudo();
                $error['firstName'] = $user->getFirstName();
                $error['lastName'] = $user->getLastName();
                $error['token'] = $user->getToken();
                $error['publictoken'] = $user->getPublicToken();
                $result = json_encode($error, JSON_PRETTY_PRINT);
                echo $result;
            } else {
                $this->tokendoesnot("", false);
            }


            /*             * *****        No pseudo and password Error */
        } else {
            $this->renderJSON("error", "missing field", "fields 'pseudo' and 'password' are required");
        }
    }

    /**     * *********************************************************** Action = addfriend **************************************
     * 
     */
    public function addfriend() {
        if (isset($_GET['token']) && isset($_GET['friendtoken'])) {
            $to = $_GET['token'];
            $fto = $_GET['friendtoken'];
            $user = OwerUser::get($to);
            if ($user != null) {
                if (OwerUser::existUSER($fto) && !$user->existfriend($fto)) {
                    $user->addfriend($fto);
                    $this->renderJSON("succes", "friend added");
                } else {
                    $this->renderJSON("error", "friend does'nt exist", "OR he is  in  list friends");
                }
            } else {
                $this->renderJSON("error", "user does not exist", "the user with token $to does not exist");
            }
            /*             * *****        No pseudo and password Error */
        } else {
            $this->renderJSON("error", "missing field", "fields 'token' and 'friendtoken' are required");
        }
    }

    /**     * *********************************************************** Action = deletefriend **************************************
     * 
     */
    public function deletefriend() {
        if (isset($_GET['token']) && isset($_GET['friendtoken'])) {
            $to = $_GET['token'];
            $fto = $_GET['friendtoken'];
            $user = OwerUser::get($to);
            if ($user != null) {
                if ($user->removefriend($fto)) {
                    $this->renderJSON("success", "friend deleted", "Friend deleted");
                } else {
                    $this->renderJSON("success", "friend deleted", " Does Not exist");
                }
            } else {
                $this->renderJSON("error", "user does not exist", "the user with token $to does not exist");
            }
            /*             * *****        No pseudo and password Error */
        } else {
            $this->renderJSON("error", "missing field", "fields 'token' and 'friendtoken' are required");
        }
    }

    /**     * *********************************************************** Action = getfreinds **************************************
     * 
     */
    public function getfriends() {
        if (isset($_GET['token'])) {
            $to = $_GET['token'];
            $user = OwerUser::get($to);
            if ($user != null) {
                $render = array();
                $render['what'] = "succes";
                $render['type'] = "friends infos";
                $render['friends'] = $user->getfriendsInformation();
                $result = json_encode($render, JSON_PRETTY_PRINT);
                echo $result;
            } else {
                $this->renderJSON("error", "User Does Not Exist", "user with token $to does not exist in the system");
            }
            /*             * *****        No pseudo and password Error */
        } else {
            $this->renderJSON("error", "missing field", "fields 'token' are required");
        }
    }

    /**     * *********************************************************** Action = getlocationfriend **************************************
     * 
     */
    public function getlocationfriend() {
        if (isset($_GET['token']) && isset($_GET['friendtoken'])) {
            $to = $_GET['token'];
            $fto = $_GET['friendtoken'];
            $user = OwerUser::get($to);
            if ($user != null) {
                if ($user->existfriend($fto)) {
                    $render = array();
                    $render['what'] = "succes";
                    $render['type'] = "friends infos";
                    $render['friends'] = $user->getlocation($fto);
                    $result = json_encode($render, JSON_PRETTY_PRINT);
                    echo $result;
                } else {
                    $this->renderJSON("error", "friend does'nt exist", "you must be freind with him");
                }
            } else {
                $this->renderJSON("error", "user does not exist", "the user with token $to does not exist");
            }
            /*             * *****        No pseudo and password Error */
        } else {
            $this->renderJSON("error", "missing field", "fields 'token' and 'friendtoken' are required");
        }
    }

    private function renderJSON($what, $type, $info = "", $details = "") {
        $render = array();
        $render['what'] = $what;
        $render['type'] = $type;
        $render['info'] = $info;
        $render['details'] = $details;
        $result = json_encode($render, JSON_PRETTY_PRINT);
        echo $result;
    }

}

?>
