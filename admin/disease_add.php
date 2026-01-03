<?php
include("../connect.php");
include("admin_header.php");
include("admin_sidebar.php");

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $img = "";
    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $newName = "images/diseases/" . uniqid() . "." . $ext;

        if (move_uploaded_file($_FILES['image']['tmp_name'], "../" . $newName)) {
            $img = $newName;
        }
    }

    $q = mysqli_query($conn, "
        INSERT INTO diseases(name, category, description, image, created_at)
        VALUES('$name','$category','$description','$img', NOW())
    ");

    if ($q) {
        $success = "Disease added successfully!";
    } else {
        $error = "Something went wrong!";
    }
}
?>

<div class="dashboard-wrapper">
<div class="container">
    <div class="text-center">
    <h2 class="section-title" data-aos="zoom-in">Add <span>Disease</span></h2>
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
                <label>Disease Name</label>
                <input required type="text" name="name" class="form-control" maxlength="50" minlength="2">
            </div>

            <div class="col-md-6">
                <label>Category</label>
                <input required type="text" name="category" class="form-control" maxlength="50" minlength="2">
            </div>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" rows="4" class="form-control" maxlength="250" minlength="3"></textarea>
        </div>

        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control">
        </div>

        <button class="btn btn-primary">Add Disease</button>
        <a href="diseases.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</div>

<?php include("admin_footer.php"); ?>
