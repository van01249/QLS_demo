<?php
class Request
{
    private $value;
    private $dataType;

    private function dataType()
    {
        switch ($this->dataType) {
            case 'int':
                $this->value = intval($this->value);
                break;
            case 'str':
                $this->value = strval($this->value);
                break;
            case 'float':
                $this->value = floatval($this->value);
                break;
            case 'double':
                $this->value = doubleval($this->value);
                break;
            case 'array':
                break;
            default:
                $this->value = intval($this->value);
        }

        return $this->value;
    }

    function get($name_value, $data_type = "int", $default_value = 0)
    {
        $this->value = $default_value;

        if (isset ($_GET[$name_value]))
            $this->value = $_GET[$name_value];

        $this->dataType = trim(strtolower($data_type));
        $this->dataType();

        return $this->value;
    }

    function post($name_value, $data_type = "int", $default_value = 0)
    {
        $this->value = $default_value;

        if (isset ($_POST[$name_value]))
            $this->value = $_POST[$name_value];

        $this->dataType = trim(strtolower($data_type));
        $this->dataType();

        return $this->value;
    }

}

$request = new Request();
$get = $request->get('van');
var_dump($get);

?>