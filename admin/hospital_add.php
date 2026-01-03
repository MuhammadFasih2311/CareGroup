<?php
include("../connect.php");
include("admin_header.php");
include("admin_sidebar.php");

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);

    $img = "";
    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $newName = "images/hospitals/" . uniqid() . "." . $ext;

        if (move_uploaded_file($_FILES['image']['tmp_name'], "../" . $newName)) {
            $img = $newName;
        }
    }

    $q = mysqli_query($conn, "
        INSERT INTO hospitals(name, address, city, image, created_at)
        VALUES('$name','$address','$city','$img', NOW())
    ");

    if ($q) $success = "Hospital added successfully!";
    else $error = "Something went wrong!";
}
?>

<div class="dashboard-wrapper">
<div class="container">
    <div class="text-center">
        <h2 class="section-title"  data-aos="zoom-in">Add <span>Hospital</span></h2>
    </div>

    <?php if($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="shadow p-4 mt-3" style="border-radius:10px;"  data-aos="zoom-in" data-aos-delay="100">

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Name</label>
                <input required type="text" name="name" class="form-control" maxlength="50" minlength="3">
            </div>

            <div class="col-md-6">
                <label>City</label>
                <input required type="text" name="city" class="form-control" maxlength="50" minlength="2">
            </div>
        </div>

        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" rows="3" class="form-control" maxlength="250" minlength="3"></textarea>
        </div>

        <div class="mb-3">
            <label>Hospital Image</label>
            <input type="file" name="image" class="form-control">
        </div>

        <button class="btn btn-primary">Add Hospital</button>
        <a href="hospitals.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</div>

<?php include("admin_footer.php"); ?>
