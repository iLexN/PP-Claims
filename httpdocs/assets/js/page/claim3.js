

var app = new Vue({
    el: '#app',
    delimiters: ['${', '}'],
    data: {
        'claim': claim,
        'size_limit' : 2 * 1024 *1024
    },
    created: function () {

    },
    components: {

    },
    watch: {
        
    },
    computed: {
        haveClaimForm : function(){
            return !_.isEmpty(this.claim.file_attachments.claim_form);
        },
        haveSupportDoc : function(){
            return !_.isEmpty(this.claim.file_attachments.support_doc);
        },
        goNext : function(){
            return this.haveClaimForm && this.haveSupportDoc ;
        }
    },
    methods: {
        uploadClaimFile: function () {
            console.log('upload claim file');
            if (window.FormData === undefined) {
                console.log('not support');
                return;
            }
            var f = $("#claimFileInput")[0].files[0];
            
            if ( _.isUndefined(f) ) {
                return ;
            }

            if (f.size > this.size_limit) {
                $('#claimform_size').addClass('errors_msg2');
                return;
            } else {
                $('#claimform_size').removeClass('errors_msg2');
            }
            
            var formData = new FormData();
            formData.append('uploadFile', f);
            var url = '/ajax/claim/' + this.claim.claim_id + '/claim_form';
            var self = this;

            this.ajaxUpload(url , formData , function(data){
                if (data.status_code === 1840) {
                    self.claim.file_attachments.claim_form.push(data.data);
                    $("#claimFileInput").parent().next().find('input').val('')
                    $("#claimFileInput").val('');
                    $("#claimFormSection").removeClass('errors_msg2');
                }
            });
        },
        uploadSupDoc: function () {
            console.log('upload claim file');
            if (window.FormData === undefined) {
                console.log('not support');
                return;
            }
            var f = $("#supDocInput")[0].files[0];
            if ( _.isUndefined(f) ) {
                return ;
            }
            if (f.size > this.size_limit) {
                $('#supdoc_size').addClass('errors_msg2');
                return;
            } else {
                $('#supdoc_size').removeClass('errors_msg2');
            }
            var formData = new FormData();
            formData.append('uploadFile', f);
            var url = '/ajax/claim/' + this.claim.claim_id + '/support_doc';
            var self = this;

            this.ajaxUpload(url , formData , function(data){
                if (data.status_code === 1840) {
                    self.claim.file_attachments.support_doc.push(data.data);
                    $("#supDocInput").parent().next().find('input').val('')
                    $("#supDocInput").val('');
                    $("#subDocSection").removeClass('errors_msg2');
                }
            });
        },
        ajaxUpload : function(url , formData , callback){
            formData.append('csrf_name', csrf.getNameKey());
            formData.append('csrf_value', csrf.getValueKey());
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: "json"
            }).done(function (data, textStatus, jqXHR) {
                callback(data);
                loadingBox.close();
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.info('try again');
                console.log(errorThrown);
                console.log(textStatus);
            });
        },
        deleteClaimForm : function (fileObj) {
            var self = this;
            this.ajaxDeletFile(fileObj,function(data){
                if (data.status_code === 1850) {
                    var index = _.findIndex(self.claim.file_attachments.claim_form, fileObj);
                    self.claim.file_attachments.claim_form.splice(index,1);
                }
            });
        },
        deleteSupDoc : function (fileObj) {
            var self = this;
            this.ajaxDeletFile(fileObj,function(data){
                if (data.status_code === 1850) {
                    var index = _.findIndex(self.claim.file_attachments.support_doc, fileObj);
                    self.claim.file_attachments.support_doc.splice(index,1);
                }
            });
        },
        ajaxDeletFile : function(fileObj , callback){
            var url = '/ajax/claim/' + this.claim.claim_id + '/file/' + fileObj.id;
            var data = [];
            _.forEach(fileObj, function (value, key) {
                data.push({name: key, value: value});
            });
            data = csrf.getFormObj(data);
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                dataType: "json"
            }).done(function (data, textStatus, jqXHR) {
                callback(data);
                loadingBox.close();
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.info('try again');
                console.log(errorThrown);
                console.log(textStatus);
            });
        },
        saveBtn1: function () {
            saveClaimBox.open();
        },
        nextBtn1: function () {
            $("#claimFormSection").removeClass('errors_msg2');
            $("#subDocSection").removeClass('errors_msg2');
            
            if ( this.goNext) {
                loadingBox.open();
                window.location.href = '/claim/'+this.claim.claim_id+'/summary';
            } else {
                if ( !this.haveSupportDoc ) {
                    this.scrollTop('subDocSection');
                    $("#subDocSection").addClass('errors_msg2');
                }
                if ( !this.haveClaimForm ) {
                    this.scrollTop('claimFormSection');
                    $("#claimFormSection").addClass('errors_msg2');
                } 
                
            }
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

