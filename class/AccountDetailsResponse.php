<?php
namespace BankAPI;
use mysqli;

/**
 * Class for handling everything about account detail responses
 * @param $account Is a variable that stores the users account details.
 * @param $error Is a variable that stores the error details.
 */
class AccountDetailsResponse {
    private array $account;
    private string $error;

    /**
     * Function that makes a constructor.
     */
    public function __construct() {
        $this->error = "";
    }

    /**
     * Function that gets a JSON array.
     * @param $array Stores an array.
     */
    public function getJSON() {
        $array = array();
        $array['account'] = $this->account;
        $array['error'] = $this->error;
        return json_encode($array);
    }

    /**
     * Function that sets an account.
     */
    public function setAccount(array $account) {
        $this->account = $account;
    }

    /**
     * Function that sets an error.
     */
    public function setError(string $error) {
        $this->error = $error;
    }
    
    /**
     * Function that sends an answer to the API.
     */
    public function send() {

        //If it returns an error either sends the Unauthorized header or OK
        if($this->error != "") {
            header('HTTP/1.1 401 Unauthorized');
        } else {
            header('HTTP/1.1 200 OK');
        }
        header('Content-Type: application/json');
        echo $this->getJSON();
    }
}
?>