<?php
// File: register.php
session_start();
require 'includes/connect.php';
include 'common/header.php';
include 'common/navbar.php';

$countries = $conn->query("SELECT id, name FROM countries ORDER BY name ASC");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, user_type) VALUES (?, ?, ?, ?, 'student')");
    $stmt->bind_param("ssss", $first_name, $last_name, $email, $password);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful. You can login now.";
        header("Location: login.php");
        exit;
    } else {
        $error = "Something went wrong. Try again.";
    }
}
?>

<style>
.register-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 2rem;
    background-color: #f8f9fa;
}
.register-image {
    flex: 1 1 400px;
    max-width: 500px;
    padding: 1rem;
}
.register-form {
    flex: 1 1 500px;
    max-width: 600px;
    background-color: #fff;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0,0,0,0.05);
}
#strengthMessage {
    font-weight: 500;
}
</style>

<div class="register-container">
    <div class="register-image">
        <img src="assets/img/login-illustration.png" alt="Register Illustration" class="img-fluid rounded">
    </div>
    <div class="register-form">
        <h2 class="mb-3">Register your Account</h2>
        <p>Join us and unlock a world of possibilities—Register today!</p>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" name="first_name" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="last_name" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Contact Number</label>
                    <input type="text" name="phone" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Country</label>
                    <select name="country_id" id="country" class="form-select" required>
                        <option value="">Select Country</option>
                        <?php while ($row = $countries->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">State</label>
                    <select name="state_id" id="state" class="form-select" required>
                        <option value="">Select country first</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="dob" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select">
                        <option value="">Select your gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                    <small id="strengthMessage"></small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <div class="col-md-8 mb-3">
                    <label class="form-label">Discount Code</label>
                    <input type="text" name="discount_code" class="form-control">
                </div>
                <div class="col-md-4 mb-3 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-primary w-100">Apply Code</button>
                </div>
                <div class="col-12 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="termsCheck">
                        <label class="form-check-label">I agree with website terms and conditions *</label>
                    </div>
                </div>
                <div class="col-12">
                    <button id="registerBtn" class="btn btn-primary w-100" disabled>Register Account</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Country-state AJAX
const countryDropdown = document.getElementById('country');
const stateDropdown = document.getElementById('state');

countryDropdown.addEventListener('change', function () {
    const countryId = this.value;
    stateDropdown.innerHTML = '<option value="">Loading...</option>';
    fetch(`get-states.php?country_id=${countryId}`)
        .then(res => res.json())
        .then(data => {
            stateDropdown.innerHTML = '<option value="">Select State</option>';
            data.forEach(state => {
                const option = document.createElement('option');
                option.value = state.id;
                option.text = state.name;
                stateDropdown.appendChild(option);
            });
        });
});

// Password strength indicator
const passwordField = document.getElementById('password');
const strengthMessage = document.getElementById('strengthMessage');
passwordField.addEventListener('input', () => {
    const value = passwordField.value;
    let strength = 'Weak';
    let color = 'red';
    if (value.length > 8 && /[A-Z]/.test(value) && /[0-9]/.test(value)) {
        strength = 'Strong';
        color = 'green';
    } else if (value.length >= 6) {
        strength = 'Medium';
        color = 'orange';
    }
    strengthMessage.textContent = `Strength: ${strength}`;
    strengthMessage.style.color = color;
});

// Enable register button on terms checked
const termsCheck = document.getElementById('termsCheck');
const registerBtn = document.getElementById('registerBtn');
termsCheck.addEventListener('change', () => {
    registerBtn.disabled = !termsCheck.checked;
});
</script>

<?php include 'common/footer.php'; ?>