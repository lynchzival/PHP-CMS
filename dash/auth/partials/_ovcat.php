<?php

require "includes/dbh.php";

$sql = "SELECT IFNULL(COUNT(*), 0) total, (
    SELECT IFNULL(name, 'TBD') FROM categories WHERE id = (
        SELECT category_id FROM article 
        GROUP BY category_id 
        ORDER BY COUNT(*) DESC LIMIT 1
    )
) popular_cat
FROM categories;";

$handler = $db_conn -> query($sql);
$result = $handler -> fetch();

?>

<div class="row">
    <div class="col-12 col-sm-6 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="card-header">
                    <i class="fas fa-box-open"></i>
                    <div class="card-info">
                        <h5>Total Categories</h5>
                        <h4><?= $result['total'] ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="card-header">
                    <i class="fas fa-box-open"></i>
                    <div class="card-info">
                        <h5>Most Published</h5>
                        <h4 class="text-capitalize"><?= $result['popular_cat'] ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>