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
        <form id="product_filter_form" method="POST">
            <input type="hidden" name="currentPage">
            <input type="hidden" name="categoryId" value="<?php echo $categoryId ?>">
            <div class="container">
                <div class="row">
                    <aside class="sidebar col-md-4 col-lg-3 order-2">
                        <div class="accordion accordion-default accordion-toggle accordion-style-1" role="tablist">
                            <div class="card">
                                <div id="toggleSidebarSearch" class="accordion-body accordion-body-show-border-top collapse show" role="tabpanel" aria-labelledby="sidebarSearchForm">
                                    <div class="card-body pt-4">
                                        <div class="input-group bg-light-5">
                                            <input type="text" class="form-control line-height-1 bg-light-5" name="search" placeholder="Ara..." value="<?php echo $search ?>">
                                            <span class="input-group-btn">
                                                <button style="margin-top: 10px; border: none;" class="btn btn-light bg-light-5" type="submit"><i class="fas fa-search text-color-primary"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header accordion-header" role="tab" id="categories">
                                    <h5 class="mb-0">
                                        <a href="#" data-toggle="collapse" data-target="#toggleCategories" aria-expanded="false" aria-controls="toggleCategories">KATEGORİLER</a>
                                    </h5>
                                </div>
                                <div id="toggleCategories" class="accordion-body collapse show" role="tabpanel" aria-labelledby="categories">
                                    <div class="card-body">
                                        <ul class="list list-unstyled mb-0">
                                            <li><a data-category-id="" class="category-filter <?php echo !$categoryId ? ' custom-class-active ' : '' ?> " href="#">Tümü</a></li>
                                            <?php foreach ($menuCategories as $key => $value): ?>
                                                <li><a class="category-filter <?php echo $categoryId == $value['id'] ? ' custom-class-active ' : ''  ?>" data-category-id="<?php echo $value['id'] ?>" href="#"><?php echo $value['name']; ?></a></li>
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
                                                <button type="submit" class="btn btn-primary btn-h-1 font-weight-bold rounded-0 price-filter-submit">FİLTRELE</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <!-- Banner Area -->
                        <?php echo $this->render('Web/banner.html.php',['banner' => isset($banner) ? $banner : null]); ?>
                        <!-- Banner Area End-->
                    </aside>
                    <div class="col-md-8 col-lg-9 order-1 mb-5 mb-md-0">
                        <div class="row align-items-center justify-content-between mb-4">
                            <div class="col-auto mb-3 mb-sm-0">
                                <div class="custom-select-1">
                                    <select name="order" class="form-control border order-filter-submit">
                                        <option <?php echo $order == 'date' ? 'selected' : '' ?> value="date" selected="selected">Sırala: En Yeni</option>
                                        <option <?php echo $order == 'price' ? 'selected' : '' ?> value="price">Sırala: En Düşük Fiyat</option>
                                        <option <?php echo $order == 'price-desc' ? 'selected' : '' ?> value="price-desc">Sırala: En Yüksek Fiyat</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="d-flex align-items-center">
                                    <span><?php echo ($perPage*$currentPage)-$perPage+1; ?>-<?php echo $perPage*$currentPage <= $products['total'] ? $perPage*$currentPage : $products['total'] ; ?> / <?php echo $products['total']; ?> sonuç gösteriliyor</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php if ($products): ?>
                                <?php foreach ($products['records'] as $key => $value): ?>
                                    <div class="col-sm-6 col-md-3 mb-4">
                                        <div class="product portfolio-item portfolio-item-style-2">
                                            <div class="image-frame image-frame-style-1 image-frame-effect-2 mb-3">
                                                <span class="image-frame-wrapper image-frame-wrapper-overlay-bottom image-frame-wrapper-overlay-light image-frame-wrapper-align-end">
                                                    <a href="/product-detail/<?php echo str_replace(" ", "-", $value['name']).'/'.$value['id'] ?>">
                                                    <?php if ($user): ?>
                                                            <img src="<?php echo json_decode($value['photo'], true)[0] ?>" class="img-fluid" alt="">
                                                    <?php else: ?>
                                                            <img src="/web/img/default-img.png" class="img-fluid" alt="">
                                                    <?php endif; ?>
                                                    </a>
                                                    <span class="image-frame-action">
                                                        <a href="/product-detail/<?php echo str_replace(" ", "-", $value['name']).'/'.$value['id'] ?>/" class="btn btn-primary btn-rounded font-weight-semibold btn-v-3 btn-fs-2">SEPETE EKLE</a>
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="product-info d-flex flex-column flex-lg-row justify-content-between">
                                                <div class="product-info-title">
                                                    <h3 class="text-color-default text-2 line-height-1 mb-1"><a href="<?php echo '/product-detail/'.strtolower(preg_replace('~-+~', '-', trim(preg_replace('~[^-\w]+~', '', iconv('utf-8', 'us-ascii//TRANSLIT', preg_replace('~[^\pL\d]+~u', '-', $value['name']))), '-'))).'/'.$value['id'] ?>"><?php echo $value['name'] ?></a></h3>
                                                    <span class="price font-primary text-4"><strong class="text-color-dark"><?php echo $value['price'] ?> TL</strong></span>
                                                    <span class="old-price font-primary text-line-trough text-1"><strong class="text-color-default"><?php echo $value['price']+10 ?> TL</strong></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            <?php endif ?>
                        </div>
                        <!-- start pagination -->
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
                        <hr class="mt-5 mb-4">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mb-3 mb-sm-0">
                                <span><?php echo ($perPage*$currentPage)-$perPage+1; ?>-<?php echo $perPage*$currentPage <= $products['total'] ? $perPage*$currentPage : $products['total'] ; ?> / <?php echo $products['total']; ?> sonuç gösteriliyor</span>
                            </div>
                            <div class="col-auto">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination mb-0">
                                        <?php if ($startPage > 1): ?>
                                            <li  <?php echo 1 == $currentPage ? ' class="page-item active" ' : ' class="page-item" '; ?>><a class="page-link pagination-action" data-page="1" href="#">1</a></li>
                                            <li><a>...</a></li>
                                        <?php endif; ?>
                                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                            <li <?php echo $i == $currentPage ? ' class="page-item active" ' : ' class="page-item" '; ?>><a class="page-link pagination-action" data-page="<?php echo $i ?>" href="#"><?php echo $i; ?></a></li>
                                        <?php endfor; ?>
                                        <?php if ($endPage < $pageCount): ?>
                                            <li><a>...</a></li>
                                            <li <?php echo $pageCount == $currentPage ? ' class="page-item active" ' : ' class="page-item" '; ?>><a class="page-link pagination-action" data-page="<?php echo $pageCount ?>" href="#"><?php echo $pageCount; ?></a></li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <?php endif; ?>
                        <!-- end pagination -->
                    </div>
                </div>
            </div>  
        </form>
    </div>
<?php $view['slots']->stop(); ?>
