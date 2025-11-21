<?php
$servername = "localhost";
$username = "root"; // Change if needed
$password = "";     // Change if your DB has a password
$dbname = "create_account_db";

// Step 1: Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Step 1 - DB Connection failed: " . $conn->connect_error);
}
echo "Step 1 - Connected to DB<br>";

// Step 2: Get POST data
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
echo "Step 2 - Collected form data<br>";

// Step 3: Validate password match
if ($password !== $confirm_password) {
    die("<script>alert('Passwords do not match'); window.history.back();</script>");
}
echo "Step 3 - Passwords matched<br>";

// Step 4: Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
echo "Step 4 - Password hashed<br>";

// Step 5: Prepare insert statement
$stmt = $conn->prepare("INSERT INTO create_acc (username, email, password) VALUES (?, ?, ?)");
if (!$stmt) {
    die("Step 5 - Prepare failed: " . $conn->error);
}
echo "Step 5 - Statement prepared<br>";

// Step 6: Bind and execute
$stmt->bind_param("sss", $username, $email, $hashed_password);
if ($stmt->execute()) {
    echo "<script>alert('Account created'); window.location.href='HOME_PAGE.html';</script>";
} else {
    die("Step 6 - Execute failed: " . $stmt->error);
}

$stmt->close();
$conn->close();
?>
