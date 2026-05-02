<?php
header("Content-Type: application/json");

// Database credentials
$host = "MySQL80"; // or your DB host
$dbname = "contact_db";
$username = "root"; // your MySQL username
$password = "Morgan.exe101_SQL";     // your MySQL password

try {
    // Connect to MySQL
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Validate input
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['message'])) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Invalid email format."]);
        exit;
    }

    // Prepare SQL statement
    $stmt = $pdo->prepare("INSERT INTO contacts (name, email, message) VALUES (:name, :email, :message)");
    $stmt->execute([
        ":name" => htmlspecialchars($_POST['name']),
        ":email" => htmlspecialchars($_POST['email']),
        ":message" => htmlspecialchars($_POST['message'])
    ]);

    echo json_encode(["success" => true]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
