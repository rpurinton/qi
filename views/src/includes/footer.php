      </div>
      </div>
      </div>
      </div>
      <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
      <script src="/assets/vendors/js/vendor.bundle.base.js"></script>
      <script src="/assets/vendors/chart.js/Chart.min.js"></script>
      <script src="/assets/vendors/progressbar.js/progressbar.min.js"></script>
      <script src="/assets/vendors/jvectormap/jquery-jvectormap.min.js"></script>
      <script src="/assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
      <script src="/assets/vendors/owl-carousel-2/owl.carousel.min.js"></script>
      <script src="/assets/js/jquery.cookie.js" type="text/javascript"></script>
      <script src="/assets/js/highlight.min.js"></script>
      <script src="/assets/js/off-canvas.js"></script>
      <script src="/assets/js/hoverable-collapse.js"></script>
      <script src="/assets/js/misc.js"></script>
      <script src="/assets/js/settings.js"></script>
      <script src="/assets/js/todolist.js"></script>
      <script src="/assets/js/dashboard.js"></script>
      <script src="/assets/js/custom.js"></script>
      <?php
      if (isset($customjs)) {
            foreach ($customjs as $script) {
                  echo ("<script src='$script'></script>\n");
            }
      }
      ?>
      </body>

      </html>