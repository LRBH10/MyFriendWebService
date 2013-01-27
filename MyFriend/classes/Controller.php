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
                $error['what'] = API_ERROR;
                $error['type'] = 'action not found';
                $error['info'] = "  '" . $_GET['action'] . "' does not supported";
                $error['details'] = '';

                $result = json_encode($error, JSON_PRETTY_PRINT);
                echo $result;
            }
        } else {
            include 'help/help.html';
        }
    }

    public function map() {
        include 'index.php';
    }

    /*     * ************************************************************* Action = createuser
     * Create User Action
     */

    public function createuser() {
        if (isset($_GET['pseudo']) && isset($_GET['password'])) {
            $ps = $_GET['pseudo'];
            $pass = $_GET['password'];
            if ($this->checkuser($ps, $pass) && !OwerUser::existPseudo($ps)) {
                $this->createIt($ps, $pass); 
            }

            /*             * *****        No pseudo and password Error */
        } else {
            $this->renderJSON(API_ERROR, "missing field", "fields 'pseudo' and 'password' are required");
        }
    }

    /*     * ************************************************************* Action = updateuser
     * Create User Action
     */

    public function updateuser() {
        if (isset($_GET['token']) && isset($_GET['city']) && isset($_GET['age']) && isset($_GET['imagelink']) && isset($_GET['number'])) {
            $city = $_GET['city'];
            $age = $_GET['age'];
            $imagelink = $_GET['imagelink'];
            $number = $_GET['number'];

            
            $to = $_GET['token'];
            $user = OwerUser::get($to);

            if ($user != null) {
                $user->updateInfotmations($city, $age, $imagelink,$number);

                $error = array();
                $error['what'] = API_SUCCESS;
                $error['type'] = 'user update';
                $error['info'] = 'update information ok';
                $error['details'] = '';



                $result = json_encode($error, JSON_PRETTY_PRINT);
                echo $result;
            } else {
                $this->tokendoesnot($to);
            }
        } else {
            $this->renderJSON(API_ERROR, "missing field", "fields 'token' and 'city' and 'age' and 'imagelink' and 'number' are required");
        }
    }

    /**
     * Existing User Check
     */
    private function checkuser($ps, $pass) {
        /*         * ****** Error when the User is existing before ************************* */
        if (OwerUser::get(OwerUser::generateToken($ps, $pass))) {
            $error = array();
            $error['what'] = API_ERROR;
            $error['type'] = 'user exist';
            $error['info'] = 'the user exist into database';
            $error['details'] = 'recover password is not disponable for NOW';

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


        $this->renderJSON(API_SUCCESS, "user have been created");
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
                $this->renderJSON(API_SUCCESS, "user deleted");
            } else {
                $this->renderJSON(API_ERROR, "user does not exist", "user with token '$to' does no exist in system");
            }
            /*             * *****        No token Error */
        } else {
            $this->renderJSON(API_ERROR, "missing field", "fields 'token' is required");
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
                $error['what'] = API_SUCCESS;
                $error['type'] = 'user update';
                $error['info'] = '';
                $error['details'] = '';

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
            $this->renderJSON(API_ERROR, "missing field", "fields 'token' and 'lat' and 'lon' are required");
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
                $error['what'] = API_SUCCESS;
                $error['type'] = 'user Connected';
                $error['pseudo'] = $user->getPseudo();
                $error['firstName'] = $user->getFirstName();
                $error['lastName'] = $user->getLastName();
                $error['token'] = $user->getToken();
                $error['publictoken'] = $user->getPublicToken();
                $error['age'] = $user->getAge();
                $error['city'] = $user->getCity();
                $error['imagelink'] = $user->getImageLink();
                $error['number'] = $user->getNumber();

                $error['info'] = "user info";
                $error['details'] = "the public token will given to add friend";

                $result = json_encode($error, JSON_PRETTY_PRINT);
                echo $result;
            } else {
                $this->renderJSON(API_ERROR, "user does not exist", "", "");
            }


            /*             * *****        No pseudo and password Error */
        } else {
            $this->renderJSON(API_ERROR, "missing field", "fields 'pseudo' and 'password' are required");
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
                    $this->renderJSON(API_SUCCESS, "friend added");
                } else {
                    $this->renderJSON(API_ERROR, "friend does'nt exist", "OR he is  in  list friends");
                }
            } else {
                $this->renderJSON(API_ERROR, "user does not exist", "the user with token $to does not exist");
            }
            /*             * *****        No pseudo and password Error */
        } else {
            $this->renderJSON(API_ERROR, "missing field", "fields 'token' and 'friendtoken' are required");
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
                    $this->renderJSON(API_SUCCESS, "friend deleted", "Friend deleted");
                } else {
                    $this->renderJSON(API_SUCCESS, "friend deleted", " Does Not exist");
                }
            } else {
                $this->renderJSON(API_ERROR, "user does not exist", "the user with token $to does not exist");
            }
            /*             * *****        No pseudo and password Error */
        } else {
            $this->renderJSON(API_ERROR, "missing field", "fields 'token' and 'friendtoken' are required");
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
                $render['what'] = API_SUCCESS;
                $render['type'] = "friends infos";
                $render['friends'] = $user->getfriendsInformation();
                $render['info'] = "get information of friends";
                $render['details'] = "";

                $result = json_encode($render, JSON_PRETTY_PRINT);
                echo $result;
            } else {
                $this->renderJSON(API_ERROR, "User Does Not Exist", "user with token $to does not exist in the system");
            }
            /*             * *****        No pseudo and password Error */
        } else {
            $this->renderJSON(API_ERROR, "missing field", "fields 'token' are required");
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
                    $render['what'] = API_SUCCESS;
                    $render['type'] = "friend's infos";
                    $render['info'] = "get location of friend";
                    $render['details'] = "";

                    $render['friends'] = $user->getlocation($fto);
                    $result = json_encode($render, JSON_PRETTY_PRINT);
                    echo $result;
                } else {
                    $this->renderJSON(API_ERROR, "friend does'nt exist", "you must be freind with him");
                }
            } else {
                $this->renderJSON(API_ERROR, "user does not exist", "the user with token $to does not exist");
            }
            /*             * *****        No pseudo and password Error */
        } else {
            $this->renderJSON(API_ERROR, "missing field", "fields 'token' and 'friendtoken' are required");
        }
    }

    /*     * *************************************************** Action=searchfriends ************************************
     * 
     */

    public function searchfriends() {
        if (isset($_GET['token']) && isset($_GET['search'])) {
            $to = $_GET['token'];
            $search = $_GET['search'];
            $user = OwerUser::get($to);
            if ($user != null) {
                $render = array();
                $render['what'] = API_SUCCESS;
                $render['type'] = "search friend";
                $render['friends'] = $user->searchFor($search);
                $render['info'] = "get a list of friends when they exist";
                $render['details'] = "search for :  '$search'";

                $result = json_encode($render, JSON_PRETTY_PRINT);
                echo $result;
            } else {
                $this->renderJSON(API_ERROR, "User Does Not Exist", "user with token $to does not exist in the system");
            }
            /*             * *****        No pseudo and password Error */
        } else {
            $this->renderJSON(API_ERROR, "missing field", "fields 'token' and 'search' are required");
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
