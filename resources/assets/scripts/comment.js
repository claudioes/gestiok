App.Comment = (function () {
    "use strict";

    var url;
    var $container;
    var $count;

    var init = function (settings) {
        url = settings.url;
        $container = $(settings.container);
        $count = $(settings.count);

        var $form = $(settings.form);

        if ($form.length) {
            $form.submit(function (e) {
                e.preventDefault();

                $.ajax({
                    type: $form.attr('method'),
                    url: $form.attr('action'),
                    data: $form.serialize(),
                    dataType: 'json',
                }).done(function (data) {
                    $form.get(0).reset();
                    show();
                });
            });
        }
    };

    var show = function () {
        if ($container.length) {
            $.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
            }).done(function (result) {
                $container.html(result.html);
                $count.html(result.count);
            });
        }
    };

    return {
        init: init,
        show: show,
    };
})();
