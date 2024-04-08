
<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$image_url =$pose_name = $duration =  "";
$image_url_err =$pose_name_err = $duration_err = "";

// Processing form data when form is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Get hidden input value
    $id = $_POST["id"];

     //validate image
     $input_image_url = trim($_POST["image_url"]);
     if (empty($input_image_url)) {
         $image_url_err = "Please enter a yoga pose url  detail correctly.";
     } 
     else {
         $image_url= $input_image_url;
     }

    $input_pose_name = trim($_POST["pose_name"]);
    if (empty($input_pose_name)) {
        $pose_name_err = "Please enter a yoga pose  detail correctly.";
    } 
    else {
        $pose_name= $input_pose_name;
    }

    // Validate duration
    $input_duration = trim($_POST["duration"]);
    if (empty($input_duration)) {
        $duration_err = "Please enter a duration of yoga.";
    } else {
        $duration = $input_duration;
    }

    // Check input errors before inserting in database
    if (empty($pose_name_err) && empty($duration_err) && empty($image_url_err)) {
        // Prepare an update statement
        $sql = "UPDATE poses SET image_url=?, pose_name=?, duration=? WHERE id=?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi",  $param_image_url, $param_pose_name, $param_duration,$param_id);

            // Set parameters
            $param_image_url = $image_url;
            $param_pose_name = $pose_name;
            $param_duration = $duration;
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records updated successfully. Redirect to landing page
                header("location: info.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM poses WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $image_url = $row["image_url"];
                    $pose_name = $row["pose_name"];
                    $duration = $row["duration"];
                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($link);
    } else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the yoga poses record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                    <div class="form-group">
                            <label>put the image Url</label>
                            <textarea name="image_url"class="form-control <?php echo (!empty($image_url_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $image_url; ?>"></textarea>
                            <span class="invalid-feedback"><?php echo $image_url_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Name of the pose</label>
                            <input type="text" name="pose_name" class="form-control <?php echo (!empty($pose_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $pose_name; ?>">
                            <span class="invalid-feedback"><?php echo $pose_name_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Duration to do</label>
                            <input type="text" name="duration" class="form-control <?php echo (!empty($duration_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $duration; ?>">
                            <span class="invalid-feedback"><?php echo $duration_err; ?></span>
                        </div>
                       
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="info.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
