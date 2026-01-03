<?php
session_start();
include("../connect.php");

if (!isset($_SESSION['doctor_id'])) {
    header("Location: ../login.php");
    exit();
}
?>

<?php include("doctor_header.php"); ?>
<?php include("doctor_sidebar.php"); ?>

<div class="dashboard-wrapper">
<div class="container-fluid">

    <div class="page-header text-center">
        <h2 class="section-title" data-aos="zoom-in">My <span>Appointments</span></h2>
    </div>

<?php
$perPage = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

$sort  = $_GET['sort']  ?? 'created_at';
$order = (($_GET['order'] ?? 'desc') === 'asc') ? 'ASC' : 'DESC';

$allowedSort = ['date','time','status','hospital','created_at'];
if (!in_array($sort, $allowedSort)) $sort = 'created_at';

$q         = trim($_GET['q'] ?? '');
$status    = trim($_GET['status'] ?? '');
$date_from = trim($_GET['date_from'] ?? '');
$date_to   = trim($_GET['date_to'] ?? '');

$where = "doctor_id = $doctor_id"; 
if ($q !== '') {
    $esc = mysqli_real_escape_string($conn, $q);
    $where .= " AND (name LIKE '%$esc%' OR hospital LIKE '%$esc%')";
}

if ($status !== '') {
    $s = mysqli_real_escape_string($conn, $status);
    $where .= " AND status = '$s'";
}

if ($date_from !== '') {
    $df = mysqli_real_escape_string($conn, $date_from);
    $where .= " AND date >= '$df'";
}

if ($date_to !== '') {
    $dt = mysqli_real_escape_string($conn, $date_to);
    $where .= " AND date <= '$dt'";
}

$totalRes = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM appointments WHERE $where");
$totalRows = mysqli_fetch_assoc($totalRes)['cnt'];
$totalPages = max(1, ceil($totalRows / $perPage));
$offset = ($page - 1) * $perPage;

function buildUrl($overrides = []) {
    return "appointments.php?" . http_build_query(array_merge($_GET, $overrides));
}

$query = "
SELECT * FROM appointments
WHERE $where
ORDER BY $sort $order
LIMIT $offset, $perPage
";

$res = mysqli_query($conn, $query);
$appointments = [];
while ($row = mysqli_fetch_assoc($res)) {
    $appointments[] = $row;
}

function sortCol($field, $label, $sort, $order) {
    $newOrder = ($sort == $field && $order == 'ASC') ? 'desc' : 'asc';
    $arrow = ($sort == $field) ? ($order == 'ASC' ? ' ↑' : ' ↓') : '';
    return "<a href='".buildUrl(['sort'=>$field,'order'=>$newOrder])."' class='sort-link'>{$label}{$arrow}</a>";
}
?>

<div class="filter-box mb-3" data-aos="zoom-in" data-aos-delay="100">
    <form method="get" class="filter-form" style="padding:18px;">

        <div style="display:flex; gap:14px; align-items:center; margin-bottom:14px;">
            <input type="text" name="q" class="form-control"
                   style="flex:1; height:46px; border-radius:10px; padding:0 12px;"
                   placeholder="Search patient / hospital..."
                   value="<?= htmlspecialchars($q) ?>" maxlength="50">
        </div>

        <div style="display:flex; gap:35px; margin-bottom:12px;">

            <div style="display:flex; flex-direction:column;">
                <label class="filter-label" style="margin-bottom:6px;">From</label>
                <input type="date" name="date_from" class="form-control"
                       style="width:300px; height:46px; border-radius:8px;"
                       value="<?= htmlspecialchars($date_from) ?>">
            </div>

            <div style="display:flex; flex-direction:column;">
                <label class="filter-label" style="margin-bottom:6px;">To</label>
                <input type="date" name="date_to" class="form-control"
                       style="width:300px; height:46px; border-radius:8px;"
                       value="<?= htmlspecialchars($date_to) ?>">
            </div>

            <div style="display:flex; flex-direction:column; margin-left:auto;">
                <label class="filter-label" style="margin-bottom:6px;">Status</label>
                <select name="status" class="form-select"
                        style="width:250px; height:46px; border-radius:8px;">
                    <option value="">All Status</option>
                    <option value="active" <?= $status=='active'?'selected':'' ?>>Active</option>
                    <option value="completed" <?= $status=='completed'?'selected':'' ?>>Completed</option>
                    <option value="canceled" <?= $status=='canceled'?'selected':'' ?>>Canceled</option>
                </select>
            </div>

        </div>

        <div style="display:flex; justify-content:center; gap:18px; margin-top:6px;">
            <button type="submit" class="btn btn-primary" style="width:160px; height:44px; border-radius:8px;">
                Apply
            </button>

            <a href="appointments.php" class="btn btn-outline-danger"
               style="width:160px; height:44px; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                Reset
            </a>
        </div>

    </form>
</div>

<div class="panel text-center" data-aos="fade-up">
    <h4 class="small-section">Your <span>Appointments</span></h4>

    <div class="table-responsive mt-3">
        <table class="table custom-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?= sortCol('name','Patient',$sort,$order) ?></th>
                    <th><?= sortCol('hospital','Hospital',$sort,$order) ?></th>
                    <th><?= sortCol('date','Date',$sort,$order) ?></th>
                    <th><?= sortCol('time','Time',$sort,$order) ?></th>
                    <th><?= sortCol('status','Status',$sort,$order) ?></th>
                    <th>View</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($appointments)): ?>
                    <tr><td colspan="7" class="text-center text-muted">No appointments found</td></tr>
                <?php endif; ?>

                <?php $i=$offset+1; foreach ($appointments as $ap): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($ap['name']) ?></td>
                    <td><?= htmlspecialchars($ap['hospital']) ?></td>
                    <td><?= $ap['date'] ?></td>
                    <td><?= $ap['time'] ?></td>

                    <td>
                    <select class="status-dropdown form-select"
                            data-id="<?= $ap['id'] ?>"
                            style="width:130px; border-radius:8px; padding:4px 8px;">
                        <option value="active"    <?= $ap['status']=='active'?'selected':'' ?>>Active</option>
                        <option value="completed" <?= $ap['status']=='completed'?'selected':'' ?>>Completed</option>
                        <option value="canceled"  <?= $ap['status']=='canceled'?'selected':'' ?>>Canceled</option>
                    </select>
                </td>

                    <td>
                        <a href="view_appointment.php?id=<?= $ap['id'] ?>" class="btn btn-sm btn-info">View</a>
                    </td>
                </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </div>

    <div class="pagination-container mt-3 text-center">
        <?php for($p=1;$p<=$totalPages;$p++): ?>
            <a href="<?= buildUrl(['page'=>$p]) ?>"
               class="pagination-btn <?= $p==$page?'active':'' ?>">
               <?= $p ?>
            </a>
        <?php endfor; ?>
    </div>

</div>

</div>
</div>
<div id="statusToast"
     class="toast align-items-center text-white border-0"
     style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;"
     role="alert"
     data-bs-autohide="true"
     data-bs-delay="5000">

    <div class="d-flex">
        <div class="toast-body" id="toastMessage">Status updated</div>

        <button type="button"
                class="btn-close btn-close-white me-2 m-auto"
                data-bs-dismiss="toast"
                aria-label="Close"></button>
    </div>
</div>

<script>
document.querySelectorAll('.status-dropdown').forEach(select => {
    select.addEventListener('change', function() {

        const id = this.dataset.id;
        const newStatus = this.value;

        fetch("update_status.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "id=" + id + "&status=" + newStatus
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {

                let toast = document.getElementById("statusToast");
                let msg = document.getElementById("toastMessage");

                if (newStatus === "active") {
                    toast.style.background = "#0cbf6b";
                    msg.innerHTML = "Appointment Activated!";
                }
                else if (newStatus === "completed") {
                    toast.style.background = "#006aff";
                    msg.innerHTML = "Appointment Completed!";
                }
                else if (newStatus === "canceled") {
                    toast.style.background = "#ff3b3b";
                    msg.innerHTML = "Appointment Canceled!";
                }

                let bsToast = new bootstrap.Toast(toast, { delay: 5000 });
                bsToast.show();

            } else {
                alert("Failed to update status.");
            }
        });

    });
});
</script>

<?php include("doctor_footer.php"); ?>
