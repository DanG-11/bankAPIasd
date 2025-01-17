<?php
namespace BankAPI;

use mysqli;

/**
 * Class for handling transfer requests.
 * @param $token Is a variable that stores the token.
 */
class TransfersRequest {
    private string $token;

    /**
     * Function that makes a constructor.
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