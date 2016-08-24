
(function($) {
    $.fn.inno = {
        ajax : function(url, idata, callback) {
            $.ajax({
                url: url,
                data: idata,
                type: 'POST'
            }).success(function(data) {
                callback(data);
            });
        }
    };
})(jQuery);