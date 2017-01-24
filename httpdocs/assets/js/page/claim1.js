var dateModule = (function () {
    function isDateValid(strDate) {
        try {
            var res = moment(strDate, "YYYY-MM-DD").format("YYYY-MM-DD");
            return res == strDate;
        } catch (e) {
            console.log("moment is not loaded yet");
        }
    }

    function isDateBefore() {
        try {
            if (arguments.length < 1 || arguments.length > 2) {
                console.log("moment is not loaded yet");
                return;
            }
            var inputDate = arguments[0];
            var baseDate = (arguments.length == 2) ? arguments[1] : null;
            if (baseDate == null) {
                baseDate = moment().format("YYYY-MM-DD");
            }
            if (isDateValid(inputDate) && isDateValid(baseDate)) {
                return moment(inputDate, "YYYY-MM-DD").isBefore(moment(baseDate, "YYYY-MM-DD"));
            }
        } catch (e) {
            console.log("moment is not loaded yet");
        }
    }

    function isSameOrBefore() {
        try {
            if (arguments.length < 1 || arguments.length > 2) {
                console.log("moment is not loaded yet");
                return;
            }
            var inputDate = arguments[0];
            var baseDate = (arguments.length == 2) ? arguments[1] : null;
            if (baseDate == null) {
                baseDate = moment().format("YYYY-MM-DD");
            }
            if (isDateValid(inputDate) && isDateValid(baseDate)) {
                return moment(inputDate, "YYYY-MM-DD").isSameOrBefore(moment(baseDate, "YYYY-MM-DD"));
            }
        } catch (e) {
            console.log("moment is not loaded yet");
        }
    }

    // date(string) , date(string) optional default:today
    function isDateAfter() {
        try {
            if (arguments.length < 1 || arguments.length > 2) {
                console.log("moment is not loaded yet");
                return;
            }
            var inputDate = arguments[0];
            var baseDate = (arguments.length == 2) ? arguments[1] : null;
            if (baseDate == null) {
                baseDate = moment().format("YYYY-MM-DD");
            }
            if (isDateValid(inputDate) && isDateValid(baseDate)) {
                return moment(inputDate, "YYYY-MM-DD").isAfter(moment(baseDate, "YYYY-MM-DD"));
            }
        } catch (e) {
            console.log("moment is not loaded yet");
        }
    }

    return {
        'isDateAfter': isDateAfter,
        'isDateBefore': isDateBefore,
        'isSameOrBefore': isSameOrBefore
    };
})();


var app = new Vue({
    el: '#app',
    delimiters: ['${', '}'],
    data: {
        'claim': claim,
        'holderID': holderID,
        'whom': '',
        'whom_dependent': '',
        'dependentbox': false,
        'oldPM' : claim.payment_method
    },
    created: function () {
        this.isDependent(this.claim.claimiant_ppmid);
    },
    components: {

    },
    watch: {
        whom: function (val, oldVal) {
            this.dependentBoxStatus(val)
        },
        date_of_treatment: function (val, oldVal) {
            this.claim.date_of_treatment = val;
        },
    },
    computed: {
        'date_of_treatment': function () {
            return this.claim.treatment_yyyy + '-' + this.claim.treatment_mm + '-' + this.claim.treatment_dd;
        }
    },
    methods: {
        isDependent: function (val) {
            if (val == this.holderID) {
                this.whom = this.holderID;
            } else {
                this.whom = 'dependent';
                this.whom_dependent = val;
            }
        },
        getDependent: function () {
            if (this.whom == this.holderID) {
                this.claim.claimiant_ppmid = this.holderID;
            } else {
                this.claim.claimiant_ppmid = this.whom_dependent;
            }
        },
        dependentBoxStatus: function (val) {
            if (val == this.holderID) {
                this.dependentbox = false;
            } else {
                this.dependentbox = true;
            }
            if (this.whom_dependent === '') {
                var id = $.jshook('dependentbox').find('input').eq(0).val();
                this.whom_dependent = id;
            }
        },
        checkStep1: function () {
            var error_el = [];
            if (!dateModule.isSameOrBefore(this.claim.date_of_treatment)) {
                $.jshook('treatment_error').show();
                error_el.push('claim_step1_when_q');
            } else {
                $.jshook('treatment_error').hide();
            }
            if (this.claim.diagnosis === '') {
                $.jshook('diagnosis').addClass('invalid');
                $.jshook('diagnosis_error').show();
                error_el.push('claim_step1_diagnosis_q');
            } else {
                $.jshook('diagnosis').removeClass('invalid');
                $.jshook('diagnosis_error').hide();
            }
            if (this.claim.amount === '') {
                error_el.push('claim_step1_invoice_q');
                $.jshook('amount').addClass('invalid');
            }
            if (error_el.length === 0) {
                return true;
            } else {
                this.scrollTop(error_el[0]);
                return false;
            }
        },
        saveBtn1: function () {
                self = this;
                this.goAjax(function(data){
                    self.claim.claim_id = data.data.id;
                    loadingBox.close();
                    saveClaimBox.open();
                });
            
        },
        nextBtn1: function () {
            var self = this;
            if (this.checkStep1()) {
                this.goAjax(function(data){
                    if ( self.checkComplete() ) {
                        window.location.href = '/claim/'+data.data.id+'/summary';
                    } else {
                        window.location.replace('/claim/'+data.data.id+'/reimburse');
                    }
                });
            }
        },
        getFormData: function () {
            var data = [];
            this.claim.status = 'Save';
            this.getDependent();
            _.forEach(this.claim, function (value, key) {
                data.push({name: key, value: value})
            });
            return data;
        },
        goAjax: function (callback) {
            var data = (csrf.getFormObj(this.getFormData()));
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
                scrollTop: ($.jshook(hook).offset().top - 150) + "px"
            }, {
                duration: 500,
                easing: "linear"
            });
        },
        checkComplete : function (){
            if ( this.oldPM === this.claim.payment_method && this.claim.isComplete ) {
                return true;
            } else {
                return false;
            }
        }
    }
});

