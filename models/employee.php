<?php

class Employee {
    private $Id;
    private $FullName;

    public function __construct() {
        if (func_num_args() > 0) {
            $this->Id       = func_get_arg(0);
            $this->FullName = func_get_arg(1);
        }
    }

    public function getId() {
        return $this->Id;
    }

    public function getFullName() {
        return $this->FullName;
    }

    public function addEmployee($conn) {
        $sql = "INSERT INTO employee (Id, FullName) VALUES ('$this->Id', '$this->FullName')";
        $result = $conn->connectData($sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function updateEmployee($conn) {
        $sql = "UPDATE employee SET Id = $this->Id, FullName = '$this->FullName' WHERE Id = $this->Id";
        $result = $conn->connectData($sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteEmployee($conn) {
        $sql = "DELETE FROM employee WHERE Id = $this->Id";
        $result = $conn->connectData($sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}

?>
