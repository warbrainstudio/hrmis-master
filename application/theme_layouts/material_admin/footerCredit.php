<footer class="footer hidden-xs-down">
  <p>© <?php echo $app->app_name ?></p>

  <ul class="nav footer__nav">
    <a class="nav-link" href="#" target="_blank">
      <?= isset($app->company_name) ? $app->company_name : '(not-set)' ?> | Version <?php echo $app->app_version ?>
    </a>
  </ul>
</footer>