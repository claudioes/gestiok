App.initForm = function (options) {
    "use strict";

    var defaults = {
        formId: null,
        onSuccess: false,
        onError: false
    };

    var settings = $.extend({}, defaults, options);

    var formId = 'form';

    if (settings.formId) {
        formId = settings.formId;
    }

    var form =  document.getElementById(formId);

    if (!form) return false;

    var $form = $(form);

    if (!settings.onError) {
        var $globalError = $('<div class="alert alert-danger" role="alert"></div>')
            .hide()
            .prependTo($form)
        ;

        var showError = function (errorMessage, $form, inputName, inputIndex) {
            var $input = $form.find('[name="' + inputName + '"]');

            if (typeof inputIndex !== 'undefined') {
                $input = $input.eq(inputIndex);
            }

            if ($input.length) {
                $input.parents('div.form-group').addClass('has-error');
                $input.after('<p class="help-block error-message">' +  errorMessage + '</p>');
            }
        };
    }

    $form.submit(function (e) {
        e.preventDefault();

        var data;
        var withData = this.enctype == 'multipart/form-data';

        if (withData) {
            data = new FormData(this);
        } else {
            data = $form.serialize();
        }

        if (!settings.onError) {
            $globalError.hide();
        }

        var $buttons = $form.find(':submit');
        $buttons.prop('disabled', true);

        $.ajax({
            url: this.getAttribute('action'),
            type: this.getAttribute('method'),
            data: data,
            dataType: "json",
            cache: withData? false: true,
            contentType: withData? false: 'application/x-www-form-urlencoded; charset=UTF-8',
            processData: withData? false: true
        }).done(function (result) {
            if (settings.onSuccess) {
                settings.onSuccess(result);
            } else {
                window.location.replace(result.redirect);
            }
        }).fail(function (xhr, textStatus, errorThrown) {
            var globalErrorMessage = '';

            if (settings.onError) {
                settings.onError(xhr, textStatus, errorThrown);
            } else {
                if (xhr.status == 400) {
                    var errors = xhr.responseJSON;

                    $form.find('div.form-group.has-error').removeClass('has-error');
                    $form.find('p.error-message').remove();

                    for (var field in errors) {
                        var message = errors[field];

                        if (typeof message === 'object') {
                            for (var index in message) {
                                showError(message[index], $form, field + '[]', index);
                            }
                        } else if (field == 'error') {
                            globalErrorMessage = '<p>' + message + '</p>';
                        } else {
                            showError(message, $form, field);
                        }
                    }
                } else {
                    globalErrorMessage = '<p>Ocurrió un error al intentar procesar la consulta. Vuelva a intentarlo y, si el problema persiste, pongase en contacto con el administrador.</p>';
                    // console.log(xhr);
                }

                if (globalErrorMessage) {
                    $globalError.html(globalErrorMessage).show();
                    $globalError.get(0).scrollIntoView();
                }
            }

            $buttons.prop('disabled', false);
        });
    });
};
