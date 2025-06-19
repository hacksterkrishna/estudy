<?php
// File: reset-password.php
session_start();
require 'includes/connect.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'common/header.php';
include 'common/navbar.php';

$token = $_GET['token'] ?? '';
$error = '';
$success = '';

if (!$token) {
    $error = "Invalid or missing token.";
} else {
    $stmt = $conn->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $resetData = $stmt->get_result()->fetch_assoc();

    if (!$resetData) {
        $error = "Token is invalid or has expired.";
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password !== $confirm_password) {
            $error = "Passwords do not match.";
        } elseif (strlen($new_password) < 6) {
            $error = "Password must be at least 6 characters.";
        } else {
            $hashed = password_hash($new_password, PASSWORD_BCRYPT);
            $email = $resetData['email'];

            $update = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $update->bind_param("ss", $hashed, $email);
            $update->execute();

            $conn->query("DELETE FROM password_resets WHERE email = '$email'");

            // Log password reset action
            $user_q = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $user_q->bind_param("s", $email);
            $user_q->execute();
            $user_data = $user_q->get_result()->fetch_assoc();
            $user_id = $user_data['id'] ?? null;

            if ($user_id) {
                $action = 'password_reset';
                $detail = 'Password was reset using token.';
                $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
                $agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

                $log = $conn->prepare("INSERT INTO activity_logs (user_id, action, detail, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
                $log->bind_param("issss", $user_id, $action, $detail, $ip, $agent);
                $log->execute();
            }

            // Send password reset confirmation email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'youremail@gmail.com';
                $mail->Password = 'yourapppassword';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('youremail@gmail.com', 'SkillzenNepal');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Your password has been reset';
                $mail->Body = '<p>Your password was successfully changed. If this wasn\'t you, please contact support immediately.</p>';

                $mail->send();
            } catch (Exception $e) {
                // Optional: log mail error
            }

            $success = "Your password has been reset successfully. You can now <a href='login.php'>login</a>.";
        }
    }
}
?>

<style>
.reset-box {
    max-width: 500px;
    margin: 5rem auto;
    background-color: #fff;
    padding: 2rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border-radius: 8px;
    text-align: center;
}
.reset-box h2 {
    font-weight: bold;
    color: #1c2c7c;
}
</style>

<div class="reset-box">
    <h2>RESET PASSWORD</h2>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>

    <?php if (!$success && $resetData): ?>
    <form method="POST">
        <div class="mb-3 text-start">
            <label class="form-label">New Password</label>
            <input type="password" name="new_password" class="form-control" required placeholder="Enter new password">
        </div>
        <div class="mb-3 text-start">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" required placeholder="Confirm new password">
        </div>
        <button type="submit" class="btn btn-primary w-100">Reset Password</button>
    </form>
    <?php endif; ?>
</div>

<?php include 'common/footer.php'; ?>