<?php
include 'connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
  echo "<script>
    alert('⚠️ Please log in before booking an appointment.');
    window.location.href = 'login.php?redirect=' + encodeURIComponent(window.location.pathname);
  </script>";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = intval($_SESSION['user_id']);
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $phone = trim($_POST['phone']);
  $hospital = trim($_POST['hospital']);
 $doctor = '';
if (!empty($_POST['doctor_id'])) {
  $docId = intval($_POST['doctor_id']);
  $res = $conn->query("SELECT name FROM doctors WHERE id = $docId LIMIT 1");
  if ($res && $res->num_rows > 0) {
    $doctor = $res->fetch_assoc()['name'];
  }
}
  $specialty = trim($_POST['specialty']);
  $doctor_id = intval($_POST['doctor_id']);
  $date = $_POST['date'];
  $time = $_POST['time'];
  $message = trim($_POST['message']);

  $checkQuery = "
    SELECT COUNT(*) AS total
    FROM appointments
    WHERE (
      (user_id = ? AND user_id != 0)
      OR email = ?
      OR phone = ?
    )
    AND created_at >= (NOW() - INTERVAL 1 HOUR)
  ";
  $stmt = $conn->prepare($checkQuery);
  $stmt->bind_param("iss", $user_id, $email, $phone);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();

  if ($row['total'] >= 3) {
    echo "<script>
      alert('⚠️ You can only book up to 3 appointments per hour.');
      window.history.back();
    </script>";
    exit;
  }
$insertQuery = "INSERT INTO appointments 
  (user_id, name, email, phone, hospital, specialty, doctor, doctor_id, date, time, message)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
  $stmt = $conn->prepare($insertQuery);
$stmt->bind_param("issssssssss", $user_id, $name, $email, $phone, $hospital, $specialty, $doctor, $doctor_id, $date, $time, $message);

 if ($stmt->execute()) {
  header("Location: appointmentsuccess.php");
  exit();
} else {
  echo "<script>alert('❌ Error while booking appointment. Please try again.'); window.history.back();</script>";
}
}
?>
