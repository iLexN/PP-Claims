//js hook
$.extend({
    jshook: function (hookName) {
        var selector;
        if (!hookName || hookName === '*') {
            // select all data-hooks
            selector = '[data-jshook]';
        } else {
            // select specific data-hook
            selector = '[data-jshook~="' + hookName + '"]';
        }
        return $(selector);
    }
});

// module
function createpasswordModule() {
    'use strict';
    var password1 = '', password2 = '';
    function isLenghtPass(passLenght) {
        return password1.length >= passLenght;
    }
    function setP1(val) {
        password1 = val;
    }
    function setP2(val) {
        password2 = val;
    }
    function hasUpperCase() {
        return password1.match(/[A-Z]/);
    }
    function hasLowerCase() {
        return password1.match(/[a-z]/);
    }
    function hasNumber() {
        return password1.match(/\d/);
    }
    function isPassSame() {
        return (password1 === password2);
    }
    return {
        setP1: setP1,
        setP2: setP2,
        isLenghtPass: isLenghtPass,
        hasUpperCase: hasUpperCase,
        hasLowerCase: hasLowerCase,
        hasNumber: hasNumber,
        isPassSame: isPassSame
    };
}

var BtnStatus = (function () {
    function disableBtn() {
        $(this).prop("disabled", true);
    }
    function enableBtn() {
        $(this).prop("disabled", false);
    }
    return {
        'disable': disableBtn,
        'enable': enableBtn
    };
})();

isOnLine = (function () {
    var $msgDiv = $.jshook('offlineMsg');
    var $formBtn = $.jshook('formBtn');
    function check() {
        if (navigator.onLine) {
            return true;
        } else {
            return false;
        }
    }
    function ui() {
        if (check()) {
            $msgDiv.addClass('hide');
            $formBtn.removeClass('disabled');
        } else {
            $msgDiv.removeClass('hide');
            $formBtn.addClass('disabled');
        }
    }
    function init() {
        ui();
        window.addEventListener('online', ui);
        window.addEventListener('offline', ui);
    }
    init(); 
    return {
        'check': check,
        'ui': ui,
        'init': init
    };
})();
//isOnLine.init();

var csrf = (function () {
    var nameKey = '';
    var valueKey = '';
    function setNameKey(val) {
        nameKey = val;
    }
    function setValueKey(val) {
        valueKey = val;
    }
    function getNameKey() {
        return nameKey;
    }
    function getValueKey() {
        return valueKey;
    }
    function getFormObj(obj) {
        obj.push({name: 'csrf_name', value: getNameKey()});
        obj.push({name: 'csrf_value', value: getValueKey()});
        return obj;
    }
    function setObj(str) {
        var obj = jQuery.parseJSON(str);
        if (obj === null) {
            return;
        }
        setNameKey(obj.csrf_name);
        setValueKey(obj.csrf_value);
    }
    return {
        'setNameKey': setNameKey,
        'setValueKey': setValueKey,
        'getFormObj': getFormObj,
        'setObj': setObj
    };
})();
//
$(document).ajaxComplete(function (event, jqXHR, settings) {
    csrf.setObj(jqXHR.getResponseHeader('X-CSRF-Token'));
});
$(document).ajaxStart(function () {
    loadingBox.open();
});

var loadingBox = (function () {
    function open() {
        $('#loadingBox').modal('open');
    }
    function close() {
        $('#loadingBox').modal('close');
    }
    function init(){
        $('#loadingBox').modal({
            dismissible: false,
            opacity: .4, 
            starting_top: '30%',
            ending_top: '30%',
            in_duration: 500,
            out_duration: 100
        });
    }
    init();
    return {
        'open': open,
        'close': close
    };
})();
//login
(function () {
    var $form = $.jshook('loginForm');
    var $btn = $.jshook('loginBtn');
    var $loginMsg = $.jshook('loginMsg');

    function submit() {
        if (!isOnLine.check()) {
            return false;
        }
        var data = (csrf.getFormObj($form.serializeArray()));
        $.ajax({
            beforeSend: function () {
                ajaxStart();
            },
            url: '/ajax/system/login',
            type: 'POST',
            data: data,
            dataType: "json"
        }).done(function (data, textStatus, jqXHR) {
            if (data.status_code === 2081) {
                //success
                window.location.replace("/login-ed");
                return;
            }
            //fail
            ajaxEnd(data.errors.title);
        }).fail(function (jqXHR, textStatus, errorThrown) {
            ajaxEnd(textStatus);
        });
    }
    function ajaxStart() {
        $btn.prop("disabled", true);
        $loginMsg.html('').addClass('hide');
    }
    function ajaxEnd(msg) {
        $loginMsg.html(msg).removeClass('hide');
        loadingBox.close();
        $btn.prop("disabled", false);
    }
    function init(){
        $btn.on({
            'click.login': submit
        });
    }
    init();
})();
//forgot password
(function () {
    var $form = $.jshook('forgotPasswordForm');
    var $btn = $.jshook('forgotpasswordBtn');
    var $msg = $.jshook('ForgotPasswordMsg');
    var $success = $.jshook('forgotPasswordSuccess');
    var $closeBtn = $.jshook('forgotpasswordBtnClose');

    function submit() {
        if (!isOnLine.check()) {
            return false;
        }
        var data = (csrf.getFormObj($form.serializeArray()));
        $.ajax({
            beforeSend: function () {
                ajaxStart();
            },
            url: '/ajax/system/forgot-password',
            type: 'POST',
            data: data,
            dataType: "json"
        }).done(function (data, textStatus, jqXHR) {
            if (data.status_code === 2540) {
                //success
                $form.addClass('hide');
                $success.removeClass('hide');
                ajaxEnd();
                return;
            }
            //fail
            ajaxEnd(data.errors.title);
            //$("#forgotpassword_username").addClass('invalid');
        }).fail(function (jqXHR, textStatus, errorThrown) {
            ajaxEnd(textStatus);
        });
    }
    function ajaxStart() {
        $btn.prop("disabled", true);
        $msg.html('').addClass('hide');
        //$("#forgotpassword_username").removeClass('invalid');
    }
    function ajaxEnd(msg) {
        $msg.html(msg).removeClass('hide');
        loadingBox.close();
        $btn.prop("disabled", false);
    }
    function modelClose(){
        $('#forgotPassword').modal('close');
    }
    function init(){
        $('#forgotPassword').modal({
            dismissible: true,
            opacity: .4,
            starting_top: '10%',
            ending_top: '10%',
            in_duration: 500,
            out_duration: 100
        });
        $btn.on({
            'click.forgotpassword': submit
        });
        $closeBtn.on({
            click : modelClose
        });
    }
    init();
})();
//forgot username
(function () {
    var $m = $('#forogtUsername');
    var $form = $.jshook('forgotUsernameForm');
    var $btn = $.jshook('forgotUsernameBtn');
    var $msg = $.jshook('ForgotUsernameMsg');
    var $success = $.jshook('forgotUsernameSuccess');
    var $closeBtn = $.jshook('forgotUsernameBtnClose');

    function submit() {
        if (!isOnLine.check()) {
            return false;
        }
        var data = (csrf.getFormObj($form.serializeArray()));
        $.ajax({
            beforeSend: function () {
                ajaxStart();
            },
            url: '/ajax/system/forgot-username',
            type: 'POST',
            data: data,
            dataType: "json"
        }).done(function (data, textStatus, jqXHR) {
            if (data.status_code === 2550) {
                //success
                $form.addClass('hide');
                $success.removeClass('hide');
                ajaxEnd();
                return;
            }
            //fail
            ajaxEnd(data.errors.title);
            $m.scrollTop(10);
        }).fail(function (jqXHR, textStatus, errorThrown) {
            ajaxEnd(textStatus);
        });
    }
    function ajaxStart() {
        $btn.prop("disabled", true);
        $msg.html('').addClass('hide');
    }
    function ajaxEnd(msg) {
        $msg.html(msg).removeClass('hide');
        loadingBox.close();
        $btn.prop("disabled", false);
    }
    function modelClose(){
        $m.modal('close');
    }
    function init(){
        $m.modal({
            dismissible: true,
            opacity: .4,
            starting_top: '10%',
            ending_top: '10%',
            in_duration: 500,
            out_duration: 100
        });
        $btn.on({
            'click.forgotusername': submit
        });
        $closeBtn.on({
            click : modelClose
        });
    }
    init();
})();
//user verfiy
(function () {
    var $m = $("#userVerify");
    var $form = $.jshook('userVerifyForm');
    var $btn = $.jshook('userVerifyBtn');
    var $msg = $.jshook('userVerifyMsg');
    
    var $formSignup = $.jshook('userSignForm');
    var $btnSignup = $.jshook('userSignupBtn');
    var $msgSignup = $.jshook('userSignUpMsg');

    function submit() {
        if (!isOnLine.check()) {
            return false;
        }
        var data = (csrf.getFormObj($form.serializeArray()));
        $.ajax({
            beforeSend: function () {
                ajaxStart();
            },
            url: '/ajax/system/user-verify',
            type: 'POST',
            data: data,
            dataType: "json"
        }).done(function (data, textStatus, jqXHR) {
            if (data.status_code === 2040) {
                //already Register
                ajaxEnd(data.data.title);
                return;
            }
            if (data.status_code === 2050) {
                //go next
                $form.addClass('hide');
                $formSignup.removeClass('hide');
                ajaxEnd();
                return;
            }
            //fail
            ajaxEnd(data.errors.title);
        }).fail(function (jqXHR, textStatus, errorThrown) {
            ajaxEnd(textStatus);
        });
    }
    function submitSignUp() {
        if (!isOnLine.check()) {
            return false;
        }
        var data = (csrf.getFormObj($formSignup.serializeArray()));
        $.ajax({
            beforeSend: function () {
                ajaxStart();
            },
            url: '/ajax/system/user-singup',
            type: 'POST',
            data: data,
            dataType: "json"
        }).done(function (data, textStatus, jqXHR) {
            if (data.status_code === 2030) {
                window.location.replace("/login-ed");
                return;
            }
            //fail
            ajaxEnd(data.errors.title);
        }).fail(function (jqXHR, textStatus, errorThrown) {
            ajaxEnd(textStatus);
        });
    }
    function ajaxStart() {
        $btn.prop("disabled", true);
        $btnSignup.prop("disabled", true);
        $msg.html('').addClass('hide');
        $msgSignup.html('').addClass('hide');
    }
    function ajaxEnd(msg) {
        $msg.html(msg).removeClass('hide');
        $msgSignup.html(msg).removeClass('hide');
        loadingBox.close();
        $btn.prop("disabled", false);
        $btnSignup.prop("disabled", false);
        $m.scrollTop(10);
    }
    function init(){
        $m.modal({
            dismissible: true,
            opacity: .4,
            starting_top: '10%',
            ending_top: '10%',
            in_duration: 500,
            out_duration: 100
        });
        $btn.on({
            'click.verfiy': submit
        });
        $btnSignup.on({
            'click.signup': submitSignUp
        });
    }
    init();
})();
//set forgot password
(function () {
    var $form = $.jshook('setforgotPasswordForm');
    var $btn = $.jshook('setforgotpasswordBtn');
    var $msg = $.jshook('setForgotPasswordMsg');
    var $success = $.jshook('setforgotPasswordSuccess');

    function submit() {
        if (!isOnLine.check()) {
            return false;
        }
        var data = (csrf.getFormObj($form.serializeArray()));
        $.ajax({
            beforeSend: function () {
                ajaxStart();
            },
            url: '/ajax/system/forgot-set-password',
            type: 'POST',
            data: data,
            dataType: "json"
        }).done(function (data, textStatus, jqXHR) {
            if (data.status_code === 2570) {
                //success
                window.location.replace("/login-ed");
                return;
            }
            //fail
            ajaxEnd(data.errors.title);
        }).fail(function (jqXHR, textStatus, errorThrown) {
            ajaxEnd(textStatus);
        });
    }
    function ajaxStart() {
        $btn.prop("disabled", true);
        $msg.html('').addClass('hide');
    }
    function ajaxEnd(msg) {
        $msg.html(msg).removeClass('hide');
        loadingBox.close();
        $btn.prop("disabled", false);
    }
    function init(){
        $btn.on({
            'click.setforgotpass': submit
        });
    }
    init();
})();

//helper
var debounce = function (func, wait) {
    // we need to save these in the closure
    var timeout, args, context, timestamp;
    return function () {
        // save details of latest call
        context = this;
        args = [].slice.call(arguments, 0);
        timestamp = new Date();
        // this is where the magic happens
        var later = function () {
            // how long ago was the last call
            var last = (new Date()) - timestamp;
            // if the latest call was less that the wait period ago
            // then we reset the timeout to wait for the difference
            if (last < wait) {
                timeout = setTimeout(later, wait - last);
                // or if not we can null out the timer and run the latest
            } else {
                timeout = null;
                func.apply(context, args);
            }
        };
        // we only need to set the timer now if one isn't already running
        if (!timeout) {
            timeout = setTimeout(later, wait);
        }
    }
};
