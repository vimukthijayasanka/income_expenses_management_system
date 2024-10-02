<?php
require 'db.php';
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $birthday = $_POST['birthday'];

    // Combine first and last name to create full name
    $name = $firstname . ' ' . $lastname;

    // Server-side password validation (minimum 8 characters, one uppercase, one number, and one symbol)
    $password_pattern = '/^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W]).{8,}$/';
    
    if (!preg_match($password_pattern, $password)) {
        $errorMessage = "Password must be at least 8 characters, contain one uppercase letter, one number, and one symbol.";
    } elseif ($password !== $confirm_password) {
        $errorMessage = "Passwords do not match!";
    } else {
        // Password hashing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $stmt = $pdo->prepare("INSERT INTO users (username, firstname, lastname, password, name, address, email, contact, birthday) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$username, $firstname, $lastname, $hashed_password, $name, $address, $email, $contact, $birthday])) {
            $successMessage = "Registration successful!";
        } else {
            $errorMessage = "Registration failed!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/register.css">
    <title>Register</title>
    <script>
        // Client-side validation

        // Validate contact number (only digits allowed)
        function validateContactNumber(input) {
            const contactNumber = input.value;
            if (!/^\d+$/.test(contactNumber)) {
                input.setCustomValidity("Contact number must only contain digits.");
            } else {
                input.setCustomValidity("");
            }
        }

        // Validate password strength
        function validatePassword() {
            const password = document.querySelector('input[name="password"]').value;
            const pattern = /^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W]).{8,}$/;
            const passwordHint = document.getElementById('passwordHint');

            if (!pattern.test(password)) {
                passwordHint.style.display = 'block';
            } else {
                passwordHint.style.display = 'none';
            }
        }

        // Validate password confirmation
        function validatePasswordMatch() {
            const password = document.querySelector('input[name="password"]').value;
            const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
            const confirmPasswordHint = document.getElementById('confirmPasswordHint');

            if (password !== confirmPassword) {
                confirmPasswordHint.style.display = 'block';
            } else {
                confirmPasswordHint.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Create Your Account</h1>
        <form method="POST">

            <input type="text" name="username" placeholder="Username" required minlength="5" maxlength="15">
            <span class="hint">Must be unique and 5-15 characters.</span>

            <div class="name-container">
                <input class="form-control" type="text" name="firstname" placeholder="Firstname" required>
                <input class="form-control" type="text" name="lastname" placeholder="Lastname" required>
            </div>
            <input class="form-control" type="email" name="email" placeholder="Email (e.g., user@gmail.com)" required>

            <input class="form-control" type="password" name="password" placeholder="Password" oninput="validatePassword()" required minlength="8">
            <span class="hint" id="passwordHint" style="display:none;color:red;">At least 8 characters, one uppercase letter, one symbol, and one number.</span>

            <input class="form-control" type="password" name="confirm_password" placeholder="Confirm Password" oninput="validatePasswordMatch()" required>
            <span class="hint" id="confirmPasswordHint" style="display:none;color:red;">Passwords do not match.</span>

            <div class="contact-container">
                <select class="form-control" name="country_code" required>
                    <option value="">Country Code</option>
                    <option value="+94">Sri Lanka (+94)</option>
                    <!-- Add more countries as needed -->
                </select>
                <input class="form-control" type="text" name="contact" placeholder="Contact Number" required oninput="validateContactNumber(this)">
            </div>

            <input type="text" name="address" placeholder="Address">
            <input type="date" name="birthday" required>
            <span class="hint">Your date of birth.</span>

            <button type="submit">Register</button>

            <!-- Success or Error message displayed here -->
            <?php if ($successMessage): ?>
                <div id="statusMessage" class="success-message">
                    <?php echo $successMessage; ?>
                </div>
            <?php elseif ($errorMessage): ?>
                <div id="statusMessage" class="error-message">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>
        </form>

        <p>Already have an account? <a class="link" href="login.php">Login here</a></p>
    </div>
</body>
</html>
