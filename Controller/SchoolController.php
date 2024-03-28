<?php
include ('Request.php');
include ("../Model/School.php");

class SchoolController extends Request
{
    private $request;
    private $school;
    function __construct()
    {
        $this->request = new Request();
        $this->school = new School();
    }

    public function add()
    {
        $data = $this->request->post();
        $name = isset($data->name) ? $data->name : '';
        $founded = isset($data->founded) ? $data->founded : '';
        $address = isset($data->address) ? $data->address : '';

        $array = array(
            'name' => $name,
            'founded' => $founded,
            'address' => $address,
        );

        $insert = $this->school->insert($array);

        $this->result = true;
        $this->message = "Thêm thành công";
        $this->data = $insert;

        return parent::output();
    }

    public function update()
    {
        $data = $this->request->post();
        $id = isset($data->id) ? $data->id : '';
        $name = isset($data->name) ? $data->name : '';
        $founded = isset($data->founded) ? $data->founded : '';
        $address = isset($data->address) ? $data->address : '';

        if ($id != '') {
            $array = array(
                'name' => $name,
                'founded' => $founded,
                'address' => $address,
            );

            $update = $this->school->where('id', $id)->update($array);
            if ($update) {
                $this->result = true;
                $this->message = "Cập nhật thành công!";
            } else {
                $this->result = false;
                $this->error = "Có lỗi xảy ra trong quá trình xử lý";
            }
        } else {
            $this->result = false;
            $this->error = "Thiếu thông tin id";
        }

        return parent::output();
    }

    public function delete()
    {
        $data = $this->request->post();
        $id = isset($data->id) ? $data->id : '';

        if ($id != '') {
            $delete = $this->school->where('id', $id)->delete();

            if ($delete) {
                $this->result = true;
                $this->message = "Xóa thành công!";
            } else {
                $this->result = false;
                $this->error = "Có lỗi xảy ra trong quá trình xử lý";
            }
        } else {
            $this->result = false;
            $this->error = "Thiếu thông tin id";
        }

        return parent::output();
    }

    public function detail()
    {
        $data = $this->request->post();
        $id = isset($data->id) ? $data->id : '';

        if ($id != '') {
            $detail = $this->school->where('id', $id)->get();
            $data = $detail[0];
            $this->result = true;
            $this->message = 'Lấy thông tin thành công!';
            $this->data = $data;
        } else {
            $this->result = false;
            $this->error = "Thiếu thông tin id";
        }

        return parent::output();
    }

    public function all()
    {
        $data = $this->school->all();
        $this->result = true;
        $this->message = 'Lấy thông tin thành công!';
        $this->data = $data;

        return parent::output();
    }
}

$type = $_GET['type'];
$school = new SchoolController();
if ($type == 'add') {
    $school->add();
} else if ($type == 'update') {
    $school->update();
} else if ($type == 'delete') {
    $school->delete();
} else if ($type == 'detail') {
    $school->detail();
} else if ($type == 'all') {
    $school->all();
}
?>