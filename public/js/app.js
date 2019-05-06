/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(1);
module.exports = __webpack_require__(4);


/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

window.Modal = __webpack_require__(2);

__webpack_require__(3);

/***/ }),
/* 2 */
/***/ (function(module, exports) {

/**
 *
 * @param message
 * @param confirmBtnText
 * @param callback function after confirm
 */
module.exports.showConfirm = function () {
    var message = typeof arguments[0] != 'function' ? arguments[0] : null;
    var callback = typeof arguments[arguments.length - 1] == 'function' ? arguments[arguments.length - 1] : function () {};

    if (message) {
        $('#confirm-modal .modal-body p').html(message);
    }
    $('#confirm-modal .btn-primary').click(callback);

    $('#confirm-modal').modal('show');
};

/**
 *
 * @param message
 */
module.exports.showMessage = function (message) {
    $('#alert-modal .modal-body p').html(message);
    $('#alert-modal').modal('show');
};

/***/ }),
/* 3 */
/***/ (function(module, exports) {

(function ($) {

    /* ---------------------------------------------------
    Prepare popup box to delete form
    -----------------------------------------------------*/
    (function () {
        $('.delete-popup-btn').on('click', function () {
            var id = $(this).data('id');
            var targetForm = $(this).data('target-form') || '#delete-record-form';
            var message = $(this).data('message');
            var targetFormAction = $(targetForm).attr('action').replace(':id', id);
            $(targetForm).attr('action', targetFormAction);

            Modal.showConfirm(message, function () {
                $(targetForm).submit();
            });
            return false;
        });
    })();

    /* ---------------------------------------------------
    Tab
    -----------------------------------------------------*/
    $('.tab-list').on('click', '.btn', function (e) {

        e.preventDefault();
        $(this).tab('show');

        $(this).siblings('.btn').removeClass('active');
        $(this).addClass('active');
    });
})(jQuery);

/***/ }),
/* 4 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
/******/ ]);