<?php $navigationData = json_decode($view['actions']->render('Web:DefaultController:/navigation/data'), true); ?>
<header id="header" class="header-effect-shrink" data-plugin-options="{'stickyEnabled': true, 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': true, 'stickyStartAt': 120, 'stickyChangeLogo': false}">
    <div class="header-body">
        <div class="header-top">
            <div class="header-top-container container">
                <div class="header-row">
                    <div class="header-column justify-content-end">
                        <ul class="nav">
                            <?php if ($user): ?>
                            <li class="nav-item">
                                <a href="siparislerim" class="nav-link">SİPARİŞLERİM</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo $this->get('router')->path('profile') ?>#favorites" class="nav-link">FAVORİLERİM</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo $this->get('router')->path('profile') ?>" class="nav-link">HESABIM</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo $this->get('router')->path('logout'); ?>" class="nav-link">ÇIKIŞ YAP</a>
                            </li>
                            <?php else: ?>
                            <li class="nav-item">
                                <a href="<?php echo $this->get('router')->path('login'); ?>" class="nav-link">GİRİŞ YAP / KAYIT OL</a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-container container">
            <div class="header-row">
                <div class="header-column justify-content-start">
                    <div class="header-logo">
                        <a href="/">
                            <img alt="EZ" width="128" height="32" src="/web/img/logo-shop.png">
                        </a>
                    </div>
                </div>
                <div class="header-column justify-content-end">
                    <div class="header-search-expanded">
                        <form method="POST" action="/">
                            <div class="input-group bg-light border">
                                <input type="text" class="form-control text-4" name="search" placeholder="I'm looking for..." aria-label="I'm looking for...">
                                <span class="input-group-btn">
                                    <button class="btn" type="submit"><i class="lnr lnr-magnifier text-color-dark"></i></button>
                                </span>
                            </div>
                        </form>
                    </div>
                    <div class="header-nav justify-content-start">
                        <a href="#" class="header-search-button order-1 text-5 d-none d-sm-block mt-1 mr-xl-2">
                            <i class="lnr lnr-magnifier"></i>
                        </a>
                        <div class="header-nav-main header-nav-main-effect-1 header-nav-main-sub-effect-1">
                            <nav class="collapse">
                                <ul class="nav flex-column flex-lg-row" id="mainNav">
                                    <li class="dropdown">
                                        <a class="dropdown-item" href="/">
                                            Anasayfa
                                        </a>
                                    </li>
                                    <?php if (isset($navigationData['menus'])): ?>
                                        <?php foreach ($navigationData['menus'] as $key => $value): ?>
                                            <li class="dropdown">
                                                <a class="dropdown-item" href="/urunler/<?php echo $value['slug'] ?>">
                                                    <?php echo $value['name']; ?>
                                                </a>
                                            </li>
                                        <?php endforeach ?>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                        <a href="/login" class="btn btn-link text-color-default font-weight-bold order-3 d-none d-sm-block ml-auto mr-2 pt-1 text-1"></a>
                        <div style="cursor: pointer;" onclick='window.location.href = "/cart/detail";' class="mini-cart order-4">
                            <span class="font-weight-bold font-primary">Sepet / <span id="cart-total" class="cart-total">0.00 ₺</span></span>
                            <div class="mini-cart-icon">
                                <img src="/web/img/icons/cart-bag.svg" class="img-fluid" alt="" />
                                <span id="cart-quantity" class="badge badge-primary rounded-circle">0</span>
                            </div>
                        </div>
                        <button class="header-btn-collapse-nav order-4 ml-3" data-toggle="collapse" data-target=".header-nav-main nav">
                            <span class="hamburguer">
                            <span></span>
                            <span></span>
                            <span></span>
                            </span>
                            <span class="close">
                            <span></span>
                            <span></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>