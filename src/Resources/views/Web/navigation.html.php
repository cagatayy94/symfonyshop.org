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
                                <a href="favorilerim" class="nav-link">FAVORİLERİM</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">HESABIM</a>
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
                        <div class="mini-cart order-4">
                            <span class="font-weight-bold font-primary">Sepet / <span class="cart-total">0.00₺</span></span>
                            <div class="mini-cart-icon">
                                <img src="/web/img/icons/cart-bag.svg" class="img-fluid" alt="" />
                                <span class="badge badge-primary rounded-circle">0</span>
                            </div>
                            <div class="mini-cart-content">
                                <div class="inner-wrapper bg-light rounded">
                                    <div class="mini-cart-product">
                                        <div class="row">
                                            <div class="col-7">
                                                <h2 class="text-color-default font-secondary text-1 mt-3 mb-0">Blue Hoodies</h2>
                                                <strong class="text-color-dark">
                                                    <span class="qty">1x</span>
                                                    <span class="product-price">$12.00</span>
                                                </strong>
                                            </div>
                                            <div class="col-5">
                                                <div class="product-image">
                                                    <a href="#" class="btn btn-light btn-rounded justify-content-center align-items-center"><i class="fas fa-times"></i></a>
                                                    <img src="/web/img/products/product-2.jpg" class="img-fluid rounded" alt="" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mini-cart-total">
                                        <div class="row">
                                            <div class="col">
                                                <strong class="text-color-dark">TOPLAM:</strong>
                                            </div>
                                            <div class="col text-right">
                                                <strong class="total-value text-color-dark">$12.00</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mini-cart-actions">
                                        <div class="row">
                                            <div class="col pr-1">
                                                <a href="shop-cart.html" class="btn btn-dark font-weight-bold rounded text-0">SEPETIM</a>
                                            </div>
                                            <div class="col pl-1">
                                                <a href="shop-checkout.html" class="btn btn-primary font-weight-bold rounded text-0">SIPARİŞİ TAMAMLA</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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