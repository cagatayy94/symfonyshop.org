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
    toastr.warning('Gerekli alanları doldurunuz.');
}

return isValid;
}
//for dynamic routes active class in admin
$(function(){
    var current = location.pathname.split('/')[2];
    $('.nav-dynamic').each(function(){
        var liItem = $(this);
            var itemHref = liItem.children().attr('href').split('/')[2];
        if (itemHref == current) {
            liItem.addClass('active');
            liItem.parent().css("display","block");
            liItem.parent().parent().addClass('menu-open'); 
        }
    });
});

function changeImg(){
    $('#img').hide();
    $('#uploadImg').show();
}

$('#create_new_admin_form').on('submit', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);
    var url = self.attr('action');
    var method = self.attr('method');
    var data = self.serialize();

    self.find('[type="submit"]').attr('disabled', 'disabled');

    var isValid = true;

    isValid = controlRequiredInputsAreFilled(self.find('.required'));

    var password1 = self.find('[name="password"]');
    var password2 = self.find('[name="password_repeat"]');

    if (password1.val() != password2.val()) {
        isValid = false;
        password1.val('');
        password2.val('');
        password1.closest('.form-group').addClass('has-error');
        password2.closest('.form-group').addClass('has-error');
        toastr.warning('Parolalar eşleşmiyor');
    }

    if (isValid) {
        $.ajax({
            type: method,
            url: url,
            data: data,
            success: function (result) {
                if (result.success) {
                    toastr.success('Kayıt Başarılı');
                    setTimeout(function() { location.reload(); }, 750);
                } else {
                    toastr.error(result.error.message);
                    self.find('[type="submit"]').removeAttr('disabled');
                    password1.val('');
                    password2.val('');
                }
            }
        });
    } else {
        self.find('[type="submit"]').removeAttr('disabled');
    }
});

$('#admin_update_form').on('submit', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);
    var url = self.attr('action');
    var method = self.attr('method');
    var data = self.serialize();

    self.find('[type="submit"]').attr('disabled', 'disabled');

    var isValid = true;

    isValid = controlRequiredInputsAreFilled(self.find('.required'));

    var password1 = self.find('[name="password"]');
    var password2 = self.find('[name="password_repeat"]');

    if (password1.val() != "" && password2.val() != "" && (password1.val() != password2.val())) {
        isValid = false;
        password1.val('');
        password2.val('');
        password1.closest('.form-group').addClass('has-error');
        password2.closest('.form-group').addClass('has-error');
        toastr.warning('Parolalar eşleşmiyor');
    }

    if (isValid) {
        $.ajax({
            type: method,
            url: url,
            data: data,
            success: function (result) {
                if (result.success) {
                    toastr.success('Güncellendi');
                    setTimeout(function() { location.reload(); }, 750);
                } else {
                    toastr.error(result.error.message);
                    self.find('[type="submit"]').removeAttr('disabled');
                    password1.val('');
                    password2.val('');
                }
            }
        });
    } else {
        self.find('[type="submit"]').removeAttr('disabled');
    }
});

$('#create_new_admin_profile').on('submit', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);
    var url = self.attr('action');
    var method = self.attr('method');
    var data = self.serialize();

    self.find('[type="submit"]').attr('disabled', 'disabled');

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
                    setTimeout(function() { location.reload(); }, 750);
                } else {
                    toastr.error(result.error.message);
                    self.find('[type="submit"]').removeAttr('disabled');
                    password1.val('');
                    password2.val('');
                }
            }
        });
    } else {
        self.find('[type="submit"]').removeAttr('disabled');
    }
});

$('#admin_profile_update_form').on('submit', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);
    var url = self.attr('action');
    var method = self.attr('method');
    var data = self.serialize();

    self.find('[type="submit"]').attr('disabled', 'disabled');

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
                    self.find('[type="submit"]').removeAttr('disabled');
                } else {
                    toastr.error(result.error.message);
                    self.find('[type="submit"]').removeAttr('disabled');
                }
            }
        });
    } else {
        self.find('[type="submit"]').removeAttr('disabled');
    }
});

$('body').on('click', '#admin_account_delete', function() {
    var self = $(this);
    var href = self.attr('data-href');
    var adminId = self.attr('data-admin-id');

    self.attr('disabled', 'disabled');

    if( confirm('Silmek istediğinize emin misiniz?')) {
        $.ajax({
            url: href,
            type: 'POST',
            data: { adminId },
            success: function(result) {
                if (result.success) {
                    toastr.success('Başarılı');
                    setTimeout(function() { window.location.href = "/admin/account/list"; }, 750);
                }else{
                    toastr.error(result.error.message);
                    self.removeAttr('disabled');
                }
            }
        }); 
    }else{
        self.removeAttr('disabled');
    }
});

$('body').on('click', '#admin_profile_delete', function() {
    var self = $(this);
    var href = self.attr('data-href');
    var profileId = self.attr('data-profile-id');

    self.attr('disabled', 'disabled');

    if( confirm('Silmek istediğinize emin misiniz?')) {
        $.ajax({
            url: href,
            type: 'POST',
            data: { profileId },
            success: function(result) {
                if (result.success) {
                    toastr.success('Başarılı');
                    setTimeout(function() { window.location.href = "admin/profile/list"; }, 750);
                }else{
                    toastr.error(result.error.message);
                    self.removeAttr('disabled');
                }
            }
        }); 
    }else{
        self.removeAttr('disabled');
    }
});

$('#site-settings-form').on('submit', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);

    var button = self.find(':submit');

    button.attr('disabled', 'disabled');

    var url = self.attr('action');
    var method = self.attr('method');
    var data = self.serialize();

    var isValid = true;

    isValid = controlRequiredInputsAreFilled(self.find('.required'));

    if (isValid) {
        $.ajax({
            url: url,
            type: method,
            data: data,
            success: function(result) {
                if (result.success) {
                    toastr.success('Başarılı');
                }else{
                    toastr.error(result.error.message);
                }
                button.removeAttr('disabled');
            }
        });
    }else{
        button.removeAttr('disabled');
    }
});

$('#general_update_strings_update_form').on('submit', function (e) {
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
            url: url,
            type: method,
            data: data,
            success: function(result) {
                if (result.success) {
                    toastr.success('Başarılı');
                }else{
                    toastr.error(result.error.message);
                }
                button.removeAttr('disabled');
            }
        });
    }else{
        button.removeAttr('disabled');
    }
});

$('.delete-bank').on('click', function (e) {

    var self = $(this);
    var url = self.attr('data-delete-url');
    self.attr('disabled', 'disabled');

    if (confirm('Silmek istediğinize emin misiniz?')) {
        $.ajax({
            url: url,
            type: 'GET',
            success: function(result) {
                if (result.success) {
                    toastr.success('Başarılı');
                    setTimeout(function() { window.location.href = location.pathname; }, 750);
                }else{
                    toastr.error(result.error.message);
                }
                self.removeAttr('disabled');
            }
        });
    }else{
        self.removeAttr('disabled');
    }
});

$('#add-new-bank-form').on('submit', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);

    var button = self.find(':submit');
    var url = self.attr('action');
    var method = self.attr('method');
    var data = new FormData(this);
    var isValid = true;

    button.attr('disabled', 'disabled');

    isValid = controlRequiredInputsAreFilled(self.find('.required'));

    if(isValid){
        $.ajax({
            url: url,
            type: method,
            data: data,
            contentType: false,
            processData: false,
            success: function(result) {
                if (result.success) {
                    toastr.success('Başarılı');
                    setTimeout(function() { window.location.href = location.pathname; }, 750);
                }else{
                    toastr.error(result.error.message);
                    button.removeAttr('disabled');
                }
            }
        });
    }else{
        button.removeAttr('disabled');
    }
});

$('.update-bank').on('click', function (e) {
    var self = $(this);
    var id = self.attr('data-update-id');
    var trChildrens = $('#'+id).children();

    var img = trChildrens.eq(0).html().trim();
    var bankName = trChildrens.eq(1).html().trim();
    var city = trChildrens.eq(2).html().trim();
    var country = trChildrens.eq(3).html().trim();
    var branch = trChildrens.eq(4).html().trim();
    var branchCode = trChildrens.eq(5).html().trim();
    var currency = trChildrens.eq(6).html().trim();
    var owner = trChildrens.eq(7).html().trim();
    var no = trChildrens.eq(8).html().trim();
    var iban = trChildrens.eq(9).html().trim();

    $('#name').val(bankName);
    $('#city').val(city);
    $('#country').val(country);
    $('#branchName').val(branch);
    $('#branchCode').val(branchCode);
    $('#currency').val(currency);
    $('#accountOwner').val(owner);
    $('#accountNumber').val(no);
    $('#iban').val(iban);
    $('#id').val(id);
    $('#img').html(img+'<button onclick="changeImg()" type="button" class="btn btn-primary"><i class="fa fa-edit"></i> Değiştir</button>');

    $('#update-bank-modal').modal('toggle');

});

$('#update-bank-form').on('submit', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);
    var url = self.attr('action');
    var method = self.attr('method');
    var data = new FormData(this);
    var button = self.find(':submit');

    button.attr('disabled', 'disabled');

    var isValid = true;

    isValid = controlRequiredInputsAreFilled(self.find('.required'));

    if(isValid){
        $.ajax({
            url: url,
            type: method,
            data: data,
            contentType: false,
            processData: false,
            success: function(result) {
                if (result.success) {
                    toastr.success('Başarılı');
                    setTimeout(function() { window.location.href = location.pathname; }, 750);
                }else{
                    toastr.error(result.error.message);
                    button.removeAttr('disabled');
                }
            }
        });
    }else{
        button.removeAttr('disabled');
    }
});

$('#add-new-faq-form').on('submit', function (e) {
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

    if(isValid){
        $.ajax({
            url: url,
            type: method,
            data: data,
            success: function(result) {
                if (result.success) {
                    toastr.success('Başarılı');
                    setTimeout(function() { window.location.href = location.pathname; }, 750);
                }else{
                    toastr.error(result.error.message);
                    button.removeAttr('disabled');
                }
            }
        });
    }else{
        button.removeAttr('disabled');
    }
});

$('.delete-faq').on('click', function (e) {

    var self = $(this);
    var url = self.attr('data-delete-url');

    self.attr('disabled', 'disabled');

    if (confirm('Silmek istediğinize emin misiniz?')) {
        $.ajax({
            url: url,
            type: 'GET',
            success: function(result) {
                if (result.success) {
                    toastr.success('Başarılı');
                    setTimeout(function() { window.location.href = location.pathname; }, 750);
                }else{
                    toastr.error(result.error.message);
                }
            }
        });
    }else{
        self.removeAttr('disabled');
    }
});

$('.update-faq').on('click', function (e) {
    var self = $(this);
    var id = self.attr('data-update-id');

    var trChildrens = $('#'+id).children();
    var question = trChildrens.eq(0).html().trim();
    var answer = trChildrens.eq(1).html().trim();

    $('#question').val(question);
    $('#answer').val(answer);
    $('#faq_id').val(id);

    $('#update-faq-modal').modal('toggle');

});

$('#faq-update-form').on('submit', function (e) {
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

    if(isValid){
        $.ajax({
            url: url,
            type: method,
            data: data,
            success: function(result) {
                if (result.success) {
                    toastr.success('Başarılı');
                    setTimeout(function() { window.location.href = location.pathname; }, 750);
                }else{
                    toastr.error(result.error.message);
                    button.removeAttr('disabled');
                }
            }
        });
    }else{
        button.removeAttr('disabled');
    }
});

$('#update_logo_form').on('submit', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);
    var url = self.attr('action');
    var method = self.attr('method');
    var button = self.find(':submit');
    
    button.attr('disabled', 'disabled');
    
    $.ajax({
        url: url,
        type: method,
        data: new FormData(this),
        contentType: false,
        processData: false,
        success: function(result) {
            if (result.success) {
                toastr.success('Başarılı');
                setTimeout(function() { window.location.href = location.pathname; }, 750);
            }else{
                toastr.error(result.error.message);
                button.removeAttr('disabled');
            }
        }
    });
});

$('#add-new-banner-form').on('submit', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);
    var button = self.find(':submit');
    var url = self.attr('action');
    var method = self.attr('method');
    var isValid = true;

    button.attr('disabled', 'disabled');

    isValid = controlRequiredInputsAreFilled(self.find('.required'));

    if(isValid){
        $.ajax({
            url: url,
            type: method,
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function(result) {
                if (result.success) {
                    toastr.success('Başarılı');
                    setTimeout(function() { window.location.href = location.pathname; }, 750);
                }else{
                    toastr.error(result.error.message);
                }
            }
        });
    }else{
        button.removeAttr('disabled');
    }
});

$('.delete-banner').on('click', function (e) {

    var self = $(this);
    var url = self.attr('data-delete-url');

    self.attr('disabled', 'disabled');

    if (confirm('Silmek istediğinize emin misiniz?')) {
        $.ajax({
            url: url,
            type: 'GET',
            success: function(result) {
                if (result.success) {
                    toastr.success('Başarılı');
                    setTimeout(function() { window.location.href = location.pathname; }, 750);
                }else{
                    toastr.error(result.error.message);
                    self.removeAttr('disabled');
                }
            }
        });
    }else{
        self.removeAttr('disabled');
    }
});

$('.update-banner').on('click', function (e) {
    var self = $(this);
    var id = self.attr('data-update-id');
    var trChildrens = $('#'+id).children();

    var img = trChildrens.eq(0).html().trim();
    var name = trChildrens.eq(1).html().trim();

    $('#name').val(name);
    $('#id').val(id);
    $('#img').html(img+'<button onclick="changeImg()" type="button" class="btn btn-primary"><i class="fa fa-edit"></i> Değiştir</button>');

    $('#update-banner-modal').modal('toggle');

});

$('#update-banner-form').on('submit', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);
    var url = self.attr('action');
    var method = self.attr('method');
    var button = self.find(':submit');
    var isValid = true;

    button.attr('disabled', 'disabled');

    isValid = controlRequiredInputsAreFilled(self.find('.required'));

    if(isValid){
        $.ajax({
            url: url,
            type: method,
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function(result) {
                if (result.success) {
                    toastr.success('Başarılı');
                    setTimeout(function() { window.location.href = location.pathname; }, 750);
                }else{
                    toastr.error(result.error.message);
                    button.removeAttr('disabled');
                }
            }
        });
    }else{
        button.removeAttr('disabled');
    }
});

$('.delete-cargo').on('click', function (e) {

    var self = $(this);
    var url = self.attr('data-delete-url');

    self.attr('disabled', 'disabled');

    if (confirm('Silmek istediğinize emin misiniz?')) {
        $.ajax({
            url: url,
            type: 'GET',
            success: function(result) {
                if (result.success) {
                    toastr.success('Başarılı');
                    setTimeout(function() { window.location.href = location.pathname; }, 750);
                }else{
                    toastr.error(result.error.message);
                    self.removeAttr('disabled');
                }
            }
        });
    }else{
        self.removeAttr('disabled');
    }
});

$('#add-new-cargo-form').on('submit', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);
    var isValid = true;

    var url = self.attr('action');
    var method = self.attr('method');
    var data = self.serialize();
    var button = self.find(':submit');

    button.attr('disabled', 'disabled');

    isValid = controlRequiredInputsAreFilled(self.find('.required'));

    if(isValid){
        $.ajax({
            url: url,
            type: method,
            data: data,
            success: function(result) {
                if (result.success) {
                    toastr.success('Başarılı');
                    setTimeout(function() { window.location.href = location.pathname; }, 750);
                }else{
                    toastr.error(result.error.message);
                    button.removeAttr('disabled');
                }
            }
        });
    }else{
        button.removeAttr('disabled');
    }
});

$('.update-cargo').on('click', function (e) {
    var self = $(this);
    var id = self.attr('data-update-id');
    var trChildrens = $('#'+id).children();
    var name = trChildrens.eq(0).html().trim();

    $('#name').val(name);
    $('#id').val(id);
    $('#update-cargo-modal').modal('toggle');

});

$('#update-cargo-form').on('submit', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);
    var url = self.attr('action');
    var method = self.attr('method');
    var data = self.serialize();
    var isValid = true;
    var button = self.find(':submit');

    button.attr('disabled', 'disabled');

    isValid = controlRequiredInputsAreFilled(self.find('.required'));

    if(isValid){
        $.ajax({
            url: url,
            type: method,
            data: data,
            success: function(result) {
                if (result.success) {
                    toastr.success('Başarılı');
                    setTimeout(function() { window.location.href = location.pathname; }, 750);
                }else{
                    toastr.error(result.error.message);
                    button.removeAttr('disabled');
                }
            }
        });
    }else{
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

$('.submit-filter').on('click', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);
    self.attr('disabled', 'disabled');
    var form = self.closest('form');
    var currentPage = form.find('input[name="currentPage"]').val("1");
    var currentPage = form.find('input[name="excelExport"]').val("0");
    form.submit();
});

$('.submit-filter-excel').on('click', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);
    var form = self.closest('form');
    var currentPage = form.find('input[name="currentPage"]').val("1");
    var currentPage = form.find('input[name="excelExport"]').val("1");
    form.submit();
});

$('.change-submit').on('change', function (e) {
    $(this).closest('form').find('input[name="currentPage"]').val("1");
    $(this).closest('form').submit();
});

$('.delete-bank-transfer').on('click', function (e) {
    e.preventDefault();
    e.stopPropagation();
    
    var self = $(this);
    var url = self.attr('data-delete-url');
    self.attr('disabled', 'disabled');

    if (confirm('Silmek istediğinize emin misiniz?')) {
        $.ajax({
            url: url,
            type: 'GET',
            success: function(result) {
                if (result.success) {
                    toastr.success('Başarılı');
                    setTimeout(function() { window.location.href = location.pathname; }, 750);
                }else{
                    toastr.error(result.error.message);
                    self.removeAttr('disabled');
                }
            }
        });
    }else{
        self.removeAttr('disabled');
        return false;
    }
});

$('.undelete-bank-transfer').on('click', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);
    var url = self.attr('data-delete-url');
    self.attr('disabled', 'disabled');

    if (confirm('Silmeyi geri almak istediğinize emin misiniz?')) {
        $.ajax({
            url: url,
            type: 'GET',
            success: function(result) {
                if (result.success) {
                    toastr.success('Başarılı');
                    setTimeout(function() { window.location.href = location.pathname; }, 750);
                }else{
                    self.removeAttr('disabled');
                    toastr.error(result.error.message);
                }
            }
        });
    }else{
        self.removeAttr('disabled');
        return false;
    }
});

$('.approve').on('click', function (e) {

    var self = $(this);
    var id = self.closest('tr').attr('data-money-order-id');
    var url = self.closest('tr').attr('data-approve-url');
    var type = self.attr('data-type');
    self.attr('disabled', 'disabled');

    if (type == 'approve') {
        var msg = "Onaylamak istediğinize emin misiniz?"

    }else if(type == 'unapprove'){
        var msg = "Onayı geri almak istediğinize emin misiniz"
    }else{
        return false;
    }

    if (confirm(msg)) {
        $.ajax({
            url: url,
            type: 'GET',
            data: 'type='+ type +'&id='+id,
            success: function(result) {
                if (result.success) {
                    toastr.success('Başarılı');
                    setTimeout(function() { self.closest('form').submit() }, 750);
                }else{
                    self.removeAttr('disabled');
                    toastr.error(result.error.message);
                }
            }
        });
    }else{
        self.removeAttr('disabled');
        return false;
    }
});

$('.clone-element').on('click', function (e) {

    var self = $(this);

    var elementsHolder = self.parent().siblings('.elements-holder');

    var newElementIndex = elementsHolder.children().length+1;

    var originalElement = elementsHolder.children(':first');

    var cloneElement = originalElement.clone();

    cloneElement.find('span.element-index').html(newElementIndex+'. ');
    cloneElement.find('input').val('');
    cloneElement.find('img').removeAttr('src');
    cloneElement.find('img').hide();

    elementsHolder.append(cloneElement);
});

$('.delete-clone').on('click', function (e) {
    var self = $(this);

    var elementsHolder = self.parent().siblings('.elements-holder');

    var elementCount = elementsHolder.children().length;

    if (elementCount>1) {
        elementsHolder.children(':last-child').remove();
    }
});

function readURL(input) {

    var imgViewer = $(input).parent().siblings().find('.img-viewer');

    if (input.files && input.files[0]) {

        var reader = new FileReader();
        reader.onload = function(e) {
            imgViewer.attr('src', e.target.result);
            imgViewer.show();
        }

        reader.readAsDataURL(input.files[0]); // convert to base64 string
    }
}

$(document).on('change', ".img-with-viewer", function() {
    readURL(this);
});

function loadCategories(){
    $.ajax({
        url: '/admin/category/get/all',
        type: 'GET',
        success: function(result) {
            if (result.success) {
                var checkboxHolder = $('.category-checkbox-holder');
                checkboxHolder.html('');
                $.each(result.data, function(index, value) {
                    checkboxHolder.append('<label class="col-md-3"><input type="checkbox" name="categoryId[]" value="'+ value.id +'"> '+ value.name +'</label>')
                });
            }else{
                toastr.error('Kategoriler yüklenirken bir sorun oluştu');
            }
        }
    });
}

$('#add_category_form').on('submit', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);
    var url = self.attr('action');
    var method = self.attr('method');
    var data = self.serialize();

    self.find('[type="submit"]').attr('disabled', 'disabled');

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
                    loadCategories();
                    self.find(':input').val('');
                    $("#add_category_modal").modal('hide');
                    self.find('[type="submit"]').removeAttr('disabled');
                } else {
                    toastr.error(result.error.message);
                    self.find('[type="submit"]').removeAttr('disabled');
                }
            }
        });
    } else {
        self.find('[type="submit"]').removeAttr('disabled');
    }
});

$('#add_product').on('submit', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);
    var url = self.attr('action');
    var method = self.attr('method');
    var button = self.find(':submit');
    var isValid = true;

    button.attr('disabled', 'disabled');

    isValid = controlRequiredInputsAreFilled(self.find('.required'));

    if(isValid){
        $.ajax({
            url: url,
            type: method,
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function(result) {
                if (result.success) {
                    toastr.success('Başarılı');
                    button.removeAttr('disabled');

                    //setTimeout(function() { window.location.href = location.pathname; }, 750);
                }else{
                    toastr.error(result.error.message);
                    button.removeAttr('disabled');
                }
            }
        });
    }else{
        button.removeAttr('disabled');
    }
});

$(document).ready(function(){
    $(':input.float').focusout(function(){

        var value  = this.value

        console.log(value);

        var float= /^\s*(\+|-)?((\d+(\.\d+)?)|(\.\d+))\s*$/;
        var a = $(".check_int_float").val();
        
        if (float.test(value)) {
            $(this).closest('.form-group').removeClass('has-error');
            return;
        }else {
            $(this).val("");
            $(this).closest('.form-group').addClass('has-error');
            toastr.error('Lütfen değeri tam sayı veya ondalık sayı olacak şekilde yazın ( <b>24 - 32.12</b> <s> - 12,32</s> )');
        }
    });
});

$('.delete-product').on('click', function (e) {
    e.preventDefault();
    e.stopPropagation();
    
    var self = $(this);
    var url = self.attr('data-delete-url');
    self.attr('disabled', 'disabled');

    if (confirm('Silmek istediğinize emin misiniz?')) {
        $.ajax({
            url: url,
            type: 'GET',
            success: function(result) {
                if (result.success) {
                    toastr.success('Başarılı');
                    setTimeout(function() { window.location.href = location.pathname; }, 750);
                }else{
                    toastr.error(result.error.message);
                    self.removeAttr('disabled');
                }
            }
        });
    }else{
        self.removeAttr('disabled');
        return false;
    }
});

$('.undelete-product').on('click', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var self = $(this);
    var url = self.attr('data-delete-url');
    self.attr('disabled', 'disabled');

    if (confirm('Silmeyi geri almak istediğinize emin misiniz?')) {
        $.ajax({
            url: url,
            type: 'GET',
            success: function(result) {
                if (result.success) {
                    toastr.success('Başarılı');
                    setTimeout(function() { window.location.href = location.pathname; }, 750);
                }else{
                    self.removeAttr('disabled');
                    toastr.error(result.error.message);
                }
            }
        });
    }else{
        self.removeAttr('disabled');
        return false;
    }
});

