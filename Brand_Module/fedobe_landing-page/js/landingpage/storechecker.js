var Storechecker = {
    doaction: function (param, obj, default_store_id, store_id, data, fieldname) {
        data = data.evalJSON(true);
        if ($(obj).checked) {
            //Here let's set the Default value
            if ($(param).getAttribute('type') && $(param).getAttribute('type') !== 'file') {
                if (data[fieldname][default_store_id])
                    $(param).setValue(data[fieldname][default_store_id]);
            }
            $(param).disable();
        } else {
            if ($(param).getAttribute('type') && $(param).getAttribute('type') !== 'file') {
                if (data[fieldname][store_id])
                    $(param).setValue(data[fieldname][store_id]);
            }
            $(param).enable();
            //Here to trigger blur and focus for url key
            if (fieldname == 'url_key') {
                setTimeout(function () {
                    $(param).focus();
                    $(param).blur();
                }.bind(this), 500);
            }
        }
    }
};