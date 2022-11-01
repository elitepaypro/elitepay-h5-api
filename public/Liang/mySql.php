<?php

class Db
{
    private $_host = 'localhost';

    private $_port = 3306;

    private $_user = 'logistics_system';

    private $_password = 'AJZyp6GczRr6r6bh';

    private $_database = 'logistics_system';

    private $_encode = 'utf8';

    private $_table = '';

    public function setHost($value)
    {
        $this->_host = $value;

    }

    public function setPort($value)
    {
        $this->_port = $value;

    }

    public function setUser($value)
    {
        $this->_user = $value;

    }

    public function setPassword($value)
    {
        $this->_password = $value;

    }

    public function setDatebase($value)
    {
        $this->_database = $value;

    }

    public function setEncode($value)
    {
        $this->_encode = $value;

    }

    public function setTable($value)
    {
        $this->_table = $value;

    }

    private function _connect()
    {
        if (mysqli_connect($this->_host, $this->_user, $this->_password, $this->_database, $this->_port)) {
            return mysqli_connect($this->_host, $this->_user, $this->_password, $this->_database, $this->_port);

        } else {
            return null;

        }

    }

    public function query($sql)
    {
        return mysqli_query($this->_connect(), $sql);

    }

    public function add($data = array())
    {
        $count = count($data);

        $field = array_keys($data);

        $value = array_values($data);

        $f = '';

        $v = '';

        for ($index = 0; $index < $count; $index++) {
            if ($index < $count - 1) {
                $f .= $field[$index] . ', ';

                $v .= "'" . $value[$index] . "', ";

            } else {
                $f .= $field[$index];

                $v .= $value[$index];

            }

        }

        $sql = "INSERT INTO $this->_table ($f) VALUES ($v)";

        $handle = $this->_connect();

        mysqli_select_db($handle, $this->_database);

        mysqli_query($handle, "SET NAMES '$this->_encode'");

        $isSuccess = mysqli_query($handle, $sql);

        if (false == $isSuccess) {
            return false;

        } else {
            return true;

        }

    }

    public function del($condition = '')
    {
        $sql = "DELETE FROM $this->_table $condition";

        $handle = $this->_connect();

        mysqli_select_db($handle, $this->_database);

        mysqli_query($handle, "SET NAMES '$this->_encode'");

        $isSuccess = mysqli_query($handle, $sql);

        if (false == $isSuccess) {
            return false;

        } else {
            return true;

        }

    }

    public function update($data, $condition = '')
    {
        $count = count($data);

        $field = array_keys($data);

        $value = array_values($data);

        $sql = "UPDATE $this->_table SET ";

        for ($index = 0; $index < $count; $index++) {
            if ($index < $count - 1) {
                $sql .= $field[$index] . ' = ' . "'" . $value[$index] . "', ";

            } else {
                $sql .= $field[$index] . ' = ' . "'" . $value[$index] . "'";

            }

        }

        echo $sql;

        $handle = $this->_connect();

        mysqli_select_db($handle, $this->_database);

        mysqli_query($handle, "SET NAMES '$this->_encode'");

        $isSuccess = mysqli_query($handle, $sql);

        if (false == $isSuccess) {
            return false;

        } else {
            return true;

        }

    }

    public function find($condition = '')
    {
        $sql = "SELECT * FROM $this->_table " . $condition;

        echo $sql;

        $handle = $this->_connect();

        mysqli_select_db($handle, $this->_database);

        mysqli_query($handle, "SET NAMES '$this->_encode'");

        $tempresult = mysqli_query($handle, $sql);

        $result = array();

        while ($row = mysqli_fetch_array($tempresult)) {
            $result[] = $row;

        }

        return $result;

    }

    public function getItemCount($condition = '')
    {
        $sql = "SELECT * FROM $this->_table " . $condition;

        $handle = $this->_connect();

        mysqli_select_db($handle, $this->_database);

        mysqli_query($handle, "SET NAMES '$this->_encode'");

        $result = mysqli_query($handle, $sql);

        return mysqli_num_rows($result);

    }

}

