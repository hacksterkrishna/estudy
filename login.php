<?php
session_start();
require 'includes/connect.php';
include 'common/header.php';
include 'common/navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['user_valid'] = $user['user_valid'] ?? 'not_valid'; // Default to 'not_valid' if not set
            $_SESSION['first_name'] = $user['first_name'];

            if (in_array($user['user_type'], ['student', 'trainer'])) {
                header("Location: home/dashboard.php");
                exit;
            } elseif (in_array($user['user_type'], ['admin', 'superadmin'])) {
                header("Location: admin/dashboard.php");
                exit;
            } else {
                $error = "Unauthorized user type.";
            }
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<style>
.login-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    min-height: 90vh;
    padding: 2rem;
}
.login-box {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    padding: 2rem;
    max-width: 450px;
    width: 100%;
    margin-left: auto;
}
.login-title {
    font-weight: bold;
    color: #1c2c7c;
}
.login-image {
    max-width: 400px;
    width: 100%;
    margin-right: auto;
    margin-bottom: 2rem;
}
</style>

<div class="login-container">
    <div class="login-image">
        <img src="assets/img/login-illustration.png" alt="Login Illustration" class="img-fluid rounded">
    </div>
    <div class="login-box">
        <h2 class="login-title mb-3">Log In to your Account</h2>
        <p class="mb-4">Sign in to unlock smooth, hassle-free access—stay connected effortlessly!</p>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required placeholder="Enter your email address">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Enter your password">
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="forgot-password.php"><span class="text-danger small">Forget Password</span></a>
            </div>
            <button type="submit" class="btn btn-primary w-100">Log In</button>
        </form>
        <!-- <div class="mt-3 text-center">
            If you don’t have an account <a href="register.php">Register</a>
        </div> -->
    </div>
</div>

<?php include 'common/footer.php'; ?>
