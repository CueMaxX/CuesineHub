<?php
	session_start(); // Start or resume the session

	// Include the database connection file
	include_once("config.php");

	// Fetch contacts (in descending order)
	$result = mysqli_query($mysqli, "SELECT * FROM recipe ORDER BY id DESC"); 
?>
<html>
	<head>	
		<title>Recipe List App</title>
		<link rel="stylesheet" href="resources/css/styles.css" />
		<link rel="stylesheet" href="resources/vendor/bootstrap5/css/bootstrap.min.css" />
	</head>
	<body id="backToTop">
		<?php include 'navbar.php'; ?>
		<main class="container mt-5">
			<div class="bg-body-teritiary p-5 rounded">
				<?php
				
					// Display success message
					if (isset($_SESSION['success_message'])) {
						echo "
							<div class='alert alert-success alert-dismissible fade show'>
								<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
							" . $_SESSION['success_message'] . "</div>";
						unset($_SESSION['success_message']);
					}

					// Display debug information if debug info is uncommented in add.php
					if (isset($_SESSION['debug_info'])) {
						echo $_SESSION['debug_info'];
						unset($_SESSION['debug_info']); // Clear the session variable
					}

					// Check if the 'page' query parameter is set
					if (isset($_GET['page'])) {
						$page = $_GET['page'];
						// Whitelist of allowed pages
						$allowedPages = ['add', 'edit', 'details'];
						// Check if the requested page is in the whitelist and include it
						if (in_array($page, $allowedPages)) {
							include $page . '.php';
						} else {
							echo "Page not found.";
						}
					} else {
						// Default content or homepage
						?>
							<h1>Recipe List</h1>
							<hr>
							<table class="table table-hover">
								<thead>
									<tr>
										<th>Recipe</th>
										<th>Cooking Time</th>
										<th>Difficulty</th>
										<th><a class="btn btn-primary" href="index.php?page=add">Add Recipe</a></th>
									</tr>
								</thead>
								<tbody>
									<?php
										// Print recipes 
										while ($res = mysqli_fetch_array($result)) {
											echo "<tr>";
											echo "<td><a href=\"index.php?page=details&id=" . htmlspecialchars($res['id']) . "\" >" . htmlspecialchars($res['title']) . "</a></td>";
											echo "<td>" . htmlspecialchars($res['time_minutes']) . " Minutes </td>";
											echo "<td>";
											// Display difficulty level based on integer value
											if ($res['difficulty'] == 1) {
												echo "Easy";
											} elseif ($res['difficulty'] == 2) {
												echo "Medium";
											} elseif ($res['difficulty'] == 3) {
												echo "Hard";
											} else {
												echo "Unknown";
											}
											echo "</td>";    
											echo "<td>
												<a href=\"index.php?page=edit&id=" . htmlspecialchars($res['id']) . "\" class=\"btn btn-success\">Edit</a> 
												<a href=\"#\" class=\"btn btn-danger\" data-bs-toggle=\"modal\" data-bs-target=\"#deleteConfirmationModal\" data-delete-url=\"delete.php?id=" . htmlspecialchars($res['id']) . "\">Delete</a>
											</td>";
											echo "</tr>";
										}
									?>
								</tbody>
							</table>
						<?php
					}
				?>
			</div>
		</main>
		<?php include 'footer.php'; ?>
		<script src="resources/vendor/bootstrap5/js/bootstrap.bundle.min.js"></script>
		
		<!-- Delete Confirmation Modal -->
		<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					Are you sure you want to delete this recipe?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<a href="#" class="btn btn-danger" id="deleteConfirmBtn">Delete</a>
				</div>
				</div>
			</div>
		</div>

		<script>
			document.addEventListener("DOMContentLoaded", function() {
				var deleteButtons = document.querySelectorAll('[data-bs-toggle="modal"][data-delete-url]');
				deleteButtons.forEach(function(button) {
					button.addEventListener('click', function(event) {
					var deleteUrl = button.getAttribute('data-delete-url');
					var modal = document.querySelector(button.getAttribute('data-bs-target'));
					modal.querySelector('#deleteConfirmBtn').setAttribute('href', deleteUrl);
					});
				});
			});
		</script>
	</body>
</html>
