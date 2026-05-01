<?php
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validate POST data
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        die("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Prepare data to save
    $entry = "Name: $name\nEmail: $email\nMessage: $message\n---\n";

    // Save to a file (append mode)
    $file = 'contacts.txt';
    if (file_put_contents($file, $entry, FILE_APPEND | LOCK_EX)) {
        echo "Thank you! Your contact information has been saved.";
    } else {
        echo "Error saving your contact. Please try again.";
    }
} else {
    echo "Invalid request.";
}
?>
