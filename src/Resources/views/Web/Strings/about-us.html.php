<?php $view->extend('Web/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
<div role="main" class="main">
    <section class="section">
        <div class="container pb-md-5 mb-lg-5">
            <div class="row justify-content-center pb-4">
                <div class="col-lg-10 text-center">
                    <div class="appear-animation animated fadeInUpShorter appear-animation-visible" data-appear-animation="fadeInUpShorter" style="animation-delay: 100ms;">
                        <span class="top-sub-title text-color-primary">Know Us Better</span>
                        <h2 class="font-weight-bold">We Have Passion</h2>
                    </div>
                    <p class="lead mb-4 appear-animation animated fadeInUpShorter appear-animation-visible" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="200" style="animation-delay: 200ms;">Incredible Designs</p>
                    <p class="text-color-light-3 appear-animation animated fadeInUpShorter appear-animation-visible" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="400" style="animation-delay: 400ms;"><?php echo $aboutUs;  ?></p>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $view['slots']->stop(); ?>
