<?php
class Request
{
    protected $result;
    protected $message;
    protected $data;
    protected $error;
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

    function output()
    {
        $result = [];
        $result['result'] = $this->result;

        if ($this->message || $this->data) {
            $data = [
                'message' => $this->message,
                'data' => $this->data
            ];
            $result['data'] = $data;
        }

        if ($this->error)
            $result['error'] = $this->error;

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}
?>