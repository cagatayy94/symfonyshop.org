<?php $view->extend('Admin/default.html.php'); ?>

<?php $view['slots']->start('body'); ?>
<div class="content-wrapper" style="min-height: 956px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Banner Ayarları
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
              <h3 class="box-title">Bannerlar</h3>
              <div class="box-tools">
                <div class="input-group input-group-sm hidden-xs" style="width: 150px;">
                  <div class="input-group-btn">
                    <?php if($admin->hasRole('add_banner')): ?>
                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-new-banner-modal"><i class="fa fa-plus"></i> Yeni Ekle</button>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody>
                  <tr>
                    <th>Logo</th>
                    <th>Banner İsmi</th>
                    <th>Gösterim Adedi</th>
                    <?php if ($admin->hasRole('update_banner')):?>
                    <th></th>
                    <?php endif; ?>
                    <?php if ($admin->hasRole('delete_banner')):?>
                    <th></th>
                    <?php endif; ?>
                  </tr>
                  <?php foreach ($banners as $value): ?>
                    <tr id="<?php echo $value['id']; ?>">
                      <td>
                        <img src="web/img/banner/<?php echo $value['pic']; ?>" width="150" height="180">
                      </td>
                      <td><?php echo $value['name']; ?></td>
                      <td><?php echo $value['number_of_show']; ?></td>
                      <?php if ($admin->hasRole('update_banner')):?>
                      <td>
                        <button data-update-id="<?php echo $value['id'] ?>" class="btn btn-primary update-banner"><i class="fa fa-edit"></i> Düzenle</button>
                      </td>
                      <?php endif;?>
                      <?php if ($admin->hasRole('delete_banner')):?>
                      <td>
                        <button data-delete-url="<?php echo $this->get('router')->path('admin_settings_banner_delete', ['bannerId' => $value['id']]); ?>" class="btn btn-danger delete-banner"><i class="fa fa-remove"></i> Sil</button>
                      </td>
                      <?php endif;?>
                    </tr>
                  <?php endforeach ?>
              </tbody>
            </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
<?php if ($admin->hasRole('add_banner')):?>
<div class="modal fade" id="add-new-banner-modal" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?php echo $this->get('router')->path('admin_settings_banner_create'); ?>" method="post" id="add-new-banner-form" enctype="multipart/form-data">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
          <h4 class="modal-title">Yeni Banner Ekle</h4>
        </div>
        <div class="modal-body">                  
          <div class="form-group">
              <label for="name">Banner İsmi</label>
              <input type="text" class="form-control required" name="name" value="">
          </div>
          <div class="form-group">
              <label for="img">Görsel 1500x1800</label>
              <input name="img" type="file" class="form-control required">
          </div>                    
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Vazgeç</button>
          <button type="submit" class="btn btn-primary">Kaydet</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<?php endif; ?>

<?php if ($admin->hasRole('update_banner')):?>
<div class="modal fade" id="update-banner-modal" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?php echo $this->get('router')->path('admin_settings_banner_update'); ?>" method="post" id="update-banner-form" enctype="multipart/form-data">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
          <h4 class="modal-title">Banner Güncelle</h4>
        </div>
        <div class="modal-body">  
        <input type="hidden" name="id" id="id" value="">                
          <div class="form-group">
              <label for="name">Banner İsmi</label>
              <input type="text" class="form-control required" id="name" name="name" value="">
          </div>
          <div id="img" class="form-group">
            
          </div>
          <div id="uploadImg" hidden="hidden" class="form-group">
              <label for="uploadImg">Logo 1500x1800</label>
              <input name="uploadImg" type="file" id="uploadImg" class="form-control">
          </div>                    
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Vazgeç</button>
          <button type="submit" class="btn btn-primary">Kaydet</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<?php endif; ?>
<?php $view['slots']->stop(); ?>
