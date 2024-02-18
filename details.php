<?php
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $query = "SELECT * FROM recipe.all WHERE id = $id";
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
                $picture = htmlspecialchars($res['picture'] ?? '');
                $notes = nl2br(htmlspecialchars($res['notes'] ?? ''));
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

<div class="card mb-3">
    <div class="row g-0">
        <div class="col-md-4 d-flex">
            <!-- Recipe Image as Background -->
            <div style="background-image: url('<?php echo $picture; ?>'); background-size: cover; background-position: center; min-height: 100%; width: 100%;" class="w-100 rounded-start d-flex align-items-stretch"></div>
        </div>
        <div class="col-md-8 d-flex flex-column">
            <div class="card-body">
                <h2 class="card-title"><?php echo $title; ?></h2>
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
                <div class="card">
                    <div class="card-header">
                        Notes
                    </div>
                    <div class="card-body">
                        <p class="card-text"><?php echo $notes; ?></p>
                    </div>
                </div>
                <div class="mt-4">
                    <h4>Adjust Recipe</h4>
                    <div class="mb-3">
                        <label for="numPeople" class="form-label">Number of People:</label>
                        <input type="number" id="numPeople" class="form-control" value="1" min="1">
                    </div>
                    <div class="btn-group mb-3">
                        <button id="updateIngredients" class="btn btn-primary">Update Ingredients</button>
                        <button class="btn btn-secondary" onclick="location.reload();">Reset Values</button>
                    </div>
                    <br/>
                    <small class="text-muted">Note: If you wish to reset the ingredients to just 1 Person please click the Reset Values Button for now.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-primary mb-3">
            <div class="card-header text-primary">
                Ingredients
            </div>
            <div class="card-body">
                <ul id="ingredients">
                    <?php 
                        // Decode HTML entities to ensure <br /> tags are correctly interpreted
                        $decodedIngredients = html_entity_decode($ingredients);
                        
                        // Split the decoded ingredients string into an array by <br /> tag
                        $ingredientsArray = preg_split('/<br\s*\/?>/', $decodedIngredients);
                        
                        // Loop through the array and output each ingredient as a list item
                        foreach ($ingredientsArray as $ingredient) {
                            // Trim to remove any leading/trailing whitespace
                            $ingredient = trim($ingredient);
                            if (!empty($ingredient)) { // Check if the ingredient is not an empty string
                                echo '<li>' . htmlspecialchars($ingredient) . '</li>';
                            }
                        }
                    ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-12 mt-4">
        <h3>Instructions</h3>
        <p><?php echo $steps; ?></p>
    </div>
</div>

<script>
    document.getElementById("updateIngredients").addEventListener("click", function() {
    var numPeople = parseInt(document.getElementById("numPeople").value, 10);

    var ingredientsListItems = document.querySelectorAll("#ingredients li");

    var updatedIngredients = [];
    ingredientsListItems.forEach(function(item) {
        var text = item.textContent || item.innerText;

        // Updated regex to include decimal values
        // This regex now correctly captures quantities expressed as decimals
        var match = text.match(/^(\d+(?:\.\d+)?)\s*([a-z]+)\s*(.*)$/i);

        if (match) {
            var quantity = parseFloat(match[1]);
            var unit = match[2];
            var name = match[3];

            // Multiply the quantity by the number of people and round to 2 decimal places
            var updatedQuantity = (quantity * numPeople).toFixed(2);

            // If the result is a whole number, parse it to remove unnecessary decimal places
            updatedQuantity = updatedQuantity.endsWith('.00') ? parseInt(updatedQuantity) : updatedQuantity;

            updatedIngredients.push(`${updatedQuantity} ${unit} ${name}`);
        } else {
            // If the ingredient doesn't match the expected format, add it as is
            updatedIngredients.push(text);
        }
    });

    // Update the ingredients list in the HTML
    var updatedListHtml = updatedIngredients.map(ingredient => `<li>${ingredient}</li>`).join('');

    document.getElementById("ingredients").innerHTML = updatedListHtml;

    // Update servings display
    document.getElementById("servings").textContent = `${numPeople} servings`;
});
</script>












