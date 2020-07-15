<?php $view->extend('Admin/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
<div class="content-wrapper" style="min-height: 956px;">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Sık Sorulan Sorular
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
						<h3 class="box-title">Sık Sorulan Sorular</h3>
						<div class="box-tools">
							<div class="input-group input-group-sm hidden-xs" style="width: 150px;">
								<div class="input-group-btn">
									<?php if($admin->hasRole('faq_create')): ?>
										<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-new-faq-modal"><i class="fa fa-plus"></i> Yeni Ekle</button>
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
									<th>Soru</th>
									<th>Cevap</th>
									<?php if ($admin->hasRole('faq_update')):?>
									<th></th>
									<?php endif; ?>
									<?php if ($admin->hasRole('faq_delete')):?>
									<th></th>
									<?php endif; ?>
								</tr>
								<?php foreach ($faqs as $value): ?>
									<tr id="<?php echo $value['id']; ?>">
										<td><?php echo $value['question']; ?></td>
										<td><?php echo $value['answer']; ?></td>
										<?php if ($admin->hasRole('faq_update')):?>
										<td>
											<button data-update-id="<?php echo $value['id'] ?>" class="btn btn-primary update-faq"><i class="fa fa-edit"></i> Düzenle</button>
										</td>
										<?php endif;?>
										<?php if ($admin->hasRole('faq_delete')):?>
										<td>
											<button data-delete-url="<?php echo $this->get('router')->path('admin_settings_faq_delete', ['faqId' => $value['id']]); ?>" class="btn btn-danger delete-faq"><i class="fa fa-remove"></i> Sil</button>
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
<?php if ($admin->hasRole('faq_create')):?>
<div class="modal fade" id="add-new-faq-modal" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="<?php echo $this->get('router')->path('admin_settings_faq_create'); ?>" method="post" id="add-new-faq-form">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span></button>
					<h4 class="modal-title">Yeni Sık Sorulan Soru Ekle</h4>
				</div>
				<div class="modal-body">                  
					<div class="form-group">
							<label for="question">Soru</label>
							<textarea name="question" class="form-control required" rows="4"></textarea>
					</div>
					<div class="form-group">
							<label for="answer">Cevap</label>
							<textarea name="answer" class="form-control required" rows="4"></textarea>
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

<?php if ($admin->hasRole('faq_update')):?>
<div class="modal fade" id="update-faq-modal" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="<?php echo $this->get('router')->path('admin_settings_faq_update'); ?>" method="post" id="faq-update-form">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span></button>
					<h4 class="modal-title">Sorulan Soru Güncelle</h4>
				</div>
				<div class="modal-body">                  
					<div class="form-group">
							<input type="hidden" id="faq_id" name="faq_id" value="">
							<label for="question">Soru</label>
							<textarea id="question" name="question" class="form-control required" rows="4"></textarea>
					</div>
					<div class="form-group">
							<label for="answer">Cevap</label>
							<textarea id="answer" name="answer" class="form-control required" rows="4"></textarea>
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

