<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include_once("config.php");

    $id = $title = $steps = $ingredients = $difficulty = $picture = $notes = $time_minutes = $portions = $tags = "";
    $titleErr = $stepsErr = $ingredientsErr = $difficultyErr = $pictureErr = $notesErr = $time_minutesErr = $portionsErr = $tagsErr = "";

    // Check if the form was submitted
    if(isset($_POST['update'])) {  
        // Retrieve record values
        $id = $_POST['id'];
        $title = mysqli_real_escape_string($mysqli, $_POST['title'] ?? '');
        $steps = mysqli_real_escape_string($mysqli, $_POST['steps']);
        $ingredients = mysqli_real_escape_string($mysqli, $_POST['ingredients']); 
        $difficulty = mysqli_real_escape_string($mysqli, $_POST['difficulty']); 
        $picture = mysqli_real_escape_string($mysqli, $_POST['picture'] ?? '');
        $notes = mysqli_real_escape_string($mysqli, $_POST['notes']); 
        $time_minutes = mysqli_real_escape_string($mysqli, $_POST['time_minutes']); 
        $portions = mysqli_real_escape_string($mysqli, $_POST['portions']); 
        $tags = mysqli_real_escape_string($mysqli, $_POST['tags']);  

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

        // If no empty fields, proceed with updating data
        if(empty($titleErr) && empty($stepsErr) && empty($ingredientsErr) && empty($difficultyErr) && empty($pictureErr) && empty($time_minutesErr) && empty($portionsErr)) {
            // Update recipe
            $stmt = $mysqli->prepare("UPDATE recipe.all SET title=?, time_minutes=?, difficulty=?, notes=?, picture=?, steps=?, ingredients=?, portions=?, tags=? WHERE id=?");
			$stmt->bind_param("sisssssisi", $title, $time_minutes, $difficulty, $notes, $picture, $steps, $ingredients, $portions, $tags, $id);
            $stmt->execute();

            // Set success message and redirect
            $_SESSION['success_message'] = 'Recipe updated successfully.';
            header("Location: index.php");
            exit();
        }
    }

    // Retrieve recipe details for editing
	if(isset($_GET['id'])) {
		$id = $_GET['id'];
		$result = mysqli_query($mysqli, "SELECT * FROM recipe.all WHERE id=$id");
		if($result) {
			$res = mysqli_fetch_assoc($result);
			$title = $res['title'];
			$steps = str_replace('\r\n', "\r\n", $res['steps']);
			$ingredients = $res['ingredients'];
			$difficulty = $res['difficulty'];
			$picture = $res['picture'];
			$notes = $res['notes'];
			$time_minutes = $res['time_minutes'];
			$portions = isset($res['portions']) ? $res['portions'] : ""; // Initialize to empty string if not set
			$tags = isset($res['tags']) ? $res['tags'] : ""; // Initialize to empty string if not set
		}
	}
?>

<!-- Uncomment for some extra debugging info
<div>
    <p>Title: <?php echo htmlspecialchars($title); ?></p>
    <p>Steps: <?php echo nl2br(htmlspecialchars($steps)); ?></p>
    <p>Ingredients: <?php echo htmlspecialchars($ingredients); ?></p>
    <p>Difficulty: <?php echo htmlspecialchars($difficulty); ?></p>
    <p>Picture: <?php echo htmlspecialchars($picture); ?></p>
    <p>Notes: <?php echo htmlspecialchars($notes); ?></p>
    <p>Time in minutes: <?php echo htmlspecialchars($time_minutes); ?></p>
    <p>Portions: <?php echo htmlspecialchars($portions); ?></p>
    <p>Tags: <?php echo htmlspecialchars($tags); ?></p>
</div>
-->

<h1>Edit your Recipe</h1>
<hr>
<form name="editForm" method="post" action="index.php?page=edit&id=<?php echo htmlspecialchars($id); ?>">
    <!-- Title field -->
    <div class="mb-3">
        <label for="inputTitle" class="form-label">Title</label>
        <input type="text" class="form-control <?php echo $titleErr ? 'is-invalid' : ''; ?>" id="inputTitle" name="title" value="<?php echo htmlspecialchars($title);?>">
        <div class="invalid-feedback">
            <?php echo $titleErr; ?>
        </div>
    </div>

    <!-- Steps field -->
	<div class="mb-3">
		<label for="inputSteps" class="form-label">Steps</label>
		<textarea class="form-control <?php echo $stepsErr ? 'is-invalid' : ''; ?>" id="inputSteps" name="steps" rows="4"><?php echo htmlspecialchars($steps); ?></textarea>
		<div class="invalid-feedback">
			<?php echo $stepsErr; ?>
		</div>
		<small class="form-text text-muted">Enter the steps for preparing the recipe.</small>
	</div>

    <!-- Ingredients field -->
    <div class="mb-3">
        <label for="inputIngredients" class="form-label">Ingredients</label>
        <input type="text" class="form-control <?php echo $ingredientsErr ? 'is-invalid' : ''; ?>" id="inputIngredients" name="ingredients" value="<?php echo $ingredients;?>">
        <div class="invalid-feedback">
            <?php echo $ingredientsErr; ?>
        </div>
        <small class="form-text text-muted">Separate ingredients by commas (e.g., flour, sugar, eggs).</small>
    </div>

    <!-- Difficulty field -->
    <div class="mb-3">
        <label for="inputDifficulty" class="form-label">Difficulty</label>
        <select class="form-select <?php echo $difficultyErr ? 'is-invalid' : ''; ?>" id="inputDifficulty" name="difficulty">
            <option value="1" <?php if($difficulty == 1) echo "selected"; ?>>Easy</option>
            <option value="2" <?php if($difficulty == 2) echo "selected"; ?>>Medium</option>
            <option value="3" <?php if($difficulty == 3) echo "selected"; ?>>Hard</option>
        </select>
        <div class="invalid-feedback">
            <?php echo $difficultyErr; ?>
        </div>
        <small class="form-text text-muted">Choose the difficulty level of the recipe.</small>
    </div>

    <!-- Picture field -->
    <div class="mb-3">
        <label for="inputPicture" class="form-label">Picture Link</label>
        <input type="text" class="form-control <?php echo $pictureErr ? 'is-invalid' : ''; ?>" id="inputPicture" name="picture" value="<?php echo htmlspecialchars($picture);?>">
        <div class="invalid-feedback">
            <?php echo $pictureErr; ?>
        </div>
        <small class="form-text text-muted">Enter the URL of the recipe's picture.</small>
    </div>

    <!-- Notes field -->
    <div class="mb-3">
        <label for="inputNotes" class="form-label">Notes</label>
        <input type="text" class="form-control" id="inputNotes" name="notes" value="<?php echo $notes;?>">
        <small class="form-text text-muted">Add any additional notes or tips.</small>
    </div>

    <!-- Time in minutes field -->
    <div class="mb-3">
        <label for="inputTime" class="form-label">Time in minutes</label>
        <input type="number" class="form-control <?php echo $time_minutesErr ? 'is-invalid' : ''; ?>" id="inputTime" name="time_minutes" value="<?php echo $time_minutes;?>">
        <div class="invalid-feedback">
            <?php echo $time_minutesErr; ?>
        </div>
        <small class="form-text text-muted">Enter the time required to prepare the recipe in minutes.</small>
    </div>

    <!-- Portions field -->
    <div class="mb-3">
        <label for="inputPortions" class="form-label">Portions</label>
		<input type="number" class="form-control <?php echo $portionsErr ? 'is-invalid' : ''; ?>" id="inputPortions" name="portions" value="<?php echo htmlspecialchars($portions);?>">
        <div class="invalid-feedback">
            <?php echo $portionsErr; ?>
        </div>
        <small class="form-text text-muted">Enter the number of portions the recipe serves.</small>
    </div>

    <!-- Tags field -->
    <div class="mb-3">
        <label for="inputTags" class="form-label">Tags</label>
		<input type="text" class="form-control" id="inputTags" name="tags" value="<?php echo htmlspecialchars($tags);?>">
        <small class="form-text text-muted">Separate tags by using a comma (e.g., breakfast, dessert, gluten-free).</small>
    </div>

    <button type="submit" class="btn btn-primary" name="update" value="Update">Update</button>
    <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php';">Cancel</button>
    <input type="hidden" name="id" value="<?php echo $id; ?>">
</form>
