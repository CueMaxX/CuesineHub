<?php
// Mockup of a simulated recipe data to test the JS
$recipeName = "Spaghetti Carbonara";
$cookingDetails = "A quick traditional Italian dish with eggs, cheese, bacon, and black pepper.";
$imageSrc = "resources/img/carbonara.jpg"; // Placeholder image path, we should upload our images obviously to the DB
$ingredients = [
    ["name" => "Pasta", "amount" => 100, "unit" => "g"],
    ["name" => "Eggs", "amount" => 2, "unit" => "pcs"],
    ["name" => "Bacon", "amount" => 50, "unit" => "g"],
    ["name" => "Heavy Cream", "amount" => 50, "unit" => "ml"]
];
$defaultServings = 2;
?>

<div class="container mt-5">
    <h1><?php echo $recipeName; ?></h1>
    <p><?php echo $cookingDetails; ?></p>
    <img src="<?php echo $imageSrc; ?>" alt="Recipe Image" class="img-fluid">
    <h3>Ingredients for <span id="servings"><?php echo $defaultServings; ?></span> servings:</h3>
    <ul id="ingredientList">
        <?php foreach ($ingredients as $ingredient): ?>
        <li><?php echo $ingredient['name'] . ": " . $ingredient['amount'] . " " . $ingredient['unit']; ?></li>
        <?php endforeach; ?>
    </ul>
    <label for="numPeople">Number of Servings:</label>
    <input type="number" id="numPeople" value="<?php echo $defaultServings; ?>" min="1" onchange="adjustIngredients()">
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
