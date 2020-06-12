<?php $view->extend('Web/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
<div role="main" class="main">
    <section class="section">
        <div class="container pb-md-5 mb-lg-5">
            <div class="row justify-content-center pb-4">
                <div class="col-lg-10 text-center">
                    <div class="appear-animation animated fadeInUpShorter appear-animation-visible" data-appear-animation="fadeInUpShorter" style="animation-delay: 100ms;">
                        <h2 class="font-weight-bold">Teslimatlar</h2>
                        <br>
                    </div>
                    <p class="text-color-light-3 appear-animation animated fadeInUpShorter appear-animation-visible" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="400" style="animation-delay: 400ms;"><?php echo $deliverables; ?></p>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $view['slots']->stop(); ?>
