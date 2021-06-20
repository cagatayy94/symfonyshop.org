<?php $view->extend('Web/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
<div role="main" class="main">
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-5 col-xl-4 mb-5 mb-md-0">
                    <div class="bg-primary rounded p-5">
                        <span class="top-sub-title text-color-light-2">ZATEN ÜYE MİSİNİZ?</span>
                        <h2 class="text-color-light font-weight-bold text-4 mb-4">Giriş Yap</h2>
                        <form method="post">
                            <input type="hidden" name="_csrf_token" value="<?php echo $token; ?>">
                            <div class="form-row">
                                <div class="form-group col mb-2">
                                    <label class="text-color-light-2" for="email">EMAIL ADRESİNİZ:<i>Try: </i></label>
                                    <input type="email" class="form-control bg-light border-0 rounded text-1" name="email" id="login-email" value="<?php echo $last_username ?>" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col">
                                    <label class="text-color-light-2" for="password">PAROLA:</label>
                                    <input type="password" class="form-control bg-light border-0 rounded text-1" name="password" id="login-password" required>
                                </div>
                            </div>
                            <div class="form-row mb-3">
                                <div class="form-group col">
                                    <div class="form-check checkbox-custom checkbox-custom-transparent checkbox-default">
                                        <input type="checkbox" name="_remember_me">
                                        <label class="form-check-label text-color-light-2" for="_remember_me">
                                            Beni hatırla
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col text-right">
                                    <a href="<?php echo $this->get('router')->path('reset_password') ?>" class="forgot-pw text-color-light-2 d-block">Parolamı Unuttum</a>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col text-right">
                                    <button type="submit" class="btn btn-dark btn-rounded btn-v-3 btn-h-3 font-weight-bold">GİRİŞ YAP</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-6 col-lg-7 col-xl-8 pt-3">
                    <span class="top-sub-title">YENİ KULLANICI</span>
                    <h2 class="font-weight-bold text-4 mb-1">Hesabınız yok mu? Şimdi Kaydolun.</h2>
                    <p class="lead mb-4">Kayıt yaptığınızda size özel fırsatlarımızdan yararlanabilirsiniz.</p>
                    <form id="register_form" method="post" action="<?php echo $this->get('router')->path('register') ?>">
                        <div class="form-row">
                            <div class="form-group col-lg-6">
                                <label class="text-color-dark" for="register-name">*ADINIZ SOYADINIZ:</label>
                                <input type="text" class="form-control bg-light-5 border-0 rounded required" name="username" id="register-name" required>
                            </div>
                            <div class="form-group col-lg-6">
                                <label class="text-color-dark" for="register-email">*EMAIL ADRESİNİZ:</label>
                                <input type="email" title="Geçerli bir mail adresi yazınız" class="form-control bg-light-5 border-0 rounded required" name="email" id="register-email" required="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-lg-6">
                                <label class="text-color-dark" for="register-password">*PAROLA:</label>
                                <input type="password" pattern=".{7,}" title="Lütfen en az 7 karakter yazınız" class="form-control bg-light-5 border-0 rounded required" name="password" id="register-password" required>
                            </div>
                            <div class="form-group col-lg-6">
                                <label class="text-color-dark" for="register-phone">TELEFON:</label>
                                <input type="text" pattern="0?\s?[0-9]{3}\s?[0-9]{3}\s?[0-9]{2}\s?[0-9]{2}" title="Geçerli bir telefon numarası yazınız veya boş bırakınız" class="form-control bg-light-5 border-0 rounded" name="phone" id="register-phone">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-lg-6">
                                <div class="form-check checkbox-custom">
                                    <input class="form-check-input required" name="agreement" value="1" type="checkbox" required id="agreement">
                                    <label class="form-check-label" for="register-agreement">
                                        <a href="<?php echo $this->get('router')->path('sign_up_aggreement'); ?>" target="_blank">Kullanıcı Sözleşmesini</a> Okudum ve Onaylıyorum.
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col text-right">
                                <button type="submit" class="btn btn-primary btn-rounded btn-v-3 btn-h-3 font-weight-bold">HESAP OLUŞTUR</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $flashbag = $this->get('session')->getFlashes();?>
<input type="hidden" name="error" id="error" value="<?php echo isset($flashbag['error'][0]) ? $flashbag['error'][0] : 0 ?>">
<?php $view['slots']->start('js'); ?>
    <script type="text/javascript">
        var errorType = $('#error').val();
        if (errorType != "0" && errorType == 'email_is_not_approved') {
            toastr.info('Email adresi onaylanmamış lütfen email gelen kutunuzu kontrol ediniz.');
        }else if (errorType != "0"){
            toastr.error('Kullanıcı adı veya parola yanlış.');
        }
    </script>
<?php $view['slots']->stop(); ?>
<?php $view['slots']->stop(); ?>
