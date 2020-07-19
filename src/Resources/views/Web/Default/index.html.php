<?php $view->extend('Web/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
    <div role="main" class="main">
        <section class="page-header">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="font-weight-bold">Ürünler - <?php echo isset($menu) ? $menu['name'] : 'Tümü'; ?></h1>
                    </div>
                </div>
            </div>
        </section>
        <div class="container">
            <div class="row">
                <aside class="sidebar col-md-4 col-lg-3 order-2">
                    <div class="accordion accordion-default accordion-toggle accordion-style-1" role="tablist">

                        <div class="card">
                            <div class="card-header accordion-header" role="tab" id="categories">
                                <h5 class="mb-0">
                                    <a href="#" data-toggle="collapse" data-target="#toggleCategories" aria-expanded="false" aria-controls="toggleCategories">KATEGORİLER</a>
                                </h5>
                            </div>
                            <div id="toggleCategories" class="accordion-body collapse show" role="tabpanel" aria-labelledby="categories">
                                <div class="card-body">
                                    <ul class="list list-unstyled mb-0">
                                        <li><a href="#">Tümü</a></li>
                                        <?php foreach ($menuCategories as $key => $value): ?>
                                            <li><a href="#"><?php echo $value['name']; ?></a></li>
                                        <?php endforeach ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header accordion-header" role="tab" id="price">
                                <h5 class="mb-0">
                                    <a href="#" data-toggle="collapse" data-target="#togglePrice" aria-expanded="false" aria-controls="togglePrice">FİYAT</a>
                                </h5>
                            </div>
                            <div id="togglePrice" class="accordion-body collapse show" role="tabpanel" aria-labelledby="price">
                                <div class="card-body">
                                    <div class="slider-range-wrapper">
                                        <div class="slider-range mb-3" data-plugin-slider-range></div>
                                        <form class="d-flex align-items-center justify-content-between" method="get">
                                            <span>
                                                Price $<span class="price-range-low">0</span> - $<span class="price-range-high">0</span>
                                            </span>
                                            <input type="hidden" data-start-value="<?php echo isset($priceLow) ? $priceLow : 0 ?>" class="hidden-price-range-low" name="priceLow" value="10" />
                                            <input type="hidden" data-max-value="<?php echo $maxPrice ?>" data-start-value="<?php echo isset($priceHigh) ? $priceHigh : $maxPrice ?>" class="hidden-price-range-high" name="priceHigh" value="1000" />
                                            <button type="submit" class="btn btn-primary btn-h-1 font-weight-bold rounded-0">FİLTRELE</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($banner): ?>
                    <div class="image-frame image-frame-style-5 mt-1">
                        <img height="1800" width="1500" src="/web/img/banner/<?php echo $banner ?>" class="img-fluid" alt="">
                    </div>
                    <?php endif ?>
                </aside>
                <div class="col-md-8 col-lg-9 order-1 mb-5 mb-md-0">
                    <div class="row align-items-center justify-content-between mb-4">
                        <div class="col-auto mb-3 mb-sm-0">
                            <form method="get">
                                <div class="custom-select-1">
                                    <select class="form-control border">
                                        <option value="rating">Ortalama oylamaya göre sırala</option>
                                        <option value="date" selected="selected">Yeniliğe göre sırala</option>
                                        <option value="price">Fiyata göre sırala: düşükten yükseğe</option>
                                        <option value="price-desc">Fiyata göre sırala: yüksekten düşüğe</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex align-items-center">
                                <span>1-12 / 60 sonuç gösteriliyor</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php if ($products): ?>
                            <?php foreach ($products as $key => $value): ?>
                                <div class="col-sm-6 col-md-3 mb-4">
                                    <div class="product portfolio-item portfolio-item-style-2">
                                        <div class="image-frame image-frame-style-1 image-frame-effect-2 mb-3">
                                            <span class="image-frame-wrapper image-frame-wrapper-overlay-bottom image-frame-wrapper-overlay-light image-frame-wrapper-align-end">
                                                <a href="shop-product-detail-right-sidebar.html">
                                                    <img src="<?php echo $value['path'] ?>" class="img-fluid" alt="">
                                                </a>
                                                <span class="image-frame-action">
                                                    <a href="#" class="btn btn-primary btn-rounded font-weight-semibold btn-v-3 btn-fs-2">SEPETE EKLE</a>
                                                </span>
                                            </span>
                                        </div>
                                        <div class="product-info d-flex flex-column flex-lg-row justify-content-between">
                                            <div class="product-info-title">
                                                <h3 class="text-color-default text-2 line-height-1 mb-1"><a href="shop-product-detail-right-sidebar.html"><?php echo $value['name'] ?></a></h3>
                                                <span class="price font-primary text-4"><strong class="text-color-dark"><?php echo $value['price']-10 ?> TL</strong></span>
                                                <span class="old-price font-primary text-line-trough text-1"><strong class="text-color-default"><?php echo $value['price'] ?> TL</strong></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        <?php endif ?>
                    </div>
                    <hr class="mt-5 mb-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <span>1-12 / 60 sonuç gösteriliyor</span>
                        </div>
                        <div class="col-auto">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination mb-0">
                                    <li class="page-item">
                                        <a class="page-link prev" href="#" aria-label="Previous">
                                            <span><i class="fas fa-angle-left" aria-label="Previous"></i></span>
                                        </a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">...</li>
                                    <li class="page-item"><a class="page-link" href="#">15</a></li>
                                    <li class="page-item">
                                        <a class="page-link next" href="#" aria-label="Next">
                                            <span><i class="fas fa-angle-right" aria-label="Next"></i></span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $view['slots']->stop(); ?>
