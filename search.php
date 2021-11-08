<?php 

  require "dash/auth/includes/dbh.php";
  require "dash/auth/includes/function.php";

  $search = isset($_GET['keyword']) ? $_GET['keyword'] : "";
  $doc_title = "Search $search - WorldVision";

  $rec_per_page = 8;

  $count = "SELECT COUNT(*) total FROM article WHERE status = 1 
  AND title LIKE :search OR user_id = (SELECT id FROM users WHERE name = :author)";
  $count_handler = $db_conn -> prepare($count);
  $count_handler -> bindValue(":search", "%$search%");
  $count_handler -> bindValue(":author", $search);
  $count_handler -> execute();
  $count_result = $count_handler -> fetch();

  $total_rec = $count_result['total'];
  $last_page = ceil($total_rec/$rec_per_page);

  $current_page = isset($_GET["page"]) ? (is_numeric($_GET["page"]) ? $_GET["page"] : 1 ) : 1;
  $current_page = $current_page < 1 ? 1 : ($current_page > $last_page ? $last_page : $current_page);

  $offset = ($current_page-1)*$rec_per_page;
  $offset = $offset < 0 ? 0 : $offset;

  $sql = "SELECT a.id as id, a.title, a.created_at as created_at, c.name, a.cover
  FROM article a JOIN categories c
  ON a.category_id = c.id
  WHERE a.title LIKE :search AND a.status = 1 
  OR a.user_id = (SELECT id FROM users WHERE name = :author) AND a.status = 1 
  ORDER BY a.created_at DESC LIMIT :offset, :length;";
  
  $handler = $db_conn -> prepare($sql);
  $handler -> bindValue(":search", "%$search%");
  $handler -> bindValue(":author", $search);
  $handler -> bindParam(":offset", $offset, PDO::PARAM_INT);
  $handler -> bindParam(":length", $rec_per_page, PDO::PARAM_INT);
  $handler -> execute();
  $result = $handler -> fetchAll();

  if (empty($result)) {
  } else {
    $found = true;
    // print_r($result);
  }

?>


<!DOCTYPE html>
<html lang="zxx">

  <?php include "_meta.php" ?>

  <body>
    <div class="container-scroller">
      <header id="header">
        <div class="container">

          <?php 
            // $search = false;
            include "partials/_navbar.php"   
          ?>

          <!-- partial -->
        </div>
      </header>
      <div class="container">
        <div class="row">
          <div class="col-sm-12 my-5">

            <?php

              $author = "SELECT * FROM users WHERE name = :author_name AND status = 1;";
              $author_handler = $db_conn -> prepare($author);
              $author_handler -> bindValue(":author_name", $search);
              $author_handler -> execute();
              $author_result = $author_handler -> fetch();

              if (!empty($author_result)) {
                
                $article_count = "SELECT COUNT(*) total_article FROM article 
                WHERE status = 1 AND user_id = (SELECT id FROM users WHERE name = :author_name)
                GROUP BY user_id;";
                $article_count_handler = $db_conn -> prepare($article_count);
                $article_count_handler -> bindValue(":author_name", $search);
                $article_count_handler -> execute();
                $article_count_result = $article_count_handler -> fetch();
                
                $author_name = $author_result['name'];
                $author_article = empty($article_count_result['total_article']) ? 0 : $article_count_result['total_article'];
                $author_role = $author_result['role'] == 1 ? "Author" : "Admin";
                $author_date = $author_result['created_at'];
                $author_profile = getProfileImg($author_name, "dash/auth/img/", $author_result['profile_img']);

                include "partials/_author_card.php";
              }
            
            ?>

            <form method="GET">
              <div class="search-container">
                  <input type="text" placeholder="Search.." name="keyword" 
                  value="<?= $search ?>"/>
                  <button type="submit"><i class="mdi mdi-magnify"></i></button>
              </div>
            </form>
          </div>
        </div>
        
        <?php if (empty($author_result)): ?>
          <b class="text-uppercase text-center d-block fs-14">
            Found <?= $total_rec ?> <?= ($total_rec>1)?"Results":"Result" ?>
          </b>
        <?php else: ?>
          <b class="text-uppercase text-center d-block fs-14">
            Found <?= $author_article ?> <?= ($author_article>1)?"Articles":"Article" ?> by <?= $author_name ?>
          </b>
        <?php endif; ?>

        <?php if(isset($found)): $rowCount = 0; ?>
          <?php foreach ($result as $key => $value): ?>
            <?php if ($rowCount % 4 == 0): ?>
            <div class="row my-5">
            <?php endif; ?>
            <?php $rowCount++; ?>

            <div class="col-md-6 col-lg-3 mb-5 mb-sm-2">
              <a href="article.php?id=<?= $value['id'] ?>">
                <div class="position-relative image-hover">
                  <img src="assets/images/content/<?= $value['cover'] ?>" 
                  class="img-fluid multi-small-thumb" alt="world-news" />
                  <span class="thumb-title text-uppercase">
                    <?= $value['name'] ?>
                  </span>
                </div>
              </a>
              <h5 class="font-weight-600 mt-3">
                <?= $value['title'] ?>
              </h5>
            </div>

          <?php if ($rowCount % 4 == 0): ?>
          </div>
          <?php endif; ?>
          <?php endforeach; ?>
        <?php endif; ?>

      </div>
    </div>

    <div class="row w-100">
      <div class="d-flex justify-content-center col-12">
        <?php pagination($current_page, $total_rec, $rec_per_page, 2, "search.php?keyword={$search}&page=%d") ?>
      </div>
    </div>
  
    <?php include "partials/_footer.php" ?>

  </body>
</html>
