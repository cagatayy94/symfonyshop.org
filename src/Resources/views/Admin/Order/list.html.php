<?php $view->extend('Admin/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Sipariş Listesi
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo $this->get('router')->path('admin_dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">Sipariş Listesi</li>
		</ol>
	</section>
	<section class="content">
	  <div class="row">
		<div class="col-md-12">
		  <div class="box">
		  <div class="box-header with-border">
			<h3 class="box-title">Sipariş Listesi <span class="label label-warning"><?php echo "Toplam :".$orders['total']; ?></span></h3>
		  </div>
		  <!-- /.box-header -->
		  <div class="box-body">
			<div class="table-responsive">
			  <form action="<?php echo $this->get('router')->path('admin_order_list') ?>" method="get" id="admin_order_list_filter">
				<input type="hidden" name="currentPage" value="<?php echo $currentPage ?>">
				<input type="hidden" name="excelExport" value="0">
				<table class="table table-striped table-bordered">
				  <tbody>
					<tr>
						<th style="width: 55px" class="text-center">Sipariş Id</th>
						<th style="width: 250px" class="text-center">Alıcı Adı</th>
						<th style="width: 250px" class="text-center">Toplam Tutar</th>
						<th style="width: 250px" class="text-center">Sipariş Onay Durumu</th>
						<th style="width: 250px" class="text-center">Kargo Gönderim Durumu</th>
						<th style="width: 180px" class="text-center">Oluşturulma Tarihi</th>
						<th></th>
					</tr>
					<?php if (!$excelExport): ?>                      
					<tr>
						<th class="text-center">
							<input class="form-control" type="text" name="orderId" value="<?php echo $orderId ?>">
						</th>
						<th class="text-center">
							<input class="form-control" type="text" name="name" value="<?php echo $name ?>">
						</th>
						<th></th>
						<th></th>
						<th></th>
						<th style="min-width: 180px" class="text-center">
						  <input value="<?php echo $createdAtStart ?>" autocomplete="off" style="width: 80px" name="createdAtStart" value="" type="text" class="form-control pull-left datepicker d-inline-flex p-2 bd-highlight">
						  <input value="<?php echo $createdAtEnd ?>" autocomplete="off" style="width: 80px" name="createdAtEnd" value="" type="text" class="form-control pull-right datepicker d-inline-flex p-2 bd-highlight">
						</th>
						<th class="text-center">
						  <?php if (!$excelExport): ?>
							<button style="margin: 2px" type="submit" class="btn btn-sm btn-primary submit-filter"><i class="fa fa-fw fa-filter"></i> Filtrele</button>
							<button style="margin: 2px" type="submit" class="btn btn-sm btn-success submit-filter-excel"><i class="fa fa-fw fa-file-excel-o"></i> Excel</button>
						<?php endif ?>
						</th>
					</tr>
					<?php endif ?>
					<?php foreach ($orders['records'] as $value): ?>
					  <tr data-order-id="<?php echo $value['order_id']; ?>">
						<td class="text-center"><a href="<?php echo $this->get('router')->path('admin_order_detail', ['orderId' => $value['order_id']]) ?>"><?php echo $value['order_id'] ?></a></td>
						<td class="text-center"><?php echo $value['name'] ?></td>
						<td class="text-center"><?php echo $value['order_total_amount'] ?></td>
						<td class="text-center">
				            <?php if (!$value['is_approved']):?>
				                <small class="label bg-red">Sipariş Onaylanmamış</small>
				            <?php else: ?>
				                <small class="label bg-green">Sipariş Onaylanmış</small>
				            <?php endif;?>
						</td>
						<td class="text-center">
							<?php if (!$value['is_shipped']):?>
								<small class="label bg-red">Kargoya Verilmemiş</small>
							<?php else: ?>
								<small class="label bg-green">Kargoya Verilmiş</small>
							<?php endif;?>			
						</td>
						<td class="text-center">
						  <?php $created_at = new \DateTime($value['created_at']); echo $created_at->format('d.m.Y H:i:s'); ?> 
						</td>
						<td><a class="btn btn-primary" href="<?php echo $this->get('router')->path('admin_order_detail', ['orderId' => $value['order_id']]) ?>">Detay</a></td>
					  </tr>
					<?php endforeach ?>
				  </tbody>
				</table>
				<?php if ($pageCount): ?>
				<?php
					$startPage = $currentPage - 2;
					$endPage = $currentPage + 2;

					if ($startPage <= 0) {
						$endPage -= ($startPage - 1);
						$startPage = 1;
					}

					if ($endPage > $pageCount) {
						$endPage = $pageCount;
					}
				?>
				<!-- start pagination -->
				<div class="box-footer clearfix">
				  <ul class="pagination pagination-sm no-margin pull-right">
					<?php if ($startPage > 1): ?>
					  <li <?php echo 1 == $currentPage ? ' class="active" ' : ''; ?>><a class="pagination-action" data-page="1" href="#">1</a></li>
					  <li><a>...</a></li>
					<?php endif; ?>
					  <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
						<li <?php echo $i == $currentPage ? ' class="active" ' : ''; ?>><a class="pagination-action" data-page="<?php echo $i ?>" href="#"><?php echo $i; ?></a></li>
					<?php endfor; ?>
					<?php if ($endPage < $pageCount): ?>
					  <li><a>...</a></li>
					  <li <?php echo $pageCount == $currentPage ? ' class="active" ' : ''; ?>><a class="pagination-action" data-page="<?php echo $pageCount ?>" href="#"><?php echo $pageCount; ?></a></li>
					<?php endif; ?>
				  </ul>
				</div>
				<?php endif ?>
				<!-- end pagination -->
			  </form>
			</div>
		  </div>
		  <!-- /.box-body -->
		  </div>
		  <!-- /.box -->
		</div>
	  </div>
	  <!-- /.row -->
	</section>
	<!-- /.content -->
</div>
<script type="text/javascript">
  $(function () {
	//Date picker
	$('.datepicker').datepicker({
	  autoclose: true,
	  format: 'dd.mm.yyyy',
	})
  });
</script>
<?php $view['slots']->stop(); ?>
