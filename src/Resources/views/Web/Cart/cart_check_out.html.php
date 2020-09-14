<?php $view->extend('Web/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
    <div role="main" class="main">
        <section class="page-header">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li><a href="/">Anasayfa</a></li>
                            <li class="active">Sepete Devam</li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="font-weight-bold">Sepete Devam</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="section">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <form id="shopCheckout" action="#" method="post">
                            <div class="row mb-5">
                                <div class="col-md-6 mb-5 mb-md-0 custom-class-disabler" id="billing_form_div">
                                    <h2 class="font-weight-bold mb-3">Fatura Adresi</h2>
                                    <p class="mb-3">Fatura adresiniz ve bilgileriniz siparişin gönderileceği adresten farklı ise doldurunuz.</p>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label class="text-color-dark font-weight-semibold" for="billing_name">İSİM:</label>
                                            <input type="text" value="" class="form-control line-height-1 bg-light-5" name="billing_name" id="billing_name" required="" disabled="disabled">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="text-color-dark font-weight-semibold" for="billing_last_name">SOYİSİM:</label>
                                            <input type="text" value="" class="form-control line-height-1 bg-light-5" name="billing_last_name" id="billing_last_name" required="" disabled="disabled">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col">
                                            <label class="text-color-dark font-weight-semibold" for="billing_company">FİRMA İSMİ:</label>
                                            <input type="text" value="" class="form-control line-height-1 bg-light-5" name="billing_company" id="billing_company" required="" disabled="disabled">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col">
                                            <label class="text-color-dark font-weight-semibold" for="billing_address">ADRES:</label>
                                            <input type="text" value="" class="form-control line-height-1 bg-light-5" name="billing_address" id="billing_address" required="" disabled="disabled">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col">
                                            <label class="text-color-dark font-weight-semibold" for="billing_city">İL / İLÇE:</label>
                                            <input type="text" value="" class="form-control line-height-1 bg-light-5" name="billing_city" id="billing_city" required="" disabled="disabled">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label class="text-color-dark font-weight-semibold" for="billing_email">EMAIL:</label>
                                            <input type="text" value="" class="form-control line-height-1 bg-light-5" name="billing_email" id="billing_email" required="" disabled="disabled">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="text-color-dark font-weight-semibold" for="billing_phone">TELEFON:</label>
                                            <input type="text" value="" class="form-control line-height-1 bg-light-5" name="billing_phone" id="billing_phone" required="" disabled="disabled">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <h2 class="font-weight-bold mb-3 float-left">Kargo Adresi</h2>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-check checkbox-custom checkbox-default float-right">
                                                    <input class="form-check-input" type="checkbox" id="different_billing_address">
                                                    <label class="form-check-label" for="different_billing_address">
                                                        Fatura Adresim Farklı
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 ">
                                            <p class="mb-3 float-left">Siparişinizin teslim edileceği adresi yazınız.</p>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-primary btn-1 btn-fs-1 mb-2 float-right">Adreslerimden Getir</button>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label class="text-color-dark font-weight-semibold" for="shipping_name">İSİM:</label>
                                            <input type="text" value="" class="form-control line-height-1 bg-light-5" name="shipping_name" id="shipping_name" required="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="text-color-dark font-weight-semibold" for="shipping_last_name">SOYİSİM:</label>
                                            <input type="text" value="" class="form-control line-height-1 bg-light-5" name="shipping_last_name" id="shipping_last_name" required="">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col">
                                            <label class="text-color-dark font-weight-semibold" for="shipping_company">KARGO FIRMASI:</label>
                                            <input type="text" value="" class="form-control line-height-1 bg-light-5" name="shipping_company" id="shipping_company" required="">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col">
                                            <label class="text-color-dark font-weight-semibold" for="shipping_address">ADRES:</label>
                                            <input type="text" value="" class="form-control line-height-1 bg-light-5" name="shipping_address" id="shipping_address" required="">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col">
                                            <label class="text-color-dark font-weight-semibold" for="shipping_city">İL / İLÇE:</label>
                                            <input type="text" value="" class="form-control line-height-1 bg-light-5" name="shipping_city" id="shipping_city" required="">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col">
                                            <label class="text-color-dark font-weight-semibold" for="shipping_notes">SİPARİŞ NOTUNUZ:</label>
                                            <textarea class="form-control line-height-1 bg-light-5" name="shipping_notes" id="shipping_notes" rows="7" required=""></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-4 mb-md-0">
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
                                    <h3 class="font-weight-bold text-4 mb-3">Sepet Özeti</h3>
                                    <div class="table-responsive mb-4">
                                        <table class="cart-totals w-100">
                                            <tbody class="border-top-0">
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
                                            </tbody>
                                        </table>
                                    </div>
                                    <h3 class="font-weight-bold text-4 mb-3">Ödeme Yöntemi</h3>
                                    <div id="shopPayment">
                                        <div class="radio-custom">
                                            <input type="radio" id="shopPaymentBankTransfer" name="paymentMethod" checked="">
                                            <label class="font-weight-semibold" for="shopPaymentBankTransfer">Banka Havale / Eft</label>
                                        </div>
                                        <div class="radio-custom">
                                            <input type="radio" id="shopPaymentCheque" name="paymentMethod">
                                            <label class="font-weight-semibold" for="shopPaymentCheque">Kredi Kartı</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col text-right">
                                    <button class="btn btn-primary btn-rounded font-weight-bold btn-h-2 btn-v-3" type="submit">SİPARİŞİ TAMAMLA</button>
                                </div>
                            </div>
                        </form>
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
                updateCartInCheckOut();
            });
        });
    </script>

<?php $view['slots']->stop(); ?>
