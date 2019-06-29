/**
 *
 * @param message
 * @param confirmBtnText
 * @param callback function after confirm
 */
module.exports.showConfirm = function() {
    const message =  typeof arguments[0] != 'function' ? arguments[0] : null;
    const btnText = typeof arguments[1] != 'function' ? arguments[1] : null;
    const callback = typeof arguments[arguments.length - 1] == 'function' ? arguments[arguments.length - 1] : function() {};

    if (message) {
        $('#confirm-modal .modal-body p').html(message);
    }
    if (btnText) {
        $('#confirm-modal #confirm-button').html(btnText);
    }
    $('#confirm-modal .btn-primary').click(callback);

    $('#confirm-modal').modal('show');
}

/**
 *
 * @param message
 */
module.exports.showMessage = function(message) {
    $('#alert-modal .modal-body p').html(message);
    $('#alert-modal').modal('show');
}