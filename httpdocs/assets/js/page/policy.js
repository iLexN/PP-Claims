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

var filelist = {
    props: {
        f: Object,
        t : String,
        up : Number
    },
    computed : {
        downloadUrl : function (){
            return 'user-policy/' + this.up + '/' + this.t + '/'+ this.f.id;
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
        'p': data[0],
        'keyIndex' : 0
    },
    created : function(){
        var hash = window.location.hash.substring(1);
        if ( hash === '') {
            this.p = this.plist[0];
        } else {
            this.keyIndex = _.findIndex(data, function(o) { return o.pivot.id == hash ; });
            if ( this.keyIndex == -1  ) {
                this.keyIndex = 0;
            }
            this.p = this.plist[this.keyIndex];
        }  
    },
    mounted : function(){
        $('select').val(this.keyIndex);
        $('select').material_select();
    },
    components: {
        'policy-user': policyuser,
        'advisor' : advisor,
        'filelist' : filelist
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
      },
      claimFormUrl : function(){
          return 'policy#' + this.p.pivot.id;
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