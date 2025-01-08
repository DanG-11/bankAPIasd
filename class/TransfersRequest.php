<?php
namespace BankAPI;

use mysqli;

/**
 * Class for handling transfer requests.
 */

 /**
 * Function that makes a new token.
 * @param int $ip Is a variable that stores the users ip.
 * @param int $UserId a variable that stores the users id.
 * @param mysqli $db is a variable that stores the database info.
 */
class TransfersRequest {
    private string $token;

    /**
     * Function that makes an association table.
     * @param $data gets the file content of php://input.
     * @param $dataArray decodes the $data.
     * token is assigned by $dataArray['token'].
     */
    public function __construct(){
        $data = file_get_contents('php://input');
        $dataArray = json_decode($data, true);
        $this->token = $dataArray['token'];
    }

    /**
     * Function that gets the token.
     */
    public function getToken() : string {
        //Returns the token.
        return $this->token;
    }
}
?>