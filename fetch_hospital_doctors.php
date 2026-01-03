<?php
include('connect.php');

$hospital = isset($_GET['hospital']) ? $conn->real_escape_string($_GET['hospital']) : '';
$specialty = isset($_GET['specialty']) ? $conn->real_escape_string($_GET['specialty']) : '';
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 9;
$offset = ($page - 1) * $limit;

$query = "SELECT * FROM doctors WHERE hospital = '$hospital'";
$countQuery = "SELECT COUNT(*) AS total FROM doctors WHERE hospital = '$hospital'";

if ($specialty != '') {
  $query .= " AND specialty = '$specialty'";
  $countQuery .= " AND specialty = '$specialty'";
}

if ($search != '') {
  $query .= " AND (name LIKE '%$search%' OR specialty LIKE '%$search%' OR description LIKE '%$search%')";
  $countQuery .= " AND (name LIKE '%$search%' OR specialty LIKE '%$search%' OR description LIKE '%$search%')";
}

$query .= " ORDER BY id ASC LIMIT $limit OFFSET $offset";

$totalResult = $conn->query($countQuery);
$totalRow = $totalResult->fetch_assoc();
$totalDoctors = (int)$totalRow['total'];
$totalPages = ceil($totalDoctors / $limit);

$result = $conn->query($query);

if ($result->num_rows > 0) {
  $index = 0;
  while ($row = $result->fetch_assoc()) {
    $name = htmlspecialchars($row['name']);
    $specialty = htmlspecialchars($row['specialty']);
    $hospitalName = htmlspecialchars($row['hospital']);
    $img = !empty($row['image']) ? htmlspecialchars($row['image']) : 'assets/images/default-doctor.jpg';
    $desc = htmlspecialchars(substr($row['description'], 0, 90)) . '...';
    $delay = ($index % 3) * 100;

    echo '
    <div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="'.$delay.'">
      <a href="doctor-detail.php?id='.$row['id'].'" class="text-decoration-none text-dark">
        <div class="doctor-card text-center p-4 bg-white rounded-4 shadow-sm h-100 clickable-card">
          <img src="'.$img.'" class="doctor-img rounded-circle mb-3" alt="'.$name.'">
          <h5 class="fw-bold text-primary">'.$name.'</h5>
          <p class="text-muted mb-1">'.$specialty.'</p>
          <small class="text-secondary d-block">'.$hospitalName.'</small>
          <hr>
          <small class="text-secondary d-block">'.$desc.'</small>
        </div>
      </a>
    </div>';
    $index++;
  }

  echo '<div class="col-12 mt-4">
    <nav aria-label="Doctor Pagination" data-aos="fade-up">
      <ul class="pagination justify-content-center flex-wrap">';
  for ($i = 1; $i <= $totalPages; $i++) {
    $active = ($i == $page) ? 'active' : '';
    echo "<li class='page-item $active'><button class='page-link pagination-btn' data-page='$i'>$i</button></li>";
  }
  echo '</ul></nav></div>';
} else {
  echo "<p class='text-center text-muted py-5'>No doctors found for this hospital.</p>";
}

$conn->close();
?>
