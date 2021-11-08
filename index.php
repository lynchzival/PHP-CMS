<!DOCTYPE html>
<html lang="zxx">

  <?php $doc_title = "WorldVision" ?>
  <?php include "_meta.php" ?>

  <body>
    <div class="container-scroller">
      <div class="main-panel">
        <header id="header">
          <div class="container">
            <!-- partial:partials/partials/_navbar.html -->
            
            <?php include "partials/_navbar.php" ?>

            <!-- partial -->
          </div>
        </header>
        <div class="container">
          
          <?php include "partials/_pin_articles.php" ?>

          <div class="row">
            <div class="col-lg-8">
              <?php include "partials/_carousel.php" ?>
            </div>
            <div class="col-lg-4">
              <?php include "partials/_mini_block_article.php" ?>
            </div>
          </div>

          <div class="world-news pb-0">
            <?php include "partials/_each_category.php" ?>
          </div>

          <!-- <div class="editors-news">
            include "partials/_popularnews.php"
          </div> -->

          <div class="popular-news">
            <div class="row">
              <div class="col-lg-3">
                <div class="d-flex position-relative float-left">
                  <h3 class="section-title">Popular This Month</h3>
                </div>
              </div>
            </div>
            <div class="row">
            
              <?php include "partials/_popular_articles.php" ?>

              <?php include "partials/_popular_sidebar.php" ?>
              
            </div>
          </div>
          
        </div>
        <!-- main-panel ends -->
        <!-- container-scroller ends -->

        <!-- partial:partials/partials/_footer.html -->
        
        <?php include "partials/_footer.php" ?>
        
        <!-- partial -->
      </div>
    </div>
    
    <script type="text/javascript">
      /* * * CONFIGURATION VARIABLES * * */
      var disqus_shortname = 'visionworld';
      /* * * DON'T EDIT BELOW THIS LINE * * */
      (function () {
          var s = document.createElement('script');
          s.async = true;
          s.type = 'text/javascript';
          s.src = '//' + disqus_shortname + '.disqus.com/count.js';
          (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
      }());
    </script>
  </body>
</html>
