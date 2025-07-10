<?php
session_start();
$pageTitle = "Pay Order";
require 'includes/connect.php';
include 'common/header.php';
include 'common/navbar.php';
if (!isset($_SESSION['user_id'])) {
    // Redirect to login or registration if session not found
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user details from the database
$user_stmt = $conn->prepare("SELECT first_name, last_name, phone, email FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_stmt->bind_result($first_name, $last_name, $phone, $email);

if ($user_stmt->fetch()) {
    $user_name = $first_name . ' ' . $last_name;
    $user_phone = $phone;
    $user_email = $email;
} else {
    $user_name = $user_phone = $user_email = "Unknown";
}
$user_stmt->close();


// Validate package_id from URL
$package_id = isset($_GET['package_id']) ? intval($_GET['package_id']) : 0;

if ($package_id <= 0) {
    die("Invalid package.");
}

// Fetch package details
$pkg_stmt = $conn->prepare("SELECT title, price, discounted_price FROM packages WHERE id = ?");
$pkg_stmt->bind_param("i", $package_id);
$pkg_stmt->execute();
$pkg_stmt->bind_result($package_name, $original_price, $discounted_price, );
$tax_percentage = 13; // Assuming a fixed tax percentage of 13%

if ($pkg_stmt->fetch()) {
    $tax = round($discounted_price * $tax_percentage / 100);
    $total = $discounted_price + $tax;
} else {
    die("Package not found.");
}
$pkg_stmt->close();


?>

<section class="py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h2>Your Details</h2>
      <p><strong>Name:</strong> <?= htmlspecialchars($user_name); ?></p>
      <p><strong>Phone:</strong> <?= htmlspecialchars($user_phone); ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($user_email); ?></p>
    </div>

    <div class="text-center mb-4">
      <h3>Your Order Details</h3>
      <div class="table-responsive d-inline-block text-start">
        <table class="table table-bordered w-auto">
          <tr>
            <th>Package Name:</th>
            <td><?= htmlspecialchars($package_name); ?></td>
          </tr>
          <tr>
            <th>Quantity:</th>
            <td>1</td>
          </tr>
          <tr>
            <th>Price:</th>
            <td><del>NPR <?= number_format($original_price); ?></del> NPR <?= number_format($discounted_price); ?> + NPR <?= number_format($tax); ?> (Tax included 13%)</td>
          </tr>
          <tr>
            <th>Total:</th>
            <td>NPR <?= number_format($total); ?></td>
          </tr>
        </table>
      </div>
      <p class="text-muted mt-2" style="max-width: 600px; margin: auto;">
        Your details will be used to process your order, support your experience throughout this website, and for other purposes described in our terms & conditions
      </p>

      <div class="form-check my-3">
        <input class="" type="checkbox" id="agreeTerms">
        <label class="form-check-label" for="agreeTerms">
          I Agree with <a href="#">terms and conditions</a> *
        </label>
      </div>

      <button class="btn btn-warning px-5 py-2" id="payButton" disabled>Pay with QR</button>
    </div>
  </div>
</section>

<script>
  // Enable Pay button when checkbox is checked
  document.getElementById('agreeTerms').addEventListener('change', function () {
    document.getElementById('payButton').disabled = !this.checked;
  });
</script>

<?php include 'common/footer.php'; ?>
<script>
  const agreeTerms = document.getElementById('agreeTerms');
  const payButton = document.getElementById('payButton');
  const packageId = <?= intval($package_id); ?>; // safely output package_id from PHP

  // Enable Pay button only when checkbox is checked
  agreeTerms.addEventListener('change', function () {
    payButton.disabled = !this.checked;
  });

  // Redirect on Pay button click
  payButton.addEventListener('click', function () {
    if (agreeTerms.checked) {
      window.location.href = `pay_with_qr.php?package_id=${packageId}`;
    }
  });
</script>

