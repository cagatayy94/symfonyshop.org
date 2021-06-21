<?php $view->extend('Web/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
    <div role="main" class="main">
        <section class="page-header">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li><a href="/">Anasayfa</a></li>
                            <li class="active">Sepete</li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="font-weight-bold">Sepeti Onayla</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="section pt-0">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 mb-5 mb-md-0">
                        <h3 class="font-weight-bold text-4">Sepet Özeti</h3>
                        <div class="shop-cart">
                            <table class="shop-cart-table w-100">
                                <thead>
                                    <tr>
                                        <th class="product-thumbnail"></th>
                                        <th class="product-name"><strong>Ürün</strong></th>
                                        <th class="product-price"><strong>Adet Fiyatı</strong></th>
                                        <th class="product-quantity"><strong>Adet</strong></th>
                                        <th class="product-subtotal"><strong>Toplam</strong></th>
                                    </tr>
                                </thead>
                                <tbody id="cart-table-tbody">

                                </tbody>
                            </table>
                        </div>  
                    </div>

                    <div class="col-md-6">
                        <h2 class="font-weight-bold text-4 mb-3">Sepet Detayı</h2>
                        <div class="table-responsive">
                            <table class="cart-totals w-100">
                                <tbody>
                                <tr>
                                    <td>
                                        <span class="cart-total-label">Kargo Adresi</span>
                                    </td>
                                    <td style="width: 400px">
                                        <span class="cart-total-value-shipping"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="cart-total-label">Fatura Adresi</span>
                                    </td>
                                    <td>
                                        <span class="cart-total-value-billing"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="cart-total-label">Kargo Firması</span>
                                    </td>
                                    <td>
                                        <span class="cart-total-value-cargo-company-name"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="cart-total-label">Sepet Tutarı</span>
                                    </td>
                                    <td>
                                        <span class="cart-total-value"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="cart-total-label">Kargo Tutarı</span>
                                    </td>
                                    <td>
                                        <span class="cart-total-value-cargo"></span>
                                    </td>
                                </tr>
                                <tr class="border-bottom-0">
                                    <td>
                                        <span class="cart-total-label">Toplam</span>
                                    </td>
                                    <td>
                                        <span class="cart-total-value-grand text-color-primary text-4"></span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <ul class="nav nav-tabs nav-tabs-minimal" id="tabMinimal" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" id="minimal-portfolio-tab" data-toggle="tab" href="#minimal-portfolio" role="tab" aria-controls="minimal-portfolio" aria-expanded="true" aria-selected="true">Kredi Kartı</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="minimal-process-tab" data-toggle="tab" href="#minimal-process" role="tab" aria-controls="minimal-process" aria-selected="false">Banka Havalesi</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="tabMinimalContent">
                            <div class="tab-pane fade pt-4 pb-4 active show" id="minimal-portfolio" role="tabpanel" aria-labelledby="minimal-portfolio-tab">
                                <p class="mb-0">Kredi Kartı ile <strong>Güvenli</strong> ödeme yapmak için aşağıdaki butonu kullanabilirsiniz.</p>
                                <p><i>5890040000000016</i></p>
                                <form class="shipping-calculator" action="#" method="post">
                                    <div class="form-row">
                                        <div class="col">
                                            <br>
                                            <button type="button" data-url="<?php echo $this->get('router')->path('get_iyzico_form') ?>" class="btn btn-primary btn-3 btn-fs-3 mb-2" data-toggle="modal" data-target="#paymentModal">
                                                SİPARİŞ VER
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <div class="tab-pane fade pt-4 pb-4" id="minimal-process" role="tabpanel" aria-labelledby="minimal-process-tab">
                                <p class="mb-0">Banka Havalesi / EFT ile ürün satın alabilmek için öncelikle alışveriş sepeti tutarını <a href="banka-hesaplarimiz"><strong>"Banka Hesaplarımız"</strong></a> sayfasında bulunan herhangi bir hesaba ödeme yaptıktan sonra <a href="havale-bildirimi"><strong>"Havale Bildirim Formu"</strong></a> aracılığı ile lütfen tarafımıza bilgi veriniz. <strong>"Ödeme Yap"</strong> butonuna tıkladığınız anda siparişiniz kayıt edilecektir.</p>
                                    <div class="form-row">
                                        <div class="col">
                                            <br>
                                            <button class="btn btn-primary btn-3 btn-fs-3 mb-2" data-toggle="modal" data-target="#exampleModal1">ÖDEME YAP</button>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModal4Label" aria-hidden="true">
        <div class="modal-dialog text-left" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal4Label">Dikkat!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?php echo $this->get('router')->path('order_bank_transfer') ?>" method="post" id="order_bank_transfer_form" class="contact-form form-style-2">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-control-label"> <b>Banka Havalesi ile ödeme yapmaya çalışıyorsunuz! <br>Banka Havalesi ile ödeme yapmak için lütfen aşağıdaki adımları takip ettiğinizden emin olun. Aksi takdirde <a style="color: red">siparişiniz geçersiz</a> sayılacaktır!</b></label>
                            <label class="form-control-label">
                                <ol>
                                    <li>Sepet tutarını <a target="_blank" href="<?php echo $this->get('router')->path('bank_accounts') ?>"><b>Banka Hesaplarımız</b></a> sayfasındaki hesaplardan birisine Havale/EFT yöntemi ile gönderiniz.
                                    </li>
                                    <li>Havale/EFT işlemi ardından <a target="_blank" href="<?php echo $this->get('router')->path('order_notice') ?>"><b>Havale Bildirim Formu</b></a> aracılığı ile tarafımıza işlemi yaptığınıza dair bilgi veriniz.
                                    </li>
                                    <li>Yukarıdaki işlemleri eksiksiz bir şekilde tamamladıktan sonra sipariş ver butonuna tıklayabilirsiniz. Aksi takdirde siparişiniz 2 iş günü içerisinde <a style="color:red;">geçersiz</a> olacaktır. </li>
                                </ol>
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button align="right" class="btn btn-primary" type="submit">Sipariş Ver</button>

                        <button type="button" class="btn" data-dismiss="modal">Vazgeç</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModal4Label" aria-hidden="true">
        <div class="modal-dialog text-left" role="document">
            <div class="modal-content p-5 text-center">
                <div id="portfolioLoadMoreLoader" class="portfolio-load-more-loader" style="height: 67.5312px; display:block;">
                    <div class="bounce-loader">
                        <div class="bounce1"></div>
                        <div class="bounce2"></div>
                        <div class="bounce3"></div>
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
            $(document).ready(function() {
                updateCartInCheckOut();
            });
        });
    </script>

<?php $view['slots']->stop(); ?>
