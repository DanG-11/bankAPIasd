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
     * Account constructor
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
     * Function that retrieves account number
     * @param int $userId Is a variable that stores the users id.
     * @param mysqli $db is a variable that stores the database data.
     */
    public static function getAccountNo(int $userId, mysqli $db) : int {
        $sql = "SELECT accountNo FROM account WHERE user_id = ? LIMIT 1";

        $query = $db->prepare($sql);
        $query->bind_param('i', $userId);
        $query->execute();

        $result = $query->get_result();

        $account = $result->fetch_assoc();
        
        return $account['accountNo'];
    }

    /**
     * Function that retrieves account number
     * @param int $userId Is a variable that stores the users id.
     * @param mysqli $db Is a variable that stores the database data.
     */
    public static function getAccount(int $accountNo, mysqli $db) : Account {
        $result = $db->query("SELECT * FROM account WHERE accountNo = $accountNo");
        $account = $result->fetch_assoc();
        $account = new Account($account['accountNo'], $account['amount'], $account['name']);
        return $account;
    }

    /**
     * @param int $accountNo Is a variable that stores the account number.
     * @param mysqli $db Is a variable that stores the database data.
     */
    public static function getAccountAmount(int $accountNo, mysqli $db) : int {
        $sql = "SELECT amount FROM account WHERE accountNo = ? LIMIT 1";

        $query = $db->prepare($sql);
        $query->bind_param('i', $accountNo);
        $query->execute();

        $result = $query->get_result();

        $account = $result->fetch_assoc();
        
        return $account['amount'];
    }

    /**
     * TODO: (nie wiem co tu wpisac nie chce mi sie myslec).
     */
    public function getArray() : array {
        $array = [
            'accountNo' => $this->accountNo,
            'amount' => $this->amount,
            'name' => $this->name
        ];
        return $array;
    }
}
?>