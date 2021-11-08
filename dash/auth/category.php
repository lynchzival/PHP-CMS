
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

    <?php $title = "Category - WorldVision" ?>
    <?php include "_meta.php"; ?>

<body>

    <div class="wrapper">

        <!-- Sidebar  -->

        <?php include("partials/_sidebar.php") ?>

        <!-- Page Content  -->

        <div id="content">
            
            <?php 

                include("partials/_nav.php") 
            
            ?>

            <div class="main-content">
                <div class="container-fluid">
                    <h1 class="title">Overview</h1>

                    <?php include "partials/_ovcat.php" ?>

                    <h1 class="title">Category</h1>

                    <div class="row">
                        <?php include "partials/_listcat.php" ?>

                        <?php 
                        
                            $edit = isset($_GET["edit"]) ? (empty($_GET['edit']) ? null : $_GET['edit']) : null;
                            $edit = is_numeric($edit) ? ($edit > 0 ? $edit : null) : null;

                            $delete = isset($_GET["delete"]) ? (empty($_GET['delete']) ? null : $_GET['delete']) : null;
                            $delete = is_numeric($delete) ? ($delete > 0 ? $delete : null) : null;

                            if(!is_null($edit)){
                                include "partials/_editcat.php";
                            } else if (!is_null($delete)) {
                                include "partials/_deletecat.php";
                            } else {
                                include "partials/_newcat.php";
                            }
                        
                        ?>
                    </div>

                </div>
            </div>
                
        </div>
    </div>

<?php require "_script.php" ?>
<script>
    $(document).ready( function () {

        let table = $('#cat_table').DataTable({
            // "bFilter": false,
            "processing": true,
            "ajax": {
                "type": "POST",
                "url": "includes/fetchcategories.php"
            },
            "columns": [
                {data: 'id'},
                {data: 'name'},
                {data: 'description'},
                {data: 'total_article'}
            ],
            columnDefs: [{
                "targets": 4,
                "defaultContent": "<div class='btn-group d-flex'><button id='edit' class='text-uppercase btn btn-sm btn-primary w-100'>Edit</button><button id='delete' class='text-uppercase btn btn-sm btn-danger w-100'>Delete</button></div>"
            }]
        });

        $('#cat_table tbody').on( 'click', 'button#edit', function () {
            let data = table.row( $(this).parents('tr') ).data();
            location.href = "category.php?edit="+data.id;
        });

        $('#cat_table tbody').on( 'click', 'button#delete', function () {
            let data = table.row( $(this).parents('tr') ).data();
            location.href = "category.php?delete="+data.id;
        });
    });
</script>
</body>
</html>