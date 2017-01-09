var policyuser = {
    props: {
        p: Object
    },    
    computed: {
        fullname : function(){
            var out = '';
            if ( this.p.first_name !== null) {
                out += this.p.first_name + ' ';
            }
            if ( this.p.middle_name !== null) {
                out += this.p.middle_name + ' ';
            }
            if ( this.p.last_name !== null) {
                out += this.p.last_name + ' ';
            }
            
            return out;
        }
    }
};

var advisor = {
    props: {
        p: Object
    }
};

var app = new Vue({
    el: '#app',
    delimiters: ['${', '}'],
    data: {
        'plist': data,
        'p': data[0]
    },
    components: {
        'policy-user': policyuser,
        'advisor' : advisor
    },
    computed : {
      newClaimUrl : function(){
          return 'user-policy/' + this.p.pivot.id + '/new-claim';
      },
      savedClaimUrl : function(){
          return 'user-policy/' + this.p.pivot.id + '/save-claim';
      },
      submitedClaimUrl : function(){
          return 'user-policy/' + this.p.pivot.id + '/submited-claim';
      }
    },
    methods: {
        changePlan: function (k) {
            this.p = this.plist[k];
        }
    }
});

$.jshook('planSelectBox').change(function () {
    app.changePlan($(this).val());
});