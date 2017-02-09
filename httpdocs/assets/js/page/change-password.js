var updateSuccess = (function () {
    var $m = $('#passwordUpdatedBox');
    function open() {
        $m.modal('open');
    }
    function close() {
        $m.modal('close');
    }
    function init() {
        $m.modal({
            dismissible: false,
            opacity: .4,
            starting_top: '30%',
            ending_top: '30%',
            in_duration: 500,
            out_duration: 100
        });
        $.jshook('passwordUpdatedBoxBtnClose').on({
            'click' : function(){
                window.location.replace("/member");
            }
        });
    }
    init();
    return {
        'open': open,
        'close': close
    };
})();

var app = new Vue({
    el: '#app',
    delimiters: ['${', '}'],
    data: {
        old_password: '',
        new_password: '',
        confirm_password: '',
    },
    created: function () {

    },
    components: {

    },
    watch: {

    },
    computed: {

    },
    methods: {
        'save': function () {
            
            $("#msg").html('').parent().addClass('hide');
            
            if (this.check()) {
                var formData = {
                    'old_password': this.old_password,
                    'new_password': this.new_password,
                    'confirm_password': this.confirm_password
                };
                var data = csrf.getFormObj(this.formPostData(formData));
                
                var self = this;
                $.ajax({
                    url: '/ajax/password/',
                    type: 'POST',
                    data: data,
                    dataType: "json"
                }).done(function (data) {
                    if ( data.status_code === 2530) {
                        updateSuccess.open();
                        return;
                    }
                    $("#msg").html(data.errors.title).parent().removeClass('hide');
                    loadingBox.close();
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    loadingBox.close();
                });
            }
        },
        check: function () {
            var error_el = [];
            if (_.isEmpty(this.old_password)) {
                error_el.push('old_password');
                $("#old_password").addClass('invalid');
            } else {
                $("#old_password").removeClass('invalid');
            }
            if (_.isEmpty(this.new_password)) {
                error_el.push('new_password');
                $("#new_password").addClass('invalid');
            } else {
                $("#new_password").removeClass('invalid');
            }
            if (_.isEmpty(this.confirm_password)) {
                error_el.push('confirm_password');
                $("#confirm_password").addClass('invalid');
            } else {
                $("#confirm_password").removeClass('invalid');
            }

            if (error_el.length === 0) {
                return true;
            } else {
                this.scrollTop(error_el[0]);
                return false;
            }
        },
        formPostData: function (input) {
            var data = [];
            _.forEach(input, function (value, key) {
                data.push({name: key, value: value})
            });
            return data;
        },
        scrollTop: function (hook) {
            $("html, body").animate({
                scrollTop: ($('#' + hook).offset().top - 150) + "px"
            }, {
                duration: 500,
                easing: "linear"
            });
        }
    }
});