<?php $view->extend('Admin/default.html.php'); ?>

<?php $view['slots']->start('body'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Yönetici Listesi
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->get('router')->path('admin_dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Yönetici Listesi</li>
        </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header">
                <div class="row">
                    <div class="col-md-10"><h3 class="box-title">Yöneticiler</h3></div>
                    <div class="col-md-2"> 
                        <?php if (true):?>
                        <button type="button" data-toggle="modal" data-target="#add-new-admin-modal" class="btn btn-block btn-info btn-flat"><i class="fa fa-fw fa-plus"></i> Yeni Ekle </button>
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
                    <th>İsim</th>
                    <th>Email</th>
                    <th>Telefon</th>
                    <th>Profil</th>
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
                                <?php if (true): ?>
                                    <a style="padding: 0;" href="<?php echo $this->get('router')->path('admin_account_detail', ['adminId' => $adminAccount['id']]) ?>" class="btn"><i class="fa fa-edit"></i> Detay </a>
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
      <?php if (true): ?>
        <div class="modal fade" id="add-new-admin-modal">
          <div class="modal-dialog">
            <div class="modal-content">
                <form role="form" id="create_new_admin_form" method="POST" action="<?php echo $this->get('router')->path('admin_account_create'); ?>">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Yeni Yönetici Hesap Oluştur</h4>
                      </div>
                      <div class="modal-body">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="email">E-posta </label>
                                    <input type="email" class="form-control required" name="email" id="email" placeholder="Email yaz">
                                </div>
                                <div class="form-group">
                                    <label for="name">İsim</label>
                                    <input type="text" class="form-control required" name="name" placeholder="İsim">
                                </div>
                                <div class="form-group">
                                    <label for="surname">Soyisim</label>
                                    <input type="text" class="form-control required" name="surname" placeholder="Soyisim">
                                </div>
                                <div class="form-group">
                                    <label for="mobile">Telefon</label>
                                    <input type="text" class="form-control required" name="mobile" placeholder="Telefon">
                                </div>
                                <div class="form-group">
                                    <label for="profile">Profil</label>
                                    <select name="profile_id" class="form-control required">
                                        <option value="0">Seçiniz</option>
                                        <?php foreach ($adminProfileList as $key => $adminProfile): ?>
                                        <option value="<?php echo $key; ?>"><?php echo $adminProfile['name']; ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="password1">Parola</label>
                                    <input type="password" class="form-control required" name="password" placeholder="Parola">
                                </div>
                                <div class="form-group">
                                    <label for="password2">Parola Tekrar</label>
                                    <input type="password" class="form-control required" name="password_repeat" placeholder="Parola Tekrar">
                                </div>
                            </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kapat</button>
                        <button type="submit" class="btn btn-primary">Kaydet</button>
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
