<?php
    /** 
     * Class Transfer for handling everything about money transfers.
    */
    class Transfer{
        /**
         * Function that makes a new money transfer
         * @param int $source Is a variable that stores the
         * @param int $target Is a variable that stores the
         * @param int $amount Is a variable that stores the
         * @param mysqli $db Is a variable that stores the data base info
         */
        public static function new(int $source, int $target, int $amount, mysqli $db) : void {
            //Begins the transaction.
            $db->begin_transaction();
            //TODO:
            try{
                //MySQL statement that updates an account amount of the account that you are getting the money from.
                $sql = "UPDATE account SET amount = amount - ? WHERE accountNo = ?";

                //Preparation of a query to send it to the database.
                $query = $db->prepare($sql); 
                //Binds the query to a prepared statement as parameters.
                $query->bind_param('ii', $amount, $source);               
                //Executes the query.
                $query->execute();                

                //MySQL statement that updates an account amount of the account that you are sending the money to.
                $sql = "UPDATE account SET amount = amount + ? WHERE accountNo = ?"; 

                //Preparation of a query to send it to the database.
                $query = $db->prepare($sql);   
                //Binds the query to a prepared statement as parameters.
                $query->bind_param('ii', $amount, $target); 
                //Executes the query.
                $query->execute();                

                //MySQL statement that send information about the money transfer to the database table transfer.
                $sql = "INSERT INTO transfer (source, target, amount) VALUES (?, ?, ?)"; 

                //Preparation of a query to send it to the database.
                $query = $db->prepare($sql);
                //Binds the query to a prepared statement as parameters.
                $query->bind_param('iii', $source, $target, $amount);  
                //Executes the query.
                $query->execute();

                //Commits the transaction
                $db->commit();
            }
            catch(Exception $e){
                //Undoes the transaction.
                $db->rollback();

                //Shows an error.
                throw new Exception('Transfer failed');
            }
            
        }

        /**
         * Function that retrieves all data from the transfer table.
         * @param int $accountNo Is a variable that stores the accounts number.
         * @param mysqli $db is a variable that stores the database info.
        */
        public static function getTransfers(int $accountNo, mysqli $db) : array {
            //MySQL statement that selects all data from the transfer table that match with the set variables.
            $sql = "SELECT * FROM transfer WHERE source = ? OR target = ?";
            //Preparation of a query to send it to the database.
            $query = $db->prepare($sql);
            //Binds the query to a prepared statement as parameters.
            $query->bind_param('ii', $accountNo, $accountNo);
            //Executes the query.
            $query->execute();
            //Gets the result of a query and stores it.
            $result = $query->get_result();
            //Creates a empty array.
            $transfers = [];
            //While loop that sets the row variable to the empty array.
            while($row = $result->fetch_assoc()){
                $transfers[] = $row;
            }
            //Returns the array.
            return $transfers;
        }
    }
?>