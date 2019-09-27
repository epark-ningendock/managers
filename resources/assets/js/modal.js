/**
 *
 * @param message
 * @param confirmBtnText
 * @param callback function after confirm
 */
module.exports.showConfirm = function() {
    const message =  typeof arguments[0] != 'function' ? arguments[0] : null;
    const btnText = typeof arguments[1] != 'function' ? arguments[1] : null;
    const modal = typeof arguments[2] != 'function' ? arguments[2] : null;
    const callback = typeof arguments[arguments.length - 1] == 'function' ? arguments[arguments.length - 1] : function() {};

    if (message) {
        $(modal).find('.modal-body p').html(message);
    }
    if (btnText) {
        $(modal).find('#confirm-button').html(btnText);
    }
    $(modal).find('.btn-primary').click(callback);

    $(modal).modal('show');
}

/**
 *
 * @param message
 */
module.exports.showMessage = function(message) {
    $('#alert-modal .modal-body p').html(message);
    $('#alert-modal').modal('show');
}