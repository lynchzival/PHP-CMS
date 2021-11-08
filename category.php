<?php

  if (isset($_GET['id'])) {
    require "dash/auth/includes/dbh.php";
    $sql = "SELECT * FROM categories WHERE id = :id";
    $handler = $db_conn -> prepare($sql);
    $handler -> bindParam(":id", $_GET['id'], PDO::PARAM_INT);
    $handler -> execute();
    $result = $handler -> fetch();

    if (empty($result)) {
      header("Location: index.php");
      exit;
    }

  } else {
    header("Location: index.php");
    exit;
  }

?>

<!DOCTYPE html>
<html lang="zxx">

  <?php $doc_title = ucwords($result['name'])." - WorldVision"; ?>
  <?php include "_meta.php" ?>

  <body>
    <div class="container-scroller">
      <header id="header">
        <div class="container">
          <!-- partial:../partials/_navbar.html -->
          
          <?php include "partials/_navbar.php" ?>

          <!-- partial -->
        </div>
      </header>
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <div class="text-center">
              <h1 class="text-center text-capitalize mt-5">
                <?= $result['name'] ?>
              </h1>
              <p class="text-secondary fs-15 mb-5 text-capitalize">
                <?= $result['description'] ?>
              </p>
            </div>
            <h5 class="text-muted font-weight-medium mb-3">Recent News</h5>
          </div>
        </div>
        <div class="row">

          <?php include "partials/_category_article.php"; ?>

        </div>
      </div>
    </div>

    <?php include "partials/_footer.php" ?>

  </body>
</html>
