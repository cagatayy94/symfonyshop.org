<?php $view->extend('Admin/default.html.php'); ?>

<?php $view['slots']->start('body'); ?>
<div class="content-wrapper" style="min-height: 956px;">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Metin Ayarları
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
					<div class="box-header with-border">
						<h3 class="box-title">Metinler ve Sözleşmeler</h3>
					</div>
					<form action="<?php echo $this->get('router')->path('admin_settings_strings_update'); ?>" method="post" id="general_update_strings_update_form">
						<div class="row">
						<div class="col-md-12">
						<!-- Custom Tabs -->
						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab_3" data-toggle="tab" aria-expanded="true">Hakkımızda</a></li>
								<li class=""><a href="#tab_1" data-toggle="tab" aria-expanded="false">Kayıt Sözleşmesi</a></li>
								<li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Kullanım Koşulları</a></li>
								<li class=""><a href="#tab_4" data-toggle="tab" aria-expanded="false">Gizlilik Sözleşmesi</a></li>
								<li class=""><a href="#tab_5" data-toggle="tab" aria-expanded="false">Uzak Mesafeli Satış Sözleşmesi</a></li>
								<li class=""><a href="#tab_6" data-toggle="tab" aria-expanded="false">Teslimatlar</a></li>
								<li class=""><a href="#tab_7" data-toggle="tab" aria-expanded="false">İptal & İade & Değişim</a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="tab_3">
									<div class="form-group">
										<label>Hakkımızda Metni</label>
										<textarea class="form-control required" style="resize: none" name="about_us" rows="10"><?php echo $agrementsAndStrings['about_us']; ?></textarea>
									</div>
								</div>
								<div class="tab-pane" id="tab_1">
									<div class="form-group">
										<label>Kayıt Sözleşmesi</label>
										<textarea class="form-control required" style="resize: none" name="sign_up_agreement" rows="10"><?php echo $agrementsAndStrings['sign_up_agreement']; ?></textarea>
									</div>
								</div>
								<!-- /.tab-pane -->
								<div class="tab-pane" id="tab_2">
									<div class="form-group">
										<label>Kullanım Koşulları</label>
										<textarea class="form-control required" style="resize: none" name="terms_of_use" rows="10"><?php echo $agrementsAndStrings['terms_of_use']; ?></textarea>
									</div>
								</div>
								<!-- /.tab-pane -->
								<div class="tab-pane" id="tab_4">
									<div class="form-group">
										<label>Gizlilik Sözleşmesi</label>
										<textarea class="form-control required" style="resize: none" name="confidentiality_agreement" rows="10"><?php echo $agrementsAndStrings['confidentiality_agreement']; ?></textarea>
									</div>
								</div>
								<!-- /.tab-pane -->
								<div class="tab-pane" id="tab_5">
									<div class="form-group">
										<label>Uzak Mesafeli Satış Sözleşmesi</label>
										<textarea class="form-control required" style="resize: none" name="distant_sales_agreement" rows="10"><?php echo $agrementsAndStrings['distant_sales_agreement']; ?></textarea>
									</div>
								</div>
								<!-- /.tab-pane -->
								<div class="tab-pane" id="tab_6">
									<div class="form-group">
										<label>Teslimatlar</label>
										<textarea class="form-control required" style="resize: none" name="deliverables" rows="10"><?php echo $agrementsAndStrings['deliverables']; ?></textarea>
									</div>
								</div>
								<!-- /.tab-pane -->
								<div class="tab-pane" id="tab_7">
									<div class="form-group">
										<label>İptal & İade & Değişim</label>
										<textarea class="form-control required" style="resize: none" name="cancel_refund_change" rows="10"><?php echo $agrementsAndStrings['cancel_refund_change']; ?></textarea>
									</div>
								</div>
								<div class="form-group">
								<?php if ($admin->hasRole('settings_strings_update')): ?>
									<button class="btn btn-primary" type="submit">Kaydet</button>
								<?php endif; ?>
							</div>
								<!-- /.tab-pane -->
								<!-- /.tab-pane -->
							</div>
							<!-- /.tab-content -->
						</div>
						<!-- nav-tabs-custom -->
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
