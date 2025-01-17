<?php
namespace BankAPI;

use mysqli;

/**
 * Class for handling transfer responses.
 * @param $transfer Is a variable that  transfer details.
 * @param $error stores the error details.
 */
class TransfersResponse {
    private array $transfer;
    private string $error;

    /**
     * Function that makes a constructor.
     */
    public function __construct() {
        $this->error = "";
    }

    /**
     * Function that gets a JSON array
     * @param $array Stores an array
     */
    public function getJSON() {
        $array = array();
        $array['transfer'] = $this->transfer;
        $array['error'] = $this->error;
        return json_encode($array);
    }

    /**
     * Function that sets a transfer
     */
    public function setTransfers(array $transfer) {
        $this->transfer = $transfer;
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

        //If it returns an error either sends the Unauthorized header or OK.
        if($this->error != "") {
            header('HTTP/1.1 401 Unauthorized');
        }
        else {
            header('HTTP/1.1 200 OK');
        }

        //Sends a header.
        header('Content-Type: application/json');
        echo $this->getJSON();
    }
}
?>