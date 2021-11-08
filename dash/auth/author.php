
<?php 
    require "includes/startup.php";
    $profileInfo = getProfileInfo($_SESSION['id']);
    if ($profileInfo['role'] == 1) {
        header("Location: .");
        exit;
    }
?>

<!DOCTYPE html>
<html>

    <?php $title = "Users - WorldVision" ?>
    <?php include "_meta.php"; ?>

<body>

    <div class="wrapper">

        <!-- Sidebar  -->

        <?php include("partials/_sidebar.php") ?>

        <!-- Page Content  -->

        <div id="content">
            
            <?php 

                $link = basename(__FILE__);
                include("partials/_nav.php") 
                
            ?>

            <div class="main-content">
                <div class="container-fluid">
                    <h1 class="title">Overview</h1>

                    <?php include "partials/_ovauthor.php" ?>

                    <?php

                        $create = isset($_GET["create"]) ? $_GET["create"] : null;
                        $edit = isset($_GET["edit"]) ? (empty($_GET['edit']) ? null : $_GET['edit']) : null;
                        $edit = is_numeric($edit) ? ($edit > 0 ? $edit : null) : null;

                        $delete = isset($_GET["delete"]) ? (empty($_GET['delete']) ? null : $_GET['delete']) : null;
                        $delete = is_numeric($delete) ? ($delete > 0 ? $delete : null) : null;

                        if (!is_null($edit)) {
                            include "partials/_editauthor.php";
                        } else if (!is_null($create)) {
                            include "partials/_newauthor.php";
                        } else if (!is_null($delete)) {
                            include "partials/_deleteauthor.php";
                        }  else  {
                            include "partials/_listauthor.php";
                        }

                    ?>

                </div>
            </div>
                
        </div>
    </div>


<?php require "_script.php" ?>
<script>
    $(document).ready( function () {
        let table = $('#author_table').DataTable({
            "processing": true,
            "ajax": {
                "type": "POST",
                "url": "includes/fetchauthors.php"
            },
            "columns": [
                {data: 'id'},
                {data: 'name'},
                {data: 'email'},
                {data: 'status'},
                {data: 'roles'},
                {data: 'created_at'}
            ],
            columnDefs: [{
                "targets": 6,
                "defaultContent": "<div class='btn-group d-flex'><button id='edit' class='text-uppercase btn btn-sm btn-primary w-100'>Edit</button><button id='delete' class='text-uppercase btn btn-sm btn-danger w-100'>Delete</button></div>"
            }]
        });

        $('#author_table tbody').on( 'click', 'button#edit', function () {
            let data = table.row( $(this).parents('tr') ).data();
            location.href = "author.php?edit="+data.id;
        });

        $('#author_table tbody').on( 'click', 'button#delete', function () {
            let data = table.row( $(this).parents('tr') ).data();
            location.href = "author.php?delete="+data.id;
        });
    });
</script>
</body>
</html>