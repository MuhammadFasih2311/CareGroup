<?php
include('connect.php');

$diseaseName = isset($_GET['disease']) ? trim($_GET['disease']) : '';

if ($diseaseName === '') {
  echo "<p class='text-center text-muted' data-aos='fade-up'>No disease specified.</p>";
  exit;
}
$stmt = $conn->prepare("SELECT name, category FROM diseases WHERE LOWER(name)=LOWER(?) LIMIT 1");
$stmt->bind_param("s", $diseaseName);
$stmt->execute();
$disease = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$disease) {
  echo "<p class='text-center text-muted' data-aos='fade-up'>Disease not found.</p>";
  exit;
}

$diseaseNameLower = strtolower($disease['name']);
$categoryLower = strtolower($disease['category']);

$likeDisease = "%$diseaseNameLower%";
$likeCategory = "%$categoryLower%";

$sql = "
  SELECT DISTINCT * FROM doctors
  WHERE
    LOWER(specialty) LIKE ? OR
    LOWER(diseases) LIKE ? OR
    LOWER(description) LIKE ? OR
    LOWER(specialty) LIKE REPLACE(?, 'ology', '') OR
    LOWER(specialty) LIKE REPLACE(?, 'ist', '') OR
    LOWER(specialty) LIKE REPLACE(?, 'ic', '')
  ORDER BY name ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $likeCategory, $likeDisease, $likeDisease, $likeCategory, $likeCategory, $likeCategory);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $index = 0;
  while ($doc = $result->fetch_assoc()) {
    $docId = intval($doc['id']);
    $docName = htmlspecialchars($doc['name']);
    $specialty = htmlspecialchars($doc['specialty']);
    $hospital = htmlspecialchars($doc['hospital']);
    $desc = htmlspecialchars(substr($doc['description'], 0, 90));
    $image = !empty($doc['image']) ? htmlspecialchars($doc['image']) : 'assets/images/default-doctor.jpg';

    $delay = ($index % 3) * 100;

    echo '
    <div class="col-md-6 col-lg-4 col-xl-4" data-aos="zoom-in" data-aos-delay="'.$delay.'">
      <a href="doctor-detail.php?id='.$docId.'" class="text-decoration-none text-dark">
        <div class="doctor-card text-center p-3 bg-white rounded-4 shadow-sm h-100 clickable-card">
          <img src="'.$image.'" loading="lazy" class="doctor-img rounded-circle mb-3" alt="'.$docName.'" />
          <h5 class="fw-bold text-primary">'.$docName.'</h5>
          <p class="text-muted mb-1">'.$specialty.'</p>
          <small class="text-secondary d-block">'.$hospital.'</small>
          <hr>
          <small class="text-secondary d-block">'.$desc.'...</small>
        </div>
      </a>
    </div>
    ';
    $index++;
  }
} else {
  echo "<p class='text-center text-muted' data-aos='fade-up'>No doctors found for this disease.</p>";
}

$stmt->close();
$conn->close();
?>
