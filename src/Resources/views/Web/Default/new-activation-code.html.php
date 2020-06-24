<?php $view->extend('Web/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
<div role="main" class="main">
    <section class="section bg-light-5">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="alert alert-warning">
                    <strong>Dikkat</strong> Kodun iletilmesi bazen 15-20 dakikayı bulabilmektedir. Email gelen kutunuzu ve spam klasörünü kontrol etmeyi unutmayınız.
                </div>
                <div class="col-md-8 col-lg-6">
                    <p class="mb-5 appear-animation animated fadeInUpShorter appear-animation-visible" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="400" style="animation-delay: 400ms;">Lütfen email adresinizi yazarak gönder butonuna basınız.</p>
                    <form id="refresh-email-approve-code" method="post">
                    <div style="display: none;" class="alert alert-success alert-success-div">
                        <strong>Başarılı!</strong> Size tekrar onay epostası gönderdik lütfen mail kutunuzu kontrol ediniz.
                    </div>
                    <div style="display: none;" class="alert alert-warning alert-success-div">
                        Onay e-postası elinize ulaşmadıysa: <br> 1.Spam klasörünü kontrol ediniz. <br> 2.Girdiğiniz e-posta adresini kontrol ediniz.<br>3. Eğer hala e-posta elinize ulaşmadıysa tekrar deneyiniz.
                    </div>
                        <div class="input-group bg-light rounded">
                            <input type="email" name="email" class="newsletter-email form-control border-0 rounded" placeholder="Email yazınız" aria-label="Email yazınız" required>
                            <span class="input-group-btn p-1">
                                <button class="btn btn-primary font-weight-semibold btn-h-2 rounded h-100" type="submit">GÖNDER</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>      
</div>
<input type="hidden" name="result" id="result" value="<?php echo $result ?>">
<?php $view['slots']->start('js'); ?>
    <script type="text/javascript">
        var result = $('#result').val();

        if (result == "1") {
            $('.alert-success-div').show();
        }
    </script>
<?php $view['slots']->stop(); ?>
<?php $view['slots']->stop(); ?>
