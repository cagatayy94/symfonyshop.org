var cartItems;
var cartItemsInCheckOut;
var grandTotal;
var totalCargoPrice;
var uniqueProduct;
updateCartTotalAndQuantity();

$(window).on('hashchange', function() {
    $('[href="'+location.hash+'"]').trigger('click');
});

$(document).ready(function() {
    toastr.options = {
        "preventDuplicates": true,
        "newestOnTop": true,
        "closeButton": true,
    }

    $('.mobile-mask').mask('(000) 000-0000');

    $('[href="'+location.hash+'"]').trigger('click');
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

                if (!count) {
                    $('.cart_cargo_select').hide();
                    $('.shop-cart-table').hide();
                    $('.cart-summary').hide();
                    $('.empty-cart-holder').html("<h2 class='text-center'>Sepetinizde hiç ürün yok alışverişe devam etmek için <a href='/'>tıklayın</a></h2>");

                }else{
                    generateCartItems(result.data).done(function(e){
                        $('#cart-table-tbody').html(cartItems);
                        $('.cart-total-value').html(grandTotal.toFixed(2)+" ₺");
                        $('.cart-total-value-cargo').html(totalCargoPrice.toFixed(2)+" ₺");
                        $('.cart-total-value-grand').html((grandTotal+totalCargoPrice).toFixed(2)+" ₺");
                    });
                    updateCartTotalAndQuantity();
                }
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
                var cargoCompanyName = result.data[0].cargo_company_name;
                var billingAddressJson = JSON.parse(result.data[0].billing_address);
                var shippingAddressJson = JSON.parse(result.data[0].shipping_address);

                var billingAddress = billingAddressJson.full_name+" / "+billingAddressJson.mobile+"<br>"+billingAddressJson.address+" "+billingAddressJson.county+" "+billingAddressJson.city;
                var shippingAddress = shippingAddressJson.full_name+" / "+shippingAddressJson.mobile+"<br>"+shippingAddressJson.address+" "+shippingAddressJson.county+" "+shippingAddressJson.city;

                var count = Object.keys(result.data).length;
                generateCartItemsInCheckOut(result.data).done(function(e){
                    $('#cart-table-tbody').html(cartItemsInCheckOut);
                    $('.cart-total-value').html(grandTotal.toFixed(2)+" ₺");
                    $('.cart-total-value-cargo').html(totalCargoPrice.toFixed(2)+" ₺");
                    $('.cart-total-value-grand').html((grandTotal+totalCargoPrice).toFixed(2)+" ₺");
                    $('.cart-total-value-cargo-company-name').html(cargoCompanyName);
                    $('.cart-total-value-billing').html(billingAddress);
                    $('.cart-total-value-shipping').html(shippingAddress);

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
                    updateAddressesInCart();
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
                updateAddressesInProfile();
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

$('body').on('click', '.my-favorites-pagination', function(e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);


    if (self.closest('.page-item').hasClass('active')) {
        return false;
    }

    var requestedPage = self.attr('data-page');

    generateFavoritesInProfile(requestedPage, self);
});

function updateAddressesInProfile(){
    $.ajax({
        type: 'GET',
        url: '/get-user-addresses',
        success: function (result) {
            if (result.success) {
                if (result.addresses.length) {
                    var table = '<thead>'+
                                    '<tr>' +
                                        '<th>Adres İsmi</th>'+
                                        '<th>Adresteki İsim Soyisim </th>'+
                                        '<th>Adres</th>'+
                                        '<th>İlçe</th>'+
                                        '<th>Şehir</th>'+
                                        '<th>Telefon Numarası</th>'+
                                        '<th colspan="2">'+
                                        '</th>'+
                                    '</tr>'+
                                '</thead>'+
                                '<tbody id="addresses_body">';

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

                    table += "</tbody>";
                        
                    } else {
                        table = "Henüz hiç adresiniz yok";
                    }
                
                $('#addresses-table').html(table);
            }
        }
    });
}

function generateFavoritesInProfile(requestedPage = 1){

    paginationButton = $('.my-favorites-pagination[data-page="'+ requestedPage +'"]');
    $('.my-favorites-pagination').parent('.page-item').removeClass('active');
    paginationButton.parent('.page-item').addClass('active');

    $.ajax({
        type: 'GET',
        url: 'get-user-account-favorites?page='+requestedPage,
        success: function (result) {
            if (result.success) {

                var table = "";

                if (result.total_count) {

                    table += "<thead>" +
                            "<tr>" +
                                "<th></th>" +
                                "<th>Adı</th>" +
                                "<th>Puanı</th>" +
                                "<th>Fiyatı</th>" +
                                "<th></th>" +
                            "</tr>" +
                        "</thead>" +
                        "<tbody id='favorites-table-body'>";

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

                    table += "</tbody>";

                    var pagination = "";

                    pagination +=   '<div class="col-auto mb-3 mb-sm-0">'+
                                        '<span>' + ((result.perPage*requestedPage) - result.perPage + 1) + '-<span class="total-span">' + (result.perPage*requestedPage <= result.total_count ? result.perPage*requestedPage : result.total_count) + '</span>/<span class="grand-total-span">'+ result.total_count +'</span> sonuç gösteriliyor </span>'+
                                    '</div>';
                    if (result.pageCount) {
                        pagination +=   '<div class="col-auto">' +
                                            '<nav aria-label="Page navigation example">' +
                                                '<ul class="pagination mb-0">';

                        for (i = 1; i <= result.pageCount; i++) {
                            pagination += '<li' + ((i == requestedPage) ? " class=\'page-item active\' " : "") +'><a class="page-link my-favorites-pagination" data-page="'+ i +'" href="#">'+ i +'</a></li>';
                        }  

                        pagination += '</ul></nav></div>';
                    }                
                                                
                    $('#favorites-table').html(table);
                    $('#favorite-pagination').html(pagination);

                }else{
                    $('#favorites-table').html("Henüz hiç favoriniz yok");
                }

            } else {
                toastr.error(result.error.message);
            }
        }
    });
}

$('body').on('click', '[href="#favorites"]', function(e) {
    generateFavoritesInProfile();
});

$('body').on('click', '[href="#addresses"]', function(e) {
    updateAddressesInProfile();
});

$('body').on('click', '.delete_favorite', function(e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);

    var favId = self.attr('data-favorite-id');

    toastr.info('<button type="button" class="btn clear" onclick="removeFavorite('+ favId +')"> Evet</button>' , 'Favorilerden silmek istediğinize emin misiniz?');
});

function removeFavorite(id){
    var favoriteTotalSpan = $('#favorite-pagination').find('span.total-span');
    var favoriteGrandTotalSpan = $('#favorite-pagination').find('span.grand-total-span');
    var favoriteTotalPaginationVal = favoriteTotalSpan.html();
    var favoriteGrandTotalSpanPaginationVal = favoriteGrandTotalSpan.html();

    $.ajax({
        type: 'POST',
        url: '/remove-user-favorite',
        data: {id},
        success: function (result) {
            if (result.success) {
                $('.delete_favorite[data-favorite-id="'+id+'"]').closest('tr').remove();
                
                if (!$('#favorites-table-body').children().length) {
                    generateFavoritesInProfile();
                }

                favoriteTotalSpan.html(favoriteTotalPaginationVal - 1);
                favoriteGrandTotalSpan.html(favoriteGrandTotalSpanPaginationVal - 1);
                
            } else {
                toastr.error(result.error.message);
            }
        }
    });
}

function updateCommentsInProfile(requestedPage = 1){
    paginationButton = $('.my-favorites-pagination[data-page="'+ requestedPage +'"]');
    $('.my-favorites-pagination').parent('.page-item').removeClass('active');
    paginationButton.parent('.page-item').addClass('active');

    $.ajax({
        type: 'GET',
        url: 'get-user-account-comments?page='+requestedPage,
        success: function (result) {
            if (result.success) {

                var table = "";

                if (result.total_count) {

                    table += "<thead>" +
                            "<tr>" +
                                "<th></th>" +
                                "<th>Adı</th>" +
                                "<th>Puan</th>" +
                                "<th>Tarih</th>" +
                                "<th>Yorum</th>" +
                                "<th></th>" +
                            "</tr>" +
                        "</thead>" +
                        "<tbody id='comments-table-body'>";

                    $.each( result.comments, function(_, value) {

                        var stars = "";
                        for (i = 0; i <= value.rate; i++) {
                            stars += '<i class="fas fa-star text-color-primary"></i>';
                        }

                        var comment = 'lalala';
                        var date = 'asd';

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
                                        value.created_at +
                                    '</td>' +
                                    '<td>' +
                                        value.comment +
                                    '</td>' +
                                    '<td>' +
                                        '<button type="button" class="btn btn-danger mb-2 delete_comment" data-comment-id="' + value.comment_id + '">Kaldır</button>' +
                                    '</td>' +
                                '</tr>';
                    });

                    table += "</tbody>";

                    var pagination = "";

                    pagination +=   '<div class="col-auto mb-3 mb-sm-0">'+
                                        '<span>' + ((result.perPage*requestedPage) - result.perPage + 1) + '-<span class="total-span">' + (result.perPage*requestedPage <= result.total_count ? result.perPage*requestedPage : result.total_count) + '</span>/<span class="grand-total-span">'+ result.total_count +'</span> sonuç gösteriliyor </span>'+
                                    '</div>';
                    if (result.pageCount) {
                        pagination +=   '<div class="col-auto">' +
                                            '<nav aria-label="Page navigation example">' +
                                                '<ul class="pagination mb-0">';

                        for (i = 1; i <= result.pageCount; i++) {
                            pagination += '<li' + ((i == requestedPage) ? " class=\'page-item active\' " : "") +'><a class="page-link my-comments-pagination" data-page="'+ i +'" href="#">'+ i +'</a></li>';
                        }  

                        pagination += '</ul></nav></div>';
                    }                
                                                
                    $('#comments-table').html(table);
                    $('#comments-pagination').html(pagination);

                }else{
                    $('#comments-table').html("Henüz hiç yorumunuz yok");
                }

            } else {
                toastr.error(result.error.message);
            }
        }
    });
}

$('body').on('click', '[href="#comments"]', function(e) {
    updateCommentsInProfile();
});

$('body').on('click', '.my-comments-pagination', function(e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);


    if (self.closest('.page-item').hasClass('active')) {
        return false;
    }

    var requestedPage = self.attr('data-page');

    updateCommentsInProfile(requestedPage);
});

$('body').on('click', '.delete_comment', function(e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);

    var commentId = self.attr('data-comment-id');

    toastr.info('<button type="button" class="btn clear" onclick="removeComment('+ commentId +')"> Evet</button>' , 'Yorumunuzu silmek istediğinize emin misiniz?');
});

function removeComment(id){

    var commentsTotalSpan = $('#comments-pagination').find('span.total-span');
    var commentsGrandTotalSpan = $('#comments-pagination').find('span.grand-total-span');
    var commentTotalPaginationVal = commentsTotalSpan.html();
    var commentsGrandTotalSpanPaginationVal = commentsGrandTotalSpan.html();

    $.ajax({
        type: 'POST',
        url: '/remove-user-comment',
        data: {id},
        success: function (result) {
            if (result.success) {
                $('.delete_comment[data-comment-id="'+id+'"]').closest('tr').remove();
                
                if (!$('#comments-table-body').children().length) {
                    updateCommentsInProfile();
                }

                commentsTotalSpan.html(commentTotalPaginationVal - 1);
                commentsGrandTotalSpan.html(commentsGrandTotalSpanPaginationVal - 1);

            } else {
                toastr.error(result.error.message);
            }
        }
    });
}

function updateAddressesInCart(){
    $.ajax({
        type: 'GET',
        url: '/get-user-addresses',
        success: function (result) {
            if (result.success) {
                var billingAddressTable = "";
                var cargoAddressTable = "";

                var count = Object.keys(result.addresses).length;

                if (count) {
                        $.each( result.addresses, function(_, value) {
                            billingAddressTable += '<div class="card mb-4">'+
                                                '<div class="card-header">'+
                                                    '<div class="float-left"><input type="radio" name="billing_address_id" class="required" value="'+value.address_id+'"></div>'+
                                                    '<div class="float-right">' +
                                                        '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#update_address_modal_in_cart"><i class="fas fa-pencil-alt"></i></button>'+
                                                        '<button type="button" class="btn btn-danger delete_address_in_cart"><i class="fas fa-trash-alt"></i></button>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="card-body address">'+
                                                    '<span class="address_name font-weight-bold">'+ value.address_name +'</span><br>'+
                                                    '<span class="full_name">'+ value.full_name +'</span><br>'+
                                                    '<span class="city">'+ value.city +'</span>-'+
                                                    '<span class="county">'+ value.county +'</span><br>'+
                                                    '<span class="mobile">'+ value.mobile +'</span><br>'+
                                                    '<span class="address">'+ value.address +'</span>'+
                                                '</div>'+
                                            '</div>';

                            cargoAddressTable += '<div class="card mb-4">'+
                                                '<div class="card-header">'+
                                                    '<div class="float-left"><input type="radio" name="shipping_address_id" class="required" value="'+value.address_id+'"></div>'+
                                                    '<div class="float-right">' +
                                                        '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#update_address_modal_in_cart"><i class="fas fa-pencil-alt"></i></button>'+
                                                        '<button type="button" class="btn btn-danger delete_address_in_cart"><i class="fas fa-trash-alt"></i></button>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="card-body address">'+
                                                    '<span class="address_name font-weight-bold">'+ value.address_name +'</span><br>'+
                                                    '<span class="full_name">'+ value.full_name +'</span><br>'+
                                                    '<span class="city">'+ value.city +'</span>-'+
                                                    '<span class="county">'+ value.county +'</span><br>'+
                                                    '<span class="mobile">'+ value.mobile +'</span><br>'+
                                                    '<span class="address">'+ value.address +'</span>'+
                                                '</div>'+
                                            '</div>';

                        });

                        $('.billing_address_holder').html(billingAddressTable);
                        $('.shipping_address_holder').html(cargoAddressTable);
                        

                    }else{
                        $('.billing_address_holder').html("<h6 class='text-center'>Hiç adresiniz yok. Yeni adres eklemek için <a style='cursor: pointer;' data-toggle='modal' data-target='#add_address_modal'>tıklayın</a></h6>");
                        $('.shipping_address_holder').html("<h6 class='text-center'>Hiç adresiniz yok. Yeni adres eklemek için <a style='cursor: pointer;' data-toggle='modal' data-target='#add_address_modal'>tıklayın</a></h6>");
                        $('.empty-cart-holder').html("");
                    }
            }
        }
    });
}

$('body').on('click', '.delete_address_in_cart', function(e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);

    var addressId = $(self.parent().siblings()[0]).find('[type="radio"]').val();

    toastr.info('<button type="button" class="btn clear" onclick="removeAddressInCart('+ addressId +')"> Evet</button>' , 'Adresi silmek istediğinize emin misiniz?');
});

function removeAddressInCart(id){
    $.ajax({
        type: 'POST',
        url: '/remove-user-address',
        data: {id},
        success: function (result) {
            if (result.success) {
                updateAddressesInCart();
            } else {
                toastr.error(result.error.message);
            }
        }
    });
}

$('body').on('click', '[data-target="#update_address_modal_in_cart"]', function(e) {
    var self = $(this);

    var addressId = $(self.parent().siblings()[0]).find('[type="radio"]').val();
    var address = $(self.parent().parent().siblings()[0]);

    var addressName = address.find('span.address_name').html();
    var addressFullName = address.find('span.full_name').html();
    var addressText = address.find('span.address').html();
    var county = address.find('span.county').html();
    var city = address.find('span.city').html();
    var mobile = address.find('span.mobile').html();

    $('#update_address_form_in_cart').find('input[name="address_name"]').val(addressName);
    $('#update_address_form_in_cart').find('input[name="full_name"]').val(addressFullName);
    $('#update_address_form_in_cart').find('input[name="address"]').val(addressText);
    $('#update_address_form_in_cart').find('input[name="county"]').val(county);
    $('#update_address_form_in_cart').find('input[name="city"]').val(city);
    $('#update_address_form_in_cart').find('input[name="mobile"]').val(mobile);
    $('#update_address_form_in_cart').find('input[name="address_id"]').val(addressId);
});

$('#update_address_form_in_cart').on('submit', function (e) {
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
                    $('#update_address_modal_in_cart').modal('toggle');
                    updateAddressesInCart();
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

$('body').on('submit', '#address_selection', function(e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);
    var url = self.attr('action');
    var method = self.attr('method');
    var data = self.serialize();
    var button = self.find(':submit');
    var billingAddress = self.find('input[name="billing_address_id"]:checked').val();
    var cargoAddress = self.find('input[name="shipping_address_id"]:checked').val();

    if (!billingAddress) {
        toastr.error("Fatura adresi seçiniz");
        return false;
    }

    if (!cargoAddress) {
        toastr.error("Kargo adresi seçiniz");
        return false;
    }

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
                    location.href = "check-out";
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
