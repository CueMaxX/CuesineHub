<?php
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once("config.php");

	$id = $name = $age = $email = "";
	$nameErr = $ageErr = $emailErr = "";

	// Check if the form was submitted
	if(isset($_POST['update'])) {  
		$id = $_POST['id']; // Assuming ID is passed as a hidden field in the form
		$name = mysqli_real_escape_string($mysqli, $_POST['name']);
		$age = mysqli_real_escape_string($mysqli, $_POST['age']);
		$email = mysqli_real_escape_string($mysqli, $_POST['email']);  

		// Validate input
		if(empty($name)) $nameErr = "Name is required";
		if(empty($age)) $ageErr = "Age is required";
		if(empty($email)) $emailErr = "Email is required";

		// If no errors, proceed with update
		if(empty($nameErr) && empty($ageErr) && empty($emailErr)) {  
			$stmt = $mysqli->prepare("UPDATE contacts SET name=?, age=?, email=? WHERE id=?");
			$stmt->bind_param("sisi", $name, $age, $email, $id);
			$stmt->execute();

			// Set success message and redirect
			$_SESSION['success_message'] = 'Contact updated successfully.';
			header("Location: index.php");
			exit();
		}
	}

	// Retrieve recipe details for editing
	if(isset($_GET['id'])) {
		$id = $_GET['id'];
		$result = mysqli_query($mysqli, "SELECT * FROM contacts WHERE id=$id");
		if($result) {
			$res = mysqli_fetch_assoc($result);
			$name = $res['name'];
			$age = $res['age'];
			$email = $res['email'];
		}
	}

?>

<form name="editForm" method="post" action="index.php?page=edit&id=<?php echo htmlspecialchars($id); ?>">
    <!-- Name field -->
    <div class="mb-3">
        <label for="inputName" class="form-label">Name</label>
        <input type="text" class="form-control <?php echo $nameErr ? 'is-invalid' : ''; ?>" id="inputName" name="name" value="<?php echo htmlspecialchars($name);?>">
        <div class="invalid-feedback">
            <?php echo $nameErr; ?>
        </div>
    </div>

    <!-- Age field -->
    <div class="mb-3">
        <label for="inputAge" class="form-label">Age</label>
        <input type="number" class="form-control <?php echo $ageErr ? 'is-invalid' : ''; ?>" id="inputAge" name="age" value="<?php echo htmlspecialchars($age);?>">
        <div class="invalid-feedback">
            <?php echo $ageErr; ?>
        </div>
    </div>

    <!-- Email field -->
    <div class="mb-3">
        <label for="inputMail" class="form-label">E-Mail</label>
        <input type="email" class="form-control <?php echo $emailErr ? 'is-invalid' : ''; ?>" id="inputMail" name="email" value="<?php echo htmlspecialchars($email);?>">
        <div class="invalid-feedback">
            <?php echo $emailErr; ?>
        </div>
    </div>

    <button type="submit" class="btn btn-primary" name="update" value="Update">Update</button>
    <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php';">Cancel</button>
    <input type="hidden" name="id" value="<?php echo $id; ?>">
</form>
