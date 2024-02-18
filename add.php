<?php
	// Include database connection file
	include_once("config.php");

    error_reporting(E_ALL);
    ini_set('display_errors', 1);


	// Initialize variables
	$title = $steps = $ingredients = $difficulty = $picture = $notes = $time_minutes = $portions = $tags = "";
	$titleErr = $stepsErr = $ingredientsErr = $difficultyErr = $pictureErr = $notesErr = $time_minutesErr = $portionsErr = $tagsErr = "";

	if(isset($_POST['submit'])) {  
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

		// Output debugging information in a div, uncomment to see on index.php
		/*
		$_SESSION['debug_info'] = '<div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">';
		$_SESSION['debug_info'] .= '<h4>Debugging Information:</h4>';
		$_SESSION['debug_info'] .= 'Title: ' . $title . '<br>';
		$_SESSION['debug_info'] .= 'Steps: ' . $steps . '<br>';
		$_SESSION['debug_info'] .= 'Ingredients: ' . $ingredients . '<br>';
		$_SESSION['debug_info'] .= 'Difficulty: ' . $difficulty . '<br>';
		$_SESSION['debug_info'] .= 'Picture: ' . $picture . '<br>';
		$_SESSION['debug_info'] .= 'Portions: ' . $portions . '<br>';
		$_SESSION['debug_info'] .= 'Tags: ' . $tags . '<br>';
		$_SESSION['debug_info'] .= '</div>';
		*/

		// Check for empty fields
		if(empty($title)) {
			$titleErr = "* Title is required";
		}
		if(empty($steps)) {
			$stepsErr = "* Steps are required";
		}
		if(empty($ingredients)) {
			$ingredientsErr = "* Ingredients are required";
		}
		if(empty($difficulty)) {
			$difficultyErr = "* Difficulty is required";
		}
		if(empty($picture)) {
			$pictureErr = "* Picture Link is required";
		}
		if(empty($time_minutes)) {
			$time_minutesErr = "* Time in minutes is required";
		}
		if(empty($portions)) {
			$portionsErr = "* Portions are required";
		}
		
		// If no empty fields, proceed with inserting data
		if(empty($titleErr) && empty($stepsErr) && empty($ingredientsErr) && empty($difficultyErr) && empty($time_minutesErr) && empty($portionsErr) && empty($pictureErr)) {
			// Insert new recipe
			$stmt = $mysqli->prepare("INSERT INTO recipe.all (title, steps, ingredients, difficulty, picture, notes, time_minutes, portions, tags) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssisssis", $title, $steps, $ingredients, $difficulty, $picture, $notes, $time_minutes, $portions, $tags);
			$stmt->execute();

			if($stmt->errno) {
				echo "Error: ".$stmt->error;
			} else {
				// Redirect to home page (index.php) after successful insertion
				$_SESSION['success_message'] = 'Recipe added successfully.';
				header("Location: index.php");
			}
		}
	}
	else if (isset($_POST['cancel'])) {
		// Redirect to home page (index.php)
		header("Location: index.php");
	}
?>
<h1>Add a new Recipe</h1>
<hr>
<form name="addForm" method="post" action="index.php?page=add">
    <div class="mb-3">
        <label for="inputTitle" class="form-label">Title</label>
        <input type="text" class="form-control" id="inputTitle" name="title" value="<?php echo $title;?>">
        <?php if (!empty($titleErr)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $titleErr; ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="inputSteps" class="form-label">Steps</label>
        <textarea class="form-control" id="inputSteps" name="steps" rows="4"><?php echo $steps;?></textarea>
        <?php if (!empty($stepsErr)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $stepsErr; ?>
            </div>
        <?php endif; ?>
        <small class="form-text text-muted">Enter the steps for preparing the recipe.</small>
    </div>
    <div class="mb-3">
        <label for="inputIngredients" class="form-label">Ingredients</label>
        <input type="text" class="form-control" id="inputIngredients" name="ingredients" value="<?php echo $ingredients;?>">
        <?php if (!empty($ingredientsErr)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $ingredientsErr; ?>
            </div>
        <?php endif; ?>
        <small class="form-text text-muted">Separate ingredients by commas (e.g., flour, sugar, eggs).</small>
    </div>
    <div class="mb-3">
        <label for="inputDifficulty" class="form-label">Difficulty</label>
        <select class="form-select" id="inputDifficulty" name="difficulty">
            <option value="1" <?php if($difficulty == 1) echo "selected"; ?>>Easy</option>
            <option value="2" <?php if($difficulty == 2) echo "selected"; ?>>Medium</option>
            <option value="3" <?php if($difficulty == 3) echo "selected"; ?>>Hard</option>
        </select>
        <?php if (!empty($difficultyErr)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $difficultyErr; ?>
            </div>
        <?php endif; ?>
        <small class="form-text text-muted">Choose the difficulty level of the recipe.</small>
    </div>
    <div class="mb-3">
        <label for="inputPicture" class="form-label">Picture Link</label>
        <input type="text" class="form-control" id="inputPicture" name="picture" value="<?php echo $picture;?>">
        <?php if (!empty($pictureErr)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $pictureErr; ?>
            </div>
        <?php endif; ?>
        <small class="form-text text-muted">Enter the URL of the recipe's picture.</small>
    </div>
    <div class="mb-3">
        <label for="inputNotes" class="form-label">Notes</label>
        <input type="text" class="form-control" id="inputNotes" name="notes" value="<?php echo $notes;?>">
        <small class="form-text text-muted">Add any additional notes or tips.</small>
    </div>
    <div class="mb-3">
        <label for="inputTime" class="form-label">Time in minutes</label>
        <input type="number" class="form-control" id="inputTime" name="time_minutes" value="<?php echo $time_minutes;?>">
        <?php if (!empty($time_minutesErr)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $time_minutesErr; ?>
            </div>
        <?php endif; ?>
        <small class="form-text text-muted">Enter the time required to prepare the recipe in minutes.</small>
    </div>
    <div class="mb-3">
        <label for="inputPortions" class="form-label">Portions</label>
        <input type="number" class="form-control" id="inputPortions" name="portions" value="<?php echo $portions;?>">
        <?php if (!empty($portionsErr)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $portionsErr; ?>
            </div>
        <?php endif; ?>
        <small class="form-text text-muted">Enter the number of portions the recipe serves.</small>
    </div>
    <div class="mb-3">
        <label for="inputTags" class="form-label">Tags</label>
        <input type="text" class="form-control" id="inputTags" name="tags" value="<?php echo $tags;?>">
        <?php if (!empty($tagsErr)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $tagsErr; ?>
            </div>
        <?php endif; ?>
        <small class="form-text text-muted">Separate tags by using a comma (e.g., breakfast, dessert, gluten-free).</small>
    </div>
    <button type="submit" class="btn btn-primary" name="submit" value="Submit">Submit</button>
    <button type="submit" class="btn btn-secondary" name="cancel" value="Cancel">Cancel</button>
</form>
