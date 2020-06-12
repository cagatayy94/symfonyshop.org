<?php $view->extend('Web/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
<div role="main" class="main">
    <section class="section">
        <div class="container">
            <div class="row text-center">
                <div class="col">
                    <h2 class="font-weight-bold appear-animation animated fadeInUpShorter appear-animation-visible" data-appear-animation="fadeInUpShorter" style="animation-delay: 100ms;">Çalıştığımız bankalar aşağıda yer almaktadır.</h2>
                    <p class="lead appear-animation animated fadeInUpShorter appear-animation-visible" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="200" style="animation-delay: 200ms;">Havale yaptıktan sonra havale bildirimi yapmayı unutmayınız. Havale bildirimi yapmak için <a href="2222">tıklayınız.</a></p>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <?php foreach ($bankAccounts as $value): ?>
                <div class="col-sm-6 col-lg-4 col-xl-4 pb-2">
                    <div class="card card-style-5 bg-light-5 rounded border-1 p-3">
                        <div class="card-body p-4">
                            <h3 class="font-weight-bold text-4 mb-1"><?php echo $value['name']; ?></h3>
                            <p class="text-color-dark font-weight-semibold mb-0">
                                <img src="web/img/bank/<?php echo $value['logo']; ?>" width="149" height="30" alt="">
                            </p>
                            <p>
                                <b>Konum :</b> <?php echo $value['country'].' / '.$value['city']; ?>
                                <br>
                                <b>Şube :</b> <?php echo $value['branch_name'].' / '.$value['branch_code']; ?>
                                <br>
                                <b>Birim :</b> <?php echo $value['currency']; ?>Türk Lirası
                                <br>
                                <b>Hesap Adı: </b><?php echo $value['account_owner']; ?>
                                <br>
                                <b>Banka No :</b><?php echo $value['account_number']; ?>
                                <br>
                                <b>IBAN :</b><?php echo $value['iban']; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endforeach ?>
            </div>
        </div>
    </section>
</div>
<?php $view['slots']->stop(); ?>
