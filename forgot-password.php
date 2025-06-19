<?php
// File: forgot-password.php
session_start();
require 'includes/connect.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'common/header.php';
include 'common/navbar.php';

$msg = "";

function generateToken($length = 64) {
    return bin2hex(random_bytes($length / 2));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $token = generateToken();
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $conn->query("DELETE FROM password_resets WHERE email = '$email'"); // cleanup old tokens
        $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $token, $expires);
        $stmt->execute();

        $resetLink = "http://yourdomain.com/reset-password.php?token=$token"; // replace with your actual domain

        // Send Email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'youremail@gmail.com'; // your gmail address
            $mail->Password = 'yourapppassword';     // use Gmail App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('youremail@gmail.com', 'SkillzenNepal');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset - SkillzenNepal';
            $mail->Body = "<p>Click the button below to reset your password:</p><p><a href='$resetLink' style='background:#007bff;color:#fff;padding:10px 20px;text-decoration:none;'>Reset Password</a></p><p>This link will expire in 1 hour.</p>";

            $mail->send();
            $msg = "A password reset link has been sent to your email.";
        } catch (Exception $e) {
            $msg = "Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $msg = "No account found with this email.";
    }
}
?>

<style>
.forgot-box {
    max-width: 500px;
    margin: 5rem auto;
    background-color: #fff;
    padding: 2rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border-radius: 8px;
    text-align: center;
}
.forgot-box h2 {
    font-weight: bold;
    color: #1c2c7c;
}
</style>

<div class="forgot-box">
    <h2>FORGOT PASSWORD</h2>
    <p>Enter your registered email address and we will send you a link to reset your password.</p>
    <?php if ($msg): ?><div class="alert alert-info"><?php echo $msg; ?></div><?php endif; ?>
    <form method="POST">
        <div class="mb-3 text-start">
            <label for="email" class="form-label">Your Email Address *</label>
            <input type="email" class="form-control" id="email" name="email" required placeholder="example@abc.com">
        </div>
        <button type="submit" class="btn btn-warning w-100 text-white fw-bold">SEND OTP</button>
    </form>
</div>

<?php include 'common/footer.php'; ?>