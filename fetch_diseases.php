<?php
include('connect.php');

$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 9;
$offset = ($page - 1) * $limit;

$query = "SELECT * FROM diseases WHERE 1";
$countQuery = "SELECT COUNT(*) AS total FROM diseases WHERE 1";

if ($category != '') {
  $query .= " AND category = '$category'";
  $countQuery .= " AND category = '$category'";
}

if ($search != '') {
  $query .= " AND (name LIKE '%$search%' OR description LIKE '%$search%' OR symptoms LIKE '%$search%' OR treatment LIKE '%$search%' OR category LIKE '%$search%')";
  $countQuery .= " AND (name LIKE '%$search%' OR description LIKE '%$search%' OR symptoms LIKE '%$search%' OR treatment LIKE '%$search%' OR category LIKE '%$search%')";
}

$query .= " ORDER BY name ASC LIMIT $limit OFFSET $offset";

$totalResult = $conn->query($countQuery);
$totalRow = $totalResult->fetch_assoc();
$totalDiseases = (int)$totalRow['total'];
$totalPages = ceil($totalDiseases / $limit);

$result = $conn->query($query);

$count = 0;

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $id = intval($row['id']);
    $name = htmlspecialchars($row['name']);
    $image = !empty($row['image']) ? $row['image'] : 'assets/images/default-disease.jpg';
    $short_desc = htmlspecialchars(substr($row['description'], 0, 120));
    $delay = ($count % 3) * 100;

    echo '
    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="'.$delay.'">
      <a href="disease-detail.php?id='.$id.'" class="text-decoration-none text-dark">
        <div class="disease-card text-center p-4 rounded-4 h-100 bg-white shadow-sm">
          <img src="'.$image.'" alt="'.$name.'" class="disease-img rounded-circle mb-3">
          <h5 class="fw-bold">'.$name.'</h5>
          <p class="text-muted small mb-3">'.$short_desc.'...</p>
        </div>
      </a>
    </div>';
    $count++;
  }

  echo '<div class="col-12 mt-4">
          <nav aria-label="Disease Pagination" data-aos="fade-up">
            <ul class="pagination justify-content-center flex-wrap">';
  
  for ($i = 1; $i <= $totalPages; $i++) {
    $active = ($i == $page) ? 'active' : '';
    echo "<li class='page-item $active'><button class='page-link pagination-btn' data-page='$i'>$i</button></li>";
  }

  echo '</ul></nav></div>';
} else {
  echo "<p class='text-center text-muted py-5'>No diseases found.</p>";
}

$conn->close();
?>
