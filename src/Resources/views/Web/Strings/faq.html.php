<?php $view->extend('Web/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
<div role="main" class="main">
    <section class="section">
        <div class="container pb-md-5 mb-lg-5">
            <div class="row justify-content-center pb-4">
                <div class="col-lg-10 text-center">
                    <div class="appear-animation animated fadeInUpShorter appear-animation-visible" data-appear-animation="fadeInUpShorter" style="animation-delay: 100ms;">
                        <h2 class="font-weight-bold">Sık Sorulan Sorular</h2>
                        <br>
                    </div>
                    <p class="text-color-light-3 appear-animation animated fadeInUpShorter appear-animation-visible" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="400" style="animation-delay: 400ms;">Sık Sorulan Sorulara Aşağıdan Ulaşabilirsiniz.  Sorunun cevabı için üstüne tıklamanız yeterli :) </p>
                </div>
                <div class="col-md-10 col-lg-10 order-1 mb-5 mb-md-0">
                    <div class="row">
                        <div class="col">
                            <div id="toggleDefault" class="accordion accordion-minimal accordion-toggle" role="tablist">
                                <?php if (count($sss)): ?>
                                    <?php $i = 1; foreach ($sss as $value): ?>
                                        <div class="card">
                                            <div class="card-header accordion-header" role="tab" id="toggleMinimalFaq<?php echo $i; ?>">
                                                <h5 class="mb-0">
                                                    <a href="#" class="collapsed" data-toggle="collapse" data-target="#toggleMinimalCollapseFaq<?php echo $i; ?>" aria-expanded="false" aria-controls="toggleMinimalCollapseFaq<?php echo $i; ?>"><?php echo $value['question']; ?></a>
                                                </h5>
                                            </div>
                                            <div id="toggleMinimalCollapseFaq<?php echo $i; ?>" class="collapse  " role="tabpanel" aria-labelledby="toggleMinimalFaq<?php echo $i; ?>">
                                                <div class="card-body">
                                                    <p class="mb-0"><?php echo $value['answer']; ?></p>
                                                </div>
                                            </div>
                                        </div>

                                    <?php $i++; endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $view['slots']->stop(); ?>
