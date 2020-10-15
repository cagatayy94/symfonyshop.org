<?php $view->extend('Web/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
    <div role="main" class="main">
        <section class="page-header">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li><a href="/">Anasayfa</a></li>
                            <li class="active">Adres Seçimi</li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="font-weight-bold">Adres Seçimi</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="section" style="padding-top: 0">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <form id="address_selection" action="<?php echo $this->get('router')->path('cart_update_address_and_cargo') ?>" method="post">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#add_address_modal">Yeni Adres Ekle</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row d-flex justify-content-center">
                                        <h2 class="font-weight-bold mb-3">Fatura Adresi</h2>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 billing_address_holder">

                                        

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row row d-flex justify-content-center">
                                        <h2 class="font-weight-bold mb-3">Kargo Adresi</h2>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 shipping_address_holder">

                                         Lütfen Adres Ekleyin

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col">
                                    <label class="text-color-dark font-weight-semibold" for="shipping_company">KARGO FIRMASI:</label>
                                    <select class="form-control line-height-1 bg-light-5 required" name="shipping_company_id" id="shipping_company" required="">
                                        <option value="">Seçiniz</option>
                                        <?php foreach ($cargoCompany as $value): ?>
                                            <option value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col text-right">
                                    <button class="btn btn-primary btn-rounded font-weight-bold btn-h-2 btn-v-3" type="submit">ÖDEME ADIMINA GEÇ</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="add_address_modal" tabindex="-1" role="dialog" aria-labelledby="add_address_modal" aria-hidden="true">
        <div class="modal-dialog text-left" role="document">
            <div class="modal-content">
                <form action="<?php echo $this->get('router')->path('add_user_addresses') ?>" method="post" class="contact-form form-style-2" id="add_address_form">
                    <div class="modal-header">
                        <h5 class="modal-title">Yeni adres ekleyin</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="adres-ismi" class="form-control-label">*Adres başlığı (Ev, İş)</label>
                            <input type="text" name="address_name" class="form-control required">
                            <label for="adres-ismi" class="form-control-label">*Adresteki isim</label>
                            <input type="text" name="full_name" class="form-control required">
                            <label for="adres" class="form-control-label">*Adres</label>
                            <input type="text" name="address" class="form-control required">
                            <label for="ilce" class="form-control-label">*İlçe</label>
                            <input type="text"  name="county" class="form-control required">
                            <label for="sehir" class="form-control-label">*Şehir</label>
                            <input type="text" name="city" class="form-control required">
                            <label for="telefon"  class="form-control-label">*Telefon</label>
                            <input type="text" name="mobile" class="form-control mobile-mask required">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                        <button type="button" class="btn" data-dismiss="modal">Kapat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="update_address_modal_in_cart" tabindex="-1" role="dialog" aria-labelledby="update_address_modal_in_cart" aria-hidden="true">
        <div class="modal-dialog text-left" role="document">
            <div class="modal-content">
                <form action="<?php echo $this->get('router')->path('update_user_addresses') ?>" method="post" class="contact-form form-style-2" id="update_address_form_in_cart">
                    <input type="hidden" name="address_id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModal4Label">Adres Güncelle</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="address_name" class="form-control-label">*Adres başlığı (Ev, İş)</label>
                            <input type="text" name="address_name" class="form-control required">
                            <label for="adres-ismi" class="form-control-label">*Adresteki isim</label>
                            <input type="text" name="full_name" class="form-control required">
                            <label for="adres" class="form-control-label">*Adres</label>
                            <input type="text" name="address" class="form-control required">
                            <label for="ilce" class="form-control-label">*İlçe</label>
                            <input type="text"  name="county" class="form-control required">
                            <label for="sehir" class="form-control-label">*Şehir</label>
                            <input type="text" name="city" class="form-control required">
                            <label for="telefon"  class="form-control-label">*Telefon</label>
                            <input type="tel" name="mobile" class="form-control mobile-mask required">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input align="right" class="btn btn-primary" value="Güncelle" type="Submit" class="form-control">
                        <button type="button" class="btn" data-dismiss="modal">Kapat</button>
                    </div>
                </form>
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
                updateAddressesInCart();
            });
        });
    </script>

<?php $view['slots']->stop(); ?>
