<?php
include 'konekcija.php';

// Check if the registration form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user already exists in the database
    $checkQuery = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param('ss', $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User already exists, display an appropriate message
        echo "A user with this username or email already exists.";
    } else {
        // User does not exist, insert new user into the database
        $passwordHash = password_hash($password, PASSWORD_DEFAULT); // Hash the password
        $insertQuery = "INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param('sss', $username, $email, $passwordHash);
        $result = $stmt->execute();

        if ($result) {
            // Successfully registered user
            header("Location: ../index.php?success=1");
            exit(); // Ensure no further code execution after redirection
        } else {
            // Error occurred during registration
            echo "An error occurred during registration.";
        }
    }

    $stmt->close();
}
?>
