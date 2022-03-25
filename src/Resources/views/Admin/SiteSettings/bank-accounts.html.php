<?php $view->extend('Admin/default.html.php'); ?>

<?php $view['slots']->start('body'); ?>
<div class="content-wrapper" style="min-height: 956px;">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Bank Settings
		</h1>
		<ol class="breadcrumb">
			<li class="active">Site Settings</li>
		</ol>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- /.row -->
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Bank Accounts</h3>
						<div class="box-tools">
							<div class="input-group input-group-sm hidden-xs" style="width: 150px;">
								<div class="input-group-btn">
									<?php if($admin->hasRole('settings_bank_create')): ?>
										<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-new-bank-modal"><i class="fa fa-plus"></i> Add New</button>
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
									<th>Bank Name</th>
									<th>City</th>
									<th>Country</th>
									<th>Branch</th>
									<th>Branch Code</th>
									<th>Currency</th>
									<th>Account Holder</th>
									<th>Account No</th>
									<th>Iban</th>
									<?php if ($admin->hasRole('settings_bank_update')):?>
									<th></th>
									<?php endif; ?>
									<?php if ($admin->hasRole('settings_bank_delete')):?>
									<th></th>
									<?php endif; ?>
								</tr>
								<?php foreach ($bankSettings as $value): ?>
									<tr id="<?php echo $value['id']; ?>">
										<td>
											<img src="web/img/bank/<?php echo $value['logo']; ?>" width="149" height="30">
										</td>
										<td><?php echo $value['name']; ?></td>
										<td><?php echo $value['city']; ?></td>
										<td><?php echo $value['country']; ?></td>
										<td><?php echo $value['branch_name']; ?></td>
										<td><?php echo $value['branch_code']; ?></td>
										<td><?php echo $value['currency']; ?></td>
										<td><?php echo $value['account_owner']; ?></td>
										<td><?php echo $value['account_number']; ?></td>
										<td><?php echo $value['iban']; ?></td>
										<?php if ($admin->hasRole('settings_bank_update')):?>
										<td>
											<button data-update-id="<?php echo $value['id'] ?>" class="btn btn-primary update-bank"><i class="fa fa-edit"></i> Update</button>
										</td>
										<?php endif;?>
										<?php if ($admin->hasRole('settings_bank_delete')):?>
										<td>
											<button data-delete-url="<?php echo $this->get('router')->path('admin_settings_bank_delete', ['bankId' => $value['id']]); ?>" class="btn btn-danger delete-bank"><i class="fa fa-remove"></i> Delete</button>
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
<?php if ($admin->hasRole('settings_bank_create')):?>
<div class="modal fade" id="add-new-bank-modal" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="<?php echo $this->get('router')->path('admin_settings_bank_create'); ?>" method="post" id="add-new-bank-form" enctype="multipart/form-data">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span></button>
					<h4 class="modal-title">Add New Bank Account</h4>
				</div>
				<div class="modal-body">                  
					<div class="form-group">
							<label for="name">Bank Name</label>
							<input type="text" class="form-control required" name="name" value="">
					</div>
					<div class="form-group">
							<label for="city">City</label>
							<input type="text" class="form-control required" name="city" value="">
					</div>
					<div class="form-group">
							<label for="country">Country</label>
							<input type="text" class="form-control required" name="country" value="">
					</div>
					<div class="form-group">
							<label for="branchName">Branch</label>
							<input type="text" class="form-control required" name="branchName" value="">
					</div>
					<div class="form-group">
							<label for="branchCode">Branch Code</label>
							<input type="text" class="form-control required" name="branchCode" value="">
					</div>
					<div class="form-group">
							<label for="currency">Currency</label>
							<input type="text" class="form-control required" name="currency" value="">
					</div>
					<div class="form-group">
							<label for="accountOwner">Account Holder</label>
							<input type="text" class="form-control required" name="accountOwner" value="">
					</div>
					<div class="form-group">
							<label for="accountNumber">Account No</label>
							<input type="text" class="form-control required" name="accountNumber" value="">
					</div>
					<div class="form-group">
							<label for="iban">IBAN</label>
							<input type="text" class="form-control required" name="iban" value="">
					</div>
					<div class="form-group">
							<label for="logo">Logo 150x30</label>
							<input name="logo" type="file" id="logo" class="form-control required">
					</div>                    
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Dismiss</button>
					<button type="submit" class="btn btn-primary">Save</button>
				</div>
			</form>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<?php endif; ?>

<?php if ($admin->hasRole('settings_bank_update')):?>
<div class="modal fade" id="update-bank-modal" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="<?php echo $this->get('router')->path('admin_settings_bank_update'); ?>" method="post" id="update-bank-form" enctype="multipart/form-data">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span></button>
					<h4 class="modal-title">Update Account</h4>
				</div>
				<div class="modal-body">  
				<input type="hidden" name="id" id="id" value="">                
					<div class="form-group">
							<label for="name">Bank Name</label>
							<input type="text" class="form-control required" id="name" name="name" value="">
					</div>
					<div class="form-group">
							<label for="city">City</label>
							<input type="text" class="form-control required" id="city" name="city" value="">
					</div>
					<div class="form-group">
							<label for="country">Country</label>
							<input type="text" class="form-control required" id="country" name="country" value="">
					</div>
					<div class="form-group">
							<label for="branchName">Branch</label>
							<input type="text" class="form-control required" id="branchName" name="branchName" value="">
					</div>
					<div class="form-group">
							<label for="branchCode">Branch Code</label>
							<input type="text" class="form-control required" id="branchCode" name="branchCode" value="">
					</div>
					<div class="form-group">
							<label for="currency">Currency</label>
							<input type="text" class="form-control required" id="currency" name="currency" value="">
					</div>
					<div class="form-group">
							<label for="accountOwner">Account Holder</label>
							<input type="text" class="form-control required" id="accountOwner" name="accountOwner" value="">
					</div>
					<div class="form-group">
							<label for="accountNumber">Account No</label>
							<input type="text" class="form-control required" id="accountNumber" name="accountNumber" value="">
					</div>
					<div class="form-group">
							<label for="iban">IBAN</label>
							<input type="text" class="form-control required" id="iban" name="iban" value="">
					</div>
					<div id="img" class="form-group">
						
					</div>
					<div id="uploadImg" hidden="hidden" class="form-group">
							<label for="logo">Logo 150x30</label>
							<input name="logo" type="file" id="updateLogo" class="form-control">
					</div>                    
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Dismiss</button>
					<button type="submit" class="btn btn-primary">Save</button>
				</div>
			</form>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<?php endif; ?>
<?php $view['slots']->stop(); ?>
