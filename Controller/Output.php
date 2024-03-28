<?php
class Output
{
    public $result;
    public $message;
    public $data;
    public $error;

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

        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}
?>