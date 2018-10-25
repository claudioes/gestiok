App.Attachment = (function () {
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
            var dz = $form.dropzone({
                init: function() {
                    this.on("success", function (file, response) {
                        this.removeFile(file);
                        show();
                    });
                },
            });
        }

        if ($container.length) {
            $container.on('click', 'a.attachment-delete', function (e) {
                e.preventDefault();

                $.ajax({
                    url: this.dataset.url,
                    type: 'delete',
                    data: App.csrf,
                }).done(function () {
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
