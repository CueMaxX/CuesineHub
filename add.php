<?php
// Include database connection file
include_once("config.php");

// Initialize variables
$name = $age = $email = "";
$nameErr = $ageErr = $emailErr = "";

if(isset($_POST['update'])) {  
    // Retrieve record values
    $name = mysqli_real_escape_string($mysqli, $_POST['name']);
    $age = mysqli_real_escape_string($mysqli, $_POST['age']);
    $email = mysqli_real_escape_string($mysqli, $_POST['email']);  

    // Check for empty fields
    if(empty($name) || empty($age) || empty($email)) {   
        if(empty($name)) {
            $nameErr = "* required";
        }
        if(empty($age)) {
            $ageErr = "* required";
        }
        if(empty($email)) {
            $emailErr = "* required";
        }       
    } else {  
        // Insert new contact
        $stmt = $mysqli->prepare("INSERT INTO contacts (name,age,email) VALUES(?, ?, ?)");
        $stmt->bind_param("sis", $name, $age, $email);
        $stmt->execute();

        // Redirect to home page (index.php)
        header("Location: index.php");
    }
}
else if (isset($_POST['cancel'])) {
    // Redirect to home page (index.php)
    header("Location: index.php");
}
?>

<form name="addForm" method="post" action="index.php?page=add">
	<div class="mb-3">
		<label for="inputName" class="form-label">Name</label>
		<input type="text" class="form-control" id="inputName" name="name" value="<?php echo $name;?>">
		<?php if ($nameErr): ?>
			<div class="alert alert-danger" role="alert">
				<?php echo $nameErr; ?>
			</div>
		<?php endif; ?>
	</div>
	<div class="mb-3">
		<label for="inputAge" class="form-label">Age</label>
		<input type="number" class="form-control" id="inputAge" name="age" value="<?php echo $age;?>">
		<?php if ($ageErr): ?>
			<div class="alert alert-danger" role="alert">
				<?php echo $ageErr; ?>
			</div>
		<?php endif; ?>
	</div>
	<div class="mb-3">
		<label for="inputMail" class="form-label">E-Mail</label>
		<input type="email" class="form-control" id="inputMail" name="email" value="<?php echo $email;?>">
		<?php if ($emailErr): ?>
			<div class="alert alert-danger" role="alert">
				<?php echo $emailErr; ?>
			</div>
		<?php endif; ?>
	</div>
	<button type="submit" class="btn btn-primary" name="update" value="Update">Submit</button>
	<button type="submit" class="btn btn-secondary" name="cancel" value="Cancel">Cancel</button>
</form>
