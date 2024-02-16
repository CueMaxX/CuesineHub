<?php
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $query = "SELECT * FROM recipes WHERE id = $id";
        $result = mysqli_query($mysqli, $query);

        if($result) {
            $res = mysqli_fetch_assoc($result);
            if($res) {
                // have to unescape those so they'll be displayed correctly
                $unescapedSteps = str_replace(['\\r\\n', '\\n', '\\r'], "\n", $res['steps'] ?? '');
                $title = htmlspecialchars($res['title'] ?? '');
                $steps = nl2br(htmlspecialchars($unescapedSteps));
                $ingredients = nl2br(htmlspecialchars($res['ingredients'] ?? ''));
                $difficulty = htmlspecialchars($res['difficulty'] ?? '');
                $picture = htmlspecialchars($res['image_path'] ?? '');
                $notes = nl2br(htmlspecialchars($res['instructions'] ?? ''));
                $time_minutes = htmlspecialchars($res['time_minutes'] ?? '');
                $portions = htmlspecialchars($res['portions'] ?? '');
                $tags = htmlspecialchars($res['tags'] ?? '');
            }
        } else {
            echo "<p>Error fetching recipe details.</p>";
        }
    } else {
        echo "<p>Recipe not specified.</p>";
        return; // Stop further execution if no ID is provided
    }
?>

<div class="row mb-5">
    <div class="col-lg-6 mb-4 mb-lg-0">
        <!-- Recipe Image -->
        <img src="<?php echo $picture; ?>" alt="Recipe Image" class="img-fluid rounded">
    </div>

    <div class="col-lg-6">
        <h2 class="mb-3"><?php echo $title; ?></h2>
        <p><strong>Difficulty:</strong> <?php echo $difficulty; ?></p>
        <p><strong>Time Needed:</strong> <?php echo $time_minutes; ?> minutes</p>
        <p><strong>Servings:</strong> <span id="servings"><?php echo htmlspecialchars($portions); ?></span></p>
        <div><strong>Tags:</strong>
            <?php 
            if (!empty($tags)) {
                $tagsArray = explode(',', $tags);
                foreach ($tagsArray as $tag) {
                    echo "<span class='badge bg-primary me-1'>" . htmlspecialchars(trim($tag)) . "</span>";
                }
            } else {
                echo "No tags available";
            }
            ?>
        </div>
        <hr>
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-header">
                    Notes
                </div>
                <div class="card-body">
                    <p class="card-text"><?php echo $notes; ?></p>
                </div>
            </div>
        </div>
        <div class="col-12 mt-4">
            <h4>Adjust Recipe</h4>
            <div class="mb-3">
                <label for="numPeople" class="form-label">Number of People:</label>
                <input type="number" id="numPeople" class="form-control" value="1" min="1">
            </div>
            <button id="updateIngredients" class="btn btn-primary">Update Ingredients</button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <h3>Ingredients</h3>
        <p id="ingredients"><?php echo htmlspecialchars($ingredients); ?></p>
    </div>

    <div class="col-12 mt-4">
        <h3>Instructions</h3>
        <p><?php echo $steps; ?></p>
    </div>
</div>

<script>
    document.getElementById("updateIngredients").addEventListener("click", function() {
        var numPeople = parseInt(document.getElementById("numPeople").value, 10);

        // Decode and split the ingredients list into an array
        var div = document.createElement('div');
        div.innerHTML = "<?php echo htmlspecialchars($ingredients); ?>";
        var ingredientsText = div.textContent || div.innerText || "";
        var ingredientsArray = ingredientsText.split(',');

        // Process each ingredient to update its quantity based on numPeople
        var updatedIngredients = ingredientsArray.map(function(ingredient) {
            // Trim and decode HTML entities for each ingredient
            ingredient = ingredient.trim();

            // Match quantities followed by a unit and the rest of the ingredient
            var match = ingredient.match(/^([a-z]+)\s*(\d+)\s*([a-z]+)?$/i);

            console.warn(match)

            if (match) {
                var quantity = parseFloat(match[2]);
                var unit = match[3];
                var name = match[1];

                // If there's a quantity, multiply it by numPeople
                if (!isNaN(quantity) && unit) {
                    var updatedQuantity = quantity * numPeople;
                    return `${updatedQuantity}${unit} ${name}`;
                } else if (!isNaN(quantity)) {
                    // If there's a numeric quantity but no unit, it's likely something like "2 eggs"
                    return `${ingredient} x${numPeople}`;
                }
            } else {
                // If the ingredient does not start with a numeric quantity, append "x{numPeople}"
                return `${ingredient} x${numPeople}`;
            }
        }).join(', ');

        // Update the ingredients display
        document.getElementById("ingredients").innerHTML = updatedIngredients.replace(/, /g, ',<br>');

        // Update servings display
        document.getElementById("servings").textContent = `${numPeople} servings`;
    });
</script>












