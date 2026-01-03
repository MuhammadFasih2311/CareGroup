<?php
include 'connect.php';
$hospital = isset($_GET['hospital']) ? $conn->real_escape_string($_GET['hospital']) : '';
$data = [];

if ($hospital != '') {
  $query = "SELECT DISTINCT specialty FROM doctors WHERE hospital = '$hospital' ORDER BY specialty ASC";
  $result = $conn->query($query);
  while ($row = $result->fetch_assoc()) {
    $data[] = $row['specialty'];
  }
}
echo json_encode($data);
$conn->close();
?>
