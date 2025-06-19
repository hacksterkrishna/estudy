<?php
require 'session.php';
require '../includes/connect.php';
include '../common/header.php';
include '../common/navbar.php';

$user_id = $_SESSION['user_id'];
$packages = $conn->query("SELECT id, title FROM packages WHERE status = 'active' ORDER BY title ASC");
?>

<style>
.referral-box { background: #fff; border-radius: 8px; padding: 1.5rem; box-shadow: 0 2px 5px rgba(0,0,0,0.05); text-align: center; }
select, input[type=text], button { width: 100%; padding: 0.5rem; border-radius: 5px; border: 1px solid #ccc; margin-top: 0.5rem; }
label { font-weight: 500; margin-top: 1rem; display: block; text-align: left; }
#linkOutput { margin-top: 1rem; font-weight: bold; color: #2e7d32; }
#qrCode { margin-top: 1rem; display: flex; justify-content: center; flex-direction: column; align-items: center; }
.qr-buttons { margin-top: 1rem; display: flex; flex-direction: column; gap: 10px; width: 100%; }
</style>

<button class="sidebar-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">👤</button>
<div class="dashboard-container">
    <?php include '../common/sidebar-student.php'; ?>
    <div class="main-content">
        
        <h3>🔗 Generate Referral Link</h3>
        <div style="margin-bottom: 1rem; font-size: 1.1rem; font-weight: bold; background: #e0f7fa; padding: 0.75rem; border-radius: 6px; border: 1px solid #b2ebf2; color: #00796b;">
                Your Referral Code: <code><?php echo $user_id; ?></code>
            </div>
            <label for="package">Select Package</label>
            <select id="package">
                <option value="">-- Choose a Package --</option>
                <?php while($row = $packages->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['title']); ?></option>
                <?php endwhile; ?>
            </select>
            <div id="linkOutput"></div>
            <div id="qrCode"></div>
            <div class="qr-buttons" id="shareButtons" style="display: none;">
                <button id="downloadBtn">⬇️ Download QR as PNG</button>
                <a id="whatsappShare" target="_blank">📱 Share on WhatsApp</a>
                <a id="facebookShare" target="_blank">📘 Share on Facebook</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
<script>
let qr;
document.getElementById('package').addEventListener('change', function () {
    const packageId = this.value;
    const userId = <?php echo $user_id; ?>;
    const linkOutput = document.getElementById('linkOutput');
    const qrCodeDiv = document.getElementById('qrCode');
    const shareButtons = document.getElementById('shareButtons');

    if (packageId) {
        const referralLink = `https://yourdomain.com/register.php?ref=${userId}&pkg=${packageId}`;
        linkOutput.innerHTML = `Your link: <input type='text' value='${referralLink}' readonly onclick='this.select()'>`;

        qr = new QRious({
            element: document.createElement('canvas'),
            value: referralLink,
            size: 180
        });

        qrCodeDiv.innerHTML = '';
        qrCodeDiv.appendChild(qr.element);
        shareButtons.style.display = 'flex';

        document.getElementById('downloadBtn').onclick = function() {
            const a = document.createElement('a');
            a.href = qr.toDataURL();
            a.download = `referral_qr_pkg${packageId}.png`;
            a.click();
        };

        document.getElementById('whatsappShare').href = `https://wa.me/?text=${encodeURIComponent('Join with my referral: ' + referralLink)}`;
        document.getElementById('facebookShare').href = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(referralLink)}`;
    } else {
        linkOutput.innerHTML = '';
        qrCodeDiv.innerHTML = '';
        shareButtons.style.display = 'none';
    }
});
</script>

<?php include '../common/footer.php'; ?>
