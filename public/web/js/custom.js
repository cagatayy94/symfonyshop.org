var cartItems;
var grandTotal;
var totalCargoPrice;
var uniqueProduct;
updateCartTotalAndQuantity();

$(document).ready(function() {
    toastr.options = {
        "preventDuplicates": true,
        "newestOnTop": true,
        "closeButton": true,
    }
});

$('#order_notice_form').on('submit', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);
    var url = self.attr('action');
    var method = self.attr('method');
    var data = self.serialize();
    var button = self.find(':submit');

    button.attr('disabled', 'disabled');

    var isValid = true;

    isValid = controlRequiredInputsAreFilled(self.find('.required'));

    if (isValid) {
        $.ajax({
            type: method,
            url: url,
            data: data,
            success: function (result) {
                if (result.success) {
                    toastr.success('Bildirim alındı. En yakın zamanda sizinle iletişime geçeceğiz.');
                    self.find(':input').val('');
                } else {
                    toastr.error(result.error.message);
                }
                button.removeAttr('disabled');
            }
        });
    } else {
        button.removeAttr('disabled');
    }
});

//form control is everything filled up?
function controlRequiredInputsAreFilled(array){
    var isValid = true;

    array.each(function() {
       if (!$(this).val().length > 0 || $(this).val() == '' || $(this).val() == 0) {
           isValid = false;
           $(this).closest('.form-group').addClass('has-error');
       } else {
           $(this).closest('.form-group').removeClass('has-error');
       }
    });

    if (!isValid) {
        toastr.warning('Tüm alanları doldurunuz.');
    }

    return isValid;
}

$('#register_form').on('submit', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);
    var url = self.attr('action');
    var method = self.attr('method');
    var data = self.serialize();
    var button = self.find(':submit');

    button.attr('disabled', 'disabled');

    var isValid = true;

    isValid = controlRequiredInputsAreFilled(self.find('.required'));

    if (isValid) {
        $.ajax({
            type: method,
            url: url,
            data: data,
            success: function (result) {
                if (result.success) {
                    toastr.success('Üyelik başarılı lütfen email gelen kutunuzu kontrol ediniz');
                    self.find(':input').val('');
                } else {
                    toastr.error(result.error.message);
                }
                button.removeAttr('disabled');
            }
        });
    } else {
        button.removeAttr('disabled');
    }
});

$('#change_password_form').on('submit', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);
    var url = self.attr('action');
    var method = self.attr('method');
    var data = self.serialize();
    var button = self.find(':submit');

    button.attr('disabled', 'disabled');

    var isValid = true;

    isValid = controlRequiredInputsAreFilled(self.find('.required'));

    if (isValid) {
        $.ajax({
            type: method,
            url: url,
            data: data,
            success: function (result) {
                console.log(result);
                if (result.success) {
                    toastr.success('Parolanız sıfırlandı şimdi giriş sayfasına yönlendiriliyorsunuz.');
                    self.find(':input').val('');
                    setTimeout(function(){ window.location = "/login"; }, 3000);
                } else {
                    toastr.error(result.error.message);
                }
                button.removeAttr('disabled');
            }
        });
    } else {
        button.removeAttr('disabled');
    }
});

$('#contact_form').on('submit', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);
    var url = self.attr('action');
    var method = self.attr('method');
    var data = self.serialize();
    var button = self.find(':submit');

    button.attr('disabled', 'disabled');

    var isValid = true;

    isValid = controlRequiredInputsAreFilled(self.find('.required'));

    if (isValid) {
        $.ajax({
            type: method,
            url: url,
            data: data,
            success: function (result) {
                if (result.success) {
                    toastr.success('Bildirim alındı. En yakın zamanda sizinle iletişime geçeceğiz.');
                    self.find(':input').val('');
                } else {
                    toastr.error(result.error.message);
                }
                button.removeAttr('disabled');
            }
        });
    } else {
        button.removeAttr('disabled');
    }
});

$('#newsletter_form').on('submit', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);
    var url = self.attr('action');
    var method = self.attr('method');
    var data = self.serialize();
    var button = self.find(':submit');

    button.attr('disabled', 'disabled');

    var isValid = true;

    isValid = controlRequiredInputsAreFilled(self.find('.required'));

    if (isValid) {
        $.ajax({
            type: method,
            url: url,
            data: data,
            success: function (result) {
                if (result.success) {
                    toastr.success('Başarılı! Email listemize başarıyla eklendiniz.');
                    self.find(':input').val('');
                } else {
                    toastr.error(result.error.message);
                }
                button.removeAttr('disabled');
            }
        });
    } else {
        button.removeAttr('disabled');
    }
});

$('.pagination-action').on('click', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);
    var form = self.closest('form');

    var currentPage = form.find('input[name="currentPage"]').val();

    var requestedPage = self.attr('data-page');

    if (currentPage == requestedPage) {
        return false;
    }else{
        form.find('input[name="currentPage"]').val(requestedPage);
        form.submit();
    }
});

$('.order-filter-submit').on('change', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);
    var form = self.closest('form');

    var currentPage = form.find('input[name="currentPage"]').val();

    var requestedPage = self.attr('data-page');

    if (currentPage == requestedPage) {
        return false;
    }else{
        form.find('input[name="currentPage"]').val(requestedPage);
        form.submit();
    }
});

$('.category-filter').on('click', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);
    var form = self.closest('form');
    var categoryId = self.attr('data-category-id');

    var currentCategory = form.find('input[name="categoryId"]').val();

    if (categoryId == currentCategory) {
        return false;
    }else{
        form.find('input[name="categoryId"]').val(categoryId);
        form.submit();
    }
});

$('.add-to-favorite').on('click', function (e) {
    var self = $(this);
    var productId = self.attr('data-product-id');
    var url = self.attr('data-url');

    $.ajax({
        type: 'POST',
        url: url,
        data: {productId},
        success: function (result) {
            if (result.success) {
                toastr.success('Başarılı! Favorilere eklendi.');
            } else {
                toastr.error(result.error.message);
            }
        }
    });
});

$('#cart_add').on('submit', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);
    var url = self.attr('action');
    var method = self.attr('method');
    var data = self.serialize();
    var button = self.find(':submit');

    button.attr('disabled', 'disabled');

    var isValid = true;

    isValid = controlRequiredInputsAreFilled(self.find('.required'));

    if (isValid) {
        $.ajax({
            type: method,
            url: url,
            data: data,
            success: function (result) {
                if (result.success) {
                    toastr.success('Başarılı');
                } else {
                    toastr.error(result.error.message);
                }
                button.removeAttr('disabled');
                updateCartTotalAndQuantity();
            }
        });
    } else {
        button.removeAttr('disabled');
    }
});

function updateCartTotalAndQuantity(){
    $.ajax({
        type: 'GET',
        url: '/cart/get-total-and-quantity',
        success: function (result) {
            if (result.success) {
                $('#cart-quantity').html(result.data[0].count);
                $('#cart-total').html(parseFloat(result.data[0].sum ? result.data[0].sum : 0).toFixed(2)+" ₺");                
            }
        }
    });
}

$('body').on('click', '.product-remove', function(e) {
    var cartId = $(this).closest('tr').attr('data-cart-row-id');
    toastr.info('<button type="button" class="btn clear" onclick="removeFromCart('+ cartId +')"> Evet</button>' , 'Sepetten kaldırmak istediğinize emin misiniz?');
});

function removeFromCart(cartId){
    $.ajax({
        type: 'POST',
        url: '/cart/remove-row',
        data: { cartId },
        success: function (result) {
            if (result.success) {
                $('[data-cart-row-id="'+ cartId +'"]').remove();
                updateCart();
            } else {
                toastr.error(result.error.message);
            }
        }
    });
}

function updateCart(){
    $.ajax({
        type: 'GET',
        url: '/cart/get-data',
        success: function (result) {
            if (result.success) {

                var count = Object.keys(result.data).length;

                generateCartItems(result.data).done(function(e){
                    $('#cart-table-tbody').html(cartItems);
                    $('.cart-total-value').html(grandTotal.toFixed(2)+" ₺");
                    $('.cart-total-value-cargo').html(totalCargoPrice.toFixed(2)+" ₺");
                    $('.cart-total-value-grand').html((grandTotal+totalCargoPrice).toFixed(2)+" ₺");
                });
                updateCartTotalAndQuantity();
            }
        }
    });
}

function generateCartItems(val){
    var dfrd1 = $.Deferred();

    cartItems = "";
    grandTotal = 0;
    totalCargoPrice = 0;
    uniqueProduct = [];

    $.each(val, function(i, value) {

        grandTotal += parseFloat(value.total);

        if (!uniqueProduct.includes(value.product_id)) {
            totalCargoPrice += parseFloat(value.cargo_price);
            uniqueProduct.push(value.product_id);
        }

        cartItems += '<tr data-cart-row-id="' + value.id + '" class="cart-item">' +
                        '<td class="product-remove">' +
                            '<a href="#"><i class="fas fa-times" aria-label="Remove"></i></a>' +
                        '</td>' +
                        '<td class="product-thumbnail">' +
                            '<img src="/web/img/product/'+ value.path +'" class="img-fluid" width="67" alt="">'+
                        '</td>'+
                        '<td class="product-name">'+
                            '<a href="/product-detail/'+ value.product_id +'"> '+ value.product_name +' -- '+value.variant_title+' / ' + value.variant_name +'</a>'+
                        '</td>'+
                        '<td class="product-price">'+
                            '<span class="unit-price">'+ value.product_price+' ₺</span>'+
                        '</td>'+
                        '<td class="product-quantity">'+
                            '<div class="quantity">'+
                                '<input type="button" value="-" class="minus">'+
                                '<input type="number" step="1" min="1" name="quantity" value="'+ value.quantity +'" title="Qty" class="qty" size="2">'+
                                '<input type="button" value="+" class="plus">'+
                            '</div>'+
                        '</td>'+
                        '<td class="product-subtotal">'+
                            '<span class="sub-total"><strong>'+ value.total +' ₺</strong></span>'+
                        '</td>'+
                    '</tr>';
    });
    dfrd1.resolve();
    return dfrd1.promise();
}

$('body').on('click', '.plus', function(e) {
    var cartId = $(this).closest('tr').attr('data-cart-row-id');
    $.ajax({
        type: 'POST',
        url: '/cart/update-quantity',
        data: { cartId, type : 'plus' },
        success: function (result) {
            if (result.success) {
                updateCart();
            } else {
                toastr.error(result.error.message);
            }
        }
    });
});

$('body').on('click', '.minus', function(e) {
    var cartId = $(this).closest('tr').attr('data-cart-row-id');
    $.ajax({
        type: 'POST',
        url: '/cart/update-quantity',
        data: { cartId, type : 'minus' },
        success: function (result) {
            if (result.success) {
                updateCart();
            } else {
                toastr.error(result.error.message);
            }
        }
    });
});
