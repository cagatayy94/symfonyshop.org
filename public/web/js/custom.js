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