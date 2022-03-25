<?php $view->extend('Admin/default.html.php'); ?>

<?php $view['slots']->start('body'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Product
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->get('router')->path('admin_dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Add Product Details</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box-body no-padding">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Product Detail</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" method="post" action="<?php echo $this->get('router')->path('admin_product_update') ?>" id="update_product">
                            <input type="hidden" name="id" value="<?php echo $id ?>">
                            <div class="box-body ">
                                <div class="form-group col-md-6">
                                    <label for="productName">Product Name</label>
                                    <input type="text" class="form-control required" id="productName" placeholder="Ürün İsmi Yazınız" value="<?php echo $product['name'] ?>" name="productName">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="productPrice">Product Price</label>
                                    <div class="input-group">
                                            <input type="text" class="form-control required float" placeholder="135.42" name="productPrice" value="<?php echo $product['price'] ?>">
                                        <span class="input-group-addon">₺</span>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="cargoPrice">Cargo Price</label>
                                    <div class="input-group">
                                            <input type="text" class="form-control float" placeholder="12.34" name="cargoPrice" value="<?php echo $product['cargo_price'] ?>">
                                        <span class="input-group-addon">₺</span>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="tax">Tax</label>
                                    <div class="input-group">
                                        <select name="tax" class="form-control required">
                                            <option <?php echo $product['tax'] == 18 ? 'selected="selected"' : '' ?> value="18">%18</option>
                                            <option <?php echo $product['tax'] == 8 ? 'selected="selected"' : '' ?> value="8">%8</option>
                                        </select>
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="productDescription">Description</label>
                                    <textarea style="resize: none;" name="description" class="form-control required" rows="5" placeholder="Detaylı Ürün Açıklaması"><?php echo $product['description'];?> </textarea>
                                </div>
                                <div class="col-md-12">
                                    <div class="box box-info">
                                        <div class="box-header with-border">
                                            <h3 class="box-title pull-left">Select Category</h3>
                                            <?php if ($admin->hasRole('category_create')): ?>
                                            <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add_category_modal"><i class="fa fa-plus" aria-hidden="true"></i> Add</button>
                                            <?php endif ?>
                                        </div>
                                        <div class="box-body">
                                            <div class="form-group col-md-12">
                                                <span class="exist-categories" data-value='<?php echo json_encode($product['category']) ?>'></span>
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
                                            <h3 class="box-title">Variant</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label for="tax">Variant Title</label>
                                                <input type="text" class="form-control required" id="variantTitle" placeholder="Beden, Ayakkabı Numarası, Uzunluk, Renk vb." name="variantTitle" value="<?php echo $product['variant_title'] ?>">
                                            </div>
                                            <div class="elements-holder">
                                                <?php $i = 1; foreach ($product['variant'] as $value): ?>
                                                <div class="variant-element">
                                                    <div class="form-group col-md-6">
                                                        <label for="tax"><span class="element-index"><?php echo $i; ?>. </span>Variant Name</label>
                                                        <input type="text" class="form-control required" placeholder="41-42 ve L-XL gibi" name="variantName[]" value="<?php echo $value['variant_name']?>">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="tax"><span class="element-index"><?php echo $i; ?>. </span>Variant stock quantity</label>
                                                        <input type="text" class="form-control required" placeholder="Stokta kaç adet varsa sayı ile yazınız" name="variantStock[]" value="<?php echo $value['variant_stock']?>">
                                                    </div>
                                                </div>
                                                <?php $i++; endforeach; ?>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <button type="button" class="btn btn-primary clone-element"><i class="fa fa-plus" aria-hidden="true"></i> Add</button>
                                                <button type="button" class="btn btn-danger delete-clone"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>
                                            </div>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>                                
                                </div>
                                <div class="col-md-6">
                                    <div class="box box-info col-md-12">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">Upload photos</h3>
                                            </div>
                                            <div class="box-body">
                                                <div class="elements-holder">
                                                    <?php foreach ($product['photo'] as $key => $value): ?>
                                                        <div class="photo-element row" style="height: 200px; border-bottom: 1px solid gray; padding: 10px; float: left ">
                                                            <div class="col-md-6">
                                                                <button type="button" data-delete-url="<?php echo $this->get('router')->path('admin_product_image_delete', ['id' => $value['id']]) ?>" style="position: absolute; margin-bottom: 56px; margin-left: 109px;" class="btn btn-danger pull-right delete-image">Delete</button>
                                                                <img width="150" height="150" class="img-viewer" src="<?php echo $value['path'] ?>" alt="your image" />
                                                            </div>
                                                        </div>  
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                            <!-- /.box-body -->
                                        </div>
                                        <div class="box box-info col-md-12">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">Product Pic</h3>
                                            </div>
                                            <div class="box-body">
                                                <div class="elements-holder">
                                                    <div class="photo-element row" style="height: 170px; border-bottom: 1px solid gray; padding: 10px ">
                                                        <div class="col-md-6">
                                                            <label for="exampleInputFile"><span class="element-index">1. </span>Pic</label>
                                                            <input type="file" class="img-with-viewer" name="img[]">
                                                        </div>
                                                        <div class="col-md-6">
                                                           <img style="display: none;" width="150" height="150" class="img-viewer" src="#" alt="your image" />
                                                        </div>
                                                    </div>  
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <p class="help-block">You can add more photo by button below</p>
                                                    <button type="button" class="btn btn-primary clone-element"><i class="fa fa-plus" aria-hidden="true"></i> Add</button>
                                                    <button type="button" class="btn btn-danger delete-clone"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>
                                                </div>
                                            </div>
                                            <!-- /.box-body -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <?php if ($admin->hasRole('product_update')): ?>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                <?php endif ?>
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
<?php if ($admin->hasRole('category_create')): ?>
<div class="modal fade" id="add_category_modal" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Category Add</h4>
            </div>
            <form action="<?php echo $this->get('router')->path('admin_category_add') ?>" method="post" id="add_category_form">
                <div class="modal-body">
                    <label for="productName">Name</label>
                    <input type="text" class="form-control required" placeholder="Kategori İsmi Yazınız" name="categoryName">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php endif; ?>
<script type="text/javascript">
    $( document ).ready(function() {
        loadCategories();
    });
</script>
<?php $view['slots']->stop(); ?>
