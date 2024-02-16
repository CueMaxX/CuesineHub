<?php

include_once("config.php");
// Retrieve id value from querystring parameter
//THIS SHOULD WORK LATER ;)
$id = $_GET['id'];
//$id = 2;

// Get contact by id
$result = mysqli_query($mysqli, "SELECT * FROM recipe.all WHERE id=$id");

if (!$result) {
    printf("Error: %s\n", mysqli_error($mysqli));
    exit();
}
else {
	while($res = mysqli_fetch_array($result))
	{
		$title = $res['title'];
		$steps = $res['steps'];
		$ingredients = $res['ingredients'];
        $difficulty = $res['difficulty'];
        $picture = $res['picture'];
        $notes = $res['notes'];
        $time_minutes = $res['time_minutes'];
        $portions = $res['portions'];
        $tags = $res['tags'];
	}
}
?>

<div class="container mt-5">
    <h1><?php echo $title; ?></h1>
    <p><?php echo $notes; ?></p>
    <img src="<?php echo $picture; ?>" alt="Recipe Image" class="img-fluid">
    <h3>Ingredients for <span id="servings"><?php echo $defaultServings; ?></span> servings:</h3>
    <ul id="ingredientList">
        <?php foreach ($ingredients as $ingredient): ?>
        <li><?php echo $ingredient['name'] . ": " . $ingredient['amount'] . " " . $ingredient['unit']; ?></li>
        <?php endforeach; ?>
    </ul>
    <label for="numPeople">Number of Servings:</label>
    <input type="number" id="numPeople" value="<?php echo $portions; ?>" min="1" onchange="adjustIngredients()">
</div>

<script>
    function adjustIngredients() {
        var numPeople = document.getElementById("numPeople").value;
        var servings = document.getElementById("servings");
        servings.textContent = numPeople;

        var ingredients = <?php echo json_encode($ingredients); ?>;
        var ingredientList = document.getElementById("ingredientList");
        ingredientList.innerHTML = '';

        ingredients.forEach(function(ingredient) {
            var li = document.createElement("li");
            var newAmount = (ingredient.amount * numPeople) / <?php echo $defaultServings; ?>;
            li.textContent = ingredient.name + ": " + newAmount.toFixed(2) + " " + ingredient.unit;
            ingredientList.appendChild(li);
        });
    }
</script>
