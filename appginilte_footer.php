<footer class="main-footer <?php echo $text_sm_footer == "on" ? "text-sm" : ""; ?>">
  <div class="float-right d-none d-sm-block">
    <!-- anything you want on the right side of the footer place it here -->
    <strong>
      <?php echo $footerText; ?>
    </strong>
  </div>
  <strong>
    <?php $translate['Copyright']; ?> &copy;
    <?php echo date('Y'); ?> <a href="#0">
      <?php echo $appName; ?>
    </a>.
  </strong>
  <?php $translate['All rights reserved.']; ?>
</footer>
<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
<!-- Back to top button -->
<a id="back-to-top" href="#" class="btn btn-primary back-to-top btn-floating" role="button" aria-label="Scroll to top"
  style="display:none">
  <i class="fas fa-chevron-up"></i>
</a>
</div>
<!-- ./wrapper -->
<!-- scroll to top script -->
<script>
  //Get the button
  let mybutton = document.getElementById("back-to-top");
  // When the user scrolls down 20px from the top of the document, show the button
  window.onscroll = function () { scrollFunction(); }; function scrollFunction() { if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) { mybutton.style.display = "block"; } else { mybutton.style.display = "none"; } }
  // When the user clicks on the button, scroll to the top of the document
  mybutton.addEventListener("click", backToTop); function backToTop() { document.body.scrollTop = 0; document.documentElement.scrollTop = 0; }

  const div = document.getElementById("advmenuitems");
  const listItem = document.getElementById("advancedfeatures");

  if (div.innerHTML.trim() === "") {
    listItem.style.display = "none";
  }
</script>
<script type="text/javascript">
  function inactivityTime() {
    var time;
    // events
    window.onload = resetTime; window.onclick = resetTime; window.onkeypress = resetTime; window.ontouchstart = resetTime; window.onmousemove = resetTime; window.onmousedown = resetTime; window.addEventListener('scroll', resetTime, true);
    function alertUser() {
      // user has been inactive beyond the specified time
      window.location.href = 'appginilte_lockscreen.php';
    }
    function resetTime() { clearTimeout(time); var sessionExpiry = <?php echo ($sessionExpiry * 60); ?>; time = setTimeout(alertUser, 1000 * sessionExpiry); }

  };

  // run the function
  inactivityTime();
</script>
<!-- Bootstrap 4 -->
<script src="appginilte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="appginilte/plugins/chart.js/Chart.min.js"></script>
<!-- AdminLTE App -->
<script src="appginilte/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="appginilte/dist/js/demo.js"></script>
<!-- Toast script -->
<script src="appginilte/plugins/toastr/toastr.min.js"></script>
<!-- page loader -->
<script src="appginilte/plugins/modernizr/modernizr.js"></script>
<script> $(document).ready(function () { setTimeout(function () { $(".se-pre-con").fadeOut("slow"); }, 0); }); </script>
<!-- DataTables  & Plugins -->
<script src="appginilte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="appginilte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="appginilte/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="appginilte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="appginilte/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="appginilte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="appginilte/plugins/jszip/jszip.min.js"></script>
<script src="appginilte/plugins/pdfmake/pdfmake.min.js"></script>
<script src="appginilte/plugins/pdfmake/vfs_fonts.js"></script>
<script src="appginilte/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="appginilte/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="appginilte/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<?php
//check if file exists: appginilte-custom-footer.php and include it
if (file_exists('appginilte/appginilte-custom-footer.php')) {
  include 'appginilte/appginilte-custom-footer.php';
}
?>
<!-- evo-calendar.js-->
<script src="appginilte/plugins/evo-calendar/js/evo-calendar.js"></script>
</body>
</html>