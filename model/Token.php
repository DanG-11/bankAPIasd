<?php

/**
 * Class for handling everything about tokens.
 */
class Token {
    /**
     * Function that makes a new token.
     * @param int $ip Is a variable that stores the users ip.
     * @param int $UserId a variable that stores the users id.
     * @param mysqli $db is a variable that stores the database info.
     */
    static function new(string $ip, int $userId, mysqli $db) : string {
        //variable that stores a newly maid hash.
        $hash = hash('sha256', $ip . $userId . time());
        //MySQL statement that inserts the token, ip and user id data into the table token.
        $sql = "INSERT INTO token (token, ip, user_id) VALUES (?, ?, ?)";
        //Preparation of a query to send it to the database.
        $query = $db->prepare($sql);

        //Binds the query to a prepared statement as parameters.
        $query->bind_param('ssi', $hash, $ip, $userId);
        //If loop that shows an error.
        if(!$query->execute()){
            //Shows an error.
            throw new Exception('Could not create token.');
        }
        else{
            //Returns the hash
            return $hash;
        }
    }

    /**
     * Function that checks the token.
     * @param string $token Is a variable that stores the users ip.
     * @param int $ip a variable that stores the users id.
     * @param mysqli $db is a variable that stores the database info.
     */
    static function check(string $token, string $ip, mysqli $db) : bool {
        //MySQL statement that selects all data from the token table that matches the set variables.
        $sql = "SELECT * FROM token WHERE token = ? AND ip = ?";

        //Preparation of a query to send it to the database.
        $query = $db->prepare($sql);
        //Binds the query to a prepared statement as parameters.
        $query->bind_param('ss', $token, $ip);
        //Executes the query.
        $query->execute();

        //Gets the result of a query and stores it.
        $result = $query->get_result();

        //If loop that returns either true or false.
        if($result->num_rows == 0) {
            //Returns false.
            return false;
        }
        else {
            //Returns true
            return true;
        }
    }

    /**
     * Function that selects the UserId.
     * @param string $token Is a variable that stores the users ip.
     * @param mysqli $db is a variable that stores the database info.
     */
    static function getUserId(string $token, mysqli $db) : int {
        //MySQL statement that only 1 user id from the token table with set variables ordered by id.
        $sql = "SELECT user_id FROM token WHERE token = ? ORDER BY id DESC LIMIT 1";

        //Preparation of a query to send it to the database.
        $query = $db->prepare($sql);
        //Binds the query to a prepared statement as parameters.
        $query->bind_param('s', $token);
        //Executes the query.
        $query->execute();

        //Gets the result of a query and stores it.
        $result = $query->get_result();

        //If loop that either retrieves data from the database or shows an error.
        if($result->num_rows == 0) {
            //Shows an error.
            throw new Exception('Invalid token');
        }
        else {
            //Retrieves data from the database.
            $row = $result->fetch_assoc();
            //Returns the users id.
            return $row['user_id'];
        }
    }
}
?>