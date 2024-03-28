<?php
include ('db_driver.php');

class Students extends Db_driver
{
    function __construct()
    {
        parent::connect();
        $this->table = "books";
    }
}
?>