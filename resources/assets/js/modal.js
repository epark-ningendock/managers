/**
 *
 * @param message
 * @param confirmBtnText
 * @param callback function after confirm
 */
module.exports.showConfirm = function() {
    const message =  typeof arguments[0] != 'function' ? arguments[0] : null;
    const callback = typeof arguments[arguments.length - 1] == 'function' ? arguments[arguments.length - 1] : function() {};

    if (message) {
        $('#confirm-modal .modal-body p').html(message);
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