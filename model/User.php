<?php
/**
 * Class for handling everything about users
 */
class User {
    /**
     * Function that handles logins.
     * @param $login Is a variable that stores the users login details.
     * @param $password Is a variable that stores the users password details.
     * @param $db Is a variable that stores the database details.
     */
    static function login(string $login, string  $password, mysqli $db) : int {
        //TODO:
        $sql = "SELECT id, passwordHash FROM user WHERE email = ?";
        //TODO:
        $query = $db->prepare($sql);
        //TODO:
        $query->bind_param('s', $login);
        //TODO:
        $query->execute();
        //TODO:
        $result = $query->get_result();

        //TODO:
        if($result->num_rows == 0){
            //TODO:
            throw new Exception('Invalid login or password.');
        }
        else {
            //TODO:
            $user = $result->fetch_assoc();
            //TODO:
            $id = $user['id'];
            //TODO:
            $hash = $user['passwordHash'];

            //TODO:
            if(password_verify($password, $hash)) {
                //TODO:
                return $id;
            }
            else {
                //TODO:
                throw new Exception('Invalid login or password.');
            }
        }
    }
}
?>