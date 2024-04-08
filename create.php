
<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$image_url = $pose_name = $duration =  "";
$image_url_err =$pose_name_err = $duration_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //validate image
    $input_image_url = trim($_POST["image_url"]);
    if (empty($input_image_url)) {
        $image_url_err = "Please enter a yoga pose url  detail correctly.";
    } 
    else {
        $image_url= $input_image_url;
    }

    // Validate order
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
        // Prepare an insert statement
        $sql = "INSERT INTO poses ( image_url, pose_name, duration) VALUES (?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_image_url, $param_pose_name, $param_duration);

            // Set parameters
            $param_image_url = $image_url;
            $param_pose_name = $pose_name;
            $param_duration = $duration;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
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
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                        <div class="form-group">
                            <label>put the image Url</label>
                            <textarea name="image_url"class="form-control <?php echo (!empty($image_url_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $image_url; ?>"></textarea>
                            <span class="invalid-feedback"><?php echo $image_url_err; ?></span>
                        </div>
                            <label>Yoga pose name</label>
                            <input type="text" name="pose_name" class="form-control <?php echo (!empty($pose_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $pose_name; ?>">
                            <span class="invalid-feedback"><?php echo $pose_name_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>duration of the yoga pose </label>
                            <input type="text" name="duration"class="form-control <?php echo (!empty($duration_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $duration; ?>">
                            <span class="invalid-feedback"><?php echo $duration_err; ?></span>
                        </div>
                       
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="info.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
