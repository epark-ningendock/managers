/**
 *
 * @param message
 * @param confirmBtnText
 * @param callback function after confirm
 */
module.exports.showConfirm = function(message, callback) {
    $('#confirm-modal .modal-body p').html(message);
    $('#confirm-modal .btn-primary').click(callback || function() {});
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