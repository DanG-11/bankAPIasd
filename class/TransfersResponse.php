<?php
namespace BankAPI;
use mysqli;
class TransfersResponse {
    private array $transfer;
    private string $error;

    public function __construct() {
        $this->error = "";
    }

    public function getJSON() {
        $array = array();
        $array['transfer'] = $this->transfer;
        $array['error'] = $this->error;
        return json_encode($array);
    }

    public function setTransfers(array $transfer) {
        $this->transfer = $transfer;
    }

    public function setError(string $error) {
        $this->error = $error;
    }

    public function send() {

        if($this->error != "") {
            header('HTTP/1.1 401 Unauthorized');
        }
        else {
            header('HTTP/1.1 200 OK');
        }

        header('Content-Type: application/json');
        echo $this->getJSON();
    }
}
?>