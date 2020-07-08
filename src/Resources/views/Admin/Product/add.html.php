<?php $view->extend('Admin/default.html.php'); ?>

<?php $view['slots']->start('body'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Ürün
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->get('router')->path('admin_dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Yeni Ürün Ekle</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box-body no-padding">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Yeni Ürün Ekle</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" method="post" action="<?php echo $this->get('router')->path('admin_product_create') ?>" id="add_product">
                            <div class="box-body ">
                                <div class="form-group col-md-6">
                                    <label for="productName">Ürün İsmi</label>
                                    <input type="text" class="form-control" id="productName" placeholder="Ürün İsmi Yazınız" name="productName">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="productPrice">Ürün Fiyatı</label>
                                    <div class="input-group">
                                            <input type="text" class="form-control" placeholder="135.42" name="productPrice">
                                        <span class="input-group-addon">₺</span>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="cargoPrice">Kargo Ücreti</label>
                                    <div class="input-group">
                                            <input type="text" class="form-control" placeholder="12.34" name="cargoPrice">
                                        <span class="input-group-addon">₺</span>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="tax">Vergi Oranı</label>
                                    <div class="input-group">
                                        <select class="form-control">
                                            <option value="">Seçiniz</option>
                                            <option value="18">%18</option>
                                            <option value="8">%8</option>
                                        </select>
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="productDescription">Ürün Açıklaması</label>
                                    <textarea style="resize: none;" class="form-control" rows="5" placeholder="Detaylı Ürün Açıklaması"></textarea>
                                </div>
                                <div class="col-md-12">
                                    <div class="box box-info">
                                        <div class="box-header with-border">
                                            <h3 class="box-title pull-left">Kategori Seçiniz</h3>
                                            <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add_category_modal"><i class="fa fa-plus" aria-hidden="true"></i> Ekle</button>
                                        </div>
                                        <div class="box-body">
                                            <div class="form-group col-md-12">
                                                <div class="category-checkbox-holder row">
                                                    <label>Yükleniyor..</label>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="box box-info">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Varyant</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label for="tax">Varyant Başlığı</label>
                                                <input type="text" class="form-control" id="variantTitle" placeholder="Beden, Ayakkabı Numarası, Uzunluk, Renk vb." name="variantTitle">
                                            </div>
                                            <div class="elements-holder">
                                                <div class="variant-element">
                                                    <div class="form-group col-md-6">
                                                        <label for="tax"><span class="element-index">1. </span>Varyant İsmi</label>
                                                        <input type="text" class="form-control" placeholder="41-42 ve L-XL gibi" name="variantTitle[]">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="tax"><span class="element-index">1. </span>Varyant Stok Adedi</label>
                                                        <input type="text" class="form-control" placeholder="Stokta kaç adet varsa sayı ile yazınız" name="variantStock[]">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <button type="button" class="btn btn-primary clone-element"><i class="fa fa-plus" aria-hidden="true"></i> Ekle</button>
                                                <button type="button" class="btn btn-danger delete-clone"><i class="fa fa-trash" aria-hidden="true"></i> Sil</button>
                                            </div>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>                                
                                </div>
                                <div class="col-md-6">
                                    <div class="box box-info">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Ürün Fotoğraf</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="elements-holder">
                                                <div class="photo-element row" style="height: 170px; border-bottom: 1px solid gray; padding: 10px ">
                                                    <div class="col-md-6">
                                                        <label for="exampleInputFile"><span class="element-index">1. </span>Fotoğraf</label>
                                                        <input type="file" class="img-with-viewer" name="img[]">
                                                    </div>
                                                    <div class="col-md-6">
                                                       <img style="display: none;" width="150" height="150" class="img-viewer" src="#" alt="your image" />
                                                    </div>
                                                </div>  
                                            </div>
                                            <div class="form-group col-md-12">
                                                <p class="help-block">Aşağıdaki ekle butonuna basarak daha fazla fotoğraf ekleyebilirsiniz</p>
                                                <button type="button" class="btn btn-primary clone-element"><i class="fa fa-plus" aria-hidden="true"></i> Ekle</button>
                                                <button type="button" class="btn btn-danger delete-clone"><i class="fa fa-trash" aria-hidden="true"></i> Sil</button>
                                            </div>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary">Kaydet</button>
                            </div>
                        </form>
                    </div>
                </div>
              <!-- /.box -->
            </div>
        </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<div class="modal fade" id="add_category_modal" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Kategori Ekle</h4>
            </div>
            <form action="<?php echo $this->get('router')->path('admin_category_add') ?>" method="post" id="add_category_form">
                <div class="modal-body">
                    <label for="productName">Kategori İsmi</label>
                    <input type="text" class="form-control required" placeholder="Kategori İsmi Yazınız" name="categoryName">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script type="text/javascript">
    $( document ).ready(function() {
        loadCategories();
    });
</script>
<?php $view['slots']->stop(); ?>
