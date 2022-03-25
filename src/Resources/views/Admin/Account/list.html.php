<?php $view->extend('Admin/default.html.php'); ?>

<?php $view['slots']->start('body'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Manager List
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->get('router')->path('admin_dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Manager List</li>
        </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header">
                <div class="row">
                    <div class="col-md-10"><h3 class="box-title">Managers</h3></div>
                    <div class="col-md-2"> 
                        <?php if ($admin->hasRole('account_create')):?>
                        <button type="button" data-toggle="modal" data-target="#add-new-admin-modal" class="btn btn-block btn-info btn-flat"><i class="fa fa-fw fa-plus"></i> Add New </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <table class="table table-striped">
                <tbody>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Profile</th>
                    <th></th>
                </tr>
                <?php if (count($adminAccountList)): ?>
                    <?php foreach ($adminAccountList as $key => $adminAccount): ?>
                        <tr>
                            <td><?php echo $adminAccount['id']; ?></td>
                            <td><?php echo $adminAccount['name']. ' ' .$adminAccount['surname']; ?></td>
                            <td><?php echo $adminAccount['email']; ?></td>
                            <td><?php echo $adminAccount['mobile']; ?></td>
                            <td><?php echo $adminAccount['profile_name']; ?></td>
                            <td>
                                <?php if ($admin->hasRole('account_detail_show')): ?>
                                    <a style="padding: 0;" href="<?php echo $this->get('router')->path('admin_account_detail', ['adminId' => $adminAccount['id']]) ?>" class="btn"><i class="fa fa-edit"></i> Detail </a>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php endif; ?>
              </tbody></table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.row -->
      <?php if ($admin->hasRole('account_create')): ?>
        <div class="modal fade" id="add-new-admin-modal">
          <div class="modal-dialog">
            <div class="modal-content">
                <form role="form" id="create_new_admin_form" method="POST" action="<?php echo $this->get('router')->path('admin_account_create'); ?>">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Add new admin account</h4>
                      </div>
                      <div class="modal-body">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="email">E-mail </label>
                                    <input type="email" class="form-control required" name="email" id="email" placeholder="Email yaz">
                                </div>
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control required" name="name" placeholder="İsim">
                                </div>
                                <div class="form-group">
                                    <label for="surname">Surname</label>
                                    <input type="text" class="form-control required" name="surname" placeholder="Soyisim">
                                </div>
                                <div class="form-group">
                                    <label for="mobile">Phone</label>
                                    <input type="text" class="form-control required" name="mobile" placeholder="Telefon">
                                </div>
                                <div class="form-group">
                                    <label for="profile">Profile</label>
                                    <select name="profile_id" class="form-control required">
                                        <option value="0">Select</option>
                                        <?php foreach ($adminProfileList as $key => $adminProfile): ?>
                                        <option value="<?php echo $key; ?>"><?php echo $adminProfile['name']; ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="password1">Password</label>
                                    <input type="password" class="form-control required" name="password" placeholder="Parola">
                                </div>
                                <div class="form-group">
                                    <label for="password2">Password Again</label>
                                    <input type="password" class="form-control required" name="password_repeat" placeholder="Parola Tekrar">
                                </div>
                            </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Dismiss</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                      </div>
                </form>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
      <?php endif ?>
    </section>
    <!-- /.content -->
</div>
<?php $view['slots']->stop(); ?>
