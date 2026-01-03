<?php
include("../connect.php");
include("admin_header.php");
include("admin_sidebar.php");

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $specialty = mysqli_real_escape_string($conn, $_POST['specialty']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $hospital = mysqli_real_escape_string($conn, $_POST['hospital']);
    $disease = mysqli_real_escape_string($conn, $_POST['disease']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $img = "";
    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $newName = "images/doctors/" . uniqid() . "." . $ext;

        if (move_uploaded_file($_FILES['image']['tmp_name'], "../" . $newName)) {
            $img = $newName;
        }
    }

$checkUser = mysqli_query($conn, "SELECT id FROM users WHERE email='$email' LIMIT 1");

$checkDoctor = mysqli_query($conn, "SELECT id FROM doctors WHERE email='$email' LIMIT 1");

if (mysqli_num_rows($checkUser) > 0) {
    $error = "This email already exists in USERS table!";
} elseif (mysqli_num_rows($checkDoctor) > 0) {
    $error = "This email already exists in DOCTORS table!";
} else {

    $q = mysqli_query($conn, "
        INSERT INTO doctors(name, specialty,email, password, hospital, diseases, description, image, created_at)
        VALUES('$name','$specialty','$email','$password','$hospital','$disease','$description','$img', NOW())
    ");

    if ($q) {
        $success = "Doctor added successfully!";
    } else {
        $error = "Something went wrong!";
    }
}
}
?>

<div class="dashboard-wrapper">
<div class="container">
    <div class="text-center">
    <h2 class="section-title" data-aos="zoom-in">Add <span>Doctor</span></h2>
    </div>
    <?php if($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="shadow p-4 mt-3" style="border-radius:10px;"  data-aos="zoom-in" data-aos-delay="100">

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Name</label>
                <input required type="text" name="name" class="form-control" maxlength="35" minlength="3">
            </div>

            <div class="col-md-6">
                <label>Specialty</label>
                <input required type="text" name="specialty" class="form-control" maxlength="50" minlength="2">
            </div>
        </div>

        <div class="row mb-3">
    <div class="col-md-6">
        <label>Email</label>
        <input required type="email" name="email" class="form-control" maxlength="40" minlength="4">
    </div>

    <div class="col-md-6">
        <label>Password</label>
        <input required minlength="6" type="password" name="password" class="form-control" maxlength="40" minlength="8">
    </div>
</div>

        <div class="row mb-3">
    <div class="col-md-6">
        <label>Hospital</label>
        <select required name="hospital" class="form-control">
            <option value="">Select Hospital</option>

            <?php
            $hospQ = mysqli_query($conn, "SELECT name FROM hospitals ORDER BY name ASC");
            while ($h = mysqli_fetch_assoc($hospQ)) {
                echo "<option value='".htmlspecialchars($h['name'])."'>".htmlspecialchars($h['name'])."</option>";
            }
            ?>
        </select>
    </div>

    <div class="col-md-6">
        <label>Disease</label>
        <input required type="text" name="disease" class="form-control" maxlength="50" minlength="2">
    </div>
</div>
  
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" rows="4" class="form-control" maxlength="250" minlength="3"></textarea>
        </div>

        <div class="mb-3">
            <label>Doctor Image</label>
            <input type="file" name="image" class="form-control">
        </div>

        <button class="btn btn-primary">Add Doctor</button>
        <a href="doctors.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</div>

<?php include("admin_footer.php"); ?>
