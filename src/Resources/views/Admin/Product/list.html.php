<?php $view->extend('Admin/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
            Product List
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo $this->get('router')->path('admin_dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">Product List</li>
		</ol>
	</section>
	<section class="content">
	  <div class="row">
		<div class="col-md-12">
		  <div class="box">
		  <div class="box-header with-border">
			<h3 class="box-title">Product List <span class="label label-warning"><?php echo "Toplam :".$products['total']; ?></span></h3>
		  </div>
		  <!-- /.box-header -->
		  <div class="box-body">
			<div class="table-responsive">
			  <form action="<?php echo $this->get('router')->path('admin_product_list') ?>" method="get" id="admin_product_list_filter">
				<input type="hidden" name="currentPage" value="<?php echo $currentPage ?>">
				<input type="hidden" name="excelExport" value="0">
				<table class="table table-striped table-bordered">
				  <tbody>
					<tr>
						<th class="text-center">Product Code</th>
						<th class="text-center">Name</th>
						<th class="text-center">Price</th>
						<th class="text-center">Cargo Price</th>
						<th class="text-center">Tax</th>
						<th class="text-center">Desc</th>
						<th class="text-center">Variant title</th>
						<th class="text-center">View Count</th>
						<th style="min-width: 180px" class="text-center">Date</th>
						<th></th>
					</tr>
					<?php if (!$excelExport): ?>                      
					<tr>
						<th class="text-center">
						  <input type="text" name="productId" class="form-control" value="<?php echo $productId ?>">
						</th>
						<th class="text-center">
						  <input type="text" name="productName" class="form-control" value="<?php echo $productName ?>">
						</th>
						<th class="text-center"></th>
						<th class="text-center"></th>
						<th class="text-center"></th>
						<th class="text-center"></th>
						<th class="text-center"></th>
						<th class="text-center"></th>
						<th style="min-width: 180px" class="text-center">
						  <input value="<?php echo $createdAtStart ?>" autocomplete="off" style="width: 80px" name="createdAtStart" value="" type="text" class="form-control pull-left datepicker d-inline-flex p-2 bd-highlight">
						  <input value="<?php echo $createdAtEnd ?>" autocomplete="off" style="width: 80px" name="createdAtEnd" value="" type="text" class="form-control pull-right datepicker d-inline-flex p-2 bd-highlight">
						</th>
						<th class="text-center">
						  <?php if (!$excelExport): ?>
							<button style="margin: 2px" type="submit" class="btn btn-sm btn-primary submit-filter"><i class="fa fa-fw fa-filter"></i> Filter</button>
							<button style="margin: 2px" type="submit" class="btn btn-sm btn-success submit-filter-excel"><i class="fa fa-fw fa-file-excel-o"></i> Export</button>
						  <?php endif ?>
						</th>
					</tr>
					<?php endif ?>
					<?php foreach ($products['records'] as $value): ?>
					  <tr data-product-id="<?php echo $value['id']; ?>">
						<td class="text-center"><a href="<?php echo $this->get('router')->path('admin_product_detail', ['id' => $value['id']]) ?>"><?php echo $value['id'] ?></a></td>
						<td class="text-center"><?php echo $value['name'] ?></td>
						<td class="text-center"><?php echo $value['price'] ?></td>
						<td class="text-center"><?php echo $value['cargo_price'] ?></td>
						<td class="text-center"><?php echo $value['tax'] ?></td>
						<td class="text-center"><?php echo substr($value['description'], 0, 50); ?></td>
						<td class="text-center"><?php echo $value['variant_title'] ?></td>
						<td class="text-center"><?php echo $value['view'] ?></td>
						<td class="text-center">
						  <?php $created_at = new \DateTime($value['created_at']); echo $created_at->format('d.m.Y H:i:s'); ?> 
						</td>
						<td class="text-center">
							<?php if ($admin->hasRole('product_detail') && !$excelExport): ?>
								<a style="margin: 2px" class="btn btn-sm btn-success" href="<?php echo $this->get('router')->path('admin_product_detail', ['id' => $value['id']]) ?>"><i class="fa fa-fw fa-info"></i> Detail</a>
						  	<?php endif ?>

							<?php if ($admin->hasRole('product_delete') && !$excelExport): ?>
								<button style="margin: 2px" data-delete-url="<?php echo $this->get('router')->path('admin_product_delete', ['productId' => $value['id']]) ?>" class="btn btn-sm btn-danger delete-product"><i class="fa fa-fw fa-eraser"></i> Delete</button>
							<?php endif ?>
						</td>
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
