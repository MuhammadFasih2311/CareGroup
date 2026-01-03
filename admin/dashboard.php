<?php
include("../connect.php");
include("admin_header.php");
include("admin_sidebar.php");

function getCount($con,$table){
  $res = mysqli_query($con, "SELECT COUNT(*) AS cnt FROM `$table`");
  $row = mysqli_fetch_assoc($res);
  return $row['cnt'] ?? 0;
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
$where = "1";
if ($q !== '') {
    $esc = mysqli_real_escape_string($conn, $q);
    $where = "
        (
            name LIKE '%$esc%' 
            OR doctor LIKE '%$esc%' 
            OR hospital LIKE '%$esc%' 
            OR specialty LIKE '%$esc%'
        )
    ";
}
$subQ = mysqli_query($conn, "SELECT id FROM appointments WHERE $where ORDER BY created_at DESC LIMIT $maxTotal");
$ids = [];
while($row = mysqli_fetch_assoc($subQ)) $ids[] = intval($row['id']);

$appointments = [];
if (!empty($ids)) {
    $idList = implode(",", $ids);
    $finalQ = "
      SELECT * FROM appointments 
      WHERE id IN ($idList)
      ORDER BY $sort $order
      LIMIT ".(($page-1)*$perPage).", $perPage
    ";
    $res = mysqli_query($conn, $finalQ);
    while($row = mysqli_fetch_assoc($res)) $appointments[] = $row;
}

function buildUrl($overrides = []) {
    $params = array_merge($_GET, $overrides);
    return "dashboard.php?" . http_build_query($params);
}
?>
<style>
.badge-success  { background:#0cbf6b !important; }
.badge-primary  { background:#007bff !important; }
.badge-danger   { background:#ff3b3b !important; }
.status-badge {
    padding:6px 10px;
    color:#fff;
    border-radius:6px;
    font-size:13px;
}
.toast-box {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 99999;
}
</style>

<div class="dashboard-wrapper">

    <!-- Title Section -->
    <div class="page-header my-5 text-center">
        <h2 class="section-title" data-aos="fade-up">Dashboard <span>Overview</span></h2>
        <h5 class="subtitle" data-aos="fade-up" data-aos-delay="100">
            Welcome back, <strong class="text-primary"><?= htmlspecialchars($_SESSION['admin_name']) ?></strong>
        </h5>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 dashboard-cards">

        <div class="col-sm-6 col-lg-3" data-aos="zoom-in">
            <div class="stat-card">
                <i class="bi bi-people-fill icon"></i>
                <h3><?= getCount($conn,'users') ?></h3>
                <p>Users</p>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3" data-aos="zoom-in" data-aos-delay="100">
            <div class="stat-card">
                <i class="bi bi-person-badge icon"></i>
                <h3><?= getCount($conn,'doctors') ?></h3>
                <p>Doctors</p>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3" data-aos="zoom-in" data-aos-delay="200">
            <div class="stat-card">
                <i class="bi bi-calendar2-check icon"></i>
                <h3><?= getCount($conn,'appointments') ?></h3>
                <p>Appointments</p>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3" data-aos="zoom-in" data-aos-delay="300">
            <div class="stat-card">
                <i class="bi bi-hospital icon"></i>
                <h3><?= getCount($conn,'hospitals') ?></h3>
                <p>Hospitals</p>
            </div>
        </div>

    </div>

    <div class="row mt-5">

        <div class="col-lg-8" data-aos="flip-left">
            <div class="panel text-center" >

                <h4 class="small-section">Recent <span>Appointments</span></h4>

                    <form method="get" class="d-flex mb-3">
        <input type="text" class="form-control me-2" name="q" 
            placeholder="Search patient, doctor, hospital..."
            value="<?= htmlspecialchars($q) ?>" maxlength="50">

        <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
        <input type="hidden" name="order" value="<?= htmlspecialchars($order === 'ASC' ? 'asc':'desc') ?>">
        <button class="btn btn-primary me-2">Search</button>
        <a href="dashboard.php" class="btn btn-danger">Reset</a>
      </form>

                <div class="table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Hospital</th>

                                <th>
                                    <a href="<?= buildUrl(['sort'=>'date','order'=>$order==='ASC'?'desc':'asc','page'=>1]) ?>">
                                        Date <?= ($sort==='date') ? ($order==='ASC'?'▲':'▼'):'' ?>
                                    </a>
                                </th>

                                <th>
                                    <a href="<?= buildUrl(['sort'=>'time','order'=>$order==='ASC'?'desc':'asc','page'=>1]) ?>">
                                        Time <?= ($sort==='time') ? ($order==='ASC'?'▲':'▼'):'' ?>
                                    </a>
                                </th>

                                <th>
                                    <div class="text-center">
                                    <a href="<?= buildUrl(['sort'=>'status','order'=>$order==='ASC'?'desc':'asc','page'=>1]) ?>">
                                        Status <?= ($sort==='status') ? ($order==='ASC'?'▲':'▼'):'' ?>
                                    </a>
                                    </div>
                                </th>
                                <th></th>
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
                                  <a href="#" 
                                    class="profile-link"
                                    data-type="patient"
                                    data-id="<?= intval($row['user_id']) ?>"
                                    data-name="<?= htmlspecialchars($row['name']) ?>"
                                  >
                                    <?= htmlspecialchars($row['name']) ?>
                                  </a>
                              </td>

                              <td>
                                  <a href="#" 
                                    class="profile-link"
                                    data-type="doctor"
                                    data-id="<?= intval($row['doctor_id']) ?>"
                                    data-name="<?= htmlspecialchars($row['doctor']) ?>"
                                  >
                                    <?= htmlspecialchars($row['doctor']) ?>
                                  </a>
                              </td>

                                    <td><?= htmlspecialchars($row['hospital']) ?></td>
                                    <td><?= htmlspecialchars($row['date']) ?></td>
                                    <td><?= htmlspecialchars($row['time']) ?></td>

                                    <td>
                                    <?php
                                        $st = strtolower($row['status']);
                                        
                                        if ($st == 'active')       $badge = 'badge-success';
                                        else if ($st == 'completed') $badge = 'badge-primary';
                                        else if ($st == 'canceled')  $badge = 'badge-danger';
                                        else                        $badge = 'badge-secondary';
                                    ?>
                                    
                                            <span class="status-badge <?= $badge ?>"><?= ucfirst($st) ?></span>
                                        </td>

                                        <td>
                                            <select class="form-select form-select-sm status-change" 
                                                    data-id="<?= $row['id'] ?>">
                                                <option value="active"    <?= $st=='active'?'selected':'' ?>>Active</option>
                                                <option value="completed" <?= $st=='completed'?'selected':'' ?>>Completed</option>
                                                <option value="canceled"  <?= $st=='canceled'?'selected':'' ?>>Canceled</option>
                                            </select>
                                        </td>

                                        <td>
                                            <a href="view_appointment.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">
                                                View
                                            </a>
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
                        <a href="<?= buildUrl(['page'=>$p]) ?>"
                           class="pagination-btn <?= ($p==$page?'active':'') ?>">
                           <?= $p ?>
                        </a>
                    <?php endfor; ?>
                </div>

            </div>
        </div>


        <!-- QUICK ACTIONS -->
        <div class="col-lg-4" data-aos="flip-left">
            <div class="panel text-center">
                <h4 class="small-section">Quick <span>Actions</span></h4>

                <div class="actions-list mt-3">
                    <a href="pages/doctors.php" class="action-btn"><i class="bi bi-person-badge"></i> Manage Doctors</a>
                    <a href="pages/appointments.php" class="action-btn"><i class="bi bi-calendar2-check"></i> Manage Appointments</a>
                    <a href="pages/users.php" class="action-btn"><i class="bi bi-people"></i> Manage Users</a>
                </div>
            </div>
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
                body: "id=" + id + "&status=" + status
            })
            .then(r => r.text())
            .then(res => {

                showToast("Status updated successfully!");

                const row = this.closest("tr");
                const badge = row.querySelector(".status-badge");

                badge.classList.remove("badge-success","badge-primary","badge-danger");

                if (status === "active") badge.classList.add("badge-success");
                if (status === "completed") badge.classList.add("badge-primary");
                if (status === "canceled") badge.classList.add("badge-danger");

                badge.textContent = status.charAt(0).toUpperCase() + status.slice(1);

            });
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


document.addEventListener('DOMContentLoaded', function(){
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
        .then(html=>{
          modalBody.innerHTML = html;
        })
        .catch(err=>{
          modalBody.innerHTML = '<p class="text-danger">Failed to load profile.</p>';
        });
    });
  });

  document.querySelectorAll('.pagination a').forEach(a=>{
    a.addEventListener('click', function(){
      document.querySelector('.dashboard-wrapper').classList.remove('visible');
      setTimeout(()=>{ document.querySelector('.dashboard-wrapper').classList.add('visible'); }, 250);
    });
  });
});
</script>


<?php include("admin_footer.php"); ?>


