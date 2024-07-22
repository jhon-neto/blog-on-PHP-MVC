<!--Start Back To Top Button-->
<a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
<!--End Back To Top Button-->

<!--start color switcher-->
<div class="right-sidebar">
  <div class="switcher-icon">
    <i class="zmdi zmdi-settings zmdi-hc-spin"></i>
  </div>
  <div class="right-sidebar-content">

    <p class="mb-0">Textura Gaussiana</p>
    <hr>

    <ul class="switcher">
      <li id="theme1"></li>
      <li id="theme2"></li>
      <li id="theme3"></li>
      <li id="theme4"></li>
      <li id="theme5"></li>
      <li id="theme6"></li>
    </ul>

    <p class="mb-0">Background Gradiente</p>
    <hr>

    <ul class="switcher">
      <li id="theme7"></li>
      <li id="theme8"></li>
      <li id="theme9"></li>
      <li id="theme10"></li>
      <li id="theme11"></li>
      <li id="theme12"></li>
      <li id="theme13"></li>
      <li id="theme14"></li>
      <li id="theme15"></li>
    </ul>

  </div>
</div>
<!--end color switcher-->

</div>
<!--End wrapper-->


<!--Start footer-->
<footer class="footer">
  <div class="container">
    <div class="text-center">
      <span>Copyright © <?= date('Y'); ?></span>
    </div>
  </div>
</footer>
<!--End footer-->

<!-- Bootstrap core JavaScript-->
<script src="<?= $base; ?>/assets/js/jquery.min.js"></script>
<script src="<?= $base; ?>/assets/js/popper.min.js"></script>
<script src="<?= $base; ?>/assets/js/bootstrap.min.js"></script>

<!-- sidebar-menu js -->
<script src="<?= $base; ?>/assets/js/sidebar-menu.js"></script>
<!-- Chart js -->
<script src="<?= $base; ?>/assets/plugins/Chart.js/Chart.min.js"></script>
<!-- Datatables -->
<script src="<?= $base; ?>/assets/vendor/datatables/jquery.dataTables.js"></script>
<script src="<?= $base; ?>/assets/vendor/datatables/dataTables.bootstrap4.js"></script>
<script src="<?= $base; ?>/assets/js/demo/datatables-demo.js"></script>
<!-- Custom scripts -->
<script src="<?= $base; ?>/assets/js/app-script.js"></script>
<script src="<?= $base; ?>/assets/js/jquery.mask.js"></script>
<script src="<?= $base; ?>/assets/js/mask.js"></script>
<script>
  function setTheme(theme) {

    var url = '<?= $base ?>/setTheme';
    $.ajax({
      url: url,
      type: 'POST',
      data: {
        theme: theme
      },
      dataType: 'json'
    });
  }
</script>

</body>

</html>