<?php
include ('db_driver.php');

class Book extends Db_driver
{
    function __construct()
    {
        parent::connect();
        $this->table = 'students';
    }
}
?>