<?php
include("../connect.php");
include("admin_header.php");
include("admin_sidebar.php");

$id = intval($_GET['id']);
$q = mysqli_query($conn, "SELECT * FROM hospitals WHERE id=$id");
$hospital = mysqli_fetch_assoc($q);

if (!$hospital) {
    echo "<h3 class='text-center text-danger mt-5'>Hospital not found!</h3>";
    exit;
}

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);

    $img = $hospital['image'];

    if (!empty($_FILES['image']['name'])) {

        if ($img != "" && file_exists("../" . $img)) {
            unlink("../" . $img);
        }

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $newName = "images/hospitals/" . uniqid() . "." . $ext;

        if (move_uploaded_file($_FILES['image']['tmp_name'], "../" . $newName)) {
            $img = $newName;
        }
    }

    $u = mysqli_query($conn, "
        UPDATE hospitals SET 
        name='$name',
        address='$address',
        city='$city',
        image='$img'
        WHERE id=$id
    ");

    if ($u) {
        $success = "Hospital updated successfully!";
        $hospital = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM hospitals WHERE id=$id"));
    } else {
        $error = "Failed to update!";
    }
}
?>

<div class="dashboard-wrapper">
<div class="container">
    <div class="text-center">
        <h2 class="section-title"  data-aos="zoom-in">Edit <span>Hospital</span></h2>
    </div>

    <?php if($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="shadow p-4 mt-3" style="border-radius:10px;"  data-aos="zoom-in" data-aos-delay="100">

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Name</label>
                <input required type="text" name="name" value="<?= $hospital['name'] ?>" class="form-control" maxlength="50" minlength="3">
            </div>

            <div class="col-md-6">
                <label>City</label>
                <input required type="text" name="city" value="<?= $hospital['city'] ?>" class="form-control" maxlength="50" minlength="3">
            </div>
        </div>

        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" rows="3" class="form-control" maxlength="250" minlength="3"><?= $hospital['address'] ?></textarea>
        </div>

        <div class="mb-3">
            <label>Hospital Image</label><br>
            <img src="../<?= $hospital['image'] ?>" style="width:70px;height:70px;border-radius:10px;object-fit:cover;border:2px solid #eee;">
            <input type="file" name="image" class="form-control mt-2">
        </div>

        <button class="btn btn-primary">Update Hospital</button>
        <a href="hospitals.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</div>

<?php include("admin_footer.php"); ?>
