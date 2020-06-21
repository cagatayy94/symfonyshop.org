<?php $view->extend('Web/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
<div role="main" class="main">
    <section class="section bg-light-5">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div style="display: none;" id="approved_success" class="alert alert-success">
                    <strong>Başarılı!</strong> Email adresi başarıyla onaylandı yönlendiriliyorsunuz.. Tarayıcınız yönlendirmeyi desteklemiyorsa <a href="<?php echo $this->get('router')->path('login') ?>" class="alert-link">tıklayınız.</a>
                </div>
                <div style="display: none;" id="approved_allready" class="alert alert-warning">
                    <strong>Daha önce onaylanmış.</strong> Email adresi daha önce onaylanmış. Giriş yapmak için <a href="<?php echo $this->get('router')->path('login') ?>" class="alert-link">tıklayınız.</a>
                </div>
                <div style="display: none;" id="invalid_crediantials" class="alert alert-danger">
                    <strong>Hata!</strong> Email adresi veya aktivasyon kodu hatalı ya da kodun süresi geçmiş olabilir. Tekrar kod almak için<a href="<?php echo $this->get('router')->path('new_email_activation_code') ?>" class="alert-link"> tıklayınız.</a>
                </div>
                <div class="col-md-8 col-lg-6">
                    <a href="index.html" class="btn btn-primary btn-rounded btn-v-3 btn-h-3 font-weight-bold appear-animation animated fadeInUpShorter appear-animation-visible" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="800" style="animation-delay: 800ms;"><i class="fas fa-angle-left mr-3 text-3"></i> ANASAYFA</a>
                </div>
            </div>
        </div>
    </section>      
</div>
<input type="hidden" id="result" name="result" value="<?php echo $result['type'] ?>">
<?php $view['slots']->start('js'); ?>
    <script type="text/javascript">
        var resultType = $('#result').val();
        if (resultType == 'invalid_crediantials') {
            $('#invalid_crediantials').show();
        }else if(resultType == 'approved_allready'){
            $('#approved_allready').show();
        }else if(resultType == "1"){
            $('#approved_success').show();
            setTimeout(function(){ window.location = 'login'; }, 4000);
        }
    </script>
<?php $view['slots']->stop(); ?>
<?php $view['slots']->stop(); ?>
