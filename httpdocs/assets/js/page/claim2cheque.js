// nealy same as member.js
// css class form_edit2 is not same
var useraddress = {
    props: {
        address: Object,
        k: Number
    },
    data: function () {
        return {
            a: {
                id: '',
                ppmid: '',
                nick_name: '',
                address_line_2: '',
                address_line_3: '',
                address_line_4: '',
                address_line_5: ''
            },
            mode: null
        };
    },
    created: function () {
        this.setA();
        this.mode = false;
    },
    computed: {

    },
    watch: {
        'mode': function (val, oldVal) {
            if (val) {
                $.jshook('addr_' + this.address.id).addClass('form_edit2').find("input , textarea").prop('disabled', false);
            } else {
                $.jshook('addr_' + this.address.id).removeClass('form_edit2').find("input , textarea").prop('disabled', true);
            }
        }
    },
    methods: {
        setA: function () {
            this.a.id = this.address.id == 'claim_cheque' ? '' : this.address.id;
            this.a.ppmid = this.address.ppmid;
            this.a.nick_name = this.address.nick_name;
            this.a.address_line_2 = this.address.address_line_2;
            this.a.address_line_3 = this.address.address_line_3;
            this.a.address_line_4 = this.address.address_line_4;
            this.a.address_line_5 = this.address.address_line_5;
        },
        edit: function () {
            this.mode = true;
            this.$emit('addressactive');
            $.jshook('member_address').addClass('member_address');
        },
        cancel: function () {
            this.mode = false;
            this.setA();
            $.jshook('member_address').removeClass('member_address');
        },
        save: function () {
            $.jshook('addr_' + this.a.id).find('input').eq(0).removeClass('invalid');
            $.jshook('addr_' + this.a.id).find('input').eq(1).removeClass('invalid');
            var isValid = true;
            if (_.isEmpty(this.a.nick_name)) {
                $.jshook('addr_' + this.a.id).find('input').eq(0).addClass('invalid');
                isValid = false;
            }
            if (_.isEmpty(this.a.address_line_2)) {
                $.jshook('addr_' + this.a.id).find('input').eq(1).addClass('invalid');
                isValid = false;
            }
            if (isValid) {
                var data = csrf.getFormObj(this.formPostData(this.a));
                var self = this;
                $.ajax({
                    url: '/ajax/address/',
                    type: 'POST',
                    data: data,
                    dataType: "json"
                }).done(function (data) {
                    if ( data.status_code === 2620) {
                        self.mode = false;
                        self.$emit('addressupdate',self.k,data.data);
                        loadingBox.close();
                    } else if ( data.status_code === 2626 ) {
                        loadingBox.close();
                        generalModel.open(sysText.sys_p_2626_t, sysText.sys_p_2626_d);
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    loadingBox.close();
                });
            } else {
                generalModel.open(sysText.sys_p_mi_t, sysText.sys_p_mi_d);
            }
        },
        del: function () {
            var data = csrf.getFormObj(this.formPostData(this.a));
            var self = this;
            $.ajax({
                url: '/ajax/address/' + this.a.id,
                type: 'POST',
                data: data,
                dataType: "json"
            }).done(function (data) {
                self.mode = false;
                self.$emit('addressdelete', self.k);
                loadingBox.close();
            }).fail(function (jqXHR, textStatus, errorThrown) {
                loadingBox.close();
            });
        },
        formPostData: function (input) {
            var data = [];
            _.forEach(input, function (value, key) {
                data.push({name: key, value: value})
            });
            return data;
        }
    }
};

var app = new Vue({
    el: '#app',
    delimiters: ['${', '}'],
    data: {
        'claim': claim,
        'address': address,
        'payto': payto,
        'activeAddress': null,
        'new_addr_mode': null,
        'new_addr': {
            'ppmid': ppmid,
            'nick_name': '',
            'address_line_2': '',
            'address_line_3': '',
            'address_line_4': '',
            'address_line_5': ''
        },
        'address_key': 0
    },
    created: function () {
        if (!_.isEmpty(this.claim.cheque)) {
            self = this;
            this.address_key = _.findIndex(this.address, function (o) {
                return o.address_line_2 == self.claim.cheque.address_line_2 && o.address_line_3 == self.claim.cheque.address_line_3;
            });
        }
    },
    components: {
        'useraddress': useraddress
    },
    watch: {
        'new_addr_mode': function (val, oldVal) {
            if (val) {
                $.jshook('newAddressMode').addClass('form_edit').find("input , textarea").prop('disabled', false);
            } else {
                $.jshook('newAddressMode').removeClass('form_edit').find("input , textarea").prop('disabled', true);
            }
        }
    },
    computed: {
        address_lenght: function () {
            return this.address.length;
        }
    },
    methods: {
        addressActives: function (key) {
            if (this.activeAddress !== null && this.activeAddress !== key && !_.isUndefined(this.$refs.addraddr[this.activeAddress])) {
                this.$refs.addraddr[this.activeAddress].cancel();
            }
            this.activeAddress = key;
        },
        resetNewAddress: function () {
            this.new_addr_mode = false;
            this.new_addr = {
                'ppmid': ppmid,
                'nick_name': '',
                'address_line_2': '',
                'address_line_3': '',
                'address_line_4': '',
                'address_line_5': ''
            };
        },
        saveNewAddress: function () {
            $.jshook('newAddressNick').removeClass('invalid');
            $.jshook('newAddressl1').removeClass('invalid');
            var isValid = true;
            if (_.isEmpty(this.new_addr.nick_name)) {
                $.jshook('newAddressNick').addClass('invalid');
                isValid = false;
            }
            if (_.isEmpty(this.new_addr.address_line_2)) {
                $.jshook('newAddressl1').addClass('invalid');
                isValid = false;
            }
            if (isValid) {
                var data = csrf.getFormObj(this.formPostData(this.new_addr));
                var self = this;
                $.ajax({
                    url: '/ajax/address/',
                    type: 'POST',
                    data: data,
                    dataType: "json"
                }).done(function (data) {
                    if ( data.status_code === 2610) {
                        self.resetNewAddress();
                        self.address.push(data.data);
                        loadingBox.close();
                        generalModel.open(sysText.sys_p_ad_t, sysText.sys_p_ad_d);
                        return;
                    } else if ( data.status_code === 2626) {
                        loadingBox.close();
                        generalModel.open(sysText.sys_p_an_t, sysText.sys_p_an_d);
                        return;
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    loadingBox.close();
                });
            } else {
                generalModel.open(sysText.sys_p_mi_t, sysText.sys_p_mi_d);
            }
        },
        addressUpdates: function (key, data) {
            this.address[key].nick_name = data.nick_name;
            this.address[key].address_line_2 = data.address_line_2;
            this.address[key].address_line_3 = data.address_line_3;
            this.address[key].address_line_4 = data.address_line_4;
            this.address[key].address_line_5 = data.address_line_5;
            $.jshook('member_address').removeClass('member_address');
        },
        addressDel: function (k) {
            this.address.splice(k, 1);
            this.address_key = 0;
        },
        formPostData: function (input) {
            var data = [];
            _.forEach(input, function (value, key) {
                data.push({name: key, value: value})
            });
            return data;
        },
        saveBtn1: function () {
                self = this;
                this.goAjax(function (data) {
                    loadingBox.close();
                    saveClaimBox.open();
                });
        },
        nextBtn1: function () {
            if (this.checkForm()) {
                self = this;
                this.goAjax(function (data) {
                    if (self.claim.isComplete) {
                        window.location.href = '/claim/' + data.data.id + '/summary';
                    } else {
                        window.location.href = '/claim/' + data.data.id + '/documents';
                    }
                });
            }
        },
        goAjax: function(callback){
            var formInput = [];
            _.forEach(this.claim, function (value, key) {
                formInput.push({name: key, value: value})
            });
            var chequeData = {
                name : this.payto,
                address_line_2 : this.address[this.address_key].address_line_2,
                address_line_3 : this.address[this.address_key].address_line_3,
                address_line_4 : this.address[this.address_key].address_line_4,
                address_line_5 : this.address[this.address_key].address_line_5,
            };
            _.forEach(chequeData, function (value, key) {
                formInput.push({name: 'cheque[' + key + ']', value: value})
            });
            
            var data = csrf.getFormObj(formInput);
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
        checkForm: function () {
            isValid = true;
            $.jshook('payto').removeClass('invalid');
            if (_.isEmpty(this.payto)) {
                $.jshook('payto').addClass('invalid');
                this.scrollTop('payto');
                isValid = false;
            }
            return isValid;
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
