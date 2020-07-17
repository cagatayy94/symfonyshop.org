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
                <p>Alexander Pierce</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">Yönetim Paneli</li>
            <!-- Optionally, you can add icons to the links -->
            <?php if ($admin->hasRole('site_dashboard_show')):?>
                <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_dashboard'); ?>"><i class="fa fa-tachometer"></i> <span>Dashboard</span></a></li>
            <?php endif; ?>
            
            <?php if ($admin->hasRole('bank_transfer_list')):?>
            <li class="treeview">
                <a href=""><i class="fa fa-bank" aria-hidden="true"></i><span>Havale Bildirimleri</span>
                    <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <?php if($admin->hasRole('bank_transfer_list')): ?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_money_order_list'); ?>">Havale Bildirimleri</a></li>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_money_order_list_deleted'); ?>">Silinmiş Havale Bildirimleri</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php if($admin->hasRole('settings_general') || $admin->hasRole('settings_strings') || $admin->hasRole('settings_bank') || $admin->hasRole('faq_list') || $admin->hasRole('settings_logo') || $admin->hasRole('all_banners') || $admin->hasRole('cargo_list')): ?>
            <li class="treeview">
                <a href=""><i class="fa fa-cog" aria-hidden="true"></i><span>Site Ayarları</span>
                    <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <?php if($admin->hasRole('settings_general')): ?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_settings_general'); ?>">Genel Ayarlar</a></li>
                    <?php endif; ?>
                    <?php if($admin->hasRole('settings_strings')): ?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_settings_strings'); ?>">Metin Ayarları</a></li>
                    <?php endif; ?>
                    <?php if($admin->hasRole('settings_bank')): ?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_settings_bank'); ?>">Banka Ayarları</a></li>
                    <?php endif; ?>
                    <?php if($admin->hasRole('faq_list')): ?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_settings_faq'); ?>">Sık Sorulan Sorular</a></li>
                    <?php endif; ?>
                    <?php if($admin->hasRole('settings_logo')): ?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_settings_logo'); ?>">Logo ve Favicon</a></li>
                    <?php endif; ?>
                    <?php if($admin->hasRole('all_banners')): ?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_settings_banner'); ?>">Banner Ayarları</a></li>
                    <?php endif; ?>
                    <?php if($admin->hasRole('cargo_list')): ?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_settings_cargo_list'); ?>">Kargo Listesi</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php if($admin->hasRole('account_list_show') && $admin->hasRole('profile_list_show')): ?>
            <li class="treeview">
                <a href=""><i class="fa fa-user-circle-o" aria-hidden="true"></i><span>Yöneticiler</span>
                    <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <?php if ($admin->hasRole('account_list_show')):?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_account_list'); ?>">Yönetici Listesi</a></li>
                    <?php endif; ?>
                    <?php if ($admin->hasRole('profile_list_show')):?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_profile_list'); ?>">Yönetici Yetki Profilleri</a></li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>

        <?php if($admin->hasRole('product_create') || $admin->hasRole('product_list')): ?>
            <li class="treeview">
                <a href=""><i class="fa fa-shopping-basket" aria-hidden="true"></i><span>Ürünler</span>
                    <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <?php if ($admin->hasRole('product_create')):?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_product_create_view'); ?>">Yeni Ürün Ekle</a></li>
                    <?php endif; ?>
                    <?php if ($admin->hasRole('product_list')):?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_product_list'); ?>">Ürün Listesi</a></li>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_product_list_deleted'); ?>">Silinmiş Ürün Listesi</a></li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>

        <?php if($admin->hasRole('menu_list')): ?>
            <li class="treeview">
                <a href=""><i class="fa fa-bars" aria-hidden="true"></i><span>Menüler</span>
                    <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <?php if ($admin->hasRole('menu_list')):?>
                    <li class="nav-dynamic"><a href="<?php echo $this->get('router')->path('admin_menu_list'); ?>">Menü Listesi</a></li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
