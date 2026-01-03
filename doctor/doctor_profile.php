<?php
session_start();
include("../connect.php");
include("doctor_header.php");
include("doctor_sidebar.php");

if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php");
    exit;
}

$doctor_id = $_SESSION['doctor_id'];

$stmt = $conn->prepare("SELECT name, email, phone, password FROM doctors WHERE id=?");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();

$success = $error = "";
if (isset($_POST['update_profile'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if (strlen($name) < 3 || strlen($name) > 35 || !preg_match("/^[A-Za-z ]+$/", $name)) {
        $error = "Name must be 3-35 alphabetic letters only.";
    } elseif (strlen($email) < 4 || strlen($email) > 35) {
        $error = "Email must be 4-35 characters.";
    } elseif (!preg_match("/^[0-9]{11}$/", $phone)) {
        $error = "Phone number must be exactly 11 digits.";
    } else {

        $update = $conn->prepare("UPDATE doctors SET name=?, email=?, phone=? WHERE id=?");
        $update->bind_param("sssi", $name, $email, $phone, $doctor_id);

        if ($update->execute()) {
            $success = "Profile updated successfully.";
            $doctor['name'] = $name;
            $doctor['email'] = $email;
            $doctor['phone'] = $phone;
        } else {
            $error = "Failed to update profile.";
        }
    }
}

if (isset($_POST['change_password'])) {

    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if (!password_verify($current, $doctor['password'])) {
        $error = "Current password is incorrect.";
    } elseif ($new === $current) {
        $error = "Old password not allowed in new password.";
    } elseif (strlen($new) < 8) {
        $error = "New password must be at least 8 characters.";
    } elseif ($new !== $confirm) {
        $error = "Passwords do not match.";
    } else {

        $hash = password_hash($new, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE doctors SET password=? WHERE id=?");
        $update->bind_param("si", $hash, $doctor_id);

        if ($update->execute()) {
            $success = "Password changed successfully.";
        } else {
            $error = "Failed to update password.";
        }
    }
}
?>

<style>
.profile-section { max-width: 600px; margin: auto; }
.profile-card {
    border: 1px solid #ddd;
    border-radius: 12px;
    padding: 25px;
    background: #fff;
}
.back-btn-modern {
    background: #f1f1f1;
    border: none;
    padding: 8px 20px;
    border-radius: 30px;
    cursor: pointer;
    font-size: 15px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all .3s ease;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
.back-btn-modern:hover {
    background: #e2e2e2;
    transform: translateX(-3px);
}
.toggle-eye {
    position: absolute;
    right: 12px;
    top: 10px;
    cursor: pointer;
    font-size: 18px;
}
</style>

<div class="dashboard-wrapper">
<div class="container-fluid">

    <div class="page-header text-center">
        <h2 class="section-title">Doctor <span>Profile</span></h2>
    </div>

    <?php 
        if ($success) echo "<div class='alert alert-success alert-dismissible fade show'>
        $success <span class='alert-close' style='float:right;cursor:pointer;font-weight:bold;'>×</span></div>";

        if ($error) echo "<div class='alert alert-danger alert-dismissible fade show'>
        $error <span class='alert-close' style='float:right;cursor:pointer;font-weight:bold;'>×</span></div>";
    ?>

    <div class="profile-section">
        <div id="profileCard" class="profile-card">
            <div class="text-center mb-3">
                <i class="bi bi-person-circle" style="font-size:100px;color:#007bff;"></i>
                <h4><?= htmlspecialchars($doctor['name']) ?></h4>
                <p><?= htmlspecialchars($doctor['phone']) ?></p>
                <p><?= htmlspecialchars($doctor['email']) ?></p>
            </div>

            <div class="text-center">
                <button id="editProfileBtn" class="btn btn-warning rounded-pill px-4">Edit Profile</button>
                <button id="changePassBtn" class="btn btn-primary rounded-pill px-4 my-1">Change Password</button>
            </div>
        </div>


        <!-- EDIT PROFILE -->
        <form method="POST" id="editProfileForm" class="profile-card d-none">

            <button type="button" class="back-btn-modern" onclick="showProfile()">
                <i class="bi bi-arrow-left"></i> Back
            </button>

            <h4 class="text-center mb-3">Edit Profile</h4>

            <label>Name</label>
            <input type="text" name="name" class="form-control mb-3"
                value="<?= htmlspecialchars($doctor['name']) ?>"
                minlength="3" maxlength="40"
                pattern="[A-Za-z ]{3,35}"
                oninput="this.value=this.value.replace(/[^A-Za-z ]/g,'')"
                required>

            <label>Email</label>
            <input type="email" name="email" class="form-control mb-3"
                minlength="4" maxlength="40"
                value="<?= htmlspecialchars($doctor['email']) ?>" required>

            <label>Phone</label>
            <input type="text" name="phone" class="form-control mb-3"
                minlength="11" maxlength="11"
                pattern="[0-9]{11}"
                oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                value="<?= htmlspecialchars($doctor['phone']) ?>" required>

            <div class="text-center">
                <button type="submit" name="update_profile" class="btn btn-success rounded-pill px-4">
                    Save Changes
                </button>
            </div>
        </form>


        <!-- CHANGE PASSWORD -->
        <form method="POST" id="passwordForm" class="profile-card d-none">

            <button type="button" class="back-btn-modern" onclick="showProfile()">
                <i class="bi bi-arrow-left"></i> Back
            </button>

            <h4 class="text-center mb-3">Change Password</h4>

            <label>Current Password</label>
            <div class="position-relative">
                <input type="password" name="current_password" class="form-control mb-3" minlength="8" required>
                <span class="toggle-eye" onclick="togglePass(this)"><i class="bi bi-eye-slash"></i></span>
            </div>

            <label>New Password</label>
            <div class="position-relative">
                <input type="password" name="new_password" class="form-control mb-3" minlength="8" required>
                <span class="toggle-eye" onclick="togglePass(this)"><i class="bi bi-eye-slash"></i></span>
            </div>

            <label>Confirm Password</label>
            <div class="position-relative">
                <input type="password" name="confirm_password" class="form-control mb-3" minlength="8" required>
                <span class="toggle-eye" onclick="togglePass(this)"><i class="bi bi-eye-slash"></i></span>
            </div>

            <div class="text-center">
                <button type="submit" name="change_password" class="btn btn-primary rounded-pill px-4">
                    Save Password
                </button>
            </div>
        </form>

    </div>
</div>
</div>


<script>
let profileCard = document.getElementById("profileCard");
let editProfileForm = document.getElementById("editProfileForm");
let passwordForm = document.getElementById("passwordForm");

function showProfile(){
    profileCard.classList.remove("d-none");
    editProfileForm.classList.add("d-none");
    passwordForm.classList.add("d-none");
}

document.getElementById("editProfileBtn").onclick = () => {
    profileCard.classList.add("d-none");
    editProfileForm.classList.remove("d-none");
};
document.getElementById("changePassBtn").onclick = () => {
    profileCard.classList.add("d-none");
    passwordForm.classList.remove("d-none");
};

function togglePass(el) {
    let inp = el.previousElementSibling;
    if (inp.type === "password") {
        inp.type = "text";
        el.innerHTML = '<i class="bi bi-eye"></i>';
    } else {
        inp.type = "password";
        el.innerHTML = '<i class="bi bi-eye-slash"></i>';
    }
}

setTimeout(() => {
    document.querySelectorAll('.alert').forEach(a => {
        a.style.opacity = "0";
        setTimeout(() => a.remove(), 500);
    });
}, 5000);

document.addEventListener("click", function(e) {
    if (e.target.classList.contains("alert-close")) {
        e.target.parentElement.style.opacity = "0";
        setTimeout(() => e.target.parentElement.remove(), 500);
    }
});
</script>

<?php include("doctor_footer.php"); ?>
