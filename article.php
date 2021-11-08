<!DOCTYPE html>
<html lang="zxx">

  <?php
    session_start();
    require "dash/auth/includes/dbh.php";
    require "dash/auth/includes/function.php";

    $sql = "SELECT a.id as aid, a.title, a.content, a.cover, a.slug,
    DATE_FORMAT(a.created_at, '%d %M, %Y') created_at, c.name, c.id as cid, 
    u.name as author, u.id, u.profile_img

    FROM article a JOIN users u
    ON a.user_id = u.id JOIN categories c
    ON a.category_id = c.id
    WHERE a.status = 1 AND a.id = :id";

    $handler = $db_conn -> prepare($sql);
    $handler -> bindParam(":id", $_GET['id'], PDO::PARAM_INT);
    $handler -> execute();
    $result = $handler -> fetch();

    if (empty($result)) {
      header("Location: .");
      exit;
    } else {
      try {
        $update_view = "UPDATE article SET view = view + 1 WHERE id = :id";
        $handler = $db_conn -> prepare($update_view);
        $handler -> bindParam(":id", $_GET['id'], PDO::PARAM_INT);
        $handler -> execute();
      } catch (PDOException $e) {
        echo $e -> getMessage();
        exit;
      }
    }

    $profile = getProfileImg($result['author'], "dash/auth/img/", $result['profile_img']);

    $meta = [
      "url" => "http://{$_SERVER['HTTP_HOST']}/vision/article.php?id={$result['aid']}",
      "title" => $result["title"],
      "description" => str_replace("'", '"', substr(strip_tags($result['content']),0,100)."..."),
      "image" => "http://{$_SERVER['HTTP_HOST']}/vision/assets/images/content/{$result['cover']}"
    ];

  ?>

  <?php $doc_title = ucwords($result['title'])." - WorldVision"; ?>
  <?php include "_meta.php"; ?>

  <body>
    <div class="container-scroller">
      <header id="header">
        <div class="container">
          
          <?php include "partials/_navbar.php" ?>

          <!-- partial -->
        </div>
      </header>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <div class="news-post-wrapper">
            <div class="news-post-wrapper-sm ">
              <h1 class="text-center">
                <?= $result['title'] ?>
              </h1>
              <div class="text-center">
                <a href="category.php?id=<?= $result['cid'] ?>" class="btn btn-dark font-weight-bold mb-4">
                  <?= ucwords($result['name']) ?>
                </a>
                <?php if(isset($_SESSION['id'])): ?>
                  <a href="dash/auth/post.php?edit=<?= $result['aid'] ?>" class="btn btn-warning font-weight-bold mb-4">
                    Edit
                  </a>
                <?php endif; ?>
              </div>
              <p class="fs-15 d-flex justify-content-center align-items-center m-0">
                <img
                  src="<?= $profile ?>"
                  alt=""
                  class="img-xs img-rounded mr-2"
                />
                <a class="text-dark" href="search.php?keyword=<?= $result["author"] ?>"><?= ucwords($result["author"]) ?></a>
                <i class="fas mx-2 fa-angle-right"></i>
                <?= $result["created_at"] ?>
              </p>
            </div>

            <div class="pt-4 pb-4" id="article_content">
              <?= $result["content"] ?>
            </div>

            <hr class="mt-5">

            <div class="sharethis-inline-share-buttons mt-5"></div>

            <div id="disqus_thread" class="mt-5"></div>
            
            <div class="news-post-wrapper-sm mt-5">

              <h1 class="font-weight-600 text-center mt-5 mb-5">
                You may also like
              </h1>

              <?php 
              
              $prev = "SELECT a.id as aid, a.cover, c.name as cname, a.title, u.name as uname, 
              a.created_at as created_at
              FROM article a JOIN categories c ON a.category_id = c.id JOIN users u ON a.user_id = u.id 
              WHERE a.id < :article_id AND category_id = :cat_id ORDER BY a.id DESC LIMIT 1;";
              $prev_handler = $db_conn -> prepare($prev);
              $prev_handler -> bindParam(":article_id", $_GET['id'], PDO::PARAM_INT);
              $prev_handler -> bindParam(":cat_id", $result['cid'], PDO::PARAM_INT);
              $prev_handler -> execute();
              $prev_result = $prev_handler -> fetch();

              ?>

              <?php if(!empty($prev_result)): ?>

              <div class="border-top py-5">
                <div class="row">
                  <div class="col-sm-4">
                    <a href="?id=<?= $prev_result['aid'] ?>">
                      <div class="position-relative image-hover">
                        <img
                          src="assets/images/content/<?= $prev_result['cover'] ?>"
                          alt="news"
                          class="img-fluid related_article_thumb"
                        />
                        <span class="thumb-title text-uppercase"><?= $prev_result['cname'] ?></span>
                      </div>
                    </a>
                  </div>
                  <div class="col-sm-8">
                    <div class="position-relative image-hover">
                      <a href="?id=<?= $prev_result['aid'] ?>" class="text-dark">
                        <h1 class="font-weight-600">
                          <?= $prev_result['title'] ?>
                        </h1>
                      </a>
                      <p class="fs-15">
                        <?= ucwords($prev_result['uname']) ?>
                        <i class="fas mx-2 fa-clock"></i>
                        <?= time_elapsed_str($prev_result['created_at']) ?>
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              <?php endif; ?>


              <?php 
              
              $next = "SELECT a.id as aid, a.cover, c.name as cname, a.title, u.name as uname, 
              a.created_at as created_at
              FROM article a JOIN categories c ON a.category_id = c.id JOIN users u ON a.user_id = u.id 
              WHERE a.id > :article_id AND category_id = :cat_id ORDER BY a.id LIMIT 1;";
              $next_handler = $db_conn -> prepare($next);
              $next_handler -> bindParam(":article_id", $_GET['id'], PDO::PARAM_INT);
              $next_handler -> bindParam(":cat_id", $result['cid'], PDO::PARAM_INT);
              $next_handler -> execute();
              $next_result = $next_handler -> fetch();

              ?>

              <?php if(!empty($next_result)): ?>

              <div class="border-top py-5">
                <div class="row">
                  <div class="col-sm-4">
                    <a href="?id=<?= $next_result['aid'] ?>">
                      <div class="position-relative image-hover">
                        <img
                          src="assets/images/content/<?= $next_result['cover'] ?>"
                          alt="news"
                          class="img-fluid related_article_thumb"
                        />
                        <span class="thumb-title text-uppercase"><?= $next_result['cname'] ?></span>
                      </div>
                    </a>
                  </div>
                  <div class="col-sm-8">
                    <div class="position-relative image-hover">
                      <a href="?id=<?= $next_result['aid'] ?>" class="text-dark">
                        <h1 class="font-weight-600">
                          <?= $next_result['title'] ?>
                        </h1>
                      </a>
                      <p class="fs-15">
                        <?= ucwords($next_result['uname']) ?>
                        <i class="fas mx-2 fa-clock"></i>
                        <?= time_elapsed_str($next_result['created_at']) ?>
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              <?php endif; ?>


            </div>
          </div>
        </div>
      </div>
    </div>
    
    <?php include "partials/_footer.php" ?>


  <script type="text/javascript"
  src="https://platform-api.sharethis.com/js/sharethis.js#property=6182c76a8afacc001dd07677&product=inline-share-buttons" 
  async="async"></script>
  <script>
      /**
      *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
      *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables    */
      var disqus_config = function () {

        this.page.url = "http://<?=$_SERVER['HTTP_HOST']?>/vision/article.php?id=<?= $_GET['id'] ?>";  
        this.page.identifier = "article_<?= $_GET['id'] ?>_identifier";

      };
      
      (function() { // DON'T EDIT BELOW THIS LINE
      var d = document, s = d.createElement('script');
      s.src = 'https://visionworld.disqus.com/embed.js';
      s.setAttribute('data-timestamp', +new Date());
      (d.head || d.body).appendChild(s);
      })();
  </script>
  <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
  </body>
</html>
