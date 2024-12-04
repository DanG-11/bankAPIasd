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
            //TODO:
            $db->begin_transaction();
            //TODO:
            try{
                //TODO:
                $sql = "UPDATE account SET amount = amount - ? WHERE accountNo = ?";

                //TODO:
                $query = $db->prepare($sql); 
                //TODO:               
                $query->bind_param('ii', $amount, $source);               
                //TODO: 
                $query->execute();                

                //TODO:
                $sql = "UPDATE account SET amount = amount + ? WHERE accountNo = ?"; 

                //TODO:
                $query = $db->prepare($sql);   
                //TODO:             
                $query->bind_param('ii', $amount, $target); 
                //TODO:               
                $query->execute();                

                //TODO:
                $sql = "INSERT INTO transfer (source, target, amount) VALUES (?, ?, ?)"; 

                //TODO:
                $query = $db->prepare($sql);
                //TODO:                
                $query->bind_param('iii', $source, $target, $amount);  
                //TODO:              
                $query->execute();

                //TODO:
                $db->commit();
            }
            catch(Exception $e){
                //TODO:
                $db->rollback();

                //TODO:
                throw new Exception('Transfer failed');
            }
            
        }

        public static function getTransfers(int $accountNo, mysqli $db) : array {
            $sql = "SELECT * FROM transfer WHERE source = ? OR target = ?";
            $query = $db->prepare($sql);
            $query->bind_param('ii', $accountNo, $accountNo);
            $query->execute();
            $result = $query->get_result();
            $transfers = [];
            while($row = $result->fetch_assoc()){
                $transfers[] = $row;
            }
            return $transfers;
        }
    }
?>