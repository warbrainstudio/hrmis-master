<footer class="footer-admin mt-auto footer-light">
  <div class="container-fluid px-4">
    <div class="row">
      <div class="col-md-6 small">
        © <?= isset($app->company_name) ? $app->company_name : '(not-set)' ?> | <?= $app->app_name ?>
      </div>
      <div class="col-md-6 text-md-end small">
        Version <?= $app->app_version ?>
      </div>
    </div>
  </div>
</footer>