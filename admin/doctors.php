<?php
include("../connect.php");
include("admin_header.php");
include("admin_sidebar.php");
?>
<style>
.section-title {
    font-weight: 700;
    font-size: 28px;
}
.section-title span {
    color: #ff3b3b;
}

.doc-thumb {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(0,0,0,0.08);
}
*/
.table.custom-table th {
    white-space: nowrap;
}
.table.custom-table td {
    vertical-align: middle;
}

.toast-box { 
    position: fixed; 
    bottom: 20px; 
    right: 20px; 
    z-index: 99999; 
}

.pagination-btn { 
    cursor: pointer; 
    padding: 6px 12px; 
    margin: 0 4px; 
    border-radius: 6px; 
    border: 1px solid #ddd;
    background: white;
}
.pagination-btn.active { 
    background: linear-gradient(90deg,#ff3b3b,#ff6b6b); 
    color:#fff; 
    border:none; 
}


@media(max-width: 992px) {
    #globalSearch {
        width: 100% !important;
        margin-top: 12px;
    }
}

@media(max-width: 768px) {
    .filter-box .col-lg-12 {
        margin-bottom: 10px;
    }

    table thead {
        display: none;
    }

    table tbody tr {
        display: block;
        background: #fff;
        margin-bottom: 12px;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0px 3px 10px rgba(0,0,0,0.07);
    }

    table tbody tr td {
        display: flex;
        justify-content: space-between;
        padding: 6px 0;
        border: none !important;
    }

    table tbody tr td::before {
        font-weight: 600;
        color: #555;
        content: attr(data-label);
    }

    .text-center.actions {
        justify-content: center !important;
    }
}

</style>

<div class="dashboard-wrapper">
<div class="container-fluid">

    <div class="page-header text-center mb-4">
        <h2 class="section-title" data-aos="zoom-in">Doctors <span>Management</span></h2>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <button id="addBtn" class="btn btn-primary" data-aos="fade-right">+ Add Doctor</button>

        <input id="globalSearch" type="text" class="form-control"
               placeholder="Search name / specialty / hospital / disease"
               style="width:360px;" maxlength="50"  data-aos="fade-left">
    </div>

    <div class="filter-box mb-3 p-3 bg-white shadow-sm rounded"  data-aos="zoom-in" data-aos-delay="100">
        <form id="filterForm" onsubmit="return false;">
            <div class="row g-3">

                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label>Specialty</label>
                    <select id="specialty" class="form-select">
                        <option value="">All</option>
                        <?php
                        $spQ = mysqli_query($conn,"SELECT DISTINCT specialty FROM doctors ORDER BY specialty");
                        while($r = mysqli_fetch_assoc($spQ)){
                            echo '<option value="'.$r['specialty'].'">'.$r['specialty'].'</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label>Hospital</label>
                    <select id="hospital" class="form-select">
                        <option value="">All</option>
                        <?php
                        $hQ = mysqli_query($conn,"SELECT DISTINCT hospital FROM doctors ORDER BY hospital");
                        while($r = mysqli_fetch_assoc($hQ)){
                            echo '<option value="'.$r['hospital'].'">'.$r['hospital'].'</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label>Disease</label>
                    <select id="disease" class="form-select">
                        <option value="">All</option>
                        <?php
                        $dQ = mysqli_query($conn,"SELECT DISTINCT diseases FROM doctors ORDER BY diseases");
                        while($r = mysqli_fetch_assoc($dQ)){
                            echo '<option value="'.$r['diseases'].'">'.$r['diseases'].'</option>';
                        }
                        ?>
                    </select>
                </div>

            </div>

            <div class="mt-3 text-center">
                <button id="applyFilters" class="btn btn-primary">Apply</button>
                <button id="resetFilters" class="btn btn-outline-danger">Reset</button>
            </div>
        </form>
    </div>

    <!-- DOCTORS TABLE -->
    <div class="panel p-3 bg-white shadow-sm rounded"  data-aos="fade-up">
        <div class="table-responsive">
            <table class="table custom-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Specialty</th>
                        <th>Email</th>
                        <th>Hospital</th>
                        <th>Disease</th>
                        <th>Description</th>
                        <th>Created</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>

                <tbody id="doctorsTbody" class="text-center"></tbody>
            </table>
        </div>

        <div id="doctorsPagination" class="pagination-container mt-3 text-center"></div>
    </div>

</div>
</div>

<div id="toastBox" class="toast-box"></div>
<script>
let currentPage = 1;

function showToast(msg, type='success'){
    const box = document.getElementById('toastBox');
    const el = document.createElement('div');
    el.className = 'alert shadow mt-2 ' + (type === 'success' ? 'alert-success':'alert-danger');
    el.textContent = msg;
    box.appendChild(el);
    setTimeout(()=> el.remove(), 2500);
}

function loadDoctors(page = 1) {

    currentPage = page;

    const searchVal     = document.getElementById("globalSearch").value.trim();
    const hospVal       = document.getElementById("hospital").value;
    const specVal       = document.getElementById("specialty").value;
    const diseaseVal    = document.getElementById("disease").value;

    fetch(
        `doctors_data.php?page=${page}&search=${encodeURIComponent(searchVal)}&hospital=${encodeURIComponent(hospVal)}&specialty=${encodeURIComponent(specVal)}&disease=${encodeURIComponent(diseaseVal)}`
    )
    .then(r => r.json())
    .then(data => {

        document.getElementById('doctorsTbody').innerHTML = data.rows;
        document.getElementById('doctorsPagination').innerHTML = data.pagination;

        // DELETE
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.onclick = () => {
                if (!confirm("Delete this doctor?")) return;
                fetch("doctor_delete.php", {
                    method:'POST',
                    headers:{'Content-Type':'application/x-www-form-urlencoded'},
                    body:'id=' + btn.dataset.id
                }).then(()=>{
                    showToast("Doctor deleted");
                    loadDoctors(page);
                })
            }
        });

        document.querySelectorAll('.btn-copy').forEach(btn => {
            btn.onclick = () => {
                fetch("doctor_copy.php", {
                    method:'POST',
                    headers:{'Content-Type':'application/x-www-form-urlencoded'},
                    body:'id='+btn.dataset.id
                }).then(()=>{
                    showToast("Doctor copied");
                    loadDoctors(page);
                })
            }
        });

        document.querySelectorAll('.pagination-btn').forEach(p=>{
            p.onclick = () => loadDoctors(parseInt(p.dataset.page));
        });

    })
    .catch(()=> showToast("Failed to load", "error"));
}

loadDoctors(1);

document.getElementById('applyFilters').onclick = ()=> loadDoctors(1);

document.getElementById('resetFilters').onclick = ()=>{
    document.getElementById("globalSearch").value = "";
    document.getElementById("hospital").value = "";
    document.getElementById("specialty").value = "";
    document.getElementById("disease").value = "";
    loadDoctors(1);
};

document.getElementById("globalSearch").oninput = ()=>{
    clearTimeout(window.typing);
    window.typing = setTimeout(()=> loadDoctors(1), 500);
};

document.getElementById("addBtn").onclick = ()=>{
    window.location.href = "doctor_add.php";
};
</script>


<?php include("admin_footer.php"); ?>
