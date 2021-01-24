<?php $view->extend('Admin/default.html.php'); ?>

<?php $view['slots']->start('body'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Kullanıcı Listesi
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->get('router')->path('admin_dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Kullanıcı Listesi</li>
        </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header">
                <div class="row">
                    <div class="col-md-12"><h3 class="box-title">Kullanıcılar</h3></div>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <form action="<?php echo $this->get('router')->path('admin_user_list') ?>" method="GET">
                    <input type="hidden" name="currentPage" value="<?php echo $currentPage ?>">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th>İsim</th>
                                <th>Email</th>
                                <th>Telefon</th>
                                <th>Durum</th>
                                <th>Mail Listesi</th>
                                <th>Kayıt Tarihi</th>
                            </tr>
                            <?php if (count($userList)): ?>
                                <?php foreach ($userList['records'] as $key => $user): ?>
                                    <tr>
                                        <td><?php echo $user['name'] ?></td>
                                        <td>
                                            <?php if($user['is_email_approved']): ?>
                                                <span><?php echo $user['email']; ?></span>
                                                <small class="label pull-left bg-green">Onaylı</small>
                                            <?php else: ?>
                                                <span><?php echo $user['email']; ?></span>
                                                <small class="label pull-left bg-red">Onaysız</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span><?php echo $user['mobile'] ? $user['mobile'] : 'Belirtilmemiş'; ?></span>
                                            <?php if($user['is_mobile_approved']): ?>
                                                <small class="label pull-left bg-green">Onaylı</small>
                                            <?php else: ?>
                                                <small class="label pull-left bg-red">Onaysız</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($user['is_deleted']): ?>
                                                <small class="label pull-left bg-red">Silinmiş</small>
                                            <?php else: ?>
                                                <small class="label pull-left bg-green">Mevcut</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($user['is_unsubscribe']): ?>
                                                <small class="label pull-left bg-red">Kapalı</small>
                                            <?php else: ?>
                                                <small class="label pull-left bg-green">Açık</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo (new \DateTime())->format('d.m.Y H:i:s') ?>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            <?php endif; ?>
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
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<?php $view['slots']->stop(); ?>
