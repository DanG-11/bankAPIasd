<?php
    /** 
     * Class Transfer for handling everything about money transfers.
    */
    class Transfer{
        /**
         * Function that makes a new transfer
         * @param int $source TODO: Dopisać opis
         * @param int $target TODO: Dopisać opis
         * @param int $amount TODO: Dopisać opis
         * @param mysqli $db TODO: Dopisać opis
         */
        public static function new(int $source, int $target, int $amount, mysqli $db) : void {
            $db->begin_transaction();
            try{
                $sql = "UPDATE account SET amount = amount - ? WHERE accountNo = ?";

                $query = $db->prepare($sql);                
                $query->bind_param('ii', $amount, $source);                
                $query->execute();                

                $sql = "UPDATE account SET amount = amount + ? WHERE accountNo = ?"; 

                $query = $db->prepare($sql);                
                $query->bind_param('ii', $amount, $target);                
                $query->execute();                

                $sql = "INSERT INTO transfer (source, target, amount) VALUES (?, ?, ?)"; 

                $query = $db->prepare($sql);                
                $query->bind_param('iii', $source, $target, $amount);                
                $query->execute();

                $db->commit();
            }
            catch(Exception $e){
                $db->rollback();

                throw new Exception('Transfer failed');
            }
            
        }
    }
?>