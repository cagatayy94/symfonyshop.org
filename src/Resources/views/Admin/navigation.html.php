<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="admin/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?php echo $admin->getName().' '.$admin->getSurname(); ?></p>
                <!-- Status -->
                <a href="/admin/"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">Yönetim Paneli</li>
            <!-- Optionally, you can add icons to the links -->
            <?php if (true):?>
                <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_dashboard'); ?>"><i class="fa fa-tachometer"></i> <span>Dashboard</span></a></li>
            <?php endif; ?>
            
            <?php if (true):?>
            <li class="treeview">
                <a href=""><i class="fa fa-bank" aria-hidden="true"></i><span>Havale Bildirimleri</span>
                    <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <?php if(true): ?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_money_order_list'); ?>">Havale Bildirimleri</a></li>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_money_order_list_deleted'); ?>">Silinmiş Havale Bildirimleri</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php if(true): ?>
            <li class="treeview">
                <a href=""><i class="fa fa-user-circle-o" aria-hidden="true"></i><span>Yöneticiler</span>
                    <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <?php if (true):?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_account_list'); ?>">Yönetici Listesi</a></li>
                    <?php endif; ?>
                    <?php if (true):?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_profile_list'); ?>">Yönetici Yetki Profilleri</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

        <?php if(true): ?>
            <li class="treeview">
                <a href=""><i class="fa fa-shopping-basket" aria-hidden="true"></i><span>Ürünler</span>
                    <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <?php if (true):?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_product_create_view'); ?>">Yeni Ürün Ekle</a></li>
                    <?php endif; ?>
                    <?php if (true):?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_product_list'); ?>">Ürün Listesi</a></li>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_product_list_deleted'); ?>">Silinmiş Ürün Listesi</a></li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>

        <?php if(true): ?>
            <li class="treeview">
                <a href=""><i class="fa fa-shopping-bag" aria-hidden="true"></i><span>Siparişler</span>
                    <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <?php if (true):?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_order_list'); ?>">Sipariş Listesi</a></li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>

        <?php if(true): ?>
            <li class="treeview">
                <a href=""><i class="fa fa-bars" aria-hidden="true"></i><span>Menüler</span>
                    <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <?php if (true):?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_menu_list'); ?>">Menü Listesi</a></li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>

        <?php if(true): ?>
            <li class="treeview">
                <a href=""><i class="fa fa-cog" aria-hidden="true"></i><span>Site Ayarları</span>
                    <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <?php if(true): ?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_settings_general'); ?>">Genel Ayarlar</a></li>
                    <?php endif; ?>
                    <?php if(true): ?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_settings_strings'); ?>">Metin Ayarları</a></li>
                    <?php endif; ?>
                    <?php if(true): ?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_settings_bank'); ?>">Banka Ayarları</a></li>
                    <?php endif; ?>
                    <?php if(true): ?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_settings_faq'); ?>">Sık Sorulan Sorular</a></li>
                    <?php endif; ?>
                    <?php if(true): ?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_settings_logo'); ?>">Logo ve Favicon</a></li>
                    <?php endif; ?>
                    <?php if(true): ?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_settings_banner'); ?>">Banner Ayarları</a></li>
                    <?php endif; ?>
                    <?php if(true): ?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_settings_cargo_list'); ?>">Kargo Listesi</a></li>
                    <?php endif; ?>
                    <?php if(true): ?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_settings_iyzico'); ?>">Izyico Ayarları</a></li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>
        <?php if(true): ?>
            <li class="treeview">
                <a href=""><i class="fa fa-user" aria-hidden="true"></i><span>Kullanıcılar</span>
                    <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <?php if (true):?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_user_list'); ?>">Kullanıcı Listesi</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
