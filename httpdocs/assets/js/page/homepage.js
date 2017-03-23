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
                e_msg = "Sorry! It looks like the field below is blank. Please fill it in to proceed.";
            } else if ( data.status_code === 2010 ){
                e_msg = 'Sorry, the username you have just entered doesn’t seem to exist. Please double check what you have entered or try re-entering your username again.';
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
                e_msg = 'Sorry! It seems that there is a missing field. Please fill in the missing fields below.';
            } else if ( data.status_code === 2010 ) {
                e_msg = 'Sorry, the email address that you have provided does not appear to be in our database. Please try re-entering your information again.';
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
                e_msg = 'Sorry, it looks like the field below is blank. Please try again and enter your registration code in the field below. If you do not have a code, please contact us.';
            } else if ( data.status_code === 2010 ) {
                e_msg = 'Sorry, it looks like you have entered the wrong registration code. Please try again, or contact us if you require further assistance.';
            } else if ( data.status_code === 2051 ){
                e_msg = 'Sorry, it appears that the birth date you have entered does not meet our requirements. Please double check the date of birth you have entered, or ensure that you have entered the main policyholder’s date of birth. Contact us if you require further assistance.';
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
                e_msg = 'This username already exists, please enter another username.';
                ajaxEnd(e_msg);
                return;
            } else if ( data.status_code === 1010) {
                e_msg = 'Sorry, it looks like the field below is blank. Please try again and enter your registration code in the field below. If you do not have a code, please contact us. ';
                ajaxEnd(e_msg);
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