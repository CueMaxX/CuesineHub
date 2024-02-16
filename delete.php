<?php
    // Include database connection file
    include("config.php");

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        // Retrieve [id] value from querystring parameter and validate
        $id = $_GET['id'];

        // Delete row for a specified id
        if ($stmt = $mysqli->prepare("DELETE FROM recipes WHERE id=?")) {
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                // Redirect to home page (index.php) if successful
                header("Location:index.php");
                exit(); // Ensure script execution ends here
            } else {
                echo "Error executing query: " . $mysqli->error;
            }
        } else {
            echo "Error preparing statement: " . $mysqli->error;
        }
    } else {
        // If id is not set or not numeric, handle the error or redirect
        echo "Invalid ID.";
    }
?>
