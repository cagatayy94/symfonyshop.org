<?php $view->extend('Web/default.html.php'); ?>
<?php $view['slots']->start('body'); ?>
<div role="main" class="main">
    <section class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li><a href="/">Anasayfa</a></li>
                        <li class="active">Profilim</li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h1 class="font-weight-bold">Profilim</h1>
                </div>
            </div>
        </div>
    </section>
    <div  class="col">
        <div class="row">
            <div class="col-md-12 mb-5 mb-md-0">
                <ul class="nav nav-tabs nav-tabs-default" id="tabDefault" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="profile-information-tab" data-toggle="tab" href="#profile-information" role="tab" aria-controls="profile-information" aria-expanded="true">ÜYELİK BİLGİLERİM</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="addresses-tab" data-toggle="tab" href="#addresses" role="tab" aria-controls="addresses">ADRESLERİM</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="favorites-tab" data-toggle="tab" href="#favorites" role="tab" aria-controls="favorites">FAVORİLERİM</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="comments-tab" data-toggle="tab" href="#comments" role="tab" aria-controls="comments">YORUMLARIM</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="orders-tab" data-toggle="tab" href="#orders" role="tab" aria-controls="orders">SİPARİŞLERİM</a>
                    </li>
                </ul>
                <div class="tab-content" id="tabDefaultContent">
                    <div class="tab-pane fade pt-4 pb-4 show active" id="profile-information" role="tabpanel" aria-labelledby="profile-information-tab">
                        <div class="container">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>
                                            Kayıt Tarihi
                                        </td>
                                        <td colspan="2">
                                            <?php echo ($user->getCreatedAt())->format('d.m.Y H:i:s');?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Adınız Soyadınız
                                        </td>
                                        <td>
                                            <?php echo $user->getName();?>
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Email Adresiniz
                                        </td>
                                        <td>
                                            <?php echo $user->getEmail();?>
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Telefon numaranız
                                        </td>
                                        <td id="phone">
                                            <input id="mobile-number-on-profile" style="background-color: white" class="form-control mobile-mask" type="text" disabled="disabled" value="<?php echo $user->getMobile();?>">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#change_mobile_modal">Telefon Değiştir</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Şifreniz
                                        </td>
                                        <td>
                                            **************
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#change_password_modal">Parola Değiştir</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="tab-pane fade pt-4 pb-4" id="addresses" role="tabpanel" aria-labelledby="addresses-tab">
                        <p class="mb-0">
                            adresler
                        </p>
                    </div>
                    <div class="tab-pane fade pt-4 pb-4" id="favorites" role="tabpanel" aria-labelledby="favorites-tab">
                        <p class="mb-0">
                            favoriler
                        </p>
                    </div>
                    <div class="tab-pane fade pt-4 pb-4" id="comments" role="tabpanel" aria-labelledby="comments-tab">
                        <p class="mb-0">
                            yorumlarım
                        </p>
                    </div>
                    <div class="tab-pane fade pt-4 pb-4" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                        <p class="mb-0">
                            siparişlerim
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="change_mobile_modal" tabindex="-1" role="dialog" aria-labelledby="change_mobile_modal" aria-hidden="true">
    <div class="modal-dialog text-left" role="document">
        <div class="modal-content">
            <form action="<?php echo $this->get('router')->path('change_mobile_on_profile') ?>" method="post" id="change_mobile_form">
                <div class="modal-header">
                    <h5 class="modal-title" id="change_mobile_modal_title">Telefon Numaranızı Değiştirin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="phone" class="form-control-label">Yeni Telefon Numaranız</label>
                        <input type="text" value="<?php echo $user->getMobile();?>" name="mobile" class="form-control mobile-mask required bg-light-5">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Değiştir</button>
                    <button type="button" class="btn" data-dismiss="modal">Vazgeç</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="change_password_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModal4Label" aria-hidden="true">
    <div class="modal-dialog text-left" role="document">
        <div class="modal-content">
            <form action="<?php echo $this->get('router')->path('change_password_on_profile') ?>" method="post" id="change_password_on_profile_form">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal4Label">Sifrenizi Değiştirin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="current_password" class="form-control-label">Mevcut Parolanız:</label>
                        <input pattern=".{7,}" title="Lütfen en az 7 karakter yazınız" required type="password" name="current_password" class="form-control required bg-light-5">
                    </div>
                    <div class="form-group">
                        <label for="new_password" class="form-control-label">Yeni Parolanız:</label>
                        <input pattern=".{7,}" title="Lütfen en az 7 karakter yazınız" required type="password" name="new_password" class="form-control required bg-light-5">
                    </div>
                    <div class="form-group">
                        <label for="new_password_again" class="form-control-label">Yeni Parolanız Tekrar:</label>
                        <input pattern=".{7,}" title="Lütfen en az 7 karakter yazınız" required type="password" name="new_password_repeat" class="form-control required bg-light-5">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" >Değiştir</button>
                    <button type="button" class="btn" data-dismiss="modal">Kapat</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $view['slots']->stop(); ?>
