<?php
class Request
{

    function get()
    {
        $listValue = (object) ($_GET);

        return $listValue;
    }

    function post()
    {
        $listValue = (object) ($_POST);

        return $listValue;
    }
}
?>