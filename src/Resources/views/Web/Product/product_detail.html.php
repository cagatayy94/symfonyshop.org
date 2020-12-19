<?php $view->extend('Web/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
<div role="main" class="main">
    <div class="container">
        <div class="row">
            <div class="col">
                <ul class="breadcrumb mt-3">
                    <li><a href="#">Anasayfa</a></li>
                    <li class="active"><?php echo $productDetail['name'] ?></li>
                </ul>
            </div>
        </div>
        <div class="row">
            <aside class="sidebar col-md-4 col-lg-3 order-2">
                <div class="accordion accordion-default accordion-toggle accordion-style-1" role="tablist">
                    <div class="card">
                        <div id="toggleSidebarSearch" class="accordion-body accordion-body-show-border-top collapse show" role="tabpanel" aria-labelledby="sidebarSearchForm">
                            <div class="card-body pt-4">
                                <form id="sidebarSearchForm" class="sidebar-search" action="/" method="post">
                                    <div class="input-group">
                                        <input type="text" class="form-control line-height-1 bg-light-5" name="search" id="s" placeholder="Ara..." required="">
                                        <span class="input-group-btn">
                                        <button class="btn btn-light" type="submit"><i class="fas fa-search text-color-primary"></i></button>
                                        </span>
                                    </div>
                                </form>
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
                <div class="row mb-5">
                    <div class="col-lg-5 mb-lg-0">
                        <div class="owl-carousel owl-theme manual dots-style-2 nav-style-2 nav-color-dark mb-3" id="thumbGalleryDetail">
                            <?php foreach ($productDetail['photos'] as $key => $value): ?>
                                <div>
                                    <img src="<?php echo $value ?>" class="img-fluid" alt="">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="owl-carousel owl-theme manual thumb-gallery-thumbs mt" id="thumbGalleryThumbs">
                            <?php foreach ($productDetail['photos'] as $key => $value): ?>
                                <div>
                                    <span class="d-block">
                                        <img alt="Product Image" src="<?php echo $value ?>" class="img-fluid">
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <h2 class="line-height-1 font-weight-bold mb-2"><?php echo $productDetail['name']; ?></h2>
                        <div class="product-info-rate d-flex mb-3">
                            <?php for ($i=0; $i < $productDetail['rate']; $i += 1):?>
                                <i class="fas fa-star text-color-primary mr-1"></i>
                            <?php endfor; ?>                      
                        </div>
                        <span class="price font-primary text-4">
                            <strong class="text-color-dark"><?php echo $productDetail['price']; ?> TL</strong>
                        </span>
                        <span class="old-price font-primary text-line-trough text-2">
                            <strong class="text-color-default"><?php echo $productDetail['price']+25; ?> TL</strong>
                        </span>
                        <p class="mt-4"><?php echo $productDetail['description']; ?></p>
                        <hr class="my-4">
                        <form action="<?php echo $this->get('router')->path('cart_add') ?>" class="shop-cart d-flex align-items-center" method="post" id="cart_add">
                            <input type="hidden" name="productId" value="<?php echo $id ?>">
                            <div class="quantity">
                                <select id="variant" class="form-control required" name="variant" required="required">
                                    <option value="null"><?php echo $productDetail['variant_title']; ?> Seçiniz</option>
                                    <?php foreach (json_decode($productDetail['variant_name'], true) as $key => $value): ?>
                                        <option <?php echo json_decode($productDetail['variant_stock'], true)[$key] < 1 ? 'disabled' : ''; ?> value="<?php echo json_decode($productDetail['variant_id'], true)[$key] ?>"><?php echo $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php if ($user): ?>
                                <button type="submit" class="add-to-cart btn btn-primary btn-rounded font-weight-semibold btn-v-3 btn-h-2 btn-fs-2 ml-3">SEPETE EKLE</button>
                            <?php else: ?>
                                <button type="button" data-container="body" data-trigger="focus" data-toggle="popover" data-placement="right" data-content="Sepete eklemek için giriş yapınız." class="btn btn-primary btn-rounded font-weight-semibold btn-v-3 btn-h-2 btn-fs-2 ml-3" data-original-title="" title="">SEPETE EKLE</button>
                            <?php endif; ?>
                        </form>
                        <hr class="my-4">
                            <?php if ($user): ?>
                                <button type="button" data-url="<?php echo $this->get('router')->path('add_favorite') ?>" data-product-id="<?php echo $id ?>" value="FAVORİLERE EKLE" data-container="body" data-trigger="focus" class="add-to-favorite btn btn-primary btn-rounded font-weight-semibold btn-v-3 btn-h-2 btn-fs-2 ml-3">FAVORİLERE EKLE</button>
                            <?php else: ?>
                                <button type="button" value="FAVORİLERE EKLE" data-container="body" data-trigger="focus" data-toggle="popover" data-placement="right" data-content="Favorilere eklemek için giriş yapınız." class="btn btn-primary btn-rounded font-weight-semibold btn-v-3 btn-h-2 btn-fs-2 ml-3" data-original-title="" title="">FAVORİLERE EKLE</button>
                            <?php endif; ?>
                        <hr class="my-4">
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col">
                        <ul class="nav nav-tabs nav-tabs-default" id="productDetailTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link font-weight-bold active show" id="productDetailDescTab" data-toggle="tab" href="#productDetailDesc" role="tab" aria-controls="productDetailDesc" aria-expanded="true" aria-selected="true">AÇIKLAMA</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link font-weight-bold" id="productDetailReviewsTab" data-toggle="tab" href="#productDetailReviews" role="tab" aria-controls="productDetailReviews" aria-selected="false">ÜRÜN YORUMLARI ( <?php echo $productDetail['comment_count']; ?> )</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="contentTabProductDetail">
                            <div class="tab-pane fade pt-4 pb-4 active show" id="productDetailDesc" role="tabpanel" aria-labelledby="productDetailDescTab">
                                <p class="text-color-light-3">
                                    <?php echo $productDetail['description']; ?>
                                </p>                            
                            </div>
                            <div class="tab-pane fade pt-4 pb-4" id="productDetailReviews" role="tabpanel" aria-labelledby="productDetailReviewsTab">
                                <div class="row">
                                    <ul data-custom-load data-custom-load-url="<?php echo $this->get('router')->path('product_detail_comments', ['productId' => $id]); ?>" class="comments comments-holder-block" >
                                    </ul>
                                </div>
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-center">
                                        <div id="portfolioLoadMoreLoader" class="portfolio-load-more-loader" style="height: 67.5312px; display:none;">
                                            <div class="bounce-loader">
                                                <div class="bounce1"></div>
                                                <div class="bounce2"></div>
                                                <div class="bounce3"></div>
                                            </div>
                                        </div>

                                        <button id="commentsLoadMore" data-custom-load-url="<?php echo $this->get('router')->path('product_detail_comments', ['productId' => $id]); ?>"type="button" class="btn btn-primary btn-rounded btn-wide-5 btn-icon-effect-2 outline-none font-weight-semibold text-0" >
                                            <span>Daha Fazla Yorum Yükle</span>
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row mt-4 pt-2">
                                    <div class="col">
                                        <h2 class="font-weight-bold text-3 mb-3">ÜRÜNÜ DEĞERLENDİR</h2>
                                        <form class="form-style-2" id="add_comment_form" action="<?php echo $this->get('router')->path('add_comment'); ?>" method="post">
                                            <input type="hidden" name="productId" value="<?php echo $id ?>">
                                            <div class="form-row">
                                                <div class="form-group">
                                                    <div class="rating p-1">
                                                        <label>
                                                            <input class="required" type="radio" name="rate" value="5" title="5 stars"> 5
                                                        </label>
                                                        <label>
                                                            <input class="required" type="radio" name="rate" value="4" title="4 stars"> 4
                                                        </label>
                                                        <label>
                                                            <input class="required" type="radio" name="rate" value="3" title="3 stars"> 3
                                                        </label>
                                                        <label>
                                                            <input class="required" type="radio" name="rate" value="2" title="2 stars"> 2
                                                        </label>
                                                        <label>
                                                            <input class="required" type="radio" name="rate" value="1" title="1 star"> 1
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col">
                                                    <textarea class="form-control bg-light-5 border-0 rounded-0" placeholder="Yorumunuz" rows="6" name="comment"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-row mt-2">
                                                <div class="col">
                                                    <?php if ($user): ?>
                                                        <input type="submit" value="YORUM YAP" class="btn btn-primary btn-rounded btn-h-2 btn-v-2 font-weight-bold">
                                                    <?php else: ?>
                                                        <button type="button" value="YORUM YAP" data-container="body" data-trigger="focus" data-toggle="popover" data-placement="right" data-content="Yorum eklemek için giriş yapınız." class="btn btn-primary btn-rounded font-weight-semibold btn-v-3 btn-h-2 btn-fs-2 ml-3" data-original-title="" title="">YORUM YAP</button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function defer(method) {
        if (window.jQuery) {
            method();
        } else {
            setTimeout(function() { defer(method) }, 50);
        }
    }

    defer(function () {
        $('#commentsLoadMore').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var self = $(this);
            var commentsHolder = $('.comments-holder-block');
            var url = self.attr('data-custom-load-url');
            var method = 'GET'
            var offset = commentsHolder.children().length;

            self.hide();
            $('#portfolioLoadMoreLoader').show();

            $.ajax({
                type: method,
                url: url,
                data: {offset},
                success: function (result) {
                    if (result) {
                        commentsHolder.append(result);
                        self.show();
                        $('#portfolioLoadMoreLoader').hide();
                    } else {
                        self.hide();
                        $('#portfolioLoadMoreLoader').hide();
                    }
                }
            });

        });
    });
</script>
<?php $view['slots']->stop(); ?>
