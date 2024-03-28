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

    function getValue($object, $property)
    {
        if (property_exists($object, $property)) {
            return $object->$property;
        } else {
            return null;
        }
    }
}
?>