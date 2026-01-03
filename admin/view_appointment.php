<?php
include("../connect.php");
include("admin_header.php");
include("admin_sidebar.php");

$id = intval($_GET['id']);
$res = mysqli_query($conn, "SELECT * FROM appointments WHERE id=$id");
$ap = mysqli_fetch_assoc($res);

if (!$ap) {
    echo "<h3 class='text-center mt-5 text-danger'>Appointment not found!</h3>";
    exit;
}
?>
<style>
.badge-success { background:#0cbf6b; }
.badge-primary { background:#006aff; }
.badge-danger  { background:#ff3b3b; }
.badge-secondary { background:#6c757d; }
.status-badge {
    color:#fff;
    padding:6px 12px;
    border-radius:6px;
    font-size:14px;
}
</style>

<div class="dashboard-wrapper">
<div class="container-fluid">

    <div class="panel text-center" style="max-width:850px;margin:auto;" data-aos="zoom-in">

        <h3 class="section-title">Appointment <span>Details</span></h3>

        <div class="row mt-4">

            <div class="col-md-6 text-start">
                <p><strong>Patient:</strong> <?= htmlspecialchars($ap['name']) ?></p>
                <p><strong>Doctor:</strong> <?= htmlspecialchars($ap['doctor']) ?></p>
                <p><strong>Hospital:</strong> <?= htmlspecialchars($ap['hospital']) ?></p>
                <p><strong>Specialty:</strong> <?= htmlspecialchars($ap['specialty']) ?></p>
            </div>

            <div class="col-md-6 text-start">
                <p><strong>Date:</strong> <?= $ap['date'] ?></p>
                <p><strong>Time:</strong> <?= $ap['time'] ?></p>

                <?php
                    $s = strtolower($ap['status']);

                    if ($s == 'active') {
                        $cls = 'badge-success';
                    } 
                    else if ($s == 'completed') {
                        $cls = 'badge-primary';
                    } 
                    else if ($s == 'canceled' || $s == 'cancelled') { 
                        $cls = 'badge-danger';
                    } 
                    else {
                        $cls = 'badge-secondary';
                    }
                 ?>
                <p><strong>Status:</strong> 
                    <span class="status-badge <?= $cls ?>"><?= ucfirst($s) ?></span>
                </p>
            </div>

        </div>

        <hr>

        <div class="text-start">
            <h5><strong>Description / Notes:</strong></h5>
            <p><?= nl2br($ap['message'] ?: "No additional notes.") ?></p>
        </div>
        <div class="d-flex justify-content-between mt-3">
    <a href="appointments.php" class="btn btn-secondary">← Back to Appointments</a>

    <button onclick="printAppointment()" class="btn btn-primary">
        🖨 Print
    </button>
        </div>
    </div>

</div>
</div>
<script>
function printAppointment() {

    var panel = document.querySelector(".panel").cloneNode(true);

    var btn = panel.querySelector("button");
    if (btn) btn.remove();

    var backBtn = panel.querySelector("a.btn-secondary");
    if (backBtn) backBtn.remove();

    var win = window.open('', '_blank');

    win.document.write('<html><head>');
    win.document.write('<title>Print Appointment</title>');

    win.document.write(`
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <style>
            body { padding:20px; font-family: Arial; }
            h3 { margin-bottom:20px; }
            .section-title span { color:#ff3b3b; }
            .status-badge {
                padding:6px 12px; 
                border-radius:6px;
                color:#fff;
                font-size:15px;
            }
            .badge-success { background:#0cbf6b; }
            .badge-primary { background:#006aff; }
            .badge-danger  { background:#ff3b3b; }
            .badge-secondary { background:#6c757d; }
        </style>
    `);

    win.document.write('</head><body>');
    win.document.write(panel.innerHTML);
    win.document.write('</body></html>');

    win.document.close();

    setTimeout(() => { win.print(); }, 400);
}
</script>
<?php include("admin_footer.php"); ?>
