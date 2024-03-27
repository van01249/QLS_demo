<?php
include ('db_driver.php');

class RentBook extends Db_driver
{
    function __construct()
    {
        parent::connect();
        $this->table = "rentBooks";
        $this->data = '*';
    }
}

$rentBook = new RentBook();

//Lay danh sach hoc sinh thue sach theo ngay

$rentBook->join('students', 'students.id', 'rentBooks.id_student')->join('books', 'books.id', "rentBooks.id_book")->where('rent_date', '1711458580')->get();
?>