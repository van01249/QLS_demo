<?php
include ('Request.php');
include ('Output.php');
include ("../Model/RentBook.php");

class RentBookController
{
    protected $request;
    protected $output;
    protected $rentBook;

    function __construct(Request $request, Output $output, RentBook $rentBook)
    {
        $this->request = $request;
        $this->output = $output;
        $this->rentBook = $rentBook;
    }

    public function add()
    {
        $data = $this->request->post();

        foreach ($data as $key => $val) {
            if ($key == 'date_rent') {
                $data->$key = strtotime($val);
            }
        }

        $insert = $this->rentBook->insert($data);

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
            $update = $this->rentBook->where('id', $id)->update($data);
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
            $delete = $this->rentBook->where('id', $id)->delete();

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
            $detail = $this->rentBook->join('books', 'id_book', 'id')->join('students', 'id_student', 'id')->where('rentBooks.id', $id)->select('*, rentBooks.id as rentBook_id')->detail();

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

        $data = $this->rentBook->join('books', 'id_book', 'id')->join('students', 'id_student', 'id')->where($data)->select('*, rentBooks.id as rentBook_id')->get();
        $this->output->result = true;
        $this->output->message = 'Lấy thông tin thành công!';
        $this->output->data = $data;

        return $this->output->output();
    }
}

$type = $_GET['type'];

$rentBook = new RentBookController(new Request, new Output, new RentBook);
if ($type == 'add') {
    echo $rentBook->add();
} else if ($type == 'update') {
    echo $rentBook->update();
} else if ($type == 'delete') {
    echo $rentBook->delete();
} else if ($type == 'detail') {
    echo $rentBook->detail();
} else if ($type == 'search') {
    echo $rentBook->search();
}
?>