<?php
include("../connect.php");
include("admin_header.php");
include("admin_sidebar.php");
?>

<style>
.filter-box .row > div {
    width: 100% !important;
}

@media(max-width: 768px){
    #globalSearch{
        width: 100% !important;
        margin-top:10px;
    }
 
    table thead{
        display:none;
    }
    table tbody tr{
        display:block;
        margin-bottom:15px;
        border:1px solid #ddd;
        padding:10px;
        border-radius:10px;
    }
    table tbody tr td{
        display:flex;
        justify-content:space-between;
        padding:6px 4px;
    }
    table tbody tr td::before{
        content: attr(data-label);
        font-weight:700;
        color:#444;
    }
    .pagination-container{
        display:flex;
        flex-wrap:wrap;
        gap:6px;
        justify-content:center;
    }
}

.doc-thumb {
    width:50px;
    height:50px;
    border-radius:10px;
    object-fit:cover;
    border:2px solid rgba(0,0,0,0.06);
}
.pagination-btn { cursor:pointer; padding:6px 12px; margin:0 4px; border-radius:6px; border:1px solid #ddd; }
.pagination-btn.active { background:#ff3b3b; color:#fff; }
.toast-box { position: fixed; bottom: 20px; right: 20px; z-index:99999; }
</style>

<div class="dashboard-wrapper">
<div class="container-fluid">

    <div class="page-header text-center mb-3">
        <h2 class="section-title"  data-aos="zoom-in">Hospitals <span>Management</span></h2>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <button id="addBtn" class="btn btn-primary"  data-aos="fade-right">+ Add Hospital</button>
        <input id="globalSearch" type="text" class="form-control" placeholder="Search name / city" style="width:360px;" maxlength="50"  data-aos="fade-left">
    </div>

    <div class="filter-box mb-3"  data-aos="zoom-in" data-aos-delay="100">
    <form id="filterForm" onsubmit="event.preventDefault();">
        <div class="row g-3">

            <div class="col-lg-6 col-md-6 col-sm-12">
                <label class="filter-label">Name</label>
                <select id="filterName" class="form-select">
                    <option value="">All</option>
                    <?php
                    $catQ = mysqli_query($conn,"SELECT DISTINCT name FROM hospitals ORDER BY name");
                    while($r = mysqli_fetch_assoc($catQ)){
                        echo '<option value="'.htmlspecialchars($r['name']).'">'.$r['name'].'</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12">
                <label class="filter-label">City</label>
                <select id="filterCity" class="form-select">
                    <option value="">All</option>
                    <?php
                    $cQ = mysqli_query($conn,"SELECT DISTINCT city FROM hospitals ORDER BY city");
                    while($r = mysqli_fetch_assoc($cQ)){
                        echo '<option value="'.htmlspecialchars($r['city']).'">'.$r['city'].'</option>';
                    }
                    ?>
                </select>
            </div>

        </div>

        <div class="mt-3 text-center">
            <button type="button" id="applyFilters" class="btn btn-primary">Apply</button>
            <button type="button" id="resetFilters" class="btn btn-outline-danger">Reset</button>
        </div>
    </form>
    </div>

    <!-- TABLE -->
    <div class="panel"  data-aos="fade-up">
        <div class="table-responsive">
            <table class="table custom-table">
                <thead>
                    <tr>
                        <th style="width:70px;">Image</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>Created</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="hospitalsTbody"></tbody>
            </table>
        </div>
        <div class="pagination-container mt-3 text-center" id="hospitalsPagination"></div>
    </div>

</div>
</div>

<div id="toastBox" class="toast-box"></div>

<script>
let currentPage = 1;

function loadHospitals(page = 1) {
    currentPage = page;

    const search = encodeURIComponent(globalSearch.value.trim());
    const nameVal = encodeURIComponent(document.getElementById("filterName").value);
    const cityVal = encodeURIComponent(document.getElementById("filterCity").value);

    fetch(`hospitals_data.php?page=${page}&search=${search}&name=${nameVal}&city=${cityVal}`)
        .then(r => r.json())
        .then(data => {
            hospitalsTbody.innerHTML = data.rows;
            hospitalsPagination.innerHTML = data.pagination;

            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.onclick = () => {
                    if (!confirm("Delete this hospital?")) return;
                    fetch("hospital_delete.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "id=" + btn.dataset.id
                    }).then(() => loadHospitals(currentPage));
                };
            });

            document.querySelectorAll('.btn-copy').forEach(btn => {
                btn.onclick = () => {
                    fetch("hospital_copy.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "id=" + btn.dataset.id
                    }).then(() => loadHospitals(currentPage));
                };
            });

            document.querySelectorAll('.pagination-btn').forEach(btn => {
                btn.onclick = () => loadHospitals(btn.dataset.page);
            });
        });
}

globalSearch.oninput = () => loadHospitals(1);
document.getElementById("applyFilters").onclick = () => loadHospitals(1);

document.getElementById("resetFilters").onclick = () => {
    globalSearch.value = "";
    document.getElementById("filterName").value = "";
    document.getElementById("filterCity").value = "";
    loadHospitals(1);
};

document.getElementById("addBtn").onclick = () => location.href = "hospital_add.php";

loadHospitals(1);
</script>

<?php include("admin_footer.php"); ?>
