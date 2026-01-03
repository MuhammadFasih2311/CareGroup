<?php
include("../connect.php");
include("admin_header.php");
include("admin_sidebar.php");

$id = intval($_GET['id']);
$q = mysqli_query($conn, "SELECT * FROM diseases WHERE id=$id");
$disease = mysqli_fetch_assoc($q);

if (!$disease) {
    echo "<h3 class='text-center text-danger mt-5'>Disease not found!</h3>";
    exit;
}

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $img = $disease['image'];  

    if (!empty($_FILES['image']['name'])) {

        if ($img != "" && file_exists("../" . $img)) {
            unlink("../" . $img);
        }

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $newName = "images/diseases/" . uniqid() . "." . $ext;

        if (move_uploaded_file($_FILES['image']['tmp_name'], "../" . $newName)) {
            $img = $newName; 
        }
    }

    $u = mysqli_query($conn, "
        UPDATE diseases SET 
        name='$name',
        category='$category',
        description='$description',
        image='$img'
        WHERE id=$id
    ");

    if ($u) {
        $success = "Disease updated successfully!";
        $disease = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM diseases WHERE id=$id"));
    } else {
        $error = "Failed to update!";
    }
}
?>

<div class="dashboard-wrapper">
<div class="container">
     <div class="text-center">
    <h2 class="section-title"  data-aos="zoom-in">Edit <span>Disease</span></h2>
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
                <input required type="text" name="name" value="<?= $disease['name'] ?>" class="form-control" maxlength="50" minlength="2">
            </div>

            <div class="col-md-6">
                <label>Category</label>
                <input required type="text" name="category" value="<?= $disease['category'] ?>" class="form-control" maxlength="50" minlength="2">
            </div>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" rows="4" class="form-control"><?= $disease['description'] ?></textarea maxlength="250" minlength="3">
        </div>

        <div class="mb-3">
            <label>Image</label><br>
            <img src="../<?= $disease['image'] ?>" style="width:70px;height:70px;border-radius:50%;object-fit:cover;border:2px solid #eee;">
            <input type="file" name="image" class="form-control mt-2">
        </div>

        <button class="btn btn-primary">Update Disease</button>
        <a href="diseases.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</div>

<?php include("admin_footer.php"); ?>
