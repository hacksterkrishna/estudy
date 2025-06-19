<?php
// File: student/profile.php

require 'session.php';
require '../includes/connect.php';
include '../common/header.php';
include '../common/navbar.php';

$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
$countries = $conn->query("SELECT * FROM countries ORDER BY name ASC");
?>

<style>
.card-box { background: linear-gradient(to right, #06b6d4, #3b82f6); color: #fff; border-radius: 10px; padding: 1.5rem; margin-bottom: 2rem; }
.tabs { display: flex; gap: 1rem; margin-bottom: 1rem; border-bottom: 2px solid #ccc; }
.tabs button { padding: 0.5rem 1rem; background: none; border: none; font-weight: bold; cursor: pointer; border-bottom: 3px solid transparent; }
.tabs button.active { border-color: #3b82f6; color: #3b82f6; }
.tab-content { display: none; }
.tab-content.active { display: block; background: #fff; padding: 1rem; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); }
label { font-weight: 500; display: block; margin: 1rem 0 0.2rem; }
input, select, textarea { width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 5px; }
input[type="submit"] { background: #f97316; color: #fff; font-weight: bold; margin-top: 1rem; border: none; cursor: pointer; padding: 0.7rem 1.5rem; }
</style>

<button class="sidebar-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">👤</button>
<div class="dashboard-container">
    <?php include '../common/sidebar-student.php'; ?>
    <div class="main-content">
        <div class="card-box">
            <h2>Your Profile Details</h2>
        </div>

        <div class="tabs">
            <button class="tab-btn active" onclick="switchTab('profile')">Profile Details</button>
            <button class="tab-btn" onclick="switchTab('picture')">Update Profile Picture</button>
            <button class="tab-btn" onclick="switchTab('password')">Update Password</button>
            <button class="tab-btn" onclick="switchTab('status')">Account Status</button>
        </div>

        <div id="profile" class="tab-content active">
            <form method="post" action="update-profile.php">
                <label>First Name</label>
                <input type="text" name="first_name" value="<?php echo $user['first_name']; ?>" required>
                <label>Last Name</label>
                <input type="text" name="last_name" value="<?php echo $user['last_name']; ?>" required>
                <label>Email Address</label>
                <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
                <label>Phone Number</label>
                <input type="text" name="phone" value="<?php echo $user['phone']; ?>">
                <label>Occupation</label>
                <input type="text" name="occupation" value="<?php echo $user['occupation'] ?? ''; ?>">
                <label>Date of Birth</label>
                <input type="date" name="dob" value="<?php echo $user['dob']; ?>">
                <label>Father Name</label>
                <input type="text" name="father_name" value="<?php echo $user['father_name'] ?? ''; ?>">
                <label>Mother Name</label>
                <input type="text" name="mother_name" value="<?php echo $user['mother_name'] ?? ''; ?>">
                <label>Country</label>
                <select name="country_id" id="country" required>
                    <option value="">-- Select Country --</option>
                    <?php while($country = $countries->fetch_assoc()): ?>
                        <option value="<?php echo $country['id']; ?>" <?php echo ($user['country_id'] == $country['id']) ? 'selected' : ''; ?>><?php echo $country['name']; ?></option>
                    <?php endwhile; ?>
                </select>
                <label>State</label>
                <select name="state_id" id="state" required>
                    <option value="">-- Select State --</option>
                </select>
                <label>Address</label>
                <input type="text" name="address" value="<?php echo $user['address']; ?>">
                <input type="submit" value="Update Details">
            </form>
        </div>

        <div id="picture" class="tab-content">
            <form method="post" enctype="multipart/form-data" action="update-picture.php">
                <label>Upload New Profile Picture</label>
                <input type="file" name="profile_img" required>
                <input type="submit" value="Upload Picture">
            </form>
        </div>

        <div id="password" class="tab-content">
            <form method="post" action="update-password.php">
                <label>Current Password</label>
                <input type="password" name="current_password" required>
                <label>New Password</label>
                <input type="password" name="new_password" required>
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" required>
                <input type="submit" value="Change Password">
            </form>
        </div>

        <div id="status" class="tab-content">
            <p><strong>Status:</strong> <?php echo $user['status'] ?? 'Active'; ?></p>
            <p><strong>Joined On:</strong> <?php echo $user['created_at']; ?></p>
        </div>
    </div>
</div>

<script>
function switchTab(tabId) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
    event.target.classList.add('active');
}

function loadStates(countryId, selectedState = null) {
    const stateSelect = document.getElementById('state');
    stateSelect.innerHTML = '<option>Loading...</option>';

    fetch('../ajax/get-states.php?country_id=' + countryId)
        .then(res => res.json())
        .then(states => {
            stateSelect.innerHTML = '<option value="">-- Select State --</option>';
            states.forEach(state => {
                const opt = document.createElement('option');
                opt.value = state.id;
                opt.textContent = state.name;
                if (selectedState && selectedState == state.id) {
                    opt.selected = true;
                }
                stateSelect.appendChild(opt);
            });
        });
}

document.getElementById('country').addEventListener('change', function () {
    loadStates(this.value);
});

// preload states
window.addEventListener('DOMContentLoaded', function () {
    const selectedCountry = document.getElementById('country').value;
    const selectedState = '<?php echo $user['state_id']; ?>';
    if (selectedCountry) {
        loadStates(selectedCountry, selectedState);
    }
});
</script>

<?php include '../common/footer.php'; ?>
