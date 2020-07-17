<?php $view->extend('Admin/default.html.php'); ?>

<?php $view['slots']->start('body'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
			<h1>
				Menü Listesi
			</h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo $this->get('router')->path('admin_dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
				<li class="active">Menü Listesi</li>
			</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header">
						<div class="row">
							<div class="col-md-10"><h3 class="box-title">Menü Listesi</h3></div>
							<div class="col-md-2">
								<?php if ($admin->hasRole('menu_create')):?>
									<button type="button" data-toggle="modal" data-target="#add-new-menu-modal" class="btn btn-block btn-info btn-flat"><i class="fa fa-fw fa-plus"></i> Yeni Ekle </button>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table class="table table-striped">
							<tbody>
							<tr>
								<th>İsim</th>
								<th>Kategoriler</th>
								<th></th>
							</tr>
							<?php foreach ($menus as $menu): ?>
								<tr data-menu-id="<?php echo $menu['id'] ?>">
									<td><?php echo $menu['name']; ?></td>
									<td>
										<?php
											if ($menu['category']) {
												foreach ($menu['category'] as $key => $value) {

													echo ($key+1 != count($menu['category'])) ? $value['name'].', ' : $value['name'];
													# code...
												}
											}








										?>
											
									</td>
									<td>
										<?php if ($admin->hasRole('menu_detail')): ?>
											<a style="padding: 0;" href="<?php echo $this->get('router')->path('admin_menu_detail', ['menuId' => $menu['id']]) ?>" class="btn"><i class="fa fa-edit"></i> Detay </a>
										<?php endif ?>
									</td>
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
		<!-- /.row -->
		<?php if ($admin->hasRole('menu_create')): ?>
		<div class="modal fade" id="add-new-menu-modal">
			<div class="modal-dialog">
				<div class="modal-content">
					<form id="create_menu_form" role="form" method="POST" action="<?php echo $this->get('router')->path('admin_menu_create'); ?>">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">×</span></button>
							<h4 class="modal-title">Yeni Menu Oluştur</h4>
						</div>
						<div class="modal-body">
							<div class="box-body">
								<div class="form-group">
									<label for="name">İsim</label>
									<input type="text" class="form-control required" name="menuName" placeholder="İsim">
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

