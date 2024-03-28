<?php
include ('db_driver.php');

class RentBook extends Db_driver
{
    function __construct()
    {
        parent::connect();
        $this->table = "rentBooks";
    }
}

?>