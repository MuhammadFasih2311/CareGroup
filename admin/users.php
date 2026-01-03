<?php
include("../connect.php");
include("admin_header.php");
include("admin_sidebar.php");

$success = $error = "";

$q = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC");
?>

<style>
@media(max-width: 768px){
    .page-header h2 { font-size: 22px; }
    .custom-table thead { display:none; }

    .custom-table tbody tr {
        display:block;
        margin-bottom:15px;
        background:#fff;
        padding:12px;
        border-radius:10px;
        box-shadow:0 3px 6px rgba(0,0,0,0.08);
    }
    
    .custom-table tbody tr td::before{
        content: attr(data-label);
        font-weight:600;
        color:#444;
    }

    .btn-delete {
        width:100%;
        margin-top:8px;
    }

    #globalSearch {
        width:100% !important;
    }
}

.doc-thumb{
    width:45px;
    height:45px;
    border-radius:50%;
    object-fit:cover;
    border:2px solid rgba(0,0,0,0.05);
}

.pagination-btn{
    cursor:pointer;
    padding:6px 12px;
    margin:0 3px;
    border:1px solid #ddd;
    border-radius:6px;
}
.pagination-btn.active{
    background:#ff3b3b;
    color:white;
}

.toast-box{
    position:fixed;
    bottom:20px;
    right:20px;
    z-index:99999;
}
</style>

<div class="dashboard-wrapper">
  <div class="container-fluid">

    <div class="page-header text-center">
      <h2 class="section-title"  data-aos="zoom-in">Patients / Users <span>Management</span></h2>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
      <input id="globalSearch" type="text" class="form-control"
             placeholder="Search by name / email"
             style="width:360px; max-width:100%;" maxlength="50"  data-aos="fade-right">
    </div>

    <div class="panel"  data-aos="fade-up">
      <div class="table-responsive">
        <table class="table custom-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Created At</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>

          <tbody id="usersTbody" class="text-center">
            <?php while ($row = mysqli_fetch_assoc($q)) { ?>
            <tr>
              <td data-label="#"> <?= htmlspecialchars($row['id']) ?> </td>
              <td data-label="Name"><?= htmlspecialchars($row['name']) ?></td>
              <td data-label="Email"><?= htmlspecialchars($row['email']) ?></td>
              <td data-label="Phone"><?= htmlspecialchars($row['phone']) ?></td>
              <td data-label="Created"><?= htmlspecialchars($row['created_at']) ?></td>

              <td data-label="Actions" class="text-center">
                <button class="btn btn-sm btn-danger btn-delete" data-id="<?= $row['id'] ?>">Delete</button>
              </td>
            </tr>
            <?php } ?>
          </tbody>

        </table>
      </div>

      <div class="pagination-container mt-3 text-center" id="usersPagination"></div>
    </div>

  </div>
</div>

<div id="toastBox" class="toast-box"></div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let currentPage = 1;

    function showToast(msg, type='success'){
        const box = document.getElementById('toastBox');
        const el = document.createElement('div');
        el.className = 'alert shadow mt-2 ' + (type==='success' ? 'alert-success' : 'alert-danger');
        el.textContent = msg;
        box.appendChild(el);
        setTimeout(()=> el.remove(), 2500);
    }

    function loadUsers(page=1){
        currentPage = page;
        const search = encodeURIComponent(globalSearch.value.trim());

        fetch(`users_data.php?page=${page}&search=${search}`)
        .then(r=>r.json())
        .then(data=>{
            usersTbody.innerHTML = data.rows;
            usersPagination.innerHTML = data.pagination;

            document.querySelectorAll('.btn-delete').forEach(btn=>{
                btn.onclick = ()=>{
                    if(!confirm("Delete user?")) return;

                    fetch("delete_user.php",{
                        method:'POST',
                        headers:{'Content-Type':'application/x-www-form-urlencoded'},
                        body:'id='+btn.dataset.id
                    })
                    .then(r=>r.text())
                    .then(res=>{
                        if(res.trim()==='success'){
                            showToast("User deleted");
                            loadUsers(currentPage);
                        } else {
                            showToast("Failed!", "error");
                        }
                    })
                }
            });

            document.querySelectorAll('.pagination-btn').forEach(btn=>{
                btn.onclick = ()=> loadUsers(btn.dataset.page);
            });

        });
    }

    globalSearch.oninput = ()=>{
        clearTimeout(window.typing);
        window.typing = setTimeout(()=> loadUsers(1), 500);
    };

    loadUsers(1);
});
</script>

<?php include("admin_footer.php"); ?>
