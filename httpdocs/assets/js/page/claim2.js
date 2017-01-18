

var app = new Vue({
    el: '#app',
    delimiters: ['${', '}'],
    data: {
        'claim': claim,
        'banks': banks,
        'bank': [],
        'edit': null,
        'bigSaveBtn': null,
        'key': 0
    },
    created: function () {
        if (_.isEmpty(this.banks[0].nick_name)) {
            this.edit = true;
            this.bigSaveBtn = true;
        } else {
            this.edit = false;
            this.bigSaveBtn = false;
        }

        if (!_.isEmpty(this.claim.bank_info)) {
            self = this;
            this.key = _.findIndex(this.banks, function (o) {
                return o.account_number == self.claim.bank_info.account_number;
            });
        }

        this.bank = this.banks[this.key];
    },
    components: {

    },
    watch: {
        'edit': function (val, oldVal) {
            if (val) {
                $.jshook('formMode').addClass('form_edit').find("input , textarea").prop('disabled', false);
            } else {
                $.jshook('formMode').removeClass('form_edit').find("input , textarea").prop('disabled', true);
            }
        }
    },
    computed: {

    },
    methods: {
        changeBank: function (k) {
            this.key = k;
            this.bank = this.banks[k];
        },
        editBank: function () {
            this.edit = true;
            this.bank = {
                'banker_transfer_id': this.banks[this.key].banker_transfer_id,
                'nick_name': this.banks[this.key].nick_name,
                'currency': this.banks[this.key].currency,
                'account_user_name': this.banks[this.key].account_user_name,
                'account_number': this.banks[this.key].account_number,
                'iban': this.banks[this.key].iban,
                'branch_code': this.banks[this.key].branch_code,
                'bank_swift_code': this.banks[this.key].bank_swift_code,
                'bank_name': this.banks[this.key].bank_name,
                'additional_information': this.banks[this.key].additional_information,
                'intermediary_bank_swift_code': this.banks[this.key].intermediary_bank_swift_code
            };
            $('select').material_select('destroy');
        },
        saveBank: function () {
            if (this.checkBank()) {
                var self = this;
                this.goAjax(function (data) {
                    self.edit = false;
                    self.bigSaveBtn = false;
                    self.bank.banker_transfer_id = data.data.banker_transfer_id;
                    self.bank.currency_display = $.jshook('currency').find('option:selected').text();
                    $('select').material_select();
                    $('.select-dropdown').val(self.bank.nick_name);
                });
            }
        },
        saveOrNewBank: function () {
            if (this.checkBank()) {
                var self = this;
                this.goAjax(function (data) {
                    self.edit = false;
                    self.bank.currency_display = $.jshook('currency').find('option:selected').text();

                    if (data.status_code == '3610') {
                        self.bank.banker_transfer_id = data.data.banker_transfer_id;
                        self.banks.push(self.bank);
                        self.key = self.key + 1;
                    } else {
                        self.banks[self.key] = self.bank;
                    }

                    _.delay(function () {
                        $('select').material_select();
                        $('.select-dropdown').val(self.bank.nick_name);
                    }, 100);

                });
            }
        },
        createNewBank: function () {
            console.log('createNewBank');
            this.bank = {
                'nick_name': '',
                'currency': this.banks[0].currency,
                'account_user_name': '',
                'account_number': '',
                'iban': '',
                'branch_code': '',
                'bank_swift_code': '',
                'bank_name': '',
                'additional_information': '',
                'intermediary_bank_swift_code': ''
            };
            this.edit = true;
            $('select').material_select('destroy');
        },
        checkBank: function () {
            var error_el = [];
            _.forEach(this.bank, function (value, key) {
                if (key === 'additional_information' || key === 'currency_display') {
                    return;
                }
                if (value === '') {
                    $("#" + key).addClass('invalid');
                    error_el.push(key);
                } else {
                    $("#" + key).removeClass('invalid');
                }
            });
            if (error_el.length === 0) {
                return true;
            } else {
                this.scrollTop(error_el[0]);
                return false;
            }
        },
        cancelBtn: function () {
            this.bank = this.banks[this.key];
            this.edit = false;
            $('select').material_select();
            $('.select-dropdown').val(this.bank.nick_name);
        },
        saveBtn1: function () {
            self = this;
            this.goAjaxClaim(function (data) {
                loadingBox.close();
                saveClaimBox.open();
            });
        },
        nextBtn1: function () {
            self = this;
            this.goAjaxClaim(function (data) {
                window.location.href = '/claim/'+data.data.id+'/documents';
            });
        },
        getFormData: function () {
            var data = [];
            _.forEach(this.bank, function (value, key) {
                data.push({name: key, value: value})
            });
            return data;
        },
        goAjax: function (callback) {
            var data = (csrf.getFormObj(this.getFormData()));
            $.ajax({
                url: '/ajax/bank/',
                type: 'POST',
                data: data,
                dataType: "json"
            }).done(function (data, textStatus, jqXHR) {
                callback(data);
                loadingBox.close();
            }).fail(function (jqXHR, textStatus, errorThrown) {
                loadingBox.close();
            });
        },
        getFormDataClaim: function () {
            var data = [];
            _.forEach(this.claim, function (value, key) {
                data.push({name: key, value: value})
            });
            _.forEach(this.bank, function (value, key) {
                data.push({name: 'bank[' + key + ']', value: value})
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


$.jshook('bankSelectBox').on({
    'change': function () {
        app.changeBank($(this).val());
    }
});