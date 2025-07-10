<?php
// File: register.php
session_start();
// Auto-redirect to pay_order.php if user is already logged in and package_id is present
if (isset($_SESSION['user_id']) && $_SESSION['user_type'] = 'student' && isset($_GET['package_id'])) {
    header("Location: pay_order.php?package_id=" . intval($_GET['package_id']));
    exit;
}


require 'includes/connect.php';
include 'common/header.php';
include 'common/navbar.php';

$countries = $conn->query("SELECT id, name FROM countries ORDER BY name ASC");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $package_id = isset($_POST['package_id']) ? intval($_POST['package_id']) : 0;

    $first_name = trim($_POST['first_name']);
    $middle_name = trim($_POST['middle_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $gender = trim($_POST['gender']);
    $country_id = trim($_POST['country_id']);
    $state_id = trim($_POST['state_id']);
    $dob = trim($_POST['dob']);
    $discount_code = trim($_POST['discount_code']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "Email is already registered. Please login or use another email.";
    } else {

        $stmt = $conn->prepare("INSERT INTO users (first_name, middle_name, last_name, email, password, phone, gender, country_id, state_id, dob, discount_code, user_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'student')");
        $stmt->bind_param("sssssssssss", $first_name, $middle_name, $last_name, $email, $password, $phone, $gender, $country_id, $state_id, $dob, $discount_code);

        if ($stmt->execute()) {
            $new_user_id = $stmt->insert_id;
            $_SESSION['user_id'] = $new_user_id;
            $_SESSION['user_type'] = 'student'; // Optional: if you want to check roles later
            $_SESSION['user_valid'] = 'not_valid'; // Set to not valid until payment is confirmed
            header("Location: pay_order.php?package_id=" . $package_id);
            exit;
        } else {
            $error = "Something went wrong. Try again.";
        }
    }
    $check->close();
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
                <input type="hidden" name="package_id" value="<?php echo isset($_GET['package_id']) ? intval($_GET['package_id']) : ''; ?>">
                <div class="col-md-4 mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" name="first_name" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Middle Name</label>
                    <input type="text" name="middle_name" class="form-control" >
                </div>
                <div class="col-md-4 mb-3">
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
                        <option value="male" selected>Male</option>
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
                <!-- Package Details Section -->
                <?php
                if (isset($_GET['package_id']) && is_numeric($_GET['package_id'])) {
                    $pkg_id = intval($_GET['package_id']);
                    $pkg_sql = $conn->prepare("SELECT title, price FROM packages WHERE id = ?");
                    $pkg_sql->bind_param("i", $pkg_id);
                    $pkg_sql->execute();
                    $pkg_sql->bind_result($pkg_name, $pkg_price);
                    if ($pkg_sql->fetch()) {
                        $tax = round($pkg_price * 0.13);
                        $total = $pkg_price + $tax;
                ?>
                    <div class="col-12 mb-4">
                        <div class="border p-3 bg-light rounded">
                            <h5>Your Order Details</h5>
                            <table class="table table-sm table-bordered mt-2">
                                <tr>
                                    <th>Package Name:</th>
                                    <td><?php echo htmlspecialchars($pkg_name); ?></td>
                                </tr>
                                <tr>
                                    <th>Quantity:</th>
                                    <td>1</td>
                                </tr>
                                <tr>
                                    <th>Price:</th>
                                    <td><del>₹<?php echo $pkg_price + $tax; ?></del> ₹<?php echo $pkg_price; ?> + ₹<?php echo $tax; ?> (Vat included 13%)</td>
                                </tr>
                                <tr>
                                    <th>Total:</th>
                                    <td>₹<?php echo $total; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                <?php
                    }
                    $pkg_sql->close();
                }
                ?>
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
    fetch(`common/get-states.php?country_id=${countryId}`)
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