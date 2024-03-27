<?php
include ('db_driver.php');
class School extends Db_driver
{
    function __construct()
    {
        parent::connect();
        $this->table = 'school';
        $this->data = '*';
    }
}

$school = new School();
// var_dump($school->all());


$where = ['school.id', 1];

// $where = [
//     ['id', 1],
//     ['id', '>', 1],
//     ['id', '<=', 5],
// ];

$where1 = [
    'school.id' => 1,
    'school.name' => "Van",
];

$whereIn = [
    'school.id' => [1, 2, 3],
    'school.name' => ["Eto'o"]
];

// $school->where(['time' => '2023', 'name' => 'Van'])->where("id", 5)->whereIn($whereIn)->whereOr(['time' => '2023', 'name' => 'Van'])->get();

// $school->where('id', 5)->where(['time' => '2023', 'name' => 'Van'])->get();
// $school->orderBy(["school.id" => "DESC", 'school.name' => "ASC"]);

// $list = $school->select('id')->where('founded', 2001)->whereIn($whereIn)->join("students", "school.i1d", "students.id_school")->get();
$data = [
    'name' => "NVanT22222",
];
// var_dump($list);
$dataInsert = array(
    'name' => "Van'2",
    'founded' => '2001',
    'address' => 'Thái Bình2',
);
// $insert = $school->insert($dataInsert);
// var_dump($insert->founded);
// $update = $school->where('id', 10)->update($data);
// var_dump($update);
// $school->where('i3d', 93)->delete();
$sql = "SELECT name FROM students WHERE school.id = students.id AND students.id = 1";
$school->raw('school.id', 'IN', $sql)->get();
// $school->whereHas('students', 'students.name', ['students.id' => 1], 'school.id', 'students.id_school')->get();
?>