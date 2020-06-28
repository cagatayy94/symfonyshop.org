<?php $view->extend('Web/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
<div role="main" class="main">
    <section class="section">
        <div class="container">
            <div class="row text-center">
                <div class="col">
                    <h2 class="font-weight-bold appear-animation animated fadeInUpShorter appear-animation-visible" data-appear-animation="fadeInUpShorter" style="animation-delay: 100ms;">Bizimle iletişime geçebilirsiniz</h2>
                    <p class="lead appear-animation animated fadeInUpShorter appear-animation-visible" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="200" style="animation-delay: 200ms;">Herhangi sorunuz veya sorununuz varsa, lütfen bizimle iletişime geçmekten çekinmeyin.</p>
                </div>
            </div>
            <div class="row pt-5">
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-12 col-md-4 col-lg-12 mb-lg-4 appear-animation animated fadeInLeftShorter appear-animation-visible" data-appear-animation="fadeInLeftShorter" style="animation-delay: 100ms;">
                            <div class="icon-box icon-box-style-1">
                                <div class="icon-box-icon">
                                    <i class="lnr lnr-apartment text-color-primary"></i>
                                </div>
                                <div class="icon-box-info mt-1">
                                    <div class="icon-box-info-title">
                                        <h3 class="font-weight-bold text-4 mb-0">Adres</h3>
                                    </div>
                                    <p><?php echo $siteSettings['address']; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-12 mb-lg-4 appear-animation animated fadeInLeftShorter appear-animation-visible" data-appear-animation="fadeInLeftShorter" data-appear-animation-delay="200" style="animation-delay: 200ms;">
                            <div class="icon-box icon-box-style-1">
                                <div class="icon-box-icon icon-box-icon-no-top">
                                    <i class="lnr lnr-envelope text-color-primary"></i>
                                </div>
                                <div class="icon-box-info mt-1">
                                    <div class="icon-box-info-title">
                                        <h3 class="font-weight-bold text-4 mb-0">Email Adresi</h3>
                                    </div>
                                    <p><a href="mailto:<?php echo $siteSettings['mail']; ?>"><?php echo $siteSettings['mail']; ?></a></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-12 appear-animation animated fadeInLeftShorter appear-animation-visible" data-appear-animation="fadeInLeftShorter" data-appear-animation-delay="400" style="animation-delay: 400ms;">
                            <div class="icon-box icon-box-style-1">
                                <div class="icon-box-icon">
                                    <i class="lnr lnr-phone-handset text-color-primary"></i>
                                </div>
                                <div class="icon-box-info mt-1">
                                    <div class="icon-box-info-title">
                                        <h3 class="font-weight-bold text-4 mb-0">Telefon Numarası</h3>
                                    </div>
                                    <p><a href="tel:+90<?php echo $siteSettings['phone']; ?>"><?php echo $siteSettings['phone']; ?></a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 appear-animation animated fadeInRightShorter appear-animation-visible" data-appear-animation="fadeInRightShorter" style="animation-delay: 100ms;">
                    <form class="contact-form form-style-2" id="contact_form" method="post" action="<?php echo $this->get('router')->path('contact_submit'); ?>">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input aria-label="" type="text" value="" pattern="[A-Z/ çÇğĞöÖşŞıİüÜa-z]{3,}" title="Lütfen geçerli bir isim yazınız" data-msg-required="Lütfen adınızı yazınız" maxlength="100" class="form-control required" name="name" placeholder="*İsim Soyisim" required="">
                            </div>
                            <div class="form-group col-md-6">
                                <input aria-label="" type="email" value="" data-msg-required="Lütfen mail yazınız" data-msg-email="Lütfen geçerli bir mail yazınız" maxlength="100" class="form-control required" name="email" placeholder="*E-mail Adresi" required="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input aria-label="" type="text" value="" pattern="[A-Z/ çÇğĞöÖşŞıİüÜa-z]{3,}" title="Lütfen geçerli bir konu belirtiniz" data-msg-required="Lütfen konu belirtiniz." maxlength="100" class="form-control required" name="subject" placeholder="*Konu" required="">
                            </div>
                            <div class="form-group col-md-6">
                                <input type="text" aria-label="" value="" pattern="0?\s?[0-9]{3}\s?[0-9]{3}\s?[0-9]{2}\s?[0-9]{2}" title="Geçerli bir telefon numarası yazınız" data-msg-required="Lütfen telefon numaranızı yazınız." data-msg-email="Lütfen geçerli bir telefon numarası yazınız." maxlength="100" class="form-control" name="mobile" placeholder="*Telefon numaranız" required="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col">
                                <textarea aria-label="" maxlength="5000" data-msg-required="Lütfen mesaj belirtiniz" rows="5" class="form-control" name="message"  placeholder="*Mesajınız" required=""></textarea>
                            </div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="col">
                                <input type="submit" value="Mesajı Gönder" class="btn btn-primary btn-rounded btn-4 font-weight-semibold text-0">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $view['slots']->stop(); ?>
