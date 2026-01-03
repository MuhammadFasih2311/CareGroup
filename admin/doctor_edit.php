<?php
include("../connect.php");
include("admin_header.php");
include("admin_sidebar.php");

$id = intval($_GET['id']);
$q = mysqli_query($conn, "SELECT * FROM doctors WHERE id=$id");
$doc = mysqli_fetch_assoc($q);

if (!$doc) {
    echo "<h3 class='text-center text-danger mt-5'>Doctor not found!</h3>";
    exit;
}

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $specialty = mysqli_real_escape_string($conn, $_POST['specialty']);
    $hospital = mysqli_real_escape_string($conn, $_POST['hospital']);
    $disease = mysqli_real_escape_string($conn, $_POST['disease']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $img = $doc['image'];  

if (!empty($_FILES['image']['name'])) {

    if ($img != "" && file_exists("../" . $img)) {
        unlink("../" . $img);
    }

    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $newName = "images/doctors/" . uniqid() . "." . $ext;

    if (move_uploaded_file($_FILES['image']['tmp_name'], "../" . $newName)) {
        $img = $newName; 
    }
}

    $u = mysqli_query($conn, "
        UPDATE doctors SET 
        name='$name',
        specialty='$specialty',
        hospital='$hospital',
        diseases='$disease',
        description='$description',
        image='$img'
        WHERE id=$id
    ");

    if ($u) {
        $success = "Doctor updated successfully!";
        $doc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM doctors WHERE id=$id"));
    } else {
        $error = "Failed to update!";
    }
}
?>

<div class="dashboard-wrapper">
<div class="container">
     <div class="text-center">
    <h2 class="section-title"  data-aos="zoom-in">Edit <span>Doctor</span></h2>
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
                <input required type="text" name="name" value="<?= $doc['name'] ?>" class="form-control" maxlength="40" minlength="3">
            </div>

            <div class="col-md-6">
                <label>Specialty</label>
                <input required type="text" name="specialty" value="<?= $doc['specialty'] ?>" class="form-control" maxlength="50" minlength="2">
            </div>
        </div>

        <div class="row mb-3">
    <div class="col-md-6">
        <label>Hospital</label>
        <select required name="hospital" class="form-control">
            <option value="">Select Hospital</option>

            <?php
            $hospitals = mysqli_query($conn, "SELECT * FROM hospitals ORDER BY name ASC");
            while ($h = mysqli_fetch_assoc($hospitals)) {
                $selected = ($doc['hospital'] == $h['name']) ? "selected" : "";
                echo "<option value='{$h['name']}' $selected>{$h['name']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="col-md-6">
        <label>Disease</label>
        <input required type="text" name="disease" value="<?= $doc['diseases'] ?>" class="form-control" maxlength="50" minlength="2">
    </div>
</div>


        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" rows="4" class="form-control"><?= $doc['description'] ?></textarea maxlength="250" minlength="3">
        </div>

        <div class="mb-3">
            <label>Doctor Image</label><br>
            <img src="../<?= $doc['image'] ?>" style="width:70px;height:70px;border-radius:50%;object-fit:cover;border:2px solid #eee;">
            <input type="file" name="image" class="form-control mt-2">
        </div>

        <button class="btn btn-primary">Update Doctor</button>
        <a href="doctors.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</div>

<?php include("admin_footer.php"); ?>
