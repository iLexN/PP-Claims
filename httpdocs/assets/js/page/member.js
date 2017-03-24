var useraddress = {
    props: {
        address: Object,
        k: Number
    },
    data: function () {
        return {
            a: {
                id: '',
                ppmid : '',
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
                $.jshook('addr_' + this.a.id).addClass('form_edit').find("input , textarea").prop('disabled', false);
            } else {
                $.jshook('addr_' + this.a.id).removeClass('form_edit').find("input , textarea").prop('disabled', true);
            }
        }
    },
    methods: {
        setA: function () {
            this.a.id = this.address.id;
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
                    url: '/ajax/address/'+ this.a.id,
                    type: 'POST',
                    data: data,
                    dataType: "json"
                }).done(function (data) {
                    self.mode = false;
                    self.$emit('addressdelete',self.k);
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
        'peopleList': data,
        'p': data[0],
        'contact_mode': null,
        'holder_mode': null,
        'holder': holder,
        'key': 0,
        'tmpData': [],
        'activeAddress': null,
        'new_addr_mode': null,
        'new_addr': {
            'ppmid': data[0].ppmid,
            'nick_name': '',
            'address_line_2': '',
            'address_line_3': '',
            'address_line_4': '',
            'address_line_5': ''
        }
    },
    components: {
        'useraddress': useraddress
    },
    created: function () {
        this.contact_mode = false;
        this.holder_mode = false;
        this.new_addr_mode = false;
        this.getDetails();
    },
    computed: {
        address_lenght: function () {
            return this.p.address.length;
        }
    },
    watch: {
        'contact_mode': function (val, oldVal) {
            if (val) {
                $.jshook('contactMode').addClass('form_edit').find("input , textarea").prop('disabled', false);
            } else {
                $.jshook('contactMode').removeClass('form_edit').find("input , textarea").prop('disabled', true);
            }
        },
        'holder_mode': function (val, oldVal) {
            if (val) {
                $.jshook('holderMode').addClass('form_edit').find("input , textarea").prop('disabled', false);
            } else {
                $.jshook('holderMode').removeClass('form_edit').find("input , textarea").prop('disabled', true);
            }
        },
        'new_addr_mode': function (val, oldVal) {
            if (val) {
                $.jshook('newAddressMode').addClass('form_edit').find("input , textarea").prop('disabled', false);
            } else {
                $.jshook('newAddressMode').removeClass('form_edit').find("input , textarea").prop('disabled', true);
            }
        },
        'p': function (val, oldVal) {
            this.getDetails();
            this.resetNewAddress();
        }
    },
    methods: {
        getDetails: function () {
            if (this.p.ajax === false) {
                var self = this;
                $.getJSON("ajax/member/" + this.p.ppmid, function (data) {
                    self.p.address = data.address;
                    self.p.renew = data.renew;
                    if (!_.isEmpty(data.renew)) {
                        self.p.phone_1 = data.renew.phone_1;
                        self.p.phone_2 = data.renew.phone_2;
                        self.p.email = data.renew.email;
                    }
                    self.p.ajax = true;
                    loadingBox.close();
                });
            }
        },
        editContact: function () {
            this.contact_mode = true;
            this.tmpData = {
                'phone_1': this.p.phone_1,
                'phone_2': this.p.phone_2,
                'email': this.p.email
            };
        },
        cancelContact: function () {
            this.contact_mode = false;
            this.p.phone_1 = this.tmpData.phone_1;
            this.p.phone_2 = this.tmpData.phone_2;
            this.p.email = this.tmpData.email;
        },
        saveContact: function () {
            var formData = {
                'phone_1': this.p.phone_1,
                'phone_2': this.p.phone_2,
                'email': this.p.email
            };
            var data = csrf.getFormObj(this.formPostData(formData));
            var self = this;
            $.ajax({
                url: '/ajax/member/' + this.p.ppmid,
                type: 'POST',
                data: data,
                dataType: "json"
            }).done(function () {
                self.p.renew = formData;
                self.contact_mode = false;
                loadingBox.close();
            }).fail(function (jqXHR, textStatus, errorThrown) {
                loadingBox.close();
            });
        },
        editHolder: function () {
            this.holder_mode = true;
            this.tmpData = {
                'policy_address_line_2': this.holder.policy_address_line_2,
                'policy_address_line_3': this.holder.policy_address_line_3,
                'policy_address_line_4': this.holder.policy_address_line_4,
                'policy_address_line_5': this.holder.policy_address_line_5,
            };
        },
        cancelHolder: function () {
            this.holder_mode = false;
            this.holder.policy_address_line_2 = this.tmpData.policy_address_line_2;
            this.holder.policy_address_line_3 = this.tmpData.policy_address_line_3;
            this.holder.policy_address_line_4 = this.tmpData.policy_address_line_4;
            this.holder.policy_address_line_5 = this.tmpData.policy_address_line_5;
        },
        saveHolder: function () {
            var formData = {
                'policy_address_line_2': this.holder.policy_address_line_2,
                'policy_address_line_3': this.holder.policy_address_line_3,
                'policy_address_line_4': this.holder.policy_address_line_4,
                'policy_address_line_5': this.holder.policy_address_line_5
            };
            var data = csrf.getFormObj(this.formPostData(formData));
            var self = this;
            $.ajax({
                url: '/ajax/holder/' + this.holder.id,
                type: 'POST',
                data: data,
                dataType: "json"
            }).done(function () {
                self.holder_mode = false;
                self.holder.renew = true;
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
        },
        changeMember: function (k) {
            this.key = k;
            this.p = this.peopleList[k];
        },
        addressActives: function (key) {
            if (this.activeAddress !== null && this.activeAddress !== key && !_.isUndefined(this.$refs.addraddr[this.activeAddress])) {
                this.$refs.addraddr[this.activeAddress].cancel();
            }
            this.activeAddress = key;
        },
        resetNewAddress: function () {
            this.new_addr_mode = false;
            this.new_addr = {
                'ppmid': this.p.ppmid,
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
                        self.p.address.push(data.data);
                        loadingBox.close();
                        generalModel.open(sysText.sys_p_ad_t, sysText.sys_p_ad_d);
                        return;
                    } else if ( data.status_code === 2626) {
                        console.log(data.errors.title);
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
        addressUpdates : function(key,data){
            this.p.address[key].nick_name = data.nick_name;
            this.p.address[key].address_line_2 = data.address_line_2;
            this.p.address[key].address_line_3 = data.address_line_3;
            this.p.address[key].address_line_4 = data.address_line_4;
            this.p.address[key].address_line_5 = data.address_line_5;
            $.jshook('member_address').removeClass('member_address');
        },
        addressDel : function (k){
            this.p.address.splice(k,1);
        }
    }
});

$.jshook('peopleSelectBox').change(function () {
    app.changeMember($(this).val());
});