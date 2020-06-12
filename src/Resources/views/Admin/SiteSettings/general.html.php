<?php $view->extend('Admin/default.html.php'); ?>

<?php $view['slots']->start('body'); ?>
<div class="content-wrapper" style="min-height: 956px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Genel Site Ayarları
      </h1>
      <ol class="breadcrumb">
        <li class="active">Site Ayarları</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="row">
              <form role="form" method="post" action="<?php echo $this->get('router')->path('admin_settings_general_update') ?>" id="site-settings-form">
                <div class="col-md-6">
                  <div class="box-header with-border">
                    <h3 class="box-title">Anasayfa Metinleri</h3>
                  </div>
                  <!-- /.box-header -->
                  <!-- form start -->
                  <div class="box-body">
                    <div class="form-group">
                      <label for="siteKeywords">Site Anahtar Kelimeleri (virgül ile ayırarak yazınız)</label>
                      <input type="text" class="form-control required" name="keywords" value="<?php echo $siteSettings['keywords'];?>">
                    </div>
                    <div class="form-group">
                      <label for="siteKeywords">Site Copyright Metni</label>
                      <input type="text" class="form-control required" name="copyright" value="<?php echo $siteSettings['copyright'];?>"> 
                    </div>
                    <div class="form-group">
                      <label for="siteKeywords">Site Email Adresi</label>
                      <input type="text" class="form-control required" name="mail" value="<?php echo $siteSettings['mail'] ?>">
                    </div>
                    <div class="form-group">
                      <label for="siteWebAddress">Site Web Adresi</label>
                      <input type="text" class="form-control required" name="link" value="<?php echo $siteSettings['link'] ?>">
                    </div>
                    <div class="form-group">
                      <label for="siteAddress">Site Açık Adresi</label>
                      <input type="text" class="form-control required" name="address" value="<?php echo $siteSettings['address'] ?>">
                    </div>
                    <div class="form-group">
                      <label for="sitePhone">Site Telefonu</label>
                      <input type="text" class="form-control required" name="phone" value="<?php echo $siteSettings['phone'] ?>">
                    </div>
                    <div class="form-group">
                      <label for="siteFooterText">SiteFooterMetni</label>
                      <textarea style="resize: none;" rows="3" class="form-control" name="footer_text"><?php echo $siteSettings['footer_text'] ?></textarea>
                    </div>
                  </div>
                  <!-- /.box-body -->
                </div>
                <div class="col-md-6">
                  <div class="box-header with-border">
                    <h3 class="box-title">Site Genel Ayarları</h3>
                  </div>
                  <div class="box-body">
                    <div class="form-group" >
                      <label for="siteName">Site İsmi</label>
                      <input type="text" class="form-control required" name="name" value="<?php echo $siteSettings['name'];?>">
                    </div>
                    <div class="form-group">
                      <label for="siteTitle">Site Başlığı</label>
                      <input type="text" class="form-control required" name="title" value="<?php echo $siteSettings['title'];?>">
                    </div>
                    <div class="form-group">
                      <label for="siteDescription">Site Açıklaması</label>
                      <input type="text" class="form-control required" name="description" value="<?php echo $siteSettings['description'];?>">
                    </div>
                  <div class="box-header with-border">
                    <h3 class="box-title">Sosyal Medya Bağlantıları</h3>
                  </div>
                  <div class="box-body">
                    <h5>Lütfen sadece adresten sonraki kısmı yazınız. Boş bırakırsanız anasayfada görünmeyecekler.</h5>
                    <div style="margin-top: 5px" class="input-group">
                      <span class="input-group-addon"><i class="fa fa-facebook"></i> www.facebook.com/</span>
                      <input name="facebook" type="text" class="form-control" value="<?php echo $siteSettings['facebook'] ?>" placeholder="username">
                    </div>
                    <div style="margin-top: 5px" class="input-group">
                      <span class="input-group-addon"><i class="fa fa-instagram"></i> www.instagram.com/</span>
                      <input name="instagram" type="text" class="form-control" value="<?php echo $siteSettings['instagram'] ?>" placeholder="username">
                    </div>
                    <div style="margin-top: 5px" class="input-group">
                      <span class="input-group-addon"><i class="fa fa-linkedin"></i> www.linkedin.com/</span>
                      <input name="linkedin" type="text" class="form-control" value="<?php echo $siteSettings['linkedin'] ?>" placeholder="username">
                    </div>
                    <div style="margin-top: 5px" class="input-group">
                      <span class="input-group-addon"><i class="fa fa-twitter"></i> www.twitter.com/</span>
                      <input name="twitter" type="text" class="form-control" value="<?php echo $siteSettings['twitter'] ?>" placeholder="username">
                    </div>
                    <div style="margin-top: 5px" class="input-group">
                      <span class="input-group-addon"><i class="fa fa-youtube"></i> www.youtube.com/</span>
                      <input name="youtube" type="text" class="form-control" value="<?php echo $siteSettings['youtube'] ?>" placeholder="username">
                    </div>
                    <div style="margin-top: 5px" class="input-group">
                      <span class="input-group-addon"><i class="fa fa-pinterest"></i> www.pinterest.com/</span>
                      <input name="pinterest" type="text" class="form-control" value="<?php echo $siteSettings['pinterest'] ?>" placeholder="username">
                    </div>
                  </div>
                  </div>
                </div>
                <div class="box-footer">
                  <?php if($admin->hasRole('settings_general_update')): ?>
                    <button type="submit" class="btn btn-primary btn-block">Kaydet</button>
                  <?php endif; ?>
                </div>
              </form>
            </div>
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
<?php $view['slots']->stop(); ?>
