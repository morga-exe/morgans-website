<?php
// Enable error reporting for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// File where contacts will be saved
$contactsFile = __DIR__ . '/contacts.csv';

// Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $name  = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $phone = trim(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING));
    $message = trim(filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING));

    // Validate required fields
    if (empty($name) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Invalid name or email.";
        exit;
    }

    // Prepare data row
    $dataRow = [
        date('Y-m-d H:i:s'),
        $name,
        $email,
        $phone,
        $message
    ];

    try {
        // Open file for appending
        $fp = fopen($contactsFile, 'a');
        if (!$fp) {
            throw new Exception("Unable to open file for writing.");
        }

        // Lock file to prevent concurrent write issues
        if (flock($fp, LOCK_EX)) {
            fputcsv($fp, $dataRow);
            flock($fp, LOCK_UN);
        } else {
            throw new Exception("Unable to lock file.");
        }

        fclose($fp);

        // Success response
        echo "Contact saved successfully.";
    } catch (Exception $e) {
        http_response_code(500);
        echo "Error: " . htmlspecialchars($e->getMessage());
    }
} else {
    http_response_code(405);
    echo "Method not allowed.";
}
?>
