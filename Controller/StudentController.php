<?php
include ('Request.php');
include ('Output.php');
include ("../Model/Students.php");

class StudentController
{
    private $students;
    private $request;
    private $output;

    function __construct(Request $request, Output $output, Students $students)
    {
        $this->students = $students;
        $this->request = $request;
        $this->output = $output;
    }

    public function add()
    {
        $data = $this->request->post();
        $insert = $this->students->insert($data);

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
            $update = $this->students->where('id', $id)->update($data);
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
            $delete = $this->students->where('id', $id)->delete();

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
            $detail = $this->students->where('id', $id)->detail();

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

        $data = $this->students->where($data)->get();
        $this->output->result = true;
        $this->output->message = 'Lấy thông tin thành công!';
        $this->output->data = $data;

        return $this->output->output();
    }
}

$type = $_GET['type'];

$student = new StudentController(new Request, new Output, new Students);
if ($type == 'add') {
    echo $student->add();
} else if ($type == 'update') {
    echo $student->update();
} else if ($type == 'delete') {
    echo $student->delete();
} else if ($type == 'detail') {
    echo $student->detail();
} else if ($type == 'search') {
    echo $student->search();
}
?>