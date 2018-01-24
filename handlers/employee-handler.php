<?php

class EmployeeHandler {
    private function __construct() {
    }

    public static function getAllEmployees($conn) {
        $sql = "SELECT * FROM employee";
        $employees = $conn->getFullData($sql, "Employee");

        if ($employees) {
            $table =
            "<table class='table table-hover table-responsive mt-5'>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>FullName</th>
                    </tr>
                </thead>
                <tbody>";

            foreach ($employees as $employee) {
                $table .=
                "<tr>
                    <td>{$employee->getId()}</td>
                    <td>{$employee->getFullName()}</td>
                </tr>";
            }

            $table .=
                "</tbody>
            </table>";

            return $table;
        } else {
            return MessageHandler::warningBig("There are no employees in the list yet");
        }
    }

    public static function getEmployeeById($conn, $id) {
        $sql = "SELECT * FROM employee WHERE Id = $id LIMIT 1";
        $employee = $conn->getSingleData($sql, "Employee");

        if ($employee) {
            return
            "<form action='' method='POST'>
                <table class='table table-hover table-responsive mt-5'>
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>FullName</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{$employee->getId()}</td>
                            <td>{$employee->getFullName()}</td>
                            <td class='pencil'><div class='btn btn-info w-100'><i class='fa fa-pencil'></i></div></td>
                            <td><button type='submit' name='deleteEmployee' class='btn btn-danger w-100' value='{$employee->getId()}'><i class='fa fa-trash'></i></button></td>
                        </tr>
                        <tr class='row-edit'>
                            <td><input type='number' name='id' class='form-control' value='{$employee->getId()}' readonly></td>
                            <td><input type='text' name='fullName' class='form-control' value='{$employee->getFullName()}'</td>
                            <td colspan='2'><button type='submit' name='updateEmployee' value='{$employee->getId()}' class='btn btn-secondary w-100'><i class='fa fa-save'></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </form>";
        } else {
            return MessageHandler::errorBig("No results");
        }
    }

    public static function getAllEmployeesAction($conn) {
        if (isset($_POST["getAll"]) && count($_POST["getAll"]) > 0) {
            return self::getAllEmployees($conn);
        }
    }

    public static function getEmployeeByIdAction($conn) {
        if (isset($_POST["search"]) && count($_POST["search"]) > 0) {
            if (ValidationHandler::validateInputs($_POST["id"], "/^[0-9]*$/")) {
                $id = ValidationHandler::testInput($_POST["id"]);
                return self::getEmployeeById($conn, $id);
            } else {
                return MessageHandler::error("Please insert only numbers in id");
            }
        }
    }

    public static function addEmployee($conn, $id, $fullName) {
        $employee = new Employee($id, $fullName);
        $employee->addEmployee($conn);

        if ($employee) {
            return MessageHandler::success("Employee added successfully");
        }
    }

    public static function addEmployeeAction($conn) {
        if (isset($_POST["addEmployee"]) && count($_POST["addEmployee"]) > 0) {
            if (ValidationHandler::validateInputs($_POST["id"], "/^[0-9]*$/")) {
                $id = ValidationHandler::testInput($_POST["id"]);
                if (self::checkEmployee($conn, $id)) {
                    $errors[] = "This id already in used choose another one";
                }
            } else {
                $errors[] = "Please insert only numbers in id";
            }

            if (ValidationHandler::validateInputs($_POST["fullName"], "/^[a-zA-Z ]*$/")) {
                $fullName = ValidationHandler::testInput($_POST["fullName"]);
            } else {
                $errors[] = "Please insert only letters in full name";
            }

            if (empty($errors)) {
                return self::addEmployee($conn, $id, $fullName);
            } else {
                return MessageHandler::error($errors);
            }
        }
    }

    public static function updateEmployeeAction($conn) {
        if (isset($_POST["updateEmployee"]) && count($_POST["updateEmployee"]) > 0) {
            $id = $_POST["updateEmployee"];

            if (ValidationHandler::validateInputs($_POST["fullName"], "/^[a-zA-Z ]*$/")) {
                $fullName = ValidationHandler::testInput($_POST["fullName"]);
            } else {
                $errors[] = "Please insert only letters in full name";
            }

            $employee = self::checkEmployee($conn, $id);

            if ($employee) {
                if (empty($errors)) {
                    return self::updateEmployee($conn, $id, $fullName) . self::getEmployeeById($conn, $id);
                } else {
                    return MessageHandler::error($errors) . self::getEmployeeById($conn, $id);
                }
            } else {
                return MessageHandler::error("You tried to update employee that does not exist");
            }
        }        
    }

    public static function updateEmployee($conn, $id, $fullName) {
        $employee = new Employee($id, $fullName);
        $employee->updateEmployee($conn);

        if ($employee) {
            return MessageHandler::success("Employee updated successfully");
        }
    }

    public static function deleteEmployeeAction($conn) {
        if (isset($_POST["deleteEmployee"]) && count($_POST["deleteEmployee"]) > 0) {
            $employeeId = $_POST["deleteEmployee"];
            $employee = self::checkEmployee($conn, $employeeId);
            
            if ($employee) {
                if ($employee->deleteEmployee($conn)) {
                    return MessageHandler::success("Employee deleted employee");
                }
            } else {
                return MessageHandler::error("You tried to delete employee that does not exist");
            }
        }
    }

    public static function checkEmployee($conn, $id) {
        $sql = "SELECT * FROM employee WHERE Id = $id LIMIT 1";
        $employee = $conn->getSingleData($sql, "Employee");

        if ($employee) {
            return $employee;
        } else {
            return false;
        }
    }

    public static function getAllActions($conn) {
        $employeesByGetAll = self::getAllEmployeesAction($conn);
        $employeeByGetId = self::getEmployeeByIdAction($conn);
        $addEmployeeAction = self::addEmployeeAction($conn);
        $deleteEmployeeAction = self::deleteEmployeeAction($conn);
        $updateEmployeeAction = self::updateEmployeeAction($conn);

        if (!empty($employeesByGetAll)) {
            return $employeesByGetAll;
        }

        if (!empty($employeeByGetId)) {
            return $employeeByGetId;
        }

        if (!empty($addEmployeeAction)) {
            return $addEmployeeAction;
        }

        if (!empty($deleteEmployeeAction)) {
            return $deleteEmployeeAction;
        }

        if (!empty($updateEmployeeAction)) {
            return $updateEmployeeAction;
        }
    }
}

?>
