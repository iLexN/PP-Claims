//login
(function () {
    var $form = $.jshook('loginForm');
    var $btn = $.jshook('loginBtn');
    var $loginMsg = $.jshook('loginMsg');

    function submit() {
        var data = csrf.getFormObj($form.serializeArray());
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
        var data = csrf.getFormObj($form.serializeArray());
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
            var e_msg = '';
            if ( data.status_code === 1010) {
                e_msg = sysText.sys_fw_1010;
            } else if ( data.status_code === 2010 ){
                e_msg = sysText.sys_fw_2010;
            }
            
            ajaxEnd(e_msg);
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
        var data = csrf.getFormObj($form.serializeArray());
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
            var e_msg = '';
            if ( data.status_code === 1020 ) {
                e_msg = sysText.sys_fu_1020;
            } else if ( data.status_code === 2010 ) {
                e_msg = sysText.sys_fu_2010;
            }
            
            ajaxEnd(  e_msg );
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
    var $msgSuccess = $.jshook('userVerifySuccess');
    var $userVerifyBtnGo = $.jshook('userVerifyBtnGo');

    function submit() {
        var data = csrf.getFormObj($form.serializeArray());
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
            var e_msg = '';
            if ( data.status_code === 1020 ){
                e_msg = sysText.sys_uv1_1020;
            } else if ( data.status_code === 2010 ) {
                e_msg = sysText.sys_uv1_2010;
            } else if ( data.status_code === 2051 ){
                e_msg = sysText.sys_uv1_2051;
            }
            
            //fail
            ajaxEnd(e_msg);
        }).fail(function (jqXHR, textStatus, errorThrown) {
            ajaxEnd(textStatus);
        });
    }
    function submitSignUp() {
        var data = csrf.getFormObj($formSignup.serializeArray());
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
                loadingBox.close();
                //window.location.replace("/main");
                $formSignup.addClass('hide');
                $msgSuccess.removeClass('hide');
                
                return;
            }
            //fail
            
            var e_msg = '';
            if ( data.status_code === 2070 ){
                ajaxEnd(sysText.sys_uv2_2070);
                return;
            } else if ( data.status_code === 1010) {
                ajaxEnd(sysText.sys_uv2_1010);
                return;
            }
            
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
        $userVerifyBtnGo.on({
            'click' : function(){
                window.location.replace("/main");
            }
        });
    }
    init();
})();