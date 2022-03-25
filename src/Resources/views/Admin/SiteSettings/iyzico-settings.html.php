<?php $view->extend('Admin/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
<div class="content-wrapper" style="min-height: 956px;">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			İyzico Settings
		</h1>
		<ol class="breadcrumb">
			<li class="active">İyzico Settings</li>
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
						<form role="form" method="post" action="<?php echo $this->get('router')->path('admin_settings_iyzico_update') ?>" id="iyzico-settings-form">
							<div class="col-md-6">
								<div class="box-header with-border">
									<h3 class="box-title">İyzico Settings</h3>
								</div>
								<!-- /.box-header -->
								<!-- form start -->
								<div class="box-body">
									<div class="form-group">
										<label for="iyzico_api_key">Iyzico API KEY</label>
										<input type="text" class="form-control required" name="iyzico_api_key" value="<?php echo $iyzicoSettings['iyzico_api_key'] ?>">
									</div>
									<div class="form-group">
										<label for="iyzico_secret_key">Iyzico SECRET KEY</label>
										<input type="text" class="form-control required" name="iyzico_secret_key" value="<?php echo $iyzicoSettings['iyzico_secret_key'] ?>"> 
									</div>
									<div class="form-group">
										<label for="iyzico_base_url">Iyzico BASE URL</label>
										<input type="text" class="form-control required" name="iyzico_base_url" value="<?php echo $iyzicoSettings['iyzico_base_url'] ?>">
									</div>
									<div class="form-group">
										<?php if($admin->hasRole('iyzico_settings_update')): ?>
											<button type="submit" class="btn btn-primary btn-block">Save</button>
										<?php endif; ?>
									</div>
								</div>
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
