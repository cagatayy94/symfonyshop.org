<?php $view->extend('Web/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
<div role="main" class="main">
    <section class="section">
        <div class="container">
            <div class="row text-center">
                <div class="col">
                    <h2 class="font-weight-bold appear-animation animated fadeInUpShorter appear-animation-visible" data-appear-animation="fadeInUpShorter" style="animation-delay: 100ms;">Havale bildirim formu</h2>
                    <p class="lead appear-animation animated fadeInUpShorter appear-animation-visible" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="200" style="animation-delay: 200ms;">Havale yaptıktan sonra bize bildirmek için aşağıdaki formu doldurun.</p>
                </div>
            </div>
            <div class="row pt-5">
                <div class="col-lg-12 appear-animation animated fadeInRightShorter appear-animation-visible" data-appear-animation="fadeInRightShorter" style="animation-delay: 100ms;">
                    <form method="post" class="contact-form form-style-2" id="order_notice_form" action="<?php echo $this->get('router')->path('order_notice_submit') ?>">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input type="text" class="form-control required" name="name" placeholder="*İsim Soyisim">
                            </div>
                            <div class="form-group col-md-6">
                                <input type="text" class="form-control required" name="email" placeholder="*E-mail Adresi">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <select style="height: 47px!important" type="select" class="form-control required" name="bankId">
                                    <option value="">Seçiniz</option>
                                    <?php foreach ($bankAccounts as $value): ?>
                                        <option value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <input type="text" class="form-control required" name="mobile" placeholder="*Telefon numaranız">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col">
                                <textarea rows="5" class="form-control" name="message" placeholder="Mesajınız"></textarea>
                            </div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="col">
                                <button type="submit" class="btn btn-primary btn-rounded btn-4 font-weight-semibold text-0">Mesajı Gönder</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $view['slots']->stop(); ?>
