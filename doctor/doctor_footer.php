<script>
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

});
</script>


<footer class="admin-footer text-center py-3">
  <div class="footer-container">
    <div class="footer-line"></div>
    <p class="mt-2 mb-0 text-white fw-semibold">
      © <?php echo date("Y"); ?> <span class="text-warning">CARE Group</span> — Doctor Panel
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
</script>
</body>
</html>
