<?php
require_once __DIR__ . '/../includes/db_connect.php';

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get and sanitize inputs
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validation
        if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
            throw new Exception('All fields are required.');
        }

        if (strlen($username) < 3) {
            throw new Exception('Username must be at least 3 characters long.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format.');
        }

        if (strlen($password) < 6) {
            throw new Exception('Password must be at least 6 characters long.');
        }

        if ($password !== $confirmPassword) {
            throw new Exception('Passwords do not match.');
        }

        // Check if email already exists
        $checkStmt = $conn->prepare("SELECT U_ID FROM user WHERE Email = ?");
        $checkStmt->bind_param('s', $email);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            throw new Exception('Email already registered. Please use a different email or sign in.');
        }
        $checkStmt->close();

        // Check if username already exists
        $checkStmt = $conn->prepare("SELECT U_ID FROM user WHERE Username = ?");
        $checkStmt->bind_param('s', $username);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            throw new Exception('Username already taken. Please choose a different username.');
        }
        $checkStmt->close();

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert user
        $stmt = $conn->prepare("INSERT INTO user (Email, Username, Password, Role) VALUES (?, ?, ?, 'user')");
        $stmt->bind_param('sss', $email, $username, $hashedPassword);
        $stmt->execute();

        $success = 'Account created successfully! You can now sign in.';

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
    <title>Sign Up - DramaLand</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 100%);
            color: #fff;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255, 77, 77, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .container {
            width: 100%;
            max-width: 480px;
            background: linear-gradient(135deg, rgba(15, 15, 25, 0.95) 0%, rgba(30, 30, 45, 0.95) 100%);
            padding: 50px 40px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 77, 77, 0.1);
            position: relative;
            z-index: 1;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .brand {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            text-align: center;
            color: #fff;
            letter-spacing: 0.5px;
        }

        .brand-subtitle {
            text-align: center;
            font-size: 12px;
            color: #888;
            margin-bottom: 35px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        h2 {
            text-align: center;
            margin-bottom: 35px;
            font-size: 32px;
            font-weight: 700;
            background: linear-gradient(135deg, #fff 0%, #ccc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-size: 13px;
            color: #bbb;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 14px 16px;
            background-color: rgba(26, 26, 36, 0.8);
            border: 1.5px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: #fff;
            font-size: 14px;
            transition: all 0.4s ease;
            backdrop-filter: blur(5px);
        }

        input[type="text"]::placeholder,
        input[type="email"]::placeholder,
        input[type="password"]::placeholder {
            color: #666;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #ff4d4d;
            background-color: rgba(40, 40, 60, 0.8);
            box-shadow: 0 0 20px rgba(255, 77, 77, 0.3);
            transform: translateY(-2px);
        }

        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #ff4d4d 0%, #ff3333 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.4s ease;
            margin-top: 28px;
            letter-spacing: 0.5px;
            box-shadow: 0 8px 20px rgba(255, 77, 77, 0.3);
        }

        .submit-btn:hover {
            background: linear-gradient(135deg, #ff3333 0%, #ff1a1a 100%);
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(255, 77, 77, 0.5);
        }

        .submit-btn:active {
            transform: translateY(-1px);
        }

        .signin-link {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #bbb;
        }

        .signin-link a {
            color: #ff4d4d;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
        }

        .signin-link a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #ff4d4d;
            transition: width 0.3s ease;
        }

        .signin-link a:hover::after {
            width: 100%;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 35px 0;
            color: #666;
            font-size: 12px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
        }

        .divider span {
            padding: 0 15px;
        }

        .oauth-section {
            text-align: center;
        }

        .oauth-text {
            font-size: 13px;
            color: #888;
            margin-bottom: 18px;
            letter-spacing: 0.3px;
        }

        .oauth-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .oauth-btn {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            border: 1.5px solid rgba(255, 255, 255, 0.1);
            background: rgba(26, 26, 36, 0.8);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s ease;
            font-size: 20px;
            font-weight: 600;
            color: #999;
            backdrop-filter: blur(5px);
        }

        .oauth-btn:hover {
            border-color: #ff4d4d;
            background: rgba(255, 77, 77, 0.15);
            transform: translateY(-4px);
            color: #ff4d4d;
            box-shadow: 0 10px 25px rgba(255, 77, 77, 0.2);
        }

        .error-message {
            background: linear-gradient(135deg, rgba(255, 77, 77, 0.15) 0%, rgba(255, 100, 100, 0.1) 100%);
            border: 1px solid rgba(255, 77, 77, 0.3);
            color: #ff9999;
            padding: 16px 18px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-size: 14px;
            text-align: center;
            animation: shake 0.4s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .success-message {
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.15) 0%, rgba(100, 200, 100, 0.1) 100%);
            border: 1px solid rgba(76, 175, 80, 0.3);
            color: #90EE90;
            padding: 16px 18px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-size: 14px;
            text-align: center;
            animation: slideDown 0.4s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-message a {
            color: #90EE90;
            font-weight: 600;
            text-decoration: underline;
        }

        .terms {
            font-size: 12px;
            color: #777;
            text-align: center;
            margin-top: 25px;
            line-height: 1.6;
            letter-spacing: 0.2px;
        }

        .terms a {
            color: #4da6ff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .terms a:hover {
            color: #66b3ff;
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .container {
                padding: 40px 28px;
            }

            h2 {
                font-size: 28px;
                margin-bottom: 30px;
            }

            .brand {
                font-size: 24px;
            }

            .oauth-buttons {
                gap: 15px;
            }

            .oauth-btn {
                width: 45px;
                height: 45px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="brand">DramaLand</div>
        <h2>Sign up</h2>

        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($success); ?>
                <br><br>
                <a href="signin.php" style="color: #fff; text-decoration: underline;">Click here to sign in</a>
            </div>
        <?php endif; ?>

        <?php if (!$success): ?>
        <form method="POST" action="">
            <div class="form-group">
                <input type="text" name="username" placeholder="User Name" required>
            </div>

            <div class="form-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <div class="form-group">
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            </div>

            <button type="submit" class="submit-btn">Create Account</button>
        </form>

        <div class="signin-link">
            Already have an account? <a href="signin.php">Sign in</a>
        </div>

        <div class="oauth-section">
            <p class="oauth-text">or sign up with</p>
            <div class="oauth-buttons">
                <button class="oauth-btn" onclick="alert('Google OAuth integration coming soon')" title="Google">
                    <span>G</span>
                </button>
                <button class="oauth-btn" onclick="alert('Facebook OAuth integration coming soon')" title="Facebook">
                    <span>f</span>
                </button>
                <button class="oauth-btn" onclick="alert('Microsoft OAuth integration coming soon')" title="Microsoft">
                    <span>≣</span>
                </button>
            </div>
        </div>

        <div class="terms">
            This page is protected by Google reCAPTCHA to ensure you're not a bot.
            <br>
            <a href="#">Learn more</a>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
