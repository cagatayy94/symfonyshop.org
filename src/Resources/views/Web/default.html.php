<!DOCTYPE html>
<html lang="zxx" class="shop">
<head>
    <!-- Basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php $view['slots']->output('title', 'Shop Anasayfa'); ?></title>
    <meta name="keywords" content="HTML5 Template" />
    <meta name="description" content="EZY - Responsive HTML5 Template">
    <meta name="author" content="okler.net">
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
    <!-- Web Fonts  -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,300,400,500,600,700,900%7COpen+Sans:300,400,600,700,800" rel="stylesheet" type="text/css">
    <!-- Vendor CSS -->
    <link rel="stylesheet" href="web/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="web/vendor/font-awesome/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="web/vendor/animate/animate.min.css">
    <link rel="stylesheet" href="web/vendor/linear-icons/css/linear-icons.min.css">
    <link rel="stylesheet" href="web/vendor/owl.carousel/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="web/vendor/owl.carousel/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="web/vendor/magnific-popup/magnific-popup.min.css">
    <link rel="stylesheet" href="web/vendor/nouislider/nouislider.min.css">
    <!-- Theme CSS -->
    <link rel="icon" href="web/img/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="web/css/theme.css">
    <link rel="stylesheet" href="web/css/theme-elements.css">
    <!-- Skin CSS -->
    <link rel="stylesheet" href="web/css/skins/default.css">
    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="web/css/custom.css">
    <!-- toastr CSS -->
    <link rel="stylesheet" href="toastr/toastr.min.css">
    <!-- Head Libs -->
    <script src="web/vendor/modernizr/modernizr.min.js"></script>
</head>
<body>
<div class="body">
    <?php echo $this->render('Web/navigation.html.php'); ?>
    <?php $view['slots']->output('body'); ?>
    <?php echo $view['actions']->render('Web:DefaultController:/footer/data'); ?>
</div>
<!-- Vendor -->
<script src="web/vendor/jquery/jquery.min.js"></script>
<script src="web/vendor/jquery.appear/jquery.appear.min.js"></script>
<script src="web/vendor/jquery.easing/jquery.easing.min.js"></script>
<script src="web/vendor/jquery-cookie/jquery-cookie.min.js"></script>
<script src="web/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="web/vendor/common/common.min.js"></script>
<script src="web/vendor/jquery.validation/jquery.validation.min.js"></script>
<script src="web/vendor/jquery.easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
<script src="web/vendor/jquery.gmap/jquery.gmap.min.js"></script>
<script src="web/vendor/jquery.lazyload/jquery.lazyload.min.js"></script>
<script src="web/vendor/isotope/jquery.isotope.min.js"></script>
<script src="web/vendor/owl.carousel/owl.carousel.min.js"></script>
<script src="web/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
<script src="web/vendor/vide/vide.min.js"></script>
<script src="web/vendor/vivus/vivus.min.js"></script>
<script src="web/vendor/nouislider/nouislider.min.js"></script>
<!-- Theme Base, Components and Settings -->
<script src="web/js/theme.js"></script>
<!-- Current Page Vendor and Views -->
<script src="web/vendor/rs-plugin/js/jquery.themepunch.tools.min.js"></script>
<script src="web/vendor/rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
<!-- Theme Custom -->
<script src="web/js/custom.js"></script>
<!-- Theme Initialization Files -->
<script src="web/js/theme.init.js"></script>
<!-- toastr js -->
<script src="toastr/toastr.min.js"></script>
<?php $view['slots']->output('js'); ?>

<!-- Google Analytics: Change UA-XXXXX-X to be your site's ID. Go to http://www.google.com/analytics/ for more information.
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-12345678-1', 'auto');
    ga('send', 'pageview');
</script>
 -->
</body>
</html>
