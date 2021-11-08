<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <!-- Primary Meta Tags -->
    <title><?= isset($doc_title) ? $doc_title : "" ?></title>
    <meta name="title" content="Vision World">
    <meta name="description" content="Entertainment news, sport, Tech and a whole lot more. Vision World informs, educates and entertains - wherever you are, whatever your age.">

    <?php if(str_contains($_SERVER['SCRIPT_NAME'], "article")): ?>
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $meta['url'] ?>">
    <meta property="og:title" content="<?= $meta['title'] ?>">
    <meta property="og:description" content='<?= $meta['description'] ?>'>
    <meta property="og:image" content="<?= $meta['image'] ?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= $meta['url'] ?>">
    <meta property="twitter:title" content="<?= $meta['title'] ?>">
    <meta property="twitter:description" content='<?= $meta['description'] ?>'>
    <meta property="twitter:image" content="<?= $meta['image'] ?>">

    <link rel="stylesheet" href="dash/auth/js/tinymce/skins/content/default/content.min.css">
    <link rel="stylesheet" href="./assets/vendors/prismjs/prism.min.css">
    <script src="./assets/vendors/prismjs/prism.min.js"></script>

    <?php endif; ?>
    
    <!-- plugin css for this page -->
    <link rel="stylesheet" href="./assets/vendors/mdi/css/materialdesignicons.min.css" />
    <link rel="stylesheet" href="./assets/vendors/aos/dist/aos.css/aos.css" />
    <link rel="stylesheet" href="./assets/vendors/owl.carousel/dist/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="./assets/vendors/owl.carousel/dist/assets/owl.theme.default.min.css" />
    <!-- End plugin css for this page -->
    <link rel="shortcut icon" href="assets/images/favicon.png" />
    <!-- inject:css -->
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/custom.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
    <!-- endinject -->

    <!-- inject:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- plugin js for this page -->
    <script src="./assets/vendors/owl.carousel/dist/owl.carousel.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- Custom js for this page-->
    <script src="./assets/js/demo.js"></script>
    <!-- End custom js for this page-->
</head>