<?php $view->extend('Admin/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
<div class="content-wrapper" style="min-height: 956px;">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Kargo Listesi
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
						<h3 class="box-title">Kargo Listesi</h3>
						<div class="box-tools">
							<div class="input-group input-group-sm hidden-xs" style="width: 150px;">
								<div class="input-group-btn">
									<?php if($admin->hasRole('cargo_create')): ?>
										<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-new-cargo-modal"><i class="fa fa-plus"></i> Yeni Ekle</button>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body table-responsive no-padding">
						<table class="table table-hover">
							<tbody>
								<?php foreach ($cargoList as $value): ?>
									<tr id="<?php echo $value['id']; ?>">
										<td><?php echo $value['name']; ?></td>
										<?php if ($admin->hasRole('cargo_update')):?>
										<td>
											<button data-update-id="<?php echo $value['id'] ?>" class="btn btn-primary update-cargo"><i class="fa fa-edit"></i> Düzenle</button>
										</td>
										<?php endif;?>
										<?php if ($admin->hasRole('cargo_delete')):?>
										<td>
											<button data-delete-url="<?php echo $this->get('router')->path('admin_settings_cargo_delete', ['cargoId' => $value['id']]); ?>" class="btn btn-danger delete-cargo"><i class="fa fa-remove"></i> Sil</button>
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
<?php if ($admin->hasRole('cargo_create')):?>
<div class="modal fade" id="add-new-cargo-modal" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="<?php echo $this->get('router')->path('admin_settings_cargo_create'); ?>" method="post" id="add-new-cargo-form">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span></button>
					<h4 class="modal-title">Yeni Kargo Firması Ekle</h4>
				</div>
				<div class="modal-body">                  
					<div class="form-group">
							<label for="name">Kargo Firması İsmi</label>
							<input type="text" class="form-control required" name="name" value="">
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

<?php if ($admin->hasRole('cargo_update')):?>
<div class="modal fade" id="update-cargo-modal" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="<?php echo $this->get('router')->path('admin_settings_cargo_update'); ?>" method="post" id="update-cargo-form" enctype="multipart/form-data">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span></button>
					<h4 class="modal-title">Kargo Firması Güncelle</h4>
				</div>
				<div class="modal-body">  
				<input type="hidden" name="id" id="id" value="">                
					<div class="form-group">
							<label for="name">Kargo Firması İsmi</label>
							<input type="text" class="form-control required" id="name" name="name" value="">
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
