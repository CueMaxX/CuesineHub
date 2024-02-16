<?php
// Include database connection file
include_once("config.php");

// Initialize variables
$title = $steps = $ingredients = $difficulty = $picture = $notes = $time_minutes = $portions = $tags = "";
$titleErr = $stepsErr = $ingredientsErr = $difficultyErr = $pictureErr = $notesErr = $time_minutesErr = $portionsErr = $tagsErr = "";

if(isset($_POST['update'])) {  
    // Retrieve record values
    $title = mysqli_real_escape_string($mysqli, $_POST['title']);
    $steps = mysqli_real_escape_string($mysqli, $_POST['steps']);
    $ingredients = mysqli_real_escape_string($mysqli, $_POST['ingredients']); 
	$difficulty = mysqli_real_escape_string($mysqli, $_POST['difficulty']); 
	$picture = mysqli_real_escape_string($mysqli, $_POST['picture']); 
	$notes = mysqli_real_escape_string($mysqli, $_POST['notes']); 
	$time_minutes = mysqli_real_escape_string($mysqli, $_POST['time_minutes']); 
	$portions = mysqli_real_escape_string($mysqli, $_POST['portions']); 
	$tags = mysqli_real_escape_string($mysqli, $_POST['tags']);  

    // Check for empty fields
    if(empty($title) || empty($steps) || empty($ingredients) || empty($difficulty) || empty($time_minutes) || empty($portions)) {   
        if(empty($title)) {
            $titleErr = "* required";
        }
        if(empty($steps)) {
            $stepsErr = "* required";
        }
        if(empty($ingredients)) {
            $ingredientsErr = "* required";
        }
		if(empty($difficulty)) {
            $difficultyErr = "* required";
        }
		if(empty($time_minutes)) {
            $time_minutesErr = "* required";
        }
		if(empty($portions)) {
            $portionsErr = "* required";
        }       
    } else {  
        // Insert new contact
        $stmt = $mysqli->prepare("INSERT INTO recipe.all (title,steps,ingredients,difficulty,picture,notes,time_minutes,portions,tags) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sis", $title, $steps, $ingredients, $difficulty, $picture, $notes, $time_minutes, $portions, $tags);
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
		<label for="inputTitle" class="form-label">Title</label>
		<input type="text" class="form-control" id="inputTitle" name="title" value="<?php echo $title;?>">
		<?php if ($titleErr): ?>
			<div class="alert alert-danger" role="alert">
				<?php echo $titleErr; ?>
			</div>
		<?php endif; ?>
	</div>
	<div class="mb-3">
		<label for="inputSteps" class="form-label">Steps</label>
		<input type="text" class="form-control" id="inputSteps" name="steps" value="<?php echo $steps;?>">
		<?php if ($stepsErr): ?>
			<div class="alert alert-danger" role="alert">
				<?php echo $stepsErr; ?>
			</div>
		<?php endif; ?>
	</div>
	<div class="mb-3">
		<label for="inputIngredients" class="form-label">Ingredients</label>
		<input type="text" class="form-control" id="inputIngredients" name="ingredients" value="<?php echo $ingredients;?>">
		<?php if ($ingredientsErr): ?>
			<div class="alert alert-danger" role="alert">
				<?php echo $ingredientsErr; ?>
			</div>
		<?php endif; ?>
	</div>
	<div class="mb-3">
		<label for="inputDifficulty" class="form-label">Difficulty</label>
		<input type="text" class="form-control" id="inputDifficulty" name="difficulty" value="<?php echo $difficulty;?>">
		<?php if ($difficultyErr): ?>
			<div class="alert alert-danger" role="alert">
				<?php echo $difficultyErr; ?>
			</div>
		<?php endif; ?>
	</div>
	<div class="mb-3">
		<label for="inputPicture" class="form-label">Picture</label>
		<input type="text" class="form-control" id="inputPicture" name="picture" value="<?php echo $picture;?>">
		<?php if ($pictureErr): ?>
			<div class="alert alert-danger" role="alert">
				<?php echo $pictureErr; ?>
			</div>
		<?php endif; ?>
	</div>
	<div class="mb-3">
		<label for="inputNotes" class="form-label">Notes</label>
		<input type="text" class="form-control" id="inputNotes" name="notes" value="<?php echo $notes;?>">
		<?php if ($notesErr): ?>
			<div class="alert alert-danger" role="alert">
				<?php echo $notesErr; ?>
			</div>
		<?php endif; ?>
	</div>
	<div class="mb-3">
		<label for="inputTime" class="form-label">Time</label>
		<input type="text" class="form-control" id="inputTime" name="time" value="<?php echo $time;?>">
		<?php if ($timeErr): ?>
			<div class="alert alert-danger" role="alert">
				<?php echo $timeErr; ?>
			</div>
		<?php endif; ?>
	</div>
	<div class="mb-3">
		<label for="inputPortions" class="form-label">Portions</label>
		<input type="text" class="form-control" id="inputPortions" name="portions" value="<?php echo $portions;?>">
		<?php if ($portionsErr): ?>
			<div class="alert alert-danger" role="alert">
				<?php echo $portionsErr; ?>
			</div>
		<?php endif; ?>
	</div>
	<div class="mb-3">
		<label for="inputTags" class="form-label">Tags</label>
		<input type="text" class="form-control" id="inputTags" name="tags" value="<?php echo $tags;?>">
		<?php if ($tagsErr): ?>
			<div class="alert alert-danger" role="alert">
				<?php echo $tagsErr; ?>
			</div>
		<?php endif; ?>
	</div>
	<button type="submit" class="btn btn-primary" name="update" value="Update">Submit</button>
	<button type="submit" class="btn btn-secondary" name="cancel" value="Cancel">Cancel</button>
</form>
