<!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
     <div class="modal-content">
        <div class="modal-header">
           <h5 class="modal-title">Profile</h5>
           <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="profileModalBody">
           <div class="text-center py-3">Loading...</div>
        </div>
     </div>
  </div>
</div>

</main>

<footer class="admin-footer text-center py-3">
  <div class="footer-container">
    <div class="footer-line"></div>
    <p class="mt-2 mb-0 text-white fw-semibold">
      © <?php echo date("Y"); ?> <span class="text-warning">CARE Group</span> — Admin Panel
    </p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 1100,
    easing: 'ease-in-out',
    offset: 100,
    once: false,    
    mirror: true      
});

document.addEventListener('DOMContentLoaded', function () {

  const toggle = document.getElementById('sidebarToggle');
  const mobileSidebar = document.getElementById('mobileSidebar');
  const mobileClose = document.getElementById('mobileClose');

  toggle?.addEventListener('click', () => {
    mobileSidebar.classList.toggle('open');
    mobileSidebar.setAttribute('aria-hidden', mobileSidebar.classList.contains('open') ? 'false' : 'true');
  });

  mobileClose?.addEventListener('click', () => {
    mobileSidebar.classList.remove('open');
    mobileSidebar.setAttribute('aria-hidden', 'true');
  });

  const dropdownBtn = document.getElementById("profileDropdownBtn");
  const dropdownIcon = document.getElementById("dropdownIcon");

  if (dropdownBtn) {
    dropdownBtn.addEventListener("click", () => {
      dropdownIcon.classList.toggle("bi-chevron-up");
      dropdownIcon.classList.toggle("bi-chevron-down");
    });

    document.addEventListener("click", function(e){
      if (!dropdownBtn.contains(e.target)) {
        dropdownIcon.classList.remove("bi-chevron-up");
        dropdownIcon.classList.add("bi-chevron-down");
      }
    });
  }
});
</script>
</body>
</html>
