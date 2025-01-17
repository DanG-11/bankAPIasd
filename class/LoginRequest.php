<?php
namespace BankAPI;

class LoginRequest {
    private $login;
    private $password;

    /**
     * Function that makes a constructor.
     * @param $data Gets the file content of php://input.
     */
    public function __construct(){
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);
        $this->login = $data['login'];
        $this->password = $data['password'];
    }

    /**
     * Function that gets the login.
     */
    public function getLogin() : string {
        //Returns the login.
        return $this->login;
    }

    /**
     * Function that gets the password.
     */
    public function getPassword() : string {
        //Returns the password.
        return $this->password;
    }
}
?>