<?php

/**
 * Class for handling everything about tokens
 */
class Token {
    //TODO:
    static function new(string $ip, int $userId, mysqli $db) : string {
        //TODO:
        $hash = hash('sha256', $ip . $userId . time());
        //TODO:
        $sql = "INSERT INTO token (token, ip, user_id) VALUES (?, ?, ?)";
        //TODO:
        $query = $db->prepare($sql);

        //TODO:
        $query->bind_param('ssi', $hash, $ip, $userId);
        //TODO:
        if(!$query->execute()){
            //TODO:
            throw new Exception('Could not create token.');
        }
        else{
            //TODO:
            return $hash;
        }
    }

    //TODO:
    static function check(string $token, string $ip, mysqli $db) : bool {
        //TODO:
        $sql = "SELECT * FROM token WHERE token = ? AND ip = ?";

        //TODO:
        $query = $db->prepare($sql);
        //TODO:
        $query->bind_param('ss', $token, $ip);
        //TODO:
        $query->execute();

        //TODO:
        $result = $query->get_result();

        //TODO:
        if($result->num_rows == 0) {
            //TODO:
            return false;
        }
        else {
            //TODO:
            return true;
        }
    }

    //TODO:
    static function getUserId(string $token, mysqli $db) : int {
        //TODO:
        $sql = "SELECT user_id FROM token WHERE token = ? ORDER BY id DESC LIMIT 1";

        //TODO:
        $query = $db->prepare($sql);
        //TODO:
        $query->bind_param('s', $token);
        //TODO:
        $query->execute();

        //TODO:
        $result = $query->get_result();

        //TODO:
        if($result->num_rows == 0) {
            //TODO:
            throw new Exception('Invalid token');
        }
        else {
            //TODO:
            $row = $result->fetch_assoc();
            //TODO:
            return $row['user_id'];
        }
    }
}
?>