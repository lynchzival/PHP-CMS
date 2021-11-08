
<?php require "includes/startup.php" ?>

<!DOCTYPE html>
<html>

    <?php $title = "Article - WorldVision" ?>
    <?php include "_meta.php"; ?>

<body>

    <div class="wrapper">

        <!-- Sidebar  -->

        <?php include("partials/_sidebar.php") ?>

        <!-- Page Content  -->

        <div id="content">
            
            <?php 

                $link = basename(__FILE__);
                include("partials/_nav.php");
                
            ?>

            <div class="main-content">
                <div class="container-fluid">
                    <h1 class="title">Overview</h1>

                    <?php include "partials/_ovpost.php" ?>

                    <?php

                        $create = isset($_GET["create"]) ? $_GET["create"] : null;
                        $edit = isset($_GET["edit"]) ? (empty($_GET['edit']) ? null : $_GET['edit']) : null;
                        $edit = is_numeric($edit) ? ($edit > 0 ? $edit : null) : null;

                        $delete = isset($_GET["delete"]) ? (empty($_GET['delete']) ? null : $_GET['delete']) : null;
                        $delete = is_numeric($delete) ? ($delete > 0 ? $delete : null) : null;

                        if (!is_null($edit)) {
                            $sql = "SELECT * FROM article WHERE id = :id";
                            $handler = $db_conn -> prepare($sql);
                            $handler -> bindParam(":id", $_GET['edit']);
                            $handler -> execute();
                            $result = $handler -> fetch();
                            
                            if (!empty($result)) {
                                include "partials/_editpost.php";
                            }

                        } else if (!is_null($create)) {
                            include "partials/_newpost.php";
                        } else if (!is_null($delete)){
                            include "partials/_deletepost.php";
                        } else {
                            include "partials/_listpost.php";
                        }

                    ?>

                </div>
            </div>
                
        </div>
    </div>


<?php require "_script.php" ?>
<script>
    $(document).ready( function () {

        let table = $('#article_table').DataTable({
            // "bFilter": false,
            "processing": true,
            "ajax": {
                "type": "POST",
                "url": "includes/fetcharticles.php"
            },
            "columns": [
                {data: 'id'},
                {data: 'title'},
                {data: 'name'},
                {data: 'cname'},
                {data: 'date'},
                {data: 'status'},
                {data: 'pins'}
            ],
            "order": [[ 4, "desc" ]],
            columnDefs: [{
                "targets": 7,
                "defaultContent": "<div class='btn-group d-flex'><button id='edit' class='text-uppercase btn btn-sm btn-primary w-100'>Edit</button><button id='delete' class='text-uppercase btn btn-sm btn-danger w-100'>Delete</button></div>"
            },
            { 
                "width": "40%", 
                "targets": 1 
            }]
        });

        $('#article_table tbody').on( 'click', 'button#edit', function () {
            let data = table.row( $(this).parents('tr') ).data();
            location.href = "post.php?edit="+data.id;
        });

        $('#article_table tbody').on( 'click', 'button#delete', function () {
            let data = table.row( $(this).parents('tr') ).data();
            location.href = "post.php?delete="+data.id;
        });

        var useDarkMode = window.matchMedia("(prefers-color-scheme: dark)").matches;

        tinymce.init({
            selector: "textarea#postcontent",
            plugins:
                "print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap emoticons",
            imagetools_cors_hosts: ["picsum.photos"],
            menubar: "file edit view insert format tools table help",
            toolbar:
                "undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media link anchor codesample | ltr rtl",
            toolbar_sticky: true,
            autosave_ask_before_unload: true,
            autosave_interval: "30s",
            autosave_prefix: "{path}{query}-{id}-",
            autosave_restore_when_empty: false,
            autosave_retention: "2m",
            image_advtab: true,
            importcss_append: true,
            file_picker_types: 'image',
            images_upload_url: 'includes/articlescrud.php',
            images_upload_handler: function (blobInfo, success, failure) {
                var xhr, formData;
            
                xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', 'includes/articlescrud.php');
            
                xhr.onload = function() {
                    var json;
                
                    if (xhr.status != 200) {

                        json = JSON.parse(xhr.responseText);
                        if (!json || typeof json.error != 'string') {
                            failure('Invalid JSON: ' + xhr.responseText);
                            return;
                        }

                        failure('HTTP Error: ' + xhr.status + "<br>" + json.error);
                        return;
                    }
                
                    json = JSON.parse(xhr.responseText);
                
                    if (!json || typeof json.location != 'string') {
                        failure('Invalid JSON: ' + xhr.responseText);
                        return;
                    }
                
                    success(json.location);
                };
            
                formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                formData.append('img_insert', true);
                formData.append("<?= isset($_GET['create']) ? 'new' : 'edit'  ?>", 
                "<?= isset($_GET['edit']) ? $_GET['edit'] : '' ?>");
            
                xhr.send(formData);
            },
            height: 600,
            image_caption: true,
            noneditable_noneditable_class: "mceNonEditable",
            toolbar_mode: "sliding",
            contextmenu: "link image imagetools table",
            skin: useDarkMode ? "oxide-dark" : "oxide",
            content_css: useDarkMode ? "dark" : "default",
            content_style: "body { font-family:Helvetica,Arial,sans-serif; font-size:14px }",
            branding: false,
            setup: function(editor) {
                editor.on('init', function(e) {
                    $('#loader').removeClass('d-block').addClass('d-none');
                });
            }
        });
    });
    
</script>
</body>
</html>