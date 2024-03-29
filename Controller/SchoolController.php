<?php
include ('Request.php');
include ('Output.php');
include ("../Model/School.php");

class SchoolController
{
    private $request;
    private $output;
    private $school;
    function __construct(Request $request, Output $output, School $school)
    {
        $this->request = $request;
        $this->output = $output;
        $this->school = $school;
    }

    public function add()
    {
        $data = $this->request->post();
        $insert = $this->school->insert($data);

        $this->output->result = true;
        $this->output->message = "Thêm thành công";
        $this->output->data = $insert;

        return $this->output->output();
    }

    public function update()
    {
        $data = $this->request->post();

        if (isset($data->id)) {
            $id = $data->id;
            $data = (array) $data;
            $update = $this->school->where('id', $id)->update($data);
            if ($update) {
                $this->output->result = true;
                $this->output->message = "Cập nhật thành công!";
            } else {
                $this->output->result = false;
                $this->output->error = "Có lỗi xảy ra trong quá trình xử lý";
            }
        } else {
            $this->output->result = false;
            $this->output->error = "Thiếu thông tin id";
        }

        return $this->output->output();
    }

    public function delete()
    {
        $data = $this->request->post();

        if (isset($data->id)) {
            $id = $data->id;
            $delete = $this->school->where('id', $id)->delete();

            if ($delete) {
                $this->output->result = true;
                $this->output->message = "Xóa thành công!";
            } else {
                $this->output->result = false;
                $this->output->error = "Có lỗi xảy ra trong quá trình xử lý";
            }
        } else {
            $this->output->result = false;
            $this->output->error = "Thiếu thông tin id";
        }

        return $this->output->output();
    }

    public function detail()
    {
        $data = $this->request->post();

        if (isset($data->id)) {
            $id = $data->id;
            $detail = $this->school->where('id', $id)->first();

            $this->output->result = true;
            $this->output->message = 'Lấy thông tin thành công!';
            $this->output->data = $detail;
        } else {
            $this->output->result = false;
            $this->output->error = "Thiếu thông tin id";
        }

        return $this->output->output();
    }

    public function search()
    {
        $data = $this->request->post();

        $data = (array) $data;

        $data = $this->school->where($data)->get();
        $this->output->result = true;
        $this->output->message = 'Lấy thông tin thành công!';
        $this->output->data = $data;

        return $this->output->output();
    }
}

$type = $_GET['type'];

$school = new SchoolController(new Request, new Output, new School);
if ($type == 'add') {
    echo $school->add();
} else if ($type == 'update') {
    echo $school->update();
} else if ($type == 'delete') {
    echo $school->delete();
} else if ($type == 'detail') {
    echo $school->detail();
} else if ($type == 'search') {
    echo $school->search();
}
?>