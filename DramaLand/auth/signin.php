<?php
require_once __DIR__ . '/../includes/db_connect.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get inputs
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validation
        if (empty($email) || empty($password)) {
            throw new Exception('Email and password are required.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format.');
        }

        // Query user
        $stmt = $conn->prepare("SELECT U_ID, Username, Password FROM user WHERE Email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception('Email or password is incorrect.');
        }

        $user = $result->fetch_assoc();
        $stmt->close();

        // Verify password
        if (!password_verify($password, $user['Password'])) {
            throw new Exception('Email or password is incorrect.');
        }

        // Update last login
        $updateStmt = $conn->prepare("UPDATE user SET Last_login = NOW() WHERE U_ID = ?");
        $updateStmt->bind_param('i', $user['U_ID']);
        $updateStmt->execute();
        $updateStmt->close();

        // Start session
        session_start();
        $_SESSION['U_ID'] = $user['U_ID'];
        $_SESSION['Username'] = $user['Username'];
        $_SESSION['Email'] = $email;

        // Redirect to home
        header('Location: ../index.php?login=success');
        exit();

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - DramaLand</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #000;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 450px;
            background-color: #0a0a0a;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
        }

        .brand {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
            text-align: center;
            color: #fff;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 700;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #ccc;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            background-color: #1a1a1a;
            border: 1px solid #333;
            border-radius: 6px;
            color: #fff;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #ff4d4d;
            background-color: #222;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 15px 0;
            font-size: 14px;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .forgot-password {
            text-align: right;
            margin-top: 10px;
        }

        .forgot-password a {
            color: #ff4d4d;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background-color: #ff4d4d;
            color: #000;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .submit-btn:hover {
            background-color: #ff3333;
            transform: translateY(-2px);
        }

        .signup-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #ccc;
        }

        .signup-link a {
            color: #ff4d4d;
            text-decoration: none;
            font-weight: 600;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        .oauth-section {
            text-align: center;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #333;
        }

        .oauth-text {
            font-size: 12px;
            color: #888;
            margin-bottom: 15px;
        }

        .oauth-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .oauth-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid #333;
            background-color: #1a1a1a;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-size: 18px;
            font-weight: bold;
        }

        .oauth-btn:hover {
            border-color: #ff4d4d;
            transform: scale(1.1);
        }

        .oauth-btn img {
            width: 20px;
            height: 20px;
        }

        .error-message {
            background-color: #ff4d4d;
            color: #fff;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }

        .terms {
            font-size: 12px;
            color: #888;
            text-align: center;
            margin-top: 15px;
            line-height: 1.6;
        }

        .terms a {
            color: #0080ff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="brand">DramaLand</div>
        <h2>Sign In</h2>

        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <div class="forgot-password">
                <a href="#">Forgot Password?</a>
            </div>

            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember" style="margin: 0;">Remember me</label>
            </div>

            <button type="submit" class="submit-btn">Sign in</button>
        </form>

        <div class="signup-link">
            New to DramaLand? <a href="signup.php">Sign up now</a>
        </div>

        <div class="oauth-section">
            <p class="oauth-text">or sign in with</p>
            <div class="oauth-buttons">
                <button class="oauth-btn" onclick="alert('Google OAuth integration coming soon')" type="button" title="Google">
                    G
                </button>
                <button class="oauth-btn" onclick="alert('Facebook OAuth integration coming soon')" type="button" title="Facebook">
                    f
                </button>
                <button class="oauth-btn" onclick="alert('Microsoft OAuth integration coming soon')" type="button" title="Microsoft">
                    ≣
                </button>
            </div>
        </div>

        <div class="terms">
            This page is protected by Google reCAPTCHA to ensure you're not a bot.
            <br>
            <a href="#">Learn more</a>
        </div>
    </div>
</body>
</html>
