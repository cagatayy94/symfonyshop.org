var cartItems;
var cartItemsInCheckOut;
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

    $('.mobile-mask').mask('(000) 000-0000');
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

$('.my-favorites-pagination').on('click', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);


    if (self.closest('.page-item').hasClass('active')) {
        return false;
    }

    var requestedPage = self.attr('data-page');

    $.ajax({
        type: 'GET',
        url: 'get-user-account-favorites?page='+requestedPage,
        success: function (result) {
            if (result.success) {

                $('.my-favorites-pagination').parent('.page-item').removeClass('active');

                self.parent('.page-item').addClass('active');

                var table = "";

                $.each( result.favorites, function(_, value) {

                    var stars = "";
                    for (i = 0; i <= value.rate; i++) {
                        stars += '<i class="fas fa-star text-color-primary"></i>';
                    }

                    table += '<tr>' +
                                '<td width="60">' +
                                    '<a href="/product-detail/' + value.product_id + '">' +
                                        '<img alt="" width="60" height="60" src="/web/img/product/' + value.path + '">' +
                                    '</a>' +
                                '</td>' +
                                '<td>' +
                                    '<a href="/product-detail/' + value.product_id + '">' + value.product_name + '</a>' +
                                '</td>' +
                                '<td>' +
                                    stars +
                                '</td>' +
                                '<td>' +
                                    '<button type="button" class="btn btn-danger mb-2 delete_favorite" data-favorite-id="' + value.fav_id + '">Kaldır</button>' +
                                '</td>' +
                            '</tr>';
                });

                $('#favorites-table').html(table);

            } else {
                toastr.error(result.error.message);
            }
        }
    });
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

function updateCartInCheckOut(){
    $.ajax({
        type: 'GET',
        url: '/cart/get-data',
        success: function (result) {
            if (result.success) {
                var count = Object.keys(result.data).length;
                generateCartItemsInCheckOut(result.data).done(function(e){
                    $('#cart-table-tbody').html(cartItemsInCheckOut);
                    $('.cart-total-value').html(grandTotal.toFixed(2)+" ₺");
                    $('.cart-total-value-cargo').html(totalCargoPrice.toFixed(2)+" ₺");
                    $('.cart-total-value-grand').html((grandTotal+totalCargoPrice).toFixed(2)+" ₺");
                });
                updateCartTotalAndQuantity();
            }
        }
    });
}

function generateCartItemsInCheckOut(val){
    var dfrd1 = $.Deferred();

    cartItemsInCheckOut = "";
    grandTotal = 0;
    totalCargoPrice = 0;
    uniqueProduct = [];

    $.each(val, function(i, value) {

        grandTotal += parseFloat(value.total);

        if (!uniqueProduct.includes(value.product_id)) {
            totalCargoPrice += parseFloat(value.cargo_price);
            uniqueProduct.push(value.product_id);
        }

        cartItemsInCheckOut += 
            '<tr class="cart-item">'+
                '<td class="product-thumbnail">'+
                    '<img src="/web/img/product/'+ value.path +'" class="img-fluid" width="67" alt="">'+
                '</td>'+
                '<td class="product-name">'+
                    '<a href="/product-detail/'+ value.product_id +'"> '+ value.product_name +' -- '+value.variant_title+' / ' + value.variant_name +'</a>'+
                '</td>'+
                '<td class="product-price">'+
                    '<span class="unit-price">'+ value.product_price+' ₺</span>'+
                '</td>'+
                '<td class="product-quantity">'+ value.quantity +'</td>'+
                '<td class="product-subtotal">'+
                    '<span class="sub-total"><strong>'+ value.total +' ₺</strong></span>'+
                '</td>'+
            '</tr>';

    });
    dfrd1.resolve();
    return dfrd1.promise();
}

$('body').on('change', '#different_billing_address', function(e) {
    if ($(this).prop('checked')) {
        $( "input[name*='billing']" ).removeAttr('disabled');
        $('#billing_form_div').removeClass('custom-class-disabler');
    } else {
        $( "input[name*='billing']" ).attr('disabled','disabled');
        $('#billing_form_div').addClass('custom-class-disabler');
    }
});

$('#change_mobile_form').on('submit', function (e) {
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
                    $('#change_mobile_modal').modal('toggle');
                    $('#mobile-number-on-profile').val(self.find('input[name="mobile"]').val());
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

$('#change_password_on_profile_form').on('submit', function (e) {
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
                    self.trigger("reset");
                    $('#change_password_modal').modal('toggle');
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

function updateAddressesInProfile(){
    $.ajax({
        type: 'GET',
        url: '/get-user-addresses',
        success: function (result) {
            if (result.success) {
                var table = "";
                $.each( result.addresses, function(_, value) {
                    table += '<tr id="'+ value.address_id +'">'+
                                '<td>'+
                                    value.address_name +
                                '</td>'+
                                '<td>'+
                                    value.full_name +
                                '</td>'+
                                '<td>'+
                                    value.address +
                                '</td>' +
                                '<td>'+
                                    value.county +
                                '</td>'+
                                '<td>'+
                                     value.city +
                                '</td>'+
                                '<td>' +
                                    value.mobile +
                                '</td>' +
                                '<td>' +
                                    '<button type="button" class="btn btn-success mb-2"  data-toggle="modal" data-target="#update_address_modal">Güncelle</button>'+
                                '</td>' +
                                '<td>' +
                                    '<button type="button" class="btn btn-danger mb-2 delete_address">Kaldır</button>' +
                                '</td>' +
                            '</tr>';

                });
                $('#addresses_body').html(table);
            }
        }
    });
}

$('#add_address_form').on('submit', function (e) {
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
                    self.trigger("reset");
                    $('#add_address_modal').modal('toggle');
                    updateAddressesInProfile();
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

$('body').on('click', '.delete_address', function(e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);

    var rowId = self.closest('tr').attr('id');

    toastr.info('<button type="button" class="btn clear" onclick="removeAddress('+ rowId +')"> Evet</button>' , 'Adresi silmek istediğinize emin misiniz?');
});

function removeAddress(id){
    $.ajax({
        type: 'POST',
        url: '/remove-user-address',
        data: {id},
        success: function (result) {
            if (result.success) {
                $('tr[id="'+ id +'"]').remove();
            } else {
                toastr.error(result.error.message);
            }
        }
    });
}

$('body').on('click', '[data-target="#update_address_modal"]', function(e) {
    var self = $(this);

    var tr = self.closest('tr');

    var addressName = $(tr.children()[0]).html().trim();
    var addressFullName = $(tr.children()[1]).html().trim();
    var address = $(tr.children()[2]).html().trim();
    var county = $(tr.children()[3]).html().trim();
    var city = $(tr.children()[4]).html().trim();
    var mobile = $(tr.children()[5]).html().trim();

    $('#update_address_form').find('input[name="address_name"]').val(addressName);
    $('#update_address_form').find('input[name="full_name"]').val(addressFullName);
    $('#update_address_form').find('input[name="address"]').val(address);
    $('#update_address_form').find('input[name="county"]').val(county);
    $('#update_address_form').find('input[name="city"]').val(city);
    $('#update_address_form').find('input[name="mobile"]').val(mobile);
    $('#update_address_form').find('input[name="address_id"]').val(tr.attr('id'));
});

$('#update_address_form').on('submit', function (e) {
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
                    self.trigger("reset");
                    $('#update_address_modal').modal('toggle');
                    updateAddressesInProfile();
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

$('body').on('click', '.delete_favorite', function(e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);

    var favId = self.attr('data-favorite-id');

    toastr.info('<button type="button" class="btn clear" onclick="removeFavorite('+ favId +')"> Evet</button>' , 'Favorilerden silmek istediğinize emin misiniz?');
});

function removeFavorite(id){
    $.ajax({
        type: 'POST',
        url: '/remove-user-favorite',
        data: {id},
        success: function (result) {
            if (result.success) {
                $('.delete_favorite[data-favorite-id="'+id+'"]').closest('tr').remove();
            } else {
                toastr.error(result.error.message);
            }
        }
    });
}
