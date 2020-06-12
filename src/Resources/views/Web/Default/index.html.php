<?php $view->extend('Web/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
    <div role="main" class="main">
        <section class="page-header">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li><a href="index.html">Home</a></li>
                            <li class="active">Shop</li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="font-weight-bold">4 Columns - Right Sidebar</h1>

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
                                    <a href="#" data-toggle="collapse" data-target="#toggleCategories" aria-expanded="false" aria-controls="toggleCategories">FASHION</a>
                                </h5>
                            </div>
                            <div id="toggleCategories" class="accordion-body collapse show" role="tabpanel" aria-labelledby="categories">
                                <div class="card-body">
                                    <ul class="list list-unstyled mb-0">
                                        <li><a href="#">Dresses</a></li>
                                        <li><a href="#">Hats</a></li>
                                        <li><a href="#">Accessories</a></li>
                                        <li><a href="#">Shoes</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header accordion-header" role="tab" id="price">
                                <h5 class="mb-0">
                                    <a href="#" data-toggle="collapse" data-target="#togglePrice" aria-expanded="false" aria-controls="togglePrice">PRICE</a>
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
                                            <input type="hidden" class="hidden-price-range-low" name="priceLow" value="" />
                                            <input type="hidden" class="hidden-price-range-high" name="priceHigh" value="" />
                                            <button type="submit" class="btn btn-primary btn-h-1 font-weight-bold rounded-0">FILTER</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header accordion-header" role="tab" id="sizes">
                                <h5 class="mb-0">
                                    <a href="#" data-toggle="collapse" data-target="#toggleSizes" aria-expanded="false" aria-controls="toggleSizes">SIZES</a>
                                </h5>
                            </div>
                            <div id="toggleSizes" class="accordion-body collapse show" role="tabpanel" aria-labelledby="sizes">
                                <div class="card-body">
                                    <ul class="list list-inline list-filter">
                                        <li class="list-inline-item">
                                            <a href="#">S</a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="#" class="active">M</a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="#">L</a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="#">XL</a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="#">2XL</a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="#">3XL</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header accordion-header" role="tab" id="brands">
                                <h5 class="mb-0">
                                    <a href="#" data-toggle="collapse" data-target="#toggleBrands" aria-expanded="false" aria-controls="toggleBrands">BRANDS</a>
                                </h5>
                            </div>
                            <div id="toggleBrands" class="accordion-body collapse show" role="tabpanel" aria-labelledby="brands">
                                <div class="card-body">
                                    <ul class="list list-unstyled mb-0">
                                        <li><a href="#">Adidas <span class="float-right">18</span></a></li>
                                        <li><a href="#">Camel <span class="float-right">22</span></a></li>
                                        <li><a href="#">Samsung Galaxy <span class="float-right">05</span></a></li>
                                        <li><a href="#">Seiko <span class="float-right">68</span></a></li>
                                        <li><a href="#">Sony <span class="float-right">03</span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($banner): ?>
                    <div class="image-frame image-frame-style-5 mt-1">
                        <img height="1800" width="1500" src="web/img/banner/<?php echo $banner ?>" class="img-fluid" alt="">
                    </div>
                    <?php endif ?>
                </aside>
                <div class="col-md-8 col-lg-9 order-1 mb-5 mb-md-0">
                    <div class="row align-items-center justify-content-between mb-4">
                        <div class="col-auto mb-3 mb-sm-0">
                            <form method="get">
                                <div class="custom-select-1">
                                    <select class="form-control border">
                                        <option value="popularity">Sort by popularity</option>
                                        <option value="rating">Sort by average rating</option>
                                        <option value="date" selected="selected">Sort by newness</option>
                                        <option value="price">Sort by price: low to high</option>
                                        <option value="price-desc">Sort by price: high to low</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex align-items-center">
                                <span>Showing 1-9 of 60 results</span>
                                <a href="#" class="text-color-dark text-3 ml-2" data-toggle="tooltip" data-placement="top" title="Grid"><i class="fas fa-th"></i></a>
                                <a href="#" class="text-color-dark text-3 ml-2" data-toggle="tooltip" data-placement="top" title="List"><i class="fas fa-list-ul"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-sm-6 col-md-3 mb-4">
                            <div class="product portfolio-item portfolio-item-style-2">
                                <div class="image-frame image-frame-style-1 image-frame-effect-2 mb-3">
                                            <span class="image-frame-wrapper image-frame-wrapper-overlay-bottom image-frame-wrapper-overlay-light image-frame-wrapper-align-end">
                                                <a href="shop-product-detail-right-sidebar.html">
                                                    <img src="web/img/products/product-1.jpg" class="img-fluid" alt="">
                                                </a>
                                                <span class="image-frame-action">
                                                    <a href="#" class="btn btn-primary btn-rounded font-weight-semibold btn-v-3 btn-fs-2">ADD TO CART</a>
                                                </span>
                                            </span>
                                </div>
                                <div class="product-info d-flex flex-column flex-lg-row justify-content-between">
                                    <div class="product-info-title">
                                        <h3 class="text-color-default text-2 line-height-1 mb-1"><a href="shop-product-detail-right-sidebar.html">Long Hoddie</a></h3>
                                        <span class="price font-primary text-4"><strong class="text-color-dark">$59</strong></span>
                                        <span class="old-price font-primary text-line-trough text-1"><strong class="text-color-default">$69</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3 mb-4">
                            <div class="product portfolio-item portfolio-item-style-2">
                                <div class="image-frame image-frame-style-1 image-frame-effect-2 mb-3">
                                            <span class="image-frame-wrapper image-frame-wrapper-overlay-bottom image-frame-wrapper-overlay-light image-frame-wrapper-align-end">
                                                <a href="shop-product-detail-right-sidebar.html">
                                                    <img src="web/img/products/product-2.jpg" class="img-fluid" alt="">
                                                </a>
                                                <span class="image-frame-action">
                                                    <a href="#" class="btn btn-primary btn-rounded font-weight-semibold btn-v-3 btn-fs-2">ADD TO CART</a>
                                                </span>
                                            </span>
                                </div>
                                <div class="product-info d-flex flex-column flex-lg-row justify-content-between">
                                    <div class="product-info-title">
                                        <h3 class="text-color-default text-2 line-height-1 mb-1"><a href="shop-product-detail-right-sidebar.html">Leather Belt</a></h3>
                                        <span class="price font-primary text-4"><strong class="text-color-dark">$19</strong></span>
                                        <span class="old-price font-primary text-line-trough text-1"><strong class="text-color-default">$29</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3 mb-4">
                            <div class="product portfolio-item portfolio-item-style-2">
                                <div class="image-frame image-frame-style-1 image-frame-effect-2 mb-3">
                                            <span class="image-frame-wrapper image-frame-wrapper-overlay-bottom image-frame-wrapper-overlay-light image-frame-wrapper-align-end">
                                                <a href="shop-product-detail-right-sidebar.html">
                                                    <img src="web/img/products/product-3.jpg" class="img-fluid" alt="">
                                                </a>
                                                <span class="image-frame-action">
                                                    <a href="#" class="btn btn-primary btn-rounded font-weight-semibold btn-v-3 btn-fs-2">ADD TO CART</a>
                                                </span>
                                            </span>
                                </div>
                                <div class="product-info d-flex flex-column flex-lg-row justify-content-between">
                                    <div class="product-info-title">
                                        <h3 class="text-color-default text-2 line-height-1 mb-1"><a href="shop-product-detail-right-sidebar.html">Jack Sandals</a></h3>
                                        <span class="price font-primary text-4"><strong class="text-color-dark">$30</strong></span>
                                        <span class="old-price font-primary text-line-trough text-1"><strong class="text-color-default">$40</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3 mb-4">
                            <div class="product portfolio-item portfolio-item-style-2">
                                <div class="image-frame image-frame-style-1 image-frame-effect-2 mb-3">
                                            <span class="image-frame-wrapper image-frame-wrapper-overlay-bottom image-frame-wrapper-overlay-light image-frame-wrapper-align-end">
                                                <a href="shop-product-detail-right-sidebar.html">
                                                    <img src="web/img/products/product-4.jpg" class="img-fluid" alt="">
                                                </a>
                                                <span class="image-frame-action">
                                                    <a href="#" class="btn btn-primary btn-rounded font-weight-semibold btn-v-3 btn-fs-2">ADD TO CART</a>
                                                </span>
                                            </span>
                                </div>
                                <div class="product-info d-flex flex-column flex-lg-row justify-content-between">
                                    <div class="product-info-title">
                                        <h3 class="text-color-default text-2 line-height-1 mb-1"><a href="shop-product-detail-right-sidebar.html">Vintage Hat</a></h3>
                                        <span class="price font-primary text-4"><strong class="text-color-dark">$79</strong></span>
                                        <span class="old-price font-primary text-line-trough text-1"><strong class="text-color-default">$99</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3 mb-4">
                            <div class="product portfolio-item portfolio-item-style-2">
                                <div class="image-frame image-frame-style-1 image-frame-effect-2 mb-3">
                                            <span class="image-frame-wrapper image-frame-wrapper-overlay-bottom image-frame-wrapper-overlay-light image-frame-wrapper-align-end">
                                                <a href="shop-product-detail-right-sidebar.html">
                                                    <img src="web/img/products/product-5.jpg" class="img-fluid" alt="">
                                                </a>
                                                <span class="image-frame-action">
                                                    <a href="#" class="btn btn-primary btn-rounded font-weight-semibold btn-v-3 btn-fs-2">ADD TO CART</a>
                                                </span>
                                            </span>
                                </div>
                                <div class="product-info d-flex flex-column flex-lg-row justify-content-between">
                                    <div class="product-info-title">
                                        <h3 class="text-color-default text-2 line-height-1 mb-1"><a href="shop-product-detail-right-sidebar.html">Timez Watch</a></h3>
                                        <span class="price font-primary text-4"><strong class="text-color-dark">$119</strong></span>
                                        <span class="old-price font-primary text-line-trough text-1"><strong class="text-color-default">$199</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3 mb-4">
                            <div class="product portfolio-item portfolio-item-style-2">
                                <div class="image-frame image-frame-style-1 image-frame-effect-2 mb-3">
                                            <span class="image-frame-wrapper image-frame-wrapper-overlay-bottom image-frame-wrapper-overlay-light image-frame-wrapper-align-end">
                                                <a href="shop-product-detail-right-sidebar.html">
                                                    <img src="web/img/products/product-6.jpg" class="img-fluid" alt="">
                                                </a>
                                                <span class="image-frame-action">
                                                    <a href="#" class="btn btn-primary btn-rounded font-weight-semibold btn-v-3 btn-fs-2">ADD TO CART</a>
                                                </span>
                                            </span>
                                </div>
                                <div class="product-info d-flex flex-column flex-lg-row justify-content-between">
                                    <div class="product-info-title">
                                        <h3 class="text-color-default text-2 line-height-1 mb-1"><a href="shop-product-detail-right-sidebar.html">Clauren Bag</a></h3>
                                        <span class="price font-primary text-4"><strong class="text-color-dark">$289</strong></span>
                                        <span class="old-price font-primary text-line-trough text-1"><strong class="text-color-default">$299</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3 mb-4">
                            <div class="product portfolio-item portfolio-item-style-2">
                                <div class="image-frame image-frame-style-1 image-frame-effect-2 mb-3">
                                            <span class="image-frame-wrapper image-frame-wrapper-overlay-bottom image-frame-wrapper-overlay-light image-frame-wrapper-align-end">
                                                <a href="shop-product-detail-right-sidebar.html">
                                                    <img src="web/img/products/product-7.jpg" class="img-fluid" alt="">
                                                </a>
                                                <span class="image-frame-action">
                                                    <a href="#" class="btn btn-primary btn-rounded font-weight-semibold btn-v-3 btn-fs-2">ADD TO CART</a>
                                                </span>
                                            </span>
                                </div>
                                <div class="product-info d-flex flex-column flex-lg-row justify-content-between">
                                    <div class="product-info-title">
                                        <h3 class="text-color-default text-2 line-height-1 mb-1"><a href="shop-product-detail-right-sidebar.html">Classik Sunglasses</a></h3>
                                        <span class="price font-primary text-4"><strong class="text-color-dark">$99</strong></span>
                                        <span class="old-price font-primary text-line-trough text-1"><strong class="text-color-default">$199</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3 mb-4">
                            <div class="product portfolio-item portfolio-item-style-2">
                                <div class="image-frame image-frame-style-1 image-frame-effect-2 mb-3">
                                            <span class="image-frame-wrapper image-frame-wrapper-overlay-bottom image-frame-wrapper-overlay-light image-frame-wrapper-align-end">
                                                <a href="shop-product-detail-right-sidebar.html">
                                                    <img src="web/img/products/product-8.jpg" class="img-fluid" alt="">
                                                </a>
                                                <span class="image-frame-action">
                                                    <a href="#" class="btn btn-primary btn-rounded font-weight-semibold btn-v-3 btn-fs-2">ADD TO CART</a>
                                                </span>
                                            </span>
                                </div>
                                <div class="product-info d-flex flex-column flex-lg-row justify-content-between">
                                    <div class="product-info-title">
                                        <h3 class="text-color-default text-2 line-height-1 mb-1"><a href="shop-product-detail-right-sidebar.html">High Heels Shoes</a></h3>
                                        <span class="price font-primary text-4"><strong class="text-color-dark">$79</strong></span>
                                        <span class="old-price font-primary text-line-trough text-1"><strong class="text-color-default">$99</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3 mb-4">
                            <div class="product portfolio-item portfolio-item-style-2">
                                <div class="image-frame image-frame-style-1 image-frame-effect-2 mb-3">
                                            <span class="image-frame-wrapper image-frame-wrapper-overlay-bottom image-frame-wrapper-overlay-light image-frame-wrapper-align-end">
                                                <a href="shop-product-detail-right-sidebar.html">
                                                    <img src="web/img/products/product-9.jpg" class="img-fluid" alt="">
                                                </a>
                                                <span class="image-frame-action">
                                                    <a href="#" class="btn btn-primary btn-rounded font-weight-semibold btn-v-3 btn-fs-2">ADD TO CART</a>
                                                </span>
                                            </span>
                                </div>
                                <div class="product-info d-flex flex-column flex-lg-row justify-content-between">
                                    <div class="product-info-title">
                                        <h3 class="text-color-default text-2 line-height-1 mb-1"><a href="shop-product-detail-right-sidebar.html">Dual Color Jacket</a></h3>
                                        <span class="price font-primary text-4"><strong class="text-color-dark">$299</strong></span>
                                        <span class="old-price font-primary text-line-trough text-1"><strong class="text-color-default">$399</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-5 mb-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <span>Showing 1-9 of 60 results</span>
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
