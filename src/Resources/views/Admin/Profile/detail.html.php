<?php $view->extend('Admin/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Yönetici Profil Detayı
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->get('router')->path('admin_dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Yönetici Profil Detayı</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="box-title">Yönetici Profil Detayı</h3></div>
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
                                    <form role="form" id="admin_profile_update_form" method="post" action=" <?php echo $this->get('router')->path('admin_profile_update') ?>">
                                        <div class="box-body">
                                            <input type="hidden" name="profileId" value="<?php echo $profileDetail['id'] ?>">
                                            <div class="form-group">
                                                <label for="name">Profil ismi</label>
                                                <input type="text" class="form-control required" id="name" name="name" value="<?php echo $profileDetail['name'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <h4>İzinler</h4>
                                                <?php foreach ($allPermissions as $permission): ?>
                                                     <div class="form-check">
                                                        <label>
                                                            <input type="checkbox" 
                                                            <?php foreach ($profileDetail['permissions'] as $key => $adminPermission): ?>
                                                                <?php if ($key == $permission['id']) {
                                                                    echo "checked='checked'";}?>
                                                            <?php endforeach; ?>
                                                         class="form-check-input" name="permissions[]" value="<?php echo $permission['id'] ?>">
                                                        <?php echo $permission['name']; ?></label>
                                                    </div>
                                                    
                                                <?php endforeach ?>
                                            </div>
                                        </div>
                                        <div class="box-footer">
                                            <?php if ($admin->hasRole('profile_update')):?>
                                                <button type="submit" class="btn btn-primary">Kaydet</button>
                                            <?php endif; ?>
                                            <?php if ($admin->hasRole('profile_delete')):?>
                                                <button type="button" id="admin_profile_delete" data-href="<?php echo $this->get('router')->path('admin_profile_delete', ['profileId' => $profileDetail['id']]) ?>" data-profile-id="<?php echo $profileDetail['id'] ?>" class="btn btn-danger">Sil</button>
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