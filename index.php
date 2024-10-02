<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $comment = $_POST['comment'];

    // Insert transaction into database
    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, date, comment) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $type, $amount, $date, $comment]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="CSS/index.css">
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
    <a href="logout.php">Logout</a>
    
    <form method="POST">
        <select name="type" required>
            <option value="income">Income</option>
            <option value="expense">Expense</option>
        </select>
        <input type="number" name="amount" placeholder="Amount" required>
        <input type="date" name="date" required>
        <textarea name="comment" placeholder="Comment"></textarea>
        <button type="submit">Submit</button>
        <button type="reset">Reset</button>
    </form>
    <a href="report_gen.php">Generate Reports</a>
</body>
</html>
