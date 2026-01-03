<?php
include('connect.php');

$search = isset($_GET['search']) ? trim($conn->real_escape_string($_GET['search'])) : '';
$location = isset($_GET['location']) ? trim($conn->real_escape_string($_GET['location'])) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 9;
$offset = ($page - 1) * $limit;

$query = "SELECT * FROM hospitals WHERE 1";
$countQuery = "SELECT COUNT(*) AS total FROM hospitals WHERE 1";

if ($search !== '') {
  $query .= " AND (name LIKE '%$search%' OR city LIKE '%$search%' OR address LIKE '%$search%')";
  $countQuery .= " AND (name LIKE '%$search%' OR city LIKE '%$search%' OR address LIKE '%$search%')";
}
if ($location !== '') {
  $query .= " AND name LIKE '%$location%'";
  $countQuery .= " AND name LIKE '%$location%'";
}

$query .= " ORDER BY name ASC LIMIT $limit OFFSET $offset";

$totalResult = $conn->query($countQuery);
$totalRow = $totalResult->fetch_assoc();
$totalHospitals = (int)$totalRow['total'];
$totalPages = ceil($totalHospitals / $limit);

$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
  $index = 0;
  while ($row = $result->fetch_assoc()) {
    $hospitalName = htmlspecialchars($row['name']);
    $address = htmlspecialchars($row['address']);
    $city = htmlspecialchars($row['city']);
    $img = !empty($row['image']) ? htmlspecialchars($row['image']) : 'assets/images/default-hospital.jpg';
    $delay = ($index % 3) * 100;

    echo '
    <div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="'.$delay.'">
      <a href="hospital_detail.php?hospital=' . urlencode($hospitalName) . '" class="text-decoration-none text-dark">
        <div class="doctor-card p-4 text-center shadow-sm rounded-4 bg-white">
          <img src="' . $img . '" alt="' . $hospitalName . '" class="doctor-img rounded-circle mb-3">
          <h5 class="fw-bold">' . $hospitalName . '</h5>
          <p class="text-muted small mb-1"><i class="bi bi-geo-alt"></i> ' . $city . '</p>
          <p class="text-muted small">' . $address . '</p>
        </div>
      </a>
    </div>';
    $index++;
  }

  if ($totalPages > 1) {
    echo '<div class="col-12 mt-4">
      <nav aria-label="Hospital Pagination" data-aos="fade-up">
        <ul class="pagination justify-content-center flex-wrap">';
    
    for ($i = 1; $i <= $totalPages; $i++) {
      $active = ($i == $page) ? 'active' : '';
      echo "<li class='page-item $active'><button class='page-link pagination-btn' data-page='$i'>$i</button></li>";
    }

    echo '</ul></nav></div>';
  }
} else {
  echo "<p class='text-center text-muted py-5'>No hospitals found.</p>";
}

$conn->close();
?>
