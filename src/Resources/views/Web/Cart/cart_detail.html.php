<?php $view->extend('Web/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
    <div role="main" class="main">
        <section class="page-header">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li><a href="/">Anasayfa</a></li>
                            <li class="active">Sepet</li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="font-weight-bold">Sepet</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="section pt-0">
            <div class="container">
                <div class="row mb-5">
                    <div class="col empty-cart-holder">
                        <form class="shop-cart" method="post" action="#">
                            <div class="table-responsive">
                                <table class="shop-cart-table w-100">
                                    <thead>
                                        <tr>
                                            <th class="product-remove"></th>
                                            <th class="product-thumbnail"></th>
                                            <th class="product-name"><strong>Ürün İsmi</strong></th>
                                            <th class="product-price"><strong>Birim Fiyatı</strong></th>
                                            <th class="product-quantity"><strong>Adet</strong></th>
                                            <th class="product-subtotal"><strong>Toplam</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody id="cart-table-tbody">

                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row cart-summary">
                    <div class="col-md-12">
                        <h2 class="font-weight-bold text-4 mb-3">Sepet Özeti</h2>
                        <div class="table-responsive">
                            <table class="cart-totals w-100">
                                <tbody>
                                    <tr>
                                        <td>
                                            <span class="cart-total-label">Sepet Toplamı</span>
                                        </td>
                                        <td>
                                            <span class="cart-total-value"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="cart-total-label">Kargo Ücreti</span>
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
                                    <tr class="border-bottom-0">
                                        <td colspan="6" class="px-0">
                                            <div class="row mx-0">
                                                <div class="col-md-12 text-right px-0">
                                                    <a href="<?php echo $this->get('router')->path('cart_cargo_select') ?>" class="btn btn-primary btn-rounded font-weight-bold btn-h-2 btn-v-3 cart_cargo_select">ALIŞVERİŞE DEVAM</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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
                updateCart();
            });
        });
    </script>
<?php $view['slots']->stop(); ?>
