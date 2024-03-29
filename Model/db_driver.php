<?php
class Db_driver
{
    private $conn;
    protected $table;
    protected $data = '*';
    private $where;
    private $subQuery;
    private $orderBy;
    private $having;
    private $groupBy;
    private $limit;
    private $join;
    private $sql;
    private $raw;
    function __construct($data = [])
    {
        $this->table = isset($data['table']) ? $data['table'] : '';
        $this->data = isset($data['data']) ? $data['data'] : '*';
        $this->where = isset($data['where']) ? $data['where'] : '';
        $this->subQuery = isset($data['subQuery']) ? $data['subQuery'] : '';
        $this->orderBy = isset($data['orderBy']) ? $data['orderBy'] : '';
        $this->having = isset($data['having']) ? $data['having'] : '';
        $this->groupBy = isset($data['groupBy']) ? $data['groupBy'] : '';
        $this->join = isset($data['join']) ? $data['join'] : '';
        $this->sql = isset($data['sql']) ? $data['sql'] : '';
        $this->limit = isset($data['limit']) ? $data['limit'] : '';
        $this->connect();
    }

    public function connect()
    {
        if (!$this->conn) {
            $this->conn = mysqli_connect('localhost', 'root', '', 'qls_demo') or die('Lỗi kết nối database');
            mysqli_query($this->conn, "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_database = 'utf', character_set_ser = 'utf8'");
        }
    }

    protected function disConnect()
    {
        if ($this->conn) {
            mysqli_close($this->conn);
            $this->conn = null;
        }
    }

    protected function query()
    {
        if (!$this->conn)
            $this->connect();

        $query = mysqli_query($this->conn, $this->sql);

        if (!$query)
            die("ERROR: " . mysqli_error($this->conn));

        return $query;
    }

    protected function list($query)
    {
        $list = [];

        if ($query && mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_object($query)) {
                $list[] = $row;
            }
        }

        return $list;
    }

    //Hàm search
    public function all()
    {
        $this->sql = "SELECT * FROM {$this->table}";

        $query = $this->query();

        return $this->list($query);
    }

    protected function row($data)
    {
        $list = '';

        if ($data && mysqli_num_rows($data) > 0) {
            while ($row = mysqli_fetch_object($data)) {
                $list = $row;
            }
        }

        return $list;
    }

    public function insert($data)
    {
        $field_list = [];
        $value_list = [];

        foreach ($data as $key => $val) {
            $val = addslashes($val);
            $field_list[] = $key;
            $value_list[] = "'" . $val . "'";
        }
        $field_list = implode(', ', $field_list);
        $value_list = implode(', ', $value_list);

        $this->sql = "INSERT INTO {$this->table} ({$field_list}) VALUES ({$value_list})";

        $this->query();

        $lastId = mysqli_insert_id($this->conn);

        $this->sql = "SELECT {$this->data} FROM {$this->table} WHERE id = {$lastId} ";

        $this->disConnect();
        return $this->row($this->query());
    }

    protected function getData()
    {
        $data = array(
            'table' => $this->table,
            'data' => $this->data,
            'where' => $this->where,
            'subQuery' => $this->subQuery,
            'orderBy' => $this->orderBy,
            'having' => $this->having,
            'groupBy' => $this->groupBy,
            'join' => $this->join,
            'limit' => $this->limit,
            'sql' => $this->sql,
        );

        return $data;
    }

    public function where(...$data)
    {
        $condition = isset($data[0]) ? $data[0] : '';
        $length = count($data);

        if (is_array($condition) && count($condition) > 0) {
            $this->where .= $this->where ? " AND " : " WHERE ";
            $multiCondition = [];
            foreach ($condition as $key => $val) {
                $val = addslashes($val);
                if ($key == 'id') {
                    $multiCondition[] = "{$this->table}.{$key} = '{$val}'";
                } else {
                    $multiCondition[] = "{$key} = '{$val}'";
                }

            }

            $this->where .= "(" . implode(" AND ", $multiCondition) . ")";

        } else if ($length > 1) {
            $this->where .= $this->where ? " AND " : " WHERE ";
            $column = $data[0] == 'id' ? "{$this->table}.{$data[0]}" : $data[0];
            if ($length == 2) {
                $operator = "=";
                $value = addslashes($data[1]);
            } else {
                $operator = $data[1];
                $value = addslashes($data[2]);
            }

            $this->where .= " {$column} {$operator} {$value} ";
        }

        return new Db_driver($this->getData());
    }

    public function whereOr(...$data)
    {
        $condition = isset($data[0]) ? $data[0] : '';
        $length = count($data);
        if (is_array($condition) && count($condition) > 0) {
            $this->where .= ($this->where) ? " AND " : " WHERE ";
            $multiCondition = [];
            foreach ($condition as $key => $val) {
                $val = addslashes($val);
                if ($key == 'id') {
                    $multiCondition[] = "{$this->table}.{$key} = '{$val}'";
                } else {
                    $multiCondition[] = "{$key} = '{$val}'";
                }
            }

            $this->where .= "(" . implode(" OR ", $multiCondition) . ")";
        } else if ($length > 1) {
            $this->where .= ($this->where) ? " OR " : " WHERE ";
            $length = count($data);
            $column = $data[0] == 'id' ? "{$this->table}.{$data[0]}" : $data[0];
            if ($length == 2) {
                $operator = "=";
                $value = addslashes($data[1]);
            } else {
                $operator = $data[1];
                $value = addslashes($data[2]);
            }

            $this->where .= " {$column} {$operator} {$value} ";
        }


        return new Db_driver($this->getData());
    }

    public function raw(...$data)
    {

        $this->where .= isset($this->where) ? " AND " : " WHERE ";
        $column = $data[0] == 'id' ? "{$this->table}.{$data[0]}" : $data[0];
        $length = count($data);
        if ($length == 2) {
            $operator = "=";
            $sql = $data[1];
        } else {
            $operator = $data[1];
            $sql = $data[2];
        }

        $this->where .= " {$column} {$operator} ({$sql}) ";

        return new Db_driver($this->getData());
    }

    public function whereHas(...$data)
    {
        $this->where .= isset($this->where) ? " AND " : " WHERE ";

        $table = $data[0];
        $column = $data[1];
        $condition = isset($data[2]) ? $data[2] : '';
        $key = isset($data[3]) ? $data[3] : '';
        $foreignKey = isset($data[4]) ? $data[4] : '';
        $multiCondition = [];
        if ($key != '' && $foreignKey != '') {
            $multiCondition[] = " {$key} = {$foreignKey} ";
        }

        if (is_array($condition)) {
            foreach ($condition as $key => $value) {
                $value = addslashes($value);
                $multiCondition[] = " {$key} = '{$value}' ";
            }
        }

        $multiCondition = implode('AND', $multiCondition);
        if ($multiCondition != '')
            $multiCondition = " WHERE " . $multiCondition;

        $this->where .= " EXISTS(SELECT {$column} FROM {$table} {$multiCondition} ) ";

        return new Db_driver($this->getData());
    }

    public function whereIn(...$data)
    {
        $condition = isset($data[0]) ? $data[0] : '';
        $length = count($data);
        if (is_array($condition) && count($condition) > 0) {
            $this->where .= isset($this->where) ? " AND " : " WHERE ";

            $multiCondition = [];
            foreach ($condition as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $k => $item) {
                        $val[$k] = "'" . addslashes($item) . "'";
                    }
                }

                $val = "(" . implode(", ", $val) . ")";
                if ($key == 'id') {
                    $multiCondition[] = "{$this->table}.{$key} IN {$val}";
                } else {
                    $multiCondition[] = "{$key} IN {$val}";
                }

            }

            $this->where .= "(" . implode(" AND ", $multiCondition) . ")";
        } else if ($length > 1) {
            $this->where .= isset($this->where) ? " AND " : " WHERE ";
            $column = $data[0] == 'id' ? "{$this->table}.{$data[0]}" : $data[0];

            $operator = "IN";
            $value = $data[1];
            if (is_array($value)) {
                foreach ($value as $val) {
                    $value[] = addslashes($val);
                }
            }

            $value = "(" . implode(', ', $value) . ")";

            $this->where .= " {$column} {$operator} {$value} ";
        }

        return new Db_driver($this->getData());
    }
    public function groupBy(...$data)
    {

        $this->groupBy .= isset($this->groupBy) ? ', ' : " GROUP BY ";
        $this->groupBy .= implode(', ', $data);

        return new Db_driver($this->getData());
    }

    public function having(...$data)
    {
        $condition = $data[0];

        $this->having .= isset($this->having) ? " AND " : " HAVING ";

        if (is_array($condition)) {
            $multiCondition = [];
            foreach ($condition as $key => $val) {
                $val = addslashes($val);
                if ($key == 'id') {
                    $multiCondition[] = "{$this->table}.{$key} = '{$val}'";
                } else {
                    $multiCondition[] = "{$key} = '{$val}'";
                }

            }

            $this->where .= "(" . implode(" AND ", $multiCondition) . ")";
        } else {
            $length = count($data);
            $column = $data[0] == 'id' ? "{$this->table}.{$data[0]}" : $data[0];
            if ($length == 2) {
                $operator = "=";
                $value = addslashes($data[1]);
            } else {
                $operator = $data[1];
                $value = addslashes($data[2]);
            }

            $this->where .= " {$column} {$operator} {$value} ";
        }

        return new Db_driver($this->getData());
    }

    public function orderBy(...$data)
    {
        $condition = $data[0];

        $this->orderBy .= isset($this->orderBy) ? " , " : " ORDER BY ";

        if (is_array($condition)) {
            $multiCondition = [];
            foreach ($condition as $key => $val) {
                if ($key == 'id') {
                    $multiCondition[] = " {$this->table}.{$key} {$val} ";
                } else {
                    $multiCondition[] = " {$key} {$val} ";
                }

            }

            $this->orderBy .= implode(', ', $multiCondition);
        } else {
            $this->orderBy .= join(' ', $data);
        }

        return new Db_driver($this->getData());
    }

    public function limit(...$data)
    {
        $count = count($data);

        if ($count > 0) {
            $this->limit = " LIMIT ";
            if ($count == 1) {
                $this->limit .= $data[0];
            } else {
                $this->limit .= " {$data[0]} OFFSET {$data[1]} ";
            }
        }

        return new Db_driver($this->getData());
    }

    public function select($data)
    {
        $this->data = $data;
        return new Db_driver($this->getData());
    }

    protected function conditionJoin($data, $type)
    {
        $table = $data[0];
        $value1 = isset($data[1]) ? "{$this->table}.{$data[1]}" : " {$this->table}.id ";
        $value2 = isset($data[2]) ? "{$table}.{$data[2]}" : " {$table}.id ";

        return " {$type} {$table} ON {$value1} = {$value2} ";
    }

    public function join(...$data)
    {
        $this->join .= $this->conditionJoin($data, "JOIN");
        return new Db_driver($this->getData());
    }

    public function innerJoin(...$data)
    {
        $this->join .= $this->conditionJoin($data, "INNER JOIN");
        return new Db_driver($this->getData());
    }

    public function leftJoin(...$data)
    {
        $this->join .= $this->conditionJoin($data, "LEFT JOIN");
        return new Db_driver($this->getData());
    }

    public function rightJoin(...$data)
    {
        $this->join .= $this->conditionJoin($data, "RIGHT JOIN");

        return new Db_driver($this->getData());
    }

    public function get()
    {
        $this->sql = "SELECT {$this->data} FROM {$this->table}";
        $this->sql .= $this->join;
        $this->sql .= $this->where;
        $this->sql .= $this->groupBy;
        $this->sql .= $this->having;
        $this->sql .= $this->limit;
        $this->sql .= $this->orderBy;

        $query = $this->query();
        $this->disConnect();
        return $this->list($query);
    }

    public function first()
    {
        $this->sql = "SELECT {$this->data} FROM {$this->table}";
        $this->sql .= $this->join;
        $this->sql .= $this->where;
        $this->sql .= $this->groupBy;
        $this->sql .= $this->having;
        $this->sql .= " LIMIT 1 ";
        $this->sql .= $this->orderBy;

        $query = $this->query();
        $this->disConnect();
        $list = $this->list($query);
        $list = isset($list[0]) ? $list[0] : [];

        return $list;
    }

    //Hàm update
    public function update($data)
    {
        $list = array_map(function ($key, $val) {
            $val = addslashes($val);
            return "$key = '$val'";
        }, array_keys($data), $data);

        $value_list = implode(', ', $list);

        $this->sql = "UPDATE {$this->table} SET {$value_list} {$this->where}";

        $this->query();
        $this->disConnect();
        return true;
    }

    // Hàm xóa
    public function delete()
    {
        $this->sql = "DELETE FROM {$this->table} {$this->where}";

        $this->query();
        $this->disConnect();
        return true;
    }
}
?>