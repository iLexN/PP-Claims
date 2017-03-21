//set forgot password
(function () {
    var $form = $.jshook('setforgotPasswordForm');
    var $btn = $.jshook('setforgotpasswordBtn');
    var $msg = $.jshook('setForgotPasswordMsg');
    var $success = $.jshook('setforgotPasswordSuccess');

    function submit() {
        var data = csrf.getFormObj($form.serializeArray());
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
                loadingBox.open();
                $("#forgotSetPassword").modal({
            dismissible: true,
            opacity: .4,
            starting_top: '10%',
            ending_top: '10%',
            in_duration: 500,
            out_duration: 100
            });
               $('#forgotSetPassword').modal('open');
                
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
        $msg.html('').parent().addClass('hide');
    }
    function ajaxEnd(msg) {
        $msg.html(msg).parent().removeClass('hide');
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