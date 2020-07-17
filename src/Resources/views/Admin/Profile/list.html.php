<?php $view->extend('Admin/default.html.php'); ?>

<?php $view['slots']->start('body'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
			<h1>
					Yönetici Profil Listesi
			</h1>
			<ol class="breadcrumb">
					<li><a href="<?php echo $this->get('router')->path('admin_dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
					<li class="active">Yönetici Profil Listesi</li>
			</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header">
						<div class="row">
							<div class="col-md-10"><h3 class="box-title">Yönetici Profil Listesi</h3></div>
							<div class="col-md-2">
								<?php if ($admin->hasRole('profile_create')):?>
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
								<th>İsim</th>
								<th>Kişiler</th>
								<th></th>
							</tr>
							<?php if (count($adminProfileList)):?>
								<?php foreach ($adminProfileList as $profileKey => $profilelist): ?>
									<tr>
										<td><?php echo $profilelist['name']; ?></td>
										<td>
											<?php if (isset($profilelist['admins'])):?>
												<?php foreach ($profilelist['admins'] as $adminKey => $adminName): ?>
													 <a href="<?php echo $this->get('router')->path('admin_account_detail', ['adminId' => $adminKey]) ?>"><?php echo $adminName; ?></a><br>
												<?php endforeach ?>
											<?php endif; ?>
										</td>
										<td>
											<?php if ($admin->hasRole('profile_detail_show')): ?>
												<a style="padding: 0;" href="<?php echo $this->get('router')->path('admin_profile_detail', ['profileId' => $profileKey]) ?>" class="btn"><i class="fa fa-edit"></i> Detay </a>
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
		<?php if ($admin->hasRole('profile_create')): ?>
		<div class="modal fade" id="add-new-admin-modal">
				<div class="modal-dialog">
					<div class="modal-content">
							<form id="create_new_admin_profile" role="form" method="POST" action="<?php echo $this->get('router')->path('admin_profile_create'); ?>">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">×</span></button>
											<h4 class="modal-title">Yeni Yönetici Profili Oluştur</h4>
										</div>
										<div class="modal-body">
													<div class="box-body">
															<div class="form-group">
																	<label for="name">İsim</label>
																	<input type="text" class="form-control required" name="profileName" placeholder="İsim">
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

