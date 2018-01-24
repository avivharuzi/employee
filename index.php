<?php

require_once("auth/config.php");

$title = "Employee Task";

?>

<?php require_once("layout/header.php"); ?>
<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="jumbotron bg-secondary text-light mt-3">
            <h3>EMPLOYEE TASK</h3>
        </div>
    </div>
    <div class="col-lg-12">
        <form action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>" method="POST" autocomplete="off">
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <input type="number" name="id" id="id" value="<?php echo ValidationHandler::preserveValue("id"); ?>"
                        class="form-control" placeholder="Id" autofocus>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <input type="text" name="fullName"  value="<?php echo ValidationHandler::preserveValue("fullName"); ?>"
                        placeholder="Full Name" class="form-control">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <input type="submit" name="addEmployee" value="Add" class="btn btn-dark form-control">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <input type="submit" name="search" value="Search" class="btn btn-primary form-control">
                    </div>
                </div>
            </div>
            <button type="submit" name="getAll" class="btn btn-success w-100">Get All</button>
        </form>
        <?php
        if (!empty($actions = EmployeeHandler::getAllActions($conn))) {
            echo $actions;
        }
        ?>
    </div>
</div>
<?php require_once("layout/footer.php"); ?>
