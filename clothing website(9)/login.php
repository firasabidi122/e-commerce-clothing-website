<?php
session_start();
require_once 'config/db_connect.php';
require_once 'includes/auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        $email = $conn->real_escape_string($_POST['email']);
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            $error = "Please fill in all fields";
        } else {
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];

                    // Transfer guest cart items to user cart if any
                    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $product_id => $item) {
                            $quantity = $item['quantity'];
                            $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?) 
                                                 ON DUPLICATE KEY UPDATE quantity = quantity + ?");
                            $stmt->bind_param("iiii", $user['id'], $product_id, $quantity, $quantity);
                            $stmt->execute();
                        }
                        unset($_SESSION['cart']);
                    }

                    header("Location: index.php");
                    exit();
                } else {
                    $error = "Invalid password";
                }
            } else {
                $error = "User not found";
            }
        }
    } elseif (isset($_POST['register'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $email = $conn->real_escape_string($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Check if email already exists
        $check_sql = "SELECT id FROM users WHERE email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $error = "Email already registered";
        } else {
            $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $name, $email, $password);
            
            if ($stmt->execute()) {
                $success = "Registration successful! Please login.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EcoFriendly Fashion</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .auth-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            display: flex;
            gap: 40px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .auth-form {
            flex: 1;
            padding: 30px;
            border-radius: 15px;
            background: #f8f9fa;
            transition: transform 0.3s ease;
        }

        .auth-form:hover {
            transform: translateY(-5px);
        }

        .auth-form h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 24px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #34495e;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            border-color: #3498db;
            outline: none;
        }

        .auth-btn {
            width: 100%;
            padding: 12px;
            background: #2ecc71;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .auth-btn:hover {
            background: #27ae60;
        }

        .error-message {
            color: #e74c3c;
            background: #fde8e8;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }

        .success-message {
            color: #2ecc71;
            background: #e8f8e8;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }

        .divider {
            width: 1px;
            background: #e0e0e0;
            position: relative;
        }

        .divider::after {
            content: 'OR';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 10px;
            color: #95a5a6;
            font-weight: 600;
        }

        .social-login {
            text-align: center;
            margin-top: 20px;
        }

        .social-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin: 0 10px;
            color: white;
            transition: transform 0.3s ease;
        }

        .social-btn:hover {
            transform: scale(1.1);
        }

        .facebook { background: #3b5998; }
        .google { background: #db4437; }
        .twitter { background: #1da1f2; }

        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .remember-me input {
            margin-right: 8px;
        }

        .forgot-password {
            text-align: right;
            margin-bottom: 15px;
        }

        .forgot-password a {
            color: #3498db;
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .password-input {
            position: relative;
        }

        .password-input i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #95a5a6;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <h1><a href="index.php">EcoFriendly Fashion</a></h1>
            </div>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="cart.php">Cart</a>
                <a href="login.php" class="active">Login</a>
            </div>
        </nav>
    </header>

    <main>
        <div class="auth-container">
            
            <div class="auth-form">
                <h2>Login</h2>
                <?php if ($error): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="success-message"><?php echo $success; ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="login-email">Email</label>
                        <input type="email" id="login-email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="login-password">Password</label>
                        <div class="password-input">
                            <input type="password" id="login-password" name="password" required>
                            <i class="fas fa-eye" onclick="togglePassword('login-password')"></i>
                        </div>
                    </div>
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <div class="forgot-password">
                        <a href="#">Forgot Password?</a>
                    </div>
                    <button type="submit" name="login" class="auth-btn">Login</button>
                </form>
                <div class="social-login">
                    <p>Or connect with</p>
                    <a href="https://facebook.com/ecofriendlyfashion" target="_blank" class="social-btn facebook" title="Login with Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://accounts.google.com" target="_blank" class="social-btn google" title="Login with Google">
                        <i class="fab fa-google"></i>
                    </a>
                    <a href="https://twitter.com/ecofashion" target="_blank" class="social-btn twitter" title="Login with Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                </div>
            </div>

            <div class="divider"></div>

            
            <div class="auth-form">
                <h2>Register</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="register-name">Full Name</label>
                        <input type="text" id="register-name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="register-email">Email</label>
                        <input type="email" id="register-email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="register-password">Password</label>
                        <div class="password-input">
                            <input type="password" id="register-password" name="password" required>
                            <i class="fas fa-eye" onclick="togglePassword('register-password')"></i>
                        </div>
                    </div>
                    <button type="submit" name="register" class="auth-btn">Create Account</button>
                </form>
            </div>
        </div>
    </main>

    <script>
        
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });

        // Show/hide password functionality
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling;
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
<?php
$conn->close();
?>
