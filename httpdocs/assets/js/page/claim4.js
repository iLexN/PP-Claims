var SubmitClaimBox = (function () {
    var $m = $('#submitClaim');
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
        $.jshook('submitClaimBtnClose').on({
            'click' : function(){
                window.location.replace("/main");
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
        'claim': claim,
    },
    created: function () {

    },
    components: {

    },
    watch: {

    },
    computed: {
        isSave : function(){
            if ( this.claim.status === 'Save') {
                return true;
            }
            return false;
        }
    },
    methods: {
        nextBtn1: function () {
            
            this.goAjaxClaim(function (data) {
                loadingBox.close();
                SubmitClaimBox.open();
            });
        },
        getFormDataClaim: function () {
            var data = [];
            this.claim.status = 'Submit';
            _.forEach(this.claim, function (value, key) {
                if (key === 'bank_info') {
                    _.forEach(this.bank, function (value, key) {
                        data.push({name: 'bank[' + key + ']', value: value})
                    });
                } else {
                    data.push({name: key, value: value})
                }
            });
            return data;
        },
        goAjaxClaim: function (callback) {
            var data = (csrf.getFormObj(this.getFormDataClaim()));
            $.ajax({
                url: '/ajax/claim/',
                type: 'POST',
                data: data,
                dataType: "json"
            }).done(function (data, textStatus, jqXHR) {
                callback(data);
            }).fail(function (jqXHR, textStatus, errorThrown) {
                loadingBox.close();
            });
        }
    }
});

