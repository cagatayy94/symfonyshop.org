<?php $view->extend('Web/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
<div role="main" class="main">
    <section class="section bg-light-5">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-md-8 col-lg-6">
                    <p class="mb-5 appear-animation animated fadeInUpShorter appear-animation-visible" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="400" style="animation-delay: 400ms;">Lütfen yeni parola yazınız.</p>
                    <form id="change_password_form" method="post" action="<?php echo $this->get('router')->path('new_password_create') ?>">
                    <input type="hidden" name="email" value="<?php echo $email ?>">
                    <input type="hidden" name="code" value="<?php echo $code ?>">
                    <div style="display: none;" class="alert alert-success alert-success-div">
                        <strong>Başarılı!</strong> Parolanız başarıyla sıfırlandı giriş sayfasına yönlendiriliyorsunuz.
                    </div>
                    <div style="display: none;" class="alert alert-success alert-success-div">
                        <strong>Hata!</strong> Parolanız sıfırlanamadı parolalar uyuşmuyor.
                    </div>
                    <div style="display: none;" class="alert alert-success alert-success-div">
                        <strong>Hata!</strong> Bir sorun oluştu parolanızı tekrar sıfırlamayı deneyin.
                    </div>
                    <div class="form-row">
                        <div class="form-group col-lg-6">
                            <label class="text-color-dark" for="register-password">*PAROLA:</label>
                            <input type="password" pattern=".{7,}" title="Lütfen en az 7 karakter yazınız" class="form-control border-0 rounded required" name="password" id="register-password" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="text-color-dark" for="register-password-repeat">*PAROLA TEKRAR:</label>
                            <input type="password" pattern=".{7,}" title="Lütfen en az 7 karakter yazınız" class="form-control border-0 rounded required" name="passwordRepeat" id="register-password-repeat" required="">
                        </div>
                        <div class="form-group col-lg-12" style="text-align: center!important;">
                            <button class="btn btn-primary font-weight-semibold btn-h-2 rounded h-100" type="submit">SIFIRLA</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </section>      
</div>
<?php $view['slots']->stop(); ?>
