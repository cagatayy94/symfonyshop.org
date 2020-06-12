<?php $view->extend('Admin/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Page Header
            <small>Optional description</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content container-fluid">

<?php echo "<pre>"; print_r($admin); ?>
    </section>
    <!-- /.content -->
</div>
<?php $view['slots']->stop(); ?>