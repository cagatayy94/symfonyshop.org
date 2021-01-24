<?php $view->extend('Admin/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3 class="order_count">0</h3>
                        <p>Siparişler</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="<?php echo $this->get('router')->path('admin_order_list') ?>" class="small-box-footer">Tümünü gör <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3 class="user_count">0</h3>
                        <p>Kullanıcılar</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="<?php echo $this->get('router')->path('admin_user_list') ?>" class="small-box-footer">Tümünü gör <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3 class="order_notice_count">0</h3>
                        <p>Havale Bildirimleri</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-disc"></i>
                    </div>
                    <a href="<?php echo $this->get('router')->path('admin_money_order_list') ?>" class="small-box-footer">Tümünü gör <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<?php $view['slots']->start('javascript'); ?>
<script>
function LoadData()
{
    $.ajax({
        type: 'GET',
        url: '/admin/dashboard/data',
        success: function (response) {
            if(response.success){
                var orderNoticeCount = response.data.orderNoticeCount;
                var ordersCount = response.data.ordersCount;
                var userCount = response.data.userCount;

                $('.order_count').html(ordersCount);
                $('.user_count').html(userCount);
                $('.order_notice_count').html(orderNoticeCount);
            }
        }
    });
}

setInterval( LoadData, 30000 );
</script>
<?php $view['slots']->stop(); ?>
<?php $view['slots']->stop(); ?>