<?php
namespace BankAPI;
use mysqli;
/** 
 * Class Account for handling everything about accounts.
*/
class Account {
    private $accountNo;
    private $amount;
    private $name;

    /**
     * Function for handling account construction.
     * @param int $accountNo Is a variable that stores the account number.
     * @param int $amount Is a variable that stores the amount of money in grosze in the account.
     * @param string $name Is a variable that stores the name of the account.
     */
    public function __construct($accountNo, $amount, $name) {
        $this->accountNo = $accountNo;
        $this->amount = $amount;
        $this->name = $name;
    }

    /**
     * Function that retrieves account number.
     * @param int $userId Is a variable that stores the users id.
     * @param mysqli $db is a variable that stores the database info.
     */
    public static function getAccountNo(int $userId, mysqli $db) : int {
        //MySQL statement that selects ONLY 1 account number from the account table from the data of user id.
        $sql = "SELECT accountNo FROM account WHERE user_id = ? LIMIT 1";

        //Query to zapytanie.
        //Preparation of a query to send it to the database.
        $query = $db->prepare($sql);
        //Binds the query to a prepared statement as parameters.
        $query->bind_param('i', $userId);
        //Executes the query
        $query->execute();

        //Gets the result of a query and stores it.
        $result = $query->get_result();

        //Fetches the account information from the $result variable.
        $account = $result->fetch_assoc();
        
        //Returns the account number from the $account variable.
        return $account['accountNo'];
    }

    /**
     * Function that retrieves account number
     * @param int $userId Is a variable that stores the users id.
     * @param mysqli $db Is a variable that stores the database info.
     */
    public static function getAccount(int $accountNo, mysqli $db) : Account {
        //Result of a MySQL query that selects everything from the account table from the account number.
        $result = $db->query("SELECT * FROM account WHERE accountNo = $accountNo");
        //Stores the fetched account data from the $result variable.
        $account = $result->fetch_assoc();
        //Makes a new account object with the data of account number amount and name of the account stored in the $account variable.
        $account = new Account($account['accountNo'], $account['amount'], $account['name']);
        //returns $account variable.
        return $account;
    }

    /**
     * @param int $accountNo Is a variable that stores the account number.
     * @param mysqli $db Is a variable that stores the database info.
     */
    public static function getAccountAmount(int $accountNo, mysqli $db) : int {
        //TODO:
        $sql = "SELECT amount FROM account WHERE accountNo = ? LIMIT 1";

        //TODO:
        $query = $db->prepare($sql);
        //TODO:
        $query->bind_param('i', $accountNo);
        //TODO:
        $query->execute();

        //TODO:
        $result = $query->get_result();

        //TODO:
        $account = $result->fetch_assoc();
        
        //TODO:
        return $account['amount'];
    }

    /**
     * Function that makes an association table
     * Account number is assigned from the property this->accountNo
     * Account amount is assigned from the property this->amount
     * Account name is assigned from the property this->name
     */
    public function getArray() : array {
        $array = [
            'accountNo' => $this->accountNo,
            'amount' => $this->amount,
            'name' => $this->name
        ];
        //Returns $array variable
        return $array;
    }
}
?>