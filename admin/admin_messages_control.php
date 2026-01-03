<?php
include("../connect.php");
include("admin_header.php");
include("admin_sidebar.php");
?>

<style>
.table-responsive {
  overflow-x: auto;
}

@media (max-width: 768px) {
  .messages-table thead {
    display: none;
  }
  .messages-table tr {
    display: block;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 10px;
  }
  .messages-table td::before {
    content: attr(data-label);
    font-weight: bold;
    color: #444;
  }
  .page-header h2 {
    font-size: 22px;
  }
  #globalSearch {
    width: 100% !important;
  }
}
</style>

<div class="dashboard-wrapper">
  <div class="container-fluid">
    <div class="page-header text-center">
      <h2 class="section-title" data-aos="zoom-in">View <span>Messages</span></h2>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
      <input id="globalSearch" type="text" class="form-control" placeholder="Search by name / email / message" style="width:360px; max-width:100%;" maxlength="50"  data-aos="fade-right">
    </div>

    <div class="panel"  data-aos="fade-up">
      <div class="table-responsive">
        <table class="table custom-table messages-table">
          <thead>
            <tr>
              <th>#</th>
              <th>User</th>
              <th>Name</th>
              <th>Email</th>
              <th>Message</th>
              <th>Created At</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody id="messagesTbody" class="text-center"></tbody>
        </table>
      </div>

      <div class="pagination-container mt-3 text-center" id="messagesPagination"></div>
    </div>
  </div>
</div>

<div id="toastBox" class="toast-box"></div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let currentPage = 1;

    function showToast(msg, type = 'success') {
        const box = document.getElementById('toastBox');
        const el = document.createElement('div');
        el.className = 'alert shadow mt-2';
        el.textContent = msg;
        el.classList.add(type === 'success' ? 'alert-success' : 'alert-danger');
        box.appendChild(el);
        setTimeout(() => el.remove(), 3000);
    }

    function loadMessages(page = 1) {
        currentPage = page;
        const search = encodeURIComponent(document.getElementById("globalSearch").value.trim());

        fetch(`admin_messages_data.php?page=${page}&search=${search}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('messagesTbody').innerHTML = data.rows;
                document.getElementById('messagesPagination').innerHTML = data.pagination;

                document.querySelectorAll('.btn-delete').forEach(btn => {
                    btn.onclick = () => {
                        if (!confirm("Are you sure you want to delete this message?")) return;

                        fetch('delete_message.php?id=' + btn.dataset.id)
                            .then(res => res.text())
                            .then(res => {
                                if (res === 'success') {
                                    showToast('Message deleted successfully');
                                    loadMessages(currentPage);
                                } else {
                                    showToast('Failed to delete message', 'error');
                                }
                            })
                            .catch(() => showToast('Error deleting message', 'error'));
                    };
                });

                document.querySelectorAll('.btn-view').forEach(btn => {
                    btn.onclick = () => {
                        alert(`Message from ${btn.dataset.name}:\n\n${btn.dataset.message}`);
                    };
                });

                document.querySelectorAll('.pagination-btn').forEach(btn => {
                    btn.onclick = () => loadMessages(btn.dataset.page);
                });
            });
    }

    document.getElementById("globalSearch").addEventListener('input', () => {
        clearTimeout(window.typingTimer);
        window.typingTimer = setTimeout(() => loadMessages(1), 500);
    });

    loadMessages(1);
});
</script>

<?php include("admin_footer.php"); ?>
