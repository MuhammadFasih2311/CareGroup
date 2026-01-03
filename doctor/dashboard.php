<?php
session_start();
include("../connect.php");
include("doctor_header.php");
include("doctor_sidebar.php");

if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php");
    exit;
}

$doctor_id = intval($_SESSION['doctor_id']);

function getCountDoctor($conn, $doctor_id, $status = null) {
    $sql = "SELECT COUNT(*) AS c FROM appointments WHERE doctor_id = ?";
    if ($status !== null) $sql .= " AND status = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($status === null) mysqli_stmt_bind_param($stmt, 'i', $doctor_id);
    else mysqli_stmt_bind_param($stmt, 'is', $doctor_id, $status);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    return intval($row['c'] ?? 0);
}

$perPage   = 5;
$maxPages  = 3;
$maxTotal  = $perPage * $maxPages;

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$sort = $_GET['sort'] ?? 'created_at';
$order = (isset($_GET['order']) && $_GET['order'] === 'asc') ? 'ASC' : 'DESC';

$allowedSort = ['date','time','status','created_at'];
if (!in_array($sort, $allowedSort)) $sort = 'created_at';

$q = trim($_GET['q'] ?? '');
$where = "doctor_id = $doctor_id";
if ($q !== '') {
    $esc = mysqli_real_escape_string($conn, $q);
    $where .= " AND (
            name LIKE '%$esc%' 
            OR hospital LIKE '%$esc%'
            OR specialty LIKE '%$esc%'
        )";
}

$subQ = mysqli_query($conn, "SELECT id FROM appointments WHERE $where ORDER BY created_at DESC LIMIT $maxTotal");
$ids = [];
while($row = mysqli_fetch_assoc($subQ)) $ids[] = intval($row['id']);

$appointments = [];
if (!empty($ids)) {
    $idList = implode(",", $ids);
    $offset = ($page-1)*$perPage;
    $finalQ = "
      SELECT * FROM appointments 
      WHERE id IN ($idList)
      ORDER BY $sort $order
      LIMIT $offset, $perPage
    ";
    $res = mysqli_query($conn, $finalQ);
    while($row = mysqli_fetch_assoc($res)) $appointments[] = $row;
}

function buildUrl($overrides = []) {
    $params = array_merge($_GET, $overrides);
    return "doctor_dashboard.php?" . http_build_query($params);
}

// Cards counts
$totalAppointments = getCountDoctor($conn, $doctor_id);
$completedAppointments = getCountDoctor($conn, $doctor_id, 'completed');
$activeAppointments = getCountDoctor($conn, $doctor_id, 'active');
$canceledAppointments = getCountDoctor($conn, $doctor_id, 'canceled');
?>

<div class="dashboard-wrapper">

    <!-- Title Section -->
    <div class="page-header my-5 text-center">
        <h2 class="section-title" data-aos="fade-up">Doctor <span>Dashboard</span></h2>
        <h5 class="subtitle" data-aos="fade-up" data-aos-delay="100">
            Welcome Dr. <strong class="text-primary"><?= htmlspecialchars($_SESSION['doctor_name']) ?></strong>
        </h5>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 dashboard-cards">

        <div class="col-sm-6 col-lg-3" data-aos="zoom-in">
            <div class="stat-card">
                <i class="bi bi-calendar2-check icon"></i>
                <h3><?= $totalAppointments ?></h3>
                <p>Total Appointments</p>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3" data-aos="zoom-in" data-aos-delay="100">
            <div class="stat-card">
                <i class="bi bi-check2-circle icon"></i>
                <h3><?= $completedAppointments ?></h3>
                <p>Completed</p>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3" data-aos="zoom-in" data-aos-delay="200">
            <div class="stat-card">
                <i class="bi bi-play-circle icon"></i>
                <h3><?= $activeAppointments ?></h3>
                <p>Active</p>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3" data-aos="zoom-in" data-aos-delay="300">
            <div class="stat-card">
                <i class="bi bi-x-circle icon"></i>
                <h3><?= $canceledAppointments ?></h3>
                <p>Canceled</p>
            </div>
        </div>

    </div>

    <div class="row mt-5">

        <div class="col-lg-8" data-aos="flip-left">
            <div class="panel text-center">

                <h4 class="small-section">Recent <span>Appointments</span></h4>

                <form method="get" class="d-flex mb-3 filter-box" style="gap:8px; align-items:center;">
                    <input type="text" class="form-control" name="q" placeholder="Search patient, hospital, specialty..." value="<?= htmlspecialchars($q) ?>" maxlength="50">
                    <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
                    <input type="hidden" name="order" value="<?= htmlspecialchars($order === 'ASC' ? 'asc':'desc') ?>">
                    <button class="btn btn-primary">Search</button>
                    <a href="dashboard.php" class="btn btn-danger">Reset</a>
                </form>

                <div class="table-responsive">
                    <table class="table custom-table messages-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Patient</th>
                                <th>Hospital</th>
                                <th>
                                    <a class="sort-link" href="<?= buildUrl(['sort'=>'date','order'=>$order==='ASC'?'desc':'asc','page'=>1]) ?>">Date
                                        <?php if ($sort==='date'): ?><span class="sort-arrow"><?= $order==='ASC'?'▲':'▼' ?></span><?php endif; ?></a>
                                </th>

                                <th>
                                    <a class="sort-link" href="<?= buildUrl(['sort'=>'time','order'=>$order==='ASC'?'desc':'asc','page'=>1]) ?>">Time
                                        <?php if ($sort==='time'): ?><span class="sort-arrow"><?= $order==='ASC'?'▲':'▼' ?></span><?php endif; ?></a>
                                </th>

                                <th>
                                    <div class="text-center">
                                    <a class="sort-link" href="<?= buildUrl(['sort'=>'status','order'=>$order==='ASC'?'desc':'asc','page'=>1]) ?>">Status
                                        <?php if ($sort==='status'): ?><span class="sort-arrow"><?= $order==='ASC'?'▲':'▼' ?></span><?php endif; ?></a>
                                    </div>
                                </th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (empty($appointments)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No appointments found.</td>
                                </tr>

                            <?php else: $i = ($page-1)*$perPage+1; ?>
                            <?php foreach($appointments as $row): ?>

                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td>
                                        <a href="#" class="profile-link" data-type="patient" data-id="<?= intval($row['user_id']) ?>" data-name="<?= htmlspecialchars($row['name']) ?>"><?= htmlspecialchars($row['name']) ?></a>
                                    </td>

                                    <td><?= htmlspecialchars($row['hospital']) ?></td>
                                    <td><?= htmlspecialchars($row['date']) ?></td>
                                    <td><?= htmlspecialchars($row['time']) ?></td>

                                    <td>
                                    <?php
                                        $st = strtolower($row['status']);
                                        if ($st == 'active')       $badge = 'badge-active';
                                        else if ($st == 'completed') $badge = 'badge-completed';
                                        else if ($st == 'canceled')  $badge = 'badge-canceled';
                                        else                        $badge = 'badge-secondary';
                                    ?>
                                        <span class="status-badge <?= $badge ?>"><?= ucfirst($st) ?></span>
                                    </td>

                                    <td>
                                        <select class="form-select form-select-sm status-change" data-id="<?= $row['id'] ?>">
                                            <option value="active"    <?= $st=='active'?'selected':'' ?>>Active</option>
                                            <option value="completed" <?= $st=='completed'?'selected':'' ?>>Completed</option>
                                            <option value="canceled"  <?= $st=='canceled'?'selected':'' ?>>Canceled</option>
                                        </select>

                                        <a href="view_appointment.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm mt-2">View</a>
                                    </td>
                                </tr>

                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="pagination-container mt-2">
                    <?php
                    $totalPages = ceil(min(count($ids), $maxTotal) / $perPage);
                    for ($p=1; $p<=$totalPages; $p++):
                    ?>
                        <a href="<?= buildUrl(['page'=>$p]) ?>" class="pagination-btn <?= ($p==$page?'active':'') ?>"><?= $p ?></a>
                    <?php endfor; ?>
                </div>

            </div>
        </div>

        <!-- QUICK ACTIONS -->
        <div class="col-lg-4" data-aos="flip-left"> 
            <div class="panel text-center">
                <h4 class="small-section">Quick <span>Actions</span></h4>

                <div class="actions-list mt-3">
                    <a href="doctor_profile.php" class="action-btn"><i class="bi bi-person-circle"></i> My Profile</a>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="profileModalBody"></div>
    </div>
  </div>
</div>

<div class="toast-box"></div>

<script>
document.addEventListener("DOMContentLoaded", function() {

    document.querySelectorAll(".status-change").forEach(sel => {

        sel.addEventListener("change", function () {

            let id = this.dataset.id;
            let status = this.value;

            fetch("update_status.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "id=" + encodeURIComponent(id) + "&status=" + encodeURIComponent(status)
            })
            .then(r => r.text())
            .then(res => {

                showToast("Status updated successfully!");

                const row = this.closest("tr");
                const badge = row.querySelector(".status-badge");

                badge.classList.remove("badge-active","badge-completed","badge-canceled","badge-secondary");

                if (status === "active") badge.classList.add("badge-active");
                if (status === "completed") badge.classList.add("badge-completed");
                if (status === "canceled") badge.classList.add("badge-canceled");

                badge.textContent = status.charAt(0).toUpperCase() + status.slice(1);

            })
            .catch(()=>{ showToast('Update failed.'); });
        });
    });

    const modalEl = document.getElementById('profileModal');
    const modalBody = document.getElementById('profileModalBody');

    document.querySelectorAll('.profile-link').forEach(link=>{
      link.addEventListener('click', function(e){
        e.preventDefault();
        const id = this.dataset.id || '';
        const type = this.dataset.type || '';
        const name = this.dataset.name || '';
        if (!id) {
          modalBody.innerHTML = '<p class="text-muted">No profile available.</p>';
          new bootstrap.Modal(modalEl).show();
          return;
        }
        modalBody.innerHTML = '<div class="text-center py-4">Loading...</div>';
        new bootstrap.Modal(modalEl).show();

        fetch('get_profile.php?type='+encodeURIComponent(type)+'&id='+encodeURIComponent(id))
          .then(r=>r.text())
          .then(html=>{ modalBody.innerHTML = html; })
          .catch(err=>{ modalBody.innerHTML = '<p class="text-danger">Failed to load profile.</p>'; });
      });
    });

});

function showToast(msg) {
    let box = document.querySelector(".toast-box");

    let t = document.createElement("div");
    t.className = "alert alert-success shadow";
    t.style.marginTop = "10px";
    t.innerHTML = msg;

    box.appendChild(t);

    setTimeout(() => { t.remove(); }, 2500);
}
</script>

<?php include("doctor_footer.php"); ?>
