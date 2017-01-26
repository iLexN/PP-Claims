var policyuser = {
    props: {
        p: Object
    },
    computed: {

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
        'tmpData': []
    },
    components: {
        //'policy-user': policyuser,
    },
    created: function () {
        this.contact_mode = false;
        this.holder_mode = false;
        this.getDetails();
    },
    computed: {

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
        'p': function (val, oldVal) {
            this.getDetails();
        }
    },
    methods: {
        getDetails: function () {
            if (_.isUndefined(this.p.address) || _.isUndefined(this.p.renew)) {
                var self = this;
                $.getJSON("ajax/member/" + this.p.ppmid, function (data) {
                    self.p.address = data.address;
                    self.p.renew = data.renew;
                    if (!_.isEmpty(data.renew)) {
                        console.log('update renew');
                        self.p.phone_1 = data.renew.phone_1;
                        self.p.phone_2 = data.renew.phone_2;
                        self.p.email = data.renew.email;
                    }
                    loadingBox.close();
                });
            }
        },
        editContact: function () {
            this.contact_mode = true;
            this.tmpData = {
                'phone_1' : this.p.phone_1,
                'phone_2' : this.p.phone_2,
                'email' : this.p.email
            };
        },
        cancelContact: function () {
            this.contact_mode = false;
            this.p.phone_1 = this.tmpData.phone_1;
            this.p.phone_2 = this.tmpData.phone_2;
            this.p.email = this.tmpData.email;
        },
        saveContact : function(){
            var formData = {
                'phone_1' : this.p.phone_1,
                'phone_2' : this.p.phone_2,
                'email' : this.p.email
            };
            var data = csrf.getFormObj(this.formPostData(formData));
            var self = this;
            $.ajax({
                url: '/ajax/member/'+ this.p.ppmid,
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
                'policy_address_line_2' : this.holder.policy_address_line_2 ,
                'policy_address_line_3' : this.holder.policy_address_line_3 ,
                'policy_address_line_4' : this.holder.policy_address_line_4 ,
                'policy_address_line_5' : this.holder.policy_address_line_5 ,
            };
        },
        cancelHolder : function(){
            this.holder_mode = false;
            this.holder.policy_address_line_2 = this.tmpData.policy_address_line_2;
            this.holder.policy_address_line_3 = this.tmpData.policy_address_line_3;
            this.holder.policy_address_line_4 = this.tmpData.policy_address_line_4;
            this.holder.policy_address_line_5 = this.tmpData.policy_address_line_5;
        },
        saveHolder : function(){
            var formData = {
                'policy_address_line_2' : this.holder.policy_address_line_2,
                'policy_address_line_3' : this.holder.policy_address_line_3,
                'policy_address_line_4' : this.holder.policy_address_line_4,
                'policy_address_line_5' : this.holder.policy_address_line_5
            };
            var data = csrf.getFormObj(this.formPostData(formData));
            var self = this;
            $.ajax({
                url: '/ajax/holder/'+ this.holder.id,
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
        formPostData : function(input){
            var data = [];
            _.forEach(input, function (value, key) {
                data.push({name: key, value: value})
            });
            return data;
        },
        changeMember: function (k) {
            this.key = k;
            this.p = this.peopleList[k];
        }
    }
});

$.jshook('peopleSelectBox').change(function () {
    app.changeMember($(this).val());
});