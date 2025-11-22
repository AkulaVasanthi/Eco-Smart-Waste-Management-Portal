<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "complaints_db";

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// File upload handling
$targetDir = "uploads/";
$picture = "";

if (isset($_FILES["picture"]) && $_FILES["picture"]["error"] == 0) {
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $picture = $targetDir . basename($_FILES["picture"]["name"]);
    move_uploaded_file($_FILES["picture"]["tmp_name"], $picture);
}

// Collect form data safely
$name = $_POST["name"] ?? '';
$mobile = $_POST["mobile"] ?? '';
$email = $_POST["email"] ?? '';
$category = isset($_POST["category"]) ? implode(",", (array)$_POST["category"]) : '';
$location = $_POST["location"] ?? '';
$details = $_POST["details"] ?? '';

// Insert query with placeholders in the correct order
$sql = "INSERT INTO complaints (name, mobile, email, category, location, location_details, picture)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $name, $mobile, $email, $category, $location, $details, $picture);

if ($stmt->execute()) {
    echo "<script>alert('Complaint Registered Successfully!'); window.location.href='/Main_Page/HOME_PAGE.html';</script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
