<?php
include ('Request.php');
include ('Output.php');
include ("../Model/Book.php");

class BookController
{
    private $request;
    private $output;
    private $book;
    function __construct(Request $request, Output $output, Book $book)
    {
        $this->request = $request;
        $this->output = $output;
        $this->book = $book;
    }

    public function add()
    {
        $data = $this->request->post();
        $insert = $this->book->insert($data);

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
            unset($data->id);
            $data = (array) $data;
            $update = $this->book->where('id', $id)->update($data);
            if ($update) {
                $this->output->result = true;
                $this->output->message = "Cập nhật thành công!";
            } else {
                $this->output->result = false;
                $this->output->error = "Thiếu dữ liệu cập nhật";
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
            $delete = $this->book->where('id', $id)->delete();

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
            $detail = $this->book->where('id', $id)->first();

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

        $data = $this->book->where($data)->get();
        $this->output->result = true;
        $this->output->message = 'Lấy thông tin thành công!';
        $this->output->data = $data;

        return $this->output->output();
    }
}

$type = $_GET['type'];

$book = new bookController(new Request, new Output, new Book);
if ($type == 'add') {
    echo $book->add();
} else if ($type == 'update') {
    echo $book->update();
} else if ($type == 'delete') {
    echo $book->delete();
} else if ($type == 'detail') {
    echo $book->detail();
} else if ($type == 'search') {
    echo $book->search();
}
?>