<?php
include ('db_driver.php');
class School extends Db_driver
{
    function __construct()
    {
        parent::connect();
        $this->table = 'school';
    }
}

?>