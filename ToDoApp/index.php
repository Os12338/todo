<?php
session_start();
include('db.php');

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `tasks` WHERE id = $id ");
}

// Editing
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $task = $_REQUEST['task'];
        mysqli_query($conn, "UPDATE `tasks` SET content = '$task' WHERE id = $id");
        header("location:{$_SERVER['PHP_SELF']}");
        $_SESSION["edit"] = true;
        exit;
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task = $_REQUEST['task'] ?? null;
    if ($task) {
        $added_task = mysqli_query($conn, "INSERT INTO `tasks` (content,create_at) value('$task',NOW())");

        if ($added_task) {
            // mysqli_close($conn);
            $_SESSION["adding"] = true;
            // header("location:{$_SERVER['PHP_SELF']}");
        } else {
            echo mysqli_connect_error();
        }

    } else {
        echo "<div class='alert alert-warning'> please write task to add</div>";
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Bootstrap To-Do App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">
        <?php
        if (isset($_SESSION["edit"])) {
            echo "<div class='alert alert-success'> Editing is done sucessully </div>";
            session_unset();
        } elseif (isset($_SESSION["adding"])) {
            echo "<div class='alert alert-success'> Adding is done sucessully </div>";
            session_unset();
        }
        ?>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">To-Do List</h3>

                        <form method="POST" action="" class="input-group mb-3">
                            <input type="text" name="task" class="form-control" value="<?php
                            if (isset($_GET["edit"])) {
                                $result = mysqli_query($conn, "SELECT * FROM tasks WHERE id = {$_GET['edit']}");
                                $arr = mysqli_fetch_assoc($result);
                                echo isset($_GET['edit']) ? $arr['content'] : "";
                            }
                            ?>" placeholder="Add a new task...">
                            <button class="btn btn-success"
                                type="submit"><?php echo isset($_GET['edit']) ? "Edit" : "Add"; ?></button>
                        </form>

                        <ul class="list-group">
                            <?php
                            $result = mysqli_query($conn, "SELECT* FROM `tasks` ORDER BY 'date' DESC ");

                            while ($task = mysqli_fetch_assoc($result)) {
                                echo '
                  <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="me-auto">
                      <div class="fw-bold">' . $task['content'] . '</div>
                      <small class="text-muted">' . $task['create_at'] . '</small>
                    </div>
                    <div class="btn-group btn-group-sm">
                      <a href="?edit=' . $task['id'] . '" class="btn btn-primary">Edit</a>
                      <a  href="?delete=' . $task['id'] . '" class="btn btn-danger">Delete</a>
                    </div>
                  </li>';
                            }
                            mysqli_close($conn);
                            ?>
                            <!-- More tasks go here -->
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (optional for dropdowns/modals/etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>