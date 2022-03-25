<footer id="footer" class="footer-hover-links-light">
    <section class="section bg-dark-4 py-5 mt-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-2">
                    <h2 class="text-color-light text-4">BÜLTEN ÜYELİĞİ</h2>
                </div>
                <div class="col-md-4">
                    <p>Haftalık indirimleri ve haberleri kaçırmak istemiyorsanız bültenimize abone olabilirsiniz. <br><small>İstediğiniz zaman ayrılabilirsiniz</small></p>
                </div>
                <div class="col-md-6">
                    <form id="newsletter_form" class="newsletter-form" action="<?php echo $this->get('router')->path('email_subscribe') ?>" method="POST">
                        <div class="input-group bg-light rounded">
                            <input name="email" required="required" type="email" class="newsletter-email form-control border-0 rounded required" placeholder="Email yazınız" aria-label="Email yazınız">
                            <span class="input-group-btn p-1">
                                <button class="btn btn-primary font-weight-semibold btn-h-2 rounded h-100" type="submit">ABONE OL</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <div class="container">
        <div class="row">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <a href="index" class="logo">
                    <img alt="SiteLogo" class="img-fluid mb-3" src="/web/img/logo-shop-light.png">
                </a>
                <p> <?php echo $footerData['footer_text']; ?> </p>
                <ul class="list list-icons list-unstyled">
                    <li class="mb-2"><i class="fas fa-angle-right mr-2 ml-1"></i> <span class="text-color-light">Adres:</span><?php echo $footerData['address']; ?></li>

                    <li class="mb-2"><i class="fas fa-angle-right mr-2 ml-1"></i> <span class="text-color-light">Telefon:</span> <a href="tel:+9<?php echo $footerData['phone']; ?>"><?php echo $footerData['phone']; ?></a></li>
                    <li class="mb-2"><i class="fas fa-angle-right mr-2 ml-1"></i> <span class="text-color-light">Email:</span> <a href="mailto:<?php echo $footerData['mail']; ?>" class="link-underline-light"><?php echo $footerData['mail']; ?></a></li>
                </ul>
            </div>
            <div class="col-lg-2 mb-4 mb-lg-0">
                <h2 class="text-3 mb-3">Kurumsal</h2>
                <ul class="list list-icon list-unstyled">
                    <li class="mb-2"><i class="fas fa-angle-right mr-2 ml-1"></i> <a href="<?php echo $this->get('router')->path('about_us'); ?>">Hakkımızda</a></li>
                    <li class="mb-2"><i class="fas fa-angle-right mr-2 ml-1"></i> <a href="<?php echo $this->get('router')->path('bank_accounts'); ?>">Banka Hesaplarımız</a></li>
                    <li class="mb-2"><i class="fas fa-angle-right mr-2 ml-1"></i> <a href="<?php echo $this->get('router')->path('order_notice'); ?>">Havale Bildirim Formu</a></li>
                    <li class="mb-2"><i class="fas fa-angle-right mr-2 ml-1"></i> <a href="kargom-nerede">Kargo Nerede?</a></li>
                    <li class="mb-2"><i class="fas fa-angle-right mr-2 ml-1"></i> <a href="<?php echo $this->get('router')->path('contact'); ?>">İletişim</a></li>
                </ul>
            </div>
            <div class="col-lg-2 mb-4 mb-lg-0">
                <h2 class="text-3 mb-3">    Üyelik &amp; Hizmetler</h2>
                <ul class="list list-icon list-unstyled">
                    <li class="mb-2"><i class="fas fa-angle-right mr-2 ml-1"></i> <a href="giris-kayit">Giriş Yap</a></li>
                    <li class="mb-2"><i class="fas fa-angle-right mr-2 ml-1"></i> <a href="giris-kayit">Yeni Üye Ol</a></li>
                    <li class="mb-2"><i class="fas fa-angle-right mr-2 ml-1"></i> <a href="<?php echo $this->get('router')->path('sss'); ?>">Sık Sorulan Sorular</a></li>
                </ul>
            </div>
            <div class="col-lg-3 mb-4 mb-lg-0">
                <h2 class="text-3 mb-3">Sözleşmeler</h2>
                <ul class="list list-icon list-unstyled">
                    <li class="mb-2"><i class="fas fa-angle-right mr-2 ml-1"></i> <a href="<?php echo $this->get('router')->path('sign_up_aggreement'); ?>">Üyelik Sözleşmesi</a></li>
                    <li class="mb-2"><i class="fas fa-angle-right mr-2 ml-1"></i> <a href="<?php echo $this->get('router')->path('terms_of_use'); ?>">Kullanım Koşulları</a></li>
                    <li class="mb-2"><i class="fas fa-angle-right mr-2 ml-1"></i> <a href="<?php echo $this->get('router')->path('confidentiality_agreement'); ?>">Gizlilik Sözleşmesi</a></li>
                    <li class="mb-2"><i class="fas fa-angle-right mr-2 ml-1"></i> <a href="<?php echo $this->get('router')->path('distant_sale_agreement'); ?>">Mesafeli Satış Sözleşmesi</a></li>
                    <li class="mb-2"><i class="fas fa-angle-right mr-2 ml-1"></i> <a href="<?php echo $this->get('router')->path('deliverables'); ?>">Teslimatlar</a></li>
                    <li class="mb-2"><i class="fas fa-angle-right mr-2 ml-1"></i> <a href="<?php echo $this->get('router')->path('cancel_refund_change'); ?>">İptal &amp; İade &amp; Değişim</a></li>
                </ul>
            </div>

        </div>
    </div>
    <div class="footer-copyright">
        <div class="container">
            <div class="row text-center text-md-left align-items-center">
                <div class="col-md-7 col-lg-8">
                    <ul class="social-icons social-icons-transparent social-icons-icon-light social-icons-lg">
                        <?php if (isset($footerData['facebook'])): ?>
                            <li class="social-icons-facebook"><a href="https://www.facebook.com/<?php echo $footerData['facebook'] ?>" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                        <?php endif; ?>
                        <?php if (isset($footerData['pinterest'])): ?>
                            <li class="social-icons-pinterest"><a href="https://tr.pinterest.com/<?php echo $footerData['pinterest'] ?>" target="_blank" title="Pinterest"><i class="fab fa-pinterest"></i></a></li>
                        <?php endif; ?>
                        <?php if (isset($footerData['twitter'])): ?>
                            <li class="social-icons-twitter"><a href="https://twitter.com/<?php echo $footerData['twitter'] ?>" target="_blank" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                        <?php endif; ?>
                        <?php if (isset($footerData['instagram'])): ?>
                            <li class="social-icons-instagram"><a href="https://www.instagram.com/<?php echo $footerData['instagram'] ?>" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a></li>
                        <?php endif; ?>
                        <?php if (isset($footerData['linkedin'])): ?>
                            <li class="social-icons-linkedin"><a href="https://www.linkedin.com/<?php echo $footerData['linkedin'] ?>" target="_blank" title="Instagram"><i class="fab fa-linkedin"></i></a></li>
                        <?php endif; ?>
                        <?php if (isset($footerData['youtube'])): ?>
                            <li class="social-icons-youtube"><a href="https://www.youtube.com/<?php echo $footerData['youtube'] ?>" target="_blank" title="Instagram"><i class="fab fa-youtube"></i></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="col-md-5 col-lg-4">
                    <p class="text-md-right pb-0 mb-0"><?php echo $footerData['copyright']; ?></p>
                </div>
            </div>
        </div>
    </div>
</footer>
<footer id="footer2" class="footer-hover-links-light bg-dark-5 mt-0">
    <div class="container">
        <div class="row text-center">
            <div class="col">
                <a href="index.html" class="logo">
                    <img alt="EZY Website Template" class="img-fluid max-width-150 mb-5"
                         src="/web/img/placeholders/logo-footer.png" />
                </a>
            </div>
        </div>
        <div class="row text-center pt-0 pb-5">
            <div class="col">
                <p class="mb-2">1234 Street Name, City Name, USA</p>
                <a href="tel:+1234567890" class="d-block mb-2">(123) 456-7890</a>
                <a href="mailto:mail@example.com"
                   class="link-underline-light text-color-light">mail@example.com</a>
            </div>
        </div>
    </div>
</footer>
<?php $navigationData = json_decode($view['actions']->render('Web:DefaultController:/navigation/data'), true); ?>