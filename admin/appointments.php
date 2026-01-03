<?php
include("../connect.php");
include("admin_header.php");
include("admin_sidebar.php");

$perPage = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

$sort  = $_GET['sort']  ?? 'created_at';
$order = (($_GET['order'] ?? 'desc') === 'asc') ? 'ASC' : 'DESC';

$allowedSort = ['date', 'time', 'status', 'doctor', 'hospital', 'created_at'];
if (!in_array($sort, $allowedSort)) $sort = 'created_at';

$q         = trim($_GET['q']         ?? '');
$specialty = trim($_GET['specialty'] ?? '');
$status = strtolower(trim($_GET['status'] ?? ''));
$date_from = trim($_GET['date_from'] ?? '');
$date_to   = trim($_GET['date_to']   ?? '');

$specialtyQuery = mysqli_query($conn, "SELECT DISTINCT specialty FROM doctors ORDER BY specialty ASC");
$allSpecialties = [];
while ($sp = mysqli_fetch_assoc($specialtyQuery)) {
    $allSpecialties[] = $sp['specialty'];
}
$where = "1";

if ($q !== '') {
    $esc = mysqli_real_escape_string($conn, $q);
    $where .= " AND (
        name LIKE '%$esc%' 
        OR doctor LIKE '%$esc%' 
        OR hospital LIKE '%$esc%' 
        OR specialty LIKE '%$esc%'
    )";
}

if ($specialty !== '') {
    $sp = mysqli_real_escape_string($conn, $specialty);
    $where .= " AND specialty = '$sp'";
}

if ($status !== '') {
    $st = mysqli_real_escape_string($conn, $status);
    $where .= " AND status = '$st'";
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

$appointments = [];
$query = "
    SELECT *
    FROM appointments
    WHERE $where
    ORDER BY $sort $order
    LIMIT $offset, $perPage
";

$res = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($res)) {
    $appointments[] = $row;
}

function buildUrl($overrides = []) {
    return "appointments.php?" . http_build_query(array_merge($_GET, $overrides));
}
?>
<div class="dashboard-wrapper">

<div class="page-header text-center">
    <h2 class="section-title" data-aos="zoom-in">Appointments <span>Management</span></h2>
</div>

<div class="container-fluid">

<div class="filter-box mb-3" data-aos="zoom-in" data-aos-delay="100">
    <form method="get" class="filter-form" style="padding:18px;">

            <div style="display:flex; gap:14px; align-items:center; margin-bottom:14px;">
                <input type="text"
                    name="q"
                    class="form-control"
                    style="flex:1; height:46px; border-radius:10px; padding:0 12px;"
                    placeholder="Search patient / doctor / hospital / specialty..."
                    value="<?= htmlspecialchars($q) ?>" maxlength="50">

                <select name="specialty"
                        class="form-select"
                        style="width:220px; height:46px; border-radius:10px;">
                    <option value="">All Specialties</option>
                    <?php foreach ($allSpecialties as $sp): ?>
                        <option value="<?= htmlspecialchars($sp) ?>" <?= ($specialty === $sp) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($sp) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

        <div style="display:flex; gap:35px; margin-bottom:12px;">

        <div style="display:flex; flex-direction:column;">
            <label class="filter-label" style="margin-bottom:6px;">From</label>
            <input type="date"
                name="date_from"
                class="form-control"
                style="width:300px; height:46px; border-radius:8px;"
                value="<?= htmlspecialchars($date_from) ?>">
        </div>

        <div style="display:flex; flex-direction:column;">
            <label class="filter-label" style="margin-bottom:6px;">To</label>
            <input type="date"
                name="date_to"
                class="form-control"
                style="width:300px; height:46px; border-radius:8px;"
                value="<?= htmlspecialchars($date_to) ?>">
        </div>

        <div style="display:flex; flex-direction:column; margin-left:auto;">
            <label class="filter-label" style="margin-bottom:6px;">Status</label>
            <select name="status"
                    class="form-select"
                    style="width:250px; height:46px; border-radius:8px;">
                <option value="">All Status</option>
                <option value="active"    <?= ($status === 'active')    ? 'selected' : '' ?>>Active</option>
                <option value="completed" <?= ($status === 'completed') ? 'selected' : '' ?>>Completed</option>
                <option value="canceled"  <?= ($status === 'canceled')  ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>

    </div>

        <div style="display:flex; justify-content:center; gap:18px; margin-top:6px;">
            <button type="submit" class="btn btn-primary" style="width:160px; height:44px; border-radius:8px;">
                Apply
            </button>

            <a href="appointments.php" class="btn btn-outline-danger" style="width:160px; height:44px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center;">
                Reset
            </a>
        </div>

    </form>
</div>
<?php
function sortCol($field, $label, $sort, $order) {
    $newOrder = ($sort == $field && $order == 'ASC') ? 'desc' : 'asc';
    $url = buildUrl(['sort' => $field, 'order' => $newOrder]);

    $arrow = '';
    if ($sort == $field) {
        $arrow = $order == 'ASC' ? ' ↑' : ' ↓';
    }

    return "<a href='$url' class='sort-link'>$label<span class='sort-arrow'>$arrow</span></a>";
}
?>
    <div class="panel text-center" data-aos="fade-up">
        <h4 class="small-section">All Booked <span>Appointments</span></h4>

        <div class="table-responsive mt-3">
            <table class="table custom-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?= sortCol('name', 'Patient', $sort, $order) ?></th>
                        <th><?= sortCol('doctor', 'Doctor', $sort, $order) ?></th>
                        <th><?= sortCol('hospital', 'Hospital', $sort, $order) ?></th>
                        <th><?= sortCol('date', 'Date', $sort, $order) ?></th>
                        <th><?= sortCol('time', 'Time', $sort, $order) ?></th>
                        <th><?= sortCol('status', 'Status', $sort, $order) ?></th>
                        <th>Action</th>
                    </tr>
                    </thead>

                <tbody>
                    <?php if (empty($appointments)): ?>
                        <tr><td colspan="7" class="text-center text-muted">No results found.</td></tr>
                    <?php endif; ?>

                    <?php $i = $offset + 1; ?>
                    <?php foreach ($appointments as $ap): ?>
                    <tr>
                        <td><?= $i++ ?></td>

                        <td>
                            <a href="#" 
                               class="profile-link"
                               data-id="<?= $ap['user_id'] ?>"
                               data-type="patient">
                                <?= htmlspecialchars($ap['name']) ?>
                            </a>
                        </td>

                        <td>
                            <a href="#" 
                               class="profile-link"
                               data-id="<?= $ap['doctor_id'] ?>"
                               data-type="doctor">
                                <?= htmlspecialchars($ap['doctor']) ?>
                            </a>
                        </td>

                        <td><?= htmlspecialchars($ap['hospital']) ?></td>
                        <td><?= htmlspecialchars($ap['date']) ?></td>
                        <td><?= htmlspecialchars($ap['time']) ?></td>

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
                            <a href="view_appointment.php?id=<?= $ap['id'] ?>" class="btn btn-sm btn-info">
                                View
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>

        <div class="pagination-container mt-3 text-center">
            <?php for($p=1; $p<=$totalPages; $p++): ?>
                <a href="<?= buildUrl(['page'=>$p]) ?>" 
                   class="pagination-btn <?= $p==$page?'active':'' ?>">
                   <?= $p ?>
                </a>
            <?php endfor; ?>
        </div>

    </div>

</div>
</div>

<script>
document.querySelectorAll('.profile-link').forEach(link => {
    link.addEventListener('click', function (e) {
        e.preventDefault();

        const id = this.dataset.id;
        const type = this.dataset.type;

        fetch("get_profile.php?type=" + type + "&id=" + id)
        .then(r => r.text())
        .then(html => {
            document.getElementById("profileModalBody").innerHTML = html;
            new bootstrap.Modal(document.getElementById("profileModal")).show();
        });
    });
});

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
                this.style.border = "2px solid #28a745";
            } else {
                this.style.border = "2px solid red";
            }
        });
    });
});

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
                    msg.innerHTML = "Appointment Cancelled!";
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

<?php include("admin_footer.php"); ?>
