<?php include 'header.php'; ?>

<style>
body {
  background-color: #f8fafc;
  font-family: 'Poppins', sans-serif;
}

.success-container {
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 40px 20px;
}

.success-box {
  background: #fff;
  border-radius: 20px;
  padding: 50px;
  text-align: center;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
  max-width: 600px;
  width: 100%;
  transition: all 0.3s ease;
}

.success-box:hover {
  transform: translateY(-4px);
  box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
}

.success-icon {
  font-size: 4rem;
  color: #22c55e;
  margin-bottom: 20px;
  animation: popIn 0.8s ease;
}

@keyframes popIn {
  0% { transform: scale(0.9); opacity: 0; }
  100% { transform: scale(1); opacity: 1; }
}

.success-box h2 {
  font-weight: 700;
  color: #1e293b;
}

.success-box p {
  color: #64748b;
}

.btn-glass {
  background: #2563eb;
  border: none;
  padding: 12px 25px;
  border-radius: 30px;
  color: #fff;
  font-weight: 500;
  margin: 10px 8px;
  transition: 0.3s;
}

.btn-glass:hover {
  background: #1e40af;
  color: #fff;
  transform: scale(1.05);
}
</style>

<section class="success-container">
  <div class="success-box" data-aos="zoom-out">
    <div class="success-icon"><i class="fa-solid fa-circle-check"></i></div>
    <h2>Appointment Booked Successfully!</h2>
    <p class="lead mb-4">
      Thank you for choosing <strong class="text-primary">MedicoCare</strong>.<br>
      A confirmation has been sent to your registered email.
    </p>
    <div>
      <a href="my_appointments.php" class="btn btn-glass">📅 View My Appointments</a>
      <a href="doctors.php" class="btn btn-glass">👨‍⚕️ Explore Doctors</a>
      <a href="index.php" class="btn btn-glass">🏠 Home</a>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
