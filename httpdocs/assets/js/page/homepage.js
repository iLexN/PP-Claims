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
                window.location.replace("/main");
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
        $form.on({
            submit : false
        });
    }
    init();
})();
//forgot password
(function () {
    var $m = $("#forgotPassword");
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
            'click.forgotpassword': submit
        });
        $form.on({
            submit : false
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
        $form.on({
            submit : false
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
                window.location.replace("/main");
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
        $form.on({
            submit : false
        });
        $formSignup.on({
            submit : false
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
                window.location.replace("/main");
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
        $form.on({
            submit : false
        });
    }
    init();
})();