<?php
include("../connect.php");
include("admin_header.php");
include("admin_sidebar.php");
?>

<div class="dashboard-wrapper">
<div class="container-fluid">

    <div class="page-header text-center mb-3">
        <h2 class="section-title"  data-aos="zoom-in">Diseases <span>Management</span></h2>
    </div>

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <button id="addBtn" class="btn btn-primary mb-2"  data-aos="fade-right">+ Add Disease</button>

        <input id="globalSearch" type="text" class="form-control"
               placeholder="Search disease name"
               style="max-width:360px; width:100%;" maxlength="50"  data-aos="fade-left">
    </div>

    <div class="filter-box mb-3"  data-aos="zoom-in" data-aos-delay="100">
        <form id="filterForm" onsubmit="return false;">
            <div class="row g-3">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <label class="filter-label">Category</label>
                    <select id="category" name="category" class="form-select">
                        <option value="">All</option>
                        <?php
                        $catQ = mysqli_query($conn,"SELECT DISTINCT category FROM diseases ORDER BY category");
                        while($r = mysqli_fetch_assoc($catQ)){
                            echo '<option value="'.htmlspecialchars($r['category']).'">'.htmlspecialchars($r['category']).'</option>';
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

    <!-- TABLE -->
    <div class="panel"  data-aos="fade-up">
        <div class="table-responsive">
            <table class="table custom-table">
                <thead>
                    <tr>
                        <th style="width:70px;">Image</th>
                        <th>Disease</th>
                        <th>Description</th>
                        <th>Created</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="diseasesTbody"></tbody>
            </table>
        </div>

        <div class="pagination-container mt-3 text-center" id="diseasesPagination"></div>
    </div>

</div>
</div>

<style>
.doc-thumb {
    width:50px;
    height:50px;
    border-radius:50%;
    object-fit:cover;
    border:2px solid rgba(0,0,0,0.08);
}

.toast-box { 
    position: fixed; 
    bottom: 20px; 
    right: 20px; 
    z-index:99999; 
}

.pagination-btn {
    cursor:pointer;
    padding:6px 12px;
    margin:0 4px;
    border-radius:6px;
    border:1px solid #ddd;
    background:#fff;
}

.pagination-btn.active {
    background:linear-gradient(90deg,#ff3b3b,#ff6b6b);
    color:#fff;
    border:none;
}

@media(max-width:768px){
    .custom-table thead { display:none; }
    .custom-table tr {
        display:block;
        margin-bottom:15px;
        border:1px solid #eee;
        padding:10px;
        border-radius:10px;
    }
    .custom-table td {
        display:flex;
        justify-content:space-between;
        padding:6px 4px;
    }
    .custom-table td::before {
        content:attr(data-label);
        font-weight:600;
        color:#333;
    }
}
</style>

<div id="toastBox" class="toast-box"></div>

<script>
let currentPage = 1;

function showToast(msg,type='success'){
    const box=document.getElementById('toastBox');
    const el=document.createElement('div');
    el.className='alert shadow '+(type==='success'?'alert-success':'alert-danger');
    el.style.marginTop='8px';
    el.textContent=msg;
    box.appendChild(el);
    setTimeout(()=>el.remove(),3000);
}

function loadDiseases(page=1){
    currentPage = page;

    const search = encodeURIComponent(globalSearch.value.trim());
    const category = encodeURIComponent(document.getElementById('category').value);

    fetch(`diseases_data.php?page=${page}&search=${search}&category=${category}`)
    .then(r=>r.json())
    .then(data=>{
        diseasesTbody.innerHTML = data.rows;
        diseasesPagination.innerHTML = data.pagination;

        document.querySelectorAll('.btn-delete').forEach(btn=>{
            btn.onclick = ()=>{
                if(!confirm("Delete this disease?")) return;

                fetch("disease_delete.php",{
                    method:"POST",
                    headers:{'Content-Type':'application/x-www-form-urlencoded'},
                    body:"id="+btn.dataset.id
                }).then(()=>{
                    showToast("Disease deleted");
                    loadDiseases(currentPage);
                });
            }
        });

        document.querySelectorAll('.btn-copy').forEach(btn=>{
            btn.onclick = ()=>{

                fetch("disease_copy.php",{
                    method:"POST",
                    headers:{'Content-Type':'application/x-www-form-urlencoded'},
                    body:"id="+btn.dataset.id
                }).then(()=>{
                    showToast("Disease copied");
                    loadDiseases(currentPage);
                });
            }
        });

        document.querySelectorAll('.pagination-btn').forEach(pg=>{
            pg.onclick = ()=> loadDiseases(pg.dataset.page);
        });
    });
}

loadDiseases(1);

applyFilters.onclick = ()=> loadDiseases(1);

resetFilters.onclick = ()=>{
    category.value = "";
    globalSearch.value = "";
    loadDiseases(1);
};

globalSearch.oninput = ()=>{
    clearTimeout(window.typingTimer);
    window.typingTimer = setTimeout(()=> loadDiseases(1), 400);
};

addBtn.onclick = ()=> location.href="disease_add.php";
</script>

<?php include("admin_footer.php"); ?>
