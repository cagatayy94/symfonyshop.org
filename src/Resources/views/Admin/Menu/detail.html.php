<?php $view->extend('Admin/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Menü Detayı
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->get('router')->path('admin_dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Menü Detayı</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="box-title">Menü Detayı</h3></div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <!-- general form elements -->
                                <div class="box box-primary">
                                    <!-- /.box-header -->
                                    <!-- form start -->
                                    <form role="form" id="menu_update_form" method="post" action=" <?php echo $this->get('router')->path('admin_menu_update') ?>">
                                        <div class="box-body">
                                            <input type="hidden" name="menuId" value="<?php echo $menuId ?>">
                                            <div class="form-group">
                                                <label for="name">Menü ismi</label>
                                                <input type="text" class="form-control required" id="name" name="name" value="<?php echo $menuDetail['name'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <h4>Kategoriler</h4>
                                                <?php foreach ($categories as $key => $category): ?>
                                                     <div class="form-check">
                                                        <label>
                                                            <input type="checkbox" <?php echo array_key_exists($key, $menuDetail['category']) ? 'checked' : ''; ?> class="form-check-input" name="category[]" value="<?php echo $key ?>">
                                                        <?php echo $category['name']; ?></label>
                                                    </div>
                                                <?php endforeach ?>
                                            </div>
                                        </div>
                                        <div class="box-footer">
                                            <?php if (true):?>
                                                <button type="submit" class="btn btn-primary">Kaydet</button>
                                            <?php endif; ?>
                                            <?php if (true):?>
                                                <button type="button" id="menu_delete" data-href="<?php echo $this->get('router')->path('admin_menu_delete', ['menuId' => $menuId]) ?>" data-menu-id="<?php echo $menuId ?>" class="btn btn-danger">Sil</button>
                                            <?php endif; ?>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<?php $view['slots']->stop(); ?>