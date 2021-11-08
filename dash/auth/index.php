
<?php require "includes/startup.php" ?>

<!DOCTYPE html>
<html>

    <?php $title = "Dashboard - WorldVision" ?>
    <?php include "_meta.php"; ?>
    
<body>

    <div class="wrapper">

        <!-- Sidebar  -->

        <?php include("partials/_sidebar.php") ?>

        <!-- Page Content  -->

        <div id="content">
            
            <?php 

                $index = true;
                include("partials/_nav.php");
                
            ?>

            <div class="main-content">
                <div class="container-fluid">
                    <h1 class="title">Overview</h1>

                    <?php include "partials/_ovindex.php" ?>

                    <h1 class="title">Brief Report</h1>
                    
                    <?php include "partials/_briefreport.php" ?>

                </div>
            </div>
                
        </div>
    </div>

<?php require "_script.php" ?>
<script>
    let table = $('#brief_table').DataTable({
        // "bFilter": false,
        "processing": true,
        "ajax": {
            "type": "POST",
            "url": "includes/fetchcategories.php"
        },
        "columns": [
            {data: 'name'},
            {data: 'description'},
            {data: 'total_article'}
        ],
        "order": [[ 2, "desc" ]]
    });
</script>
</body>
</html>