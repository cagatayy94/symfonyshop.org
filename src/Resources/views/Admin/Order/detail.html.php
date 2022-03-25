<?php $view->extend('Admin/default.html.php'); ?>

<?php $view['slots']->start('body'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Order Detail
            <?php if (!$orderDetail['order'][0]['is_approved']):?>
                <small class="label bg-red">Non Approved </small>
            <?php else: ?>
                <small class="label bg-green">Approved</small>
            <?php endif;?>
            <?php if (!$orderDetail['order'][0]['is_shipped']):?>
                <small class="label bg-red">Non Shipped</small>
            <?php else: ?>
                <small class="label bg-green">Shipped</small>
            <?php endif;?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->get('router')->path('admin_dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Order Detail</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box-body no-padding">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Customer Details</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body ">
                            <div class="form-group col-md-4">
                                <label for="name">Name</label>
                                <input disabled="disabled" type="text" class="form-control" name="name" value="<?php echo $orderDetail['userAccount']['name'] ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="email">Email</label>
                                <input disabled="disabled" type="text" class="form-control" name="email" value="<?php echo $orderDetail['userAccount']['email'] ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="pull-left" for="mobile">Mobile &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $orderDetail['userAccount']['is_mobile_approved'] ? '<small class="label pull-right bg-green">Approved</small>' : ' <small class="label pull-right bg-red">Unapproved</small>' ?></label>
                                <input disabled="disabled" type="text" class="form-control" name="mobile" value="<?php echo $orderDetail['userAccount']['mobile'] ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="mobile">Cargo Sent Code</label>
                                <input disabled="disabled" type="text" class="form-control" name="cargo_send_code" value="<?php echo $orderDetail['order'][0]['cargo_send_code'] ?>">
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
              <!-- /.box -->
            </div>
            <div class="col-md-6">
                <div class="box-body no-padding">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Order General Details</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body ">
                            <div class="form-group col-md-3">
                                <label for="name">Total Amount</label>
                                <input disabled="disabled" type="text" class="form-control" name="name" value="<?php echo $orderDetail['order'][0]['order_total_amount']; ?> ₺">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="email">Date</label>
                                <input disabled="disabled" type="text" class="form-control" name="email" value="<?php echo (new \DateTime($orderDetail['order'][0]['created_at']))->format('d.m.Y H:i:s'); ?>">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="mobile">Payment Method</label>
                                <input disabled="disabled" type="text" class="form-control" name="mobile" value="<?php echo $orderDetail['order'][0]['payment_selection'] == 'bank_transfer' ? 'Banka Havalesi' : 'Kredi Kartı'; ?>">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="mobile">IP Address</label>
                                <input disabled="disabled" type="text" class="form-control" name="mobile" value="<?php echo $orderDetail['order'][0]['order_ip']; ?>">
                            </div>
                            <div class="form-group col-md-12">
                                <span>
                                <?php if (!$orderDetail['order'][0]['is_approved'] && $admin->hasRole('approve_the_order')): ?>
                                    <button type="button" data-toggle="modal" data-target="#approve_the_order_modal" class="btn btn-primary pull-left">Approve</button>
                                <?php endif; ?>
                                </span>
                                <span>
                                <?php if (!$orderDetail['order'][0]['is_shipped'] && $admin->hasRole('ship_the_order')): ?>
                                    <button type="button" data-toggle="modal" data-target="#ship_the_order_modal" class="btn btn-primary pull-right">Mark as Shipped</button>
                                <?php endif; ?>
                                </span>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
              <!-- /.box -->
            </div>
            <div class="col-md-12">
                <div class="box-body no-padding">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Order Product Details</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body ">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th width="150" class="text-center">Pic </th>
                                        <th class="text-center">Id</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Unit Price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Shipping Price</th>
                                        <th class="text-center">Variants</th>
                                    </tr>
                                </thead>
                                <?php foreach ($orderDetail['order'] as $key => $value): ?>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">
                                                <img width="150" src="<?php echo $value['product_pic']; ?>">
                                            </td>
                                            <td class="text-center"><?php echo $value['product_id']; ?></td>
                                            <td class="text-center"><?php echo $value['product_name']; ?></td>
                                            <td class="text-center"><?php echo $value['product_price']; ?></td>
                                            <td class="text-center"><?php echo $value['product_quantity']; ?></td>
                                            <td class="text-center"><?php echo $value['cargo_price']; ?> ₺</td>
                                            <td class="text-center"><?php echo $value['variant_title'].' / '.$value['variant_selection']; ?></td>
                                        </tr>
                                    </tbody>
                                <?php endforeach; ?>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
              <!-- /.box -->
            </div>
            <div class="col-md-6">
                <div class="box-body no-padding">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Billing Details</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <?php $billing = json_decode($orderDetail['order'][0]['billing_address_detail'], true);?>
                        <div class="box-body ">
                            <div class="form-group col-md-4">
                                <label for="name">Address Name</label>
                                <input disabled="disabled" type="text" class="form-control" name="name" value="<?php echo $billing['address_name'] ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="email">Name</label>
                                <input disabled="disabled" type="text" class="form-control" name="email" value="<?php echo $billing['full_name'] ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="name">County</label>
                                <input disabled="disabled" type="text" class="form-control" name="payment_selection" value="<?php echo $billing['county'] ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="email">City</label>
                                <input disabled="disabled" type="text" class="form-control" name="created_at" value="<?php echo $billing['city'] ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="mobile">Phone</label>
                                <input disabled="disabled" type="text" class="form-control" name="cargo_send_code" value="<?php echo $billing['mobile'] ?>">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="mobile">Address</label>
                                <textarea style="resize: none;" class="form-control" rows="4" disabled="disabled"><?php echo $billing['address'] ?></textarea>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
              <!-- /.box -->
            </div>
            <div class="col-md-6">
                <div class="box-body no-padding">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Cargo Details</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <?php $shipping = json_decode($orderDetail['order'][0]['shipping_address_detail'], true);?>
                        <div class="box-body ">
                            <div class="form-group col-md-4">
                                <label for="name">Address Name</label>
                                <input disabled="disabled" type="text" class="form-control" name="name" value="<?php echo $shipping['address_name'] ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="email">Name</label>
                                <input disabled="disabled" type="text" class="form-control" name="email" value="<?php echo $shipping['full_name'] ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="name">County</label>
                                <input disabled="disabled" type="text" class="form-control" name="payment_selection" value="<?php echo $shipping['county'] ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="email">City</label>
                                <input disabled="disabled" type="text" class="form-control" name="created_at" value="<?php echo $shipping['city'] ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="mobile">Mobile</label>
                                <input disabled="disabled" type="text" class="form-control" name="cargo_send_code" value="<?php echo $shipping['mobile'] ?>">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="mobile">Address</label>
                                <textarea style="resize: none;" class="form-control" rows="4" disabled="disabled"><?php echo $shipping['address'] ?></textarea>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
              <!-- /.box -->
            </div>
        </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<div class="modal fade" id="approve_the_order_modal" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="approve_the_order_form" method="post" action="<?php echo $this->get('router')->path('admin_approve_the_order') ?>">
                <input type="hidden" name="orderId" value="<?php echo $orderId ?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Approve Order</h4>
                </div>
                <div class="modal-body">
                    <p>You sure to approve?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Approve</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="ship_the_order_modal" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="ship_the_order_form" action="<?php echo $this->get('router')->path('admin_ship_the_order') ?>" method="post">
                <input type="hidden" name="orderId" value="<?php echo $orderId ?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Mark as Shipped</h4>
                </div>
                <div class="modal-body">
                    <label for="name">Cargo Sent Code (*)</label>
                    <input type="text" class="form-control required" name="cargo_send_code" value="">
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
<?php $view['slots']->stop(); ?>