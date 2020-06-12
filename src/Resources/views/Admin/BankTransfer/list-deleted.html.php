<?php $view->extend('Admin/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Silinmiş Havale Bildirimleri Listesi
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->get('router')->path('admin_dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Silinmiş Havale Bildirimleri Listesi</li>
        </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Silinmiş Havale Bildirimleri Listesi <span class="label label-warning"><?php echo "Toplam :".$bankTrasferList['total']; ?></span></h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="table-responsive">
              <form action="<?php echo $this->get('router')->path('admin_money_order_list_deleted') ?>" method="get" id="admin_money_transfer_list_filter">
                <input type="hidden" name="currentPage" value="<?php echo $currentPage ?>">
                <table class="table table-striped table-bordered">
                  <tbody>
                    <tr>
                        <th class="text-center">İsim</th>
                        <th class="text-center">Mail</th>
                        <th class="text-center">Telefon</th>
                        <th class="text-center">Banka</th>
                        <th class="text-center">Onaylı</th>
                        <th style="min-width: 180px" class="text-center">Tarih Aralığı</th>
                        <th style="width: 150px" class="text-center">Mesaj</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td class="text-center">
                          <input type="text" name="name" class="form-control" value="<?php echo $name ?>">
                        </td>
                        <td class="text-center">
                          <input type="text" name="email" class="form-control" value="<?php echo $email ?>">
                        </td>
                        <td class="text-center">
                          <input type="text" name="mobile" class="form-control" value="<?php echo $mobile ?>">
                        </td>
                        <td class="text-center">
                          <select name="bankId" class="form-control change-submit">
                            <option value="">Tümü</option>
                            <?php foreach ($allBanks as $value): ?>
                              <option <?php echo $bankId == $value['id'] ? 'selected="selected"' : '' ?> value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
                            <?php endforeach ?>
                          </select>
                        </td>
                        <td class="text-center">
                          <select name="isApproved" class="form-control change-submit">
                            <option <?php echo is_null($isApproved) ? 'selected="selected"' : '' ?> value="">Tümü</option>
                            <option <?php echo !is_null($isApproved) && $isApproved == "0" ? 'selected="selected"' : '' ?> value="0">Onaylanmamış</option>
                            <option <?php echo !is_null($isApproved) && $isApproved == "1" ? 'selected="selected"' : '' ?> value="1">Onaylanmış</option>
                          </select>
                        </td>
                        <td class="text-center">
                          <input value="<?php echo $startDate ?>" autocomplete="off" style="width: 80px" name="startDate" value="" type="text" class="form-control pull-left datepicker d-inline-flex p-2 bd-highlight">
                          <input value="<?php echo $endDate ?>" autocomplete="off" style="width: 80px" name="endDate" value="" type="text" class="form-control pull-right datepicker d-inline-flex p-2 bd-highlight">
                        </td>
                        <td></td>
                        <td class="text-center">
                          <button type="submit" class="btn btn-success submit-filter"><i class="fa fa-fw fa-filter"></i> Filtrele</button>
                        </td>
                    </tr>
                    <?php foreach ($bankTrasferList['records'] as $value): ?>
                      <tr>
                        <td class="text-center"><?php echo $value['name'] ?></td>
                        <td class="text-center"><?php echo $value['email'] ?></td>
                        <td class="text-center"><?php echo $value['mobile'] ?></td>
                        <td class="text-center"><?php echo $value['bank_name'] ?></td>
                        <td class="text-center">
                          <?php echo $value['is_approved'] ? '<span class="label label-success">Onaylanmış</span>' : '<span class="label label-danger">Onaylanmamış</span>'; ?>
                        </td>
                        <td class="text-center">
                          <?php $created_at = new \DateTime($value['created_at']); echo $created_at->format('d.m.Y H:i:s'); ?> 
                        </td>
                        <td class="text-center"><?php echo $value['message'] ?></td>
                        <td class="text-center">
                          <?php if ($admin->hasRole('bank_transfer_undelete')): ?>
                            <button data-delete-url="<?php echo $this->get('router')->path('admin_money_order_undelete', ['moneyOrderId' => $value['id']]) ?>" class="btn btn-primary undelete-bank-transfer"><i class="fa fa-fw fa-eraser"></i> Silmeyi Geri Al</button>
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
