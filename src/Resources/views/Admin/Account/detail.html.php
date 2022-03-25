<?php $view->extend('Admin/default.html.php'); ?>

<?php $view['slots']->start('body'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Yönetici Detayı
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->get('router')->path('admin_dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Manager Detail</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="box-title">Manager Detail</h3></div>
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
                                    <form role="form" id="admin_update_form" method="post" action="<?php echo $this->get('router')->path('admin_account_update'); ?>">
                                        <div class="box-body">
                                            <input type="hidden" name="adminId" value="<?php echo $adminDetails['details']['id'];?>">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control required" id="name" name="name" value="<?php echo $adminDetails['details']['name'];?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="surname">Surname</label>
                                                <input type="text" class="form-control required" id="surname" name="surname" value="<?php echo $adminDetails['details']['surname'];?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Mail</label>
                                                <input type="email" class="form-control required" id="email" name="email" value="<?php echo $adminDetails['details']['email'];?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="mobile">Phone</label>
                                                <input type="text" class="form-control required" id="mobile" name="mobile" value="<?php echo $adminDetails['details']['mobile'];?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="profile">Profile</label>
                                                <select name="profile_id" class="form-control required">
                                                    <option value="0">Select</option>
                                                    <?php foreach ($adminProfileList as $key => $adminProfile):?>
                                                        <option <?php echo $key == $adminDetails['details']['profile_id'] ? 'selected="selected"' : '' ?>value="<?php echo $key ?>"><?php echo $adminProfile['name']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="password">Password (Blank left if you dont wanna change)</label>
                                                <input type="password" class="form-control" id="password" name="password" autocomplete="off">
                                            </div>
                                            <div class="form-group">
                                                <label for="passwordRepeat">Password Again</label>
                                                <input type="password" class="form-control" id="passwordRepeat" name="password_repeat" autocomplete="off">
                                            </div>
                                            <div class="form-group">
                                                <?php if ($admin->hasRole('account_update')):?>
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                <?php endif; ?>
                                                <?php if ($admin->hasRole('account_delete')):?>
                                                    <button type="button" id="admin_account_delete" data-href="<?php echo $this->get('router')->path('admin_account_delete', [ 'adminId' => $adminDetails['details']['id']]); ?>" class="btn btn-danger">Delete</button>
                                                <?php endif; ?>
                                            </div>
                                            <div class="form-group">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="box box-primary">
                                    <div class="box-header">
                                        <div class="col-md-12">
                                            <table class="table table-striped">
                                                <tbody>
                                                    <tr>
                                                        <th>Profile</th>
                                                        <td><?php echo $adminDetails['details']['profile_name']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Permissions</th>
                                                        <td>
                                                        <?php foreach ($adminDetails['allPermissions'] as $key => $permission):
                                                            echo $permission['name'];?>
                                                            <br> 
                                                        <?php endforeach; ?>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
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