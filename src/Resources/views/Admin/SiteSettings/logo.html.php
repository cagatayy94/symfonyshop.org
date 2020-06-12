<?php $view->extend('Admin/default.html.php'); ?>

<?php $view['slots']->start('body'); ?>
<div class="content-wrapper" style="min-height: 956px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Logo ve Favicon
      </h1>
      <ol class="breadcrumb">
        <li class="active">Site Ayarları</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <!-- /.row -->
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Logo ve Favicon</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <form action="<?php echo $this->get('router')->path('admin_settings_logo_update'); ?>" method="post" id="update_logo_form" enctype="multipart/form-data">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th colspan="2">
                        <h5>
                          Logolarınızı ve faviconlarınızı güncelledikten sonra tarayıcı geçmişinizi silmeden değişiklikleri görüntüleyemeyebilirsiniz. En iyi görüntü için yüklediğiniz logoların 256 × 64 boyutunda faviconun ise 16x16px boyutunda olduğundan emin olun.
                        </h3>
                      </th>
                    </tr>
                    <tr>
                      <th>Mevcut</th>
                      <th>Yeni Yükle</th>
                    </tr>
                  </thead>
                    <tbody>
                      <tr>
                          <td style="background: repeating-linear-gradient(45deg,#606dbc,#606dbc 10px,#465298 10px,#465298 20px);"><img height="16" src="web/img/logo-shop.png"></td>
                          <td>
                            <div class="form-group">
                              <label for="dark_logo">Dark Logo Yükle</label>
                              <input name="dark_logo" type="file" id="dark_logo">
                              <p class="help-block">En iyi sonuç için logonun 256×64 boyutlarında olduğundan emin olun. </p>
                            </div>
                          </td>
                      </tr>
                      <tr>
                        <td style="background: repeating-linear-gradient(45deg,#606dbc,#606dbc 10px,#465298 10px,#465298 20px);"><img height="16" src="web/img/logo-shop-light.png"></td>                    
                          <td>
                            <div class="form-group">
                              <label for="light_logo">Light Logo Yükle</label>
                              <input name="light_logo" type="file" id="light_logo">
                              <p class="help-block">En iyi sonuç için logonun 256×64 boyutlarında olduğundan emin olun. </p>
                            </div>
                          </td>
                      </tr>
                      <tr>
                        <td style="background: repeating-linear-gradient(45deg,#606dbc,#606dbc 10px,#465298 10px,#465298 20px);"><img height="16" src="web/img/favicon.ico"></td>                    
                          <td>
                            <div class="form-group">
                              <label for="favicon">Favicon Yükle</label>
                              <input name="favicon" type="file" id="favicon">
                              <p class="help-block">En iyi sonuç için favioconun 16x16 boyutlarında olduğundan emin olun.</p>
                            </div>
                          </td>
                      </tr>
                    </tbody>
                  <tr>
                    <td colspan="2">
                      <?php if ($admin->hasRole('update_logo')): ?>
                        <button style="float: right;" type="submit" class="btn btn-primary">Güncelle</button>
                      <?php endif ?>
                    </td>
                  </tr>
                </table>
              </form>
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

