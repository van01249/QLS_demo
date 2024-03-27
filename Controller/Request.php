<?php
class Request
{
    function get()
    {
        $listValue = json_decode(json_encode($_GET));

        return $listValue;
    }

    function post($name_value, $data_type = "int", $default_value = 0)
    {
        $listValue = json_decode(json_encode($_POST));

        return $listValue;
    }

}

$request = new Request();
$get = $request->get();
var_dump($get->van);

// $post = $request->post('aa');
// var_dump($post);
?>