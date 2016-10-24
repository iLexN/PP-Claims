//js hook
$.extend({
    jshook: function (hookName) {
        var selector;
        if (!hookName || hookName === '*') {
            // select all data-hooks
            selector = '[data-jshook]';
        } else {
            // select specific data-hook
            selector = '[data-jshook~="' + hookName + '"]';
        }
        return $(selector);
    }
});

// module
function createpasswordModule() {
    'use strict';
    var password1 = '', password2 = '';
    function isLenghtPass(passLenght) {
        return password1.length >= passLenght;
    }
    function setP1(val) {
        password1 = val;
    }
    function setP2(val) {
        password2 = val;
    }
    function hasUpperCase() {
        return password1.match(/[A-Z]/);
    }
    function hasLowerCase() {
        return password1.match(/[a-z]/);
    }
    function hasNumber() {
        return password1.match(/\d/);
    }
    function isPassSame() {
        return (password1 === password2);
    }
    return {
        setP1: setP1,
        setP2: setP2,
        isLenghtPass: isLenghtPass,
        hasUpperCase: hasUpperCase,
        hasLowerCase: hasLowerCase,
        hasNumber: hasNumber,
        isPassSame: isPassSame
    };
}

var BtnStatus = (function () {
    function disableBtn() {
        $(this).prop("disabled", true);
    }
    function enableBtn() {
        $(this).prop("disabled", false);
    }
    return {
        'disable': disableBtn,
        'enable': enableBtn
    };
})();

isOnLine = (function () {
    var $msgDiv = $.jshook('offlineMsg');
    function check(){
        if (navigator.onLine) {
            console.log('online');
            $msgDiv.addClass('hide');
        } else {
            console.log('offline');
            $msgDiv.removeClass('hide');
        }
    }
    return {
        'check': check
    };
})();
isOnLine.check();
window.addEventListener('online', isOnLine.check);
window.addEventListener('offline', isOnLine.check);

var csrf = (function () {
    var nameKey = '';
    var valueKey = '';
    
    function setNameKey(val) {
        nameKey = val;
    }
    function setValueKey(val) {
        valueKey = val;
    }
    function getNameKey() {
        return nameKey;
    }
    function getValueKey() {
        return valueKey;
    }
    function getFormObj(obj){
        obj.push({name:'csrf_name',value:getNameKey()});
        obj.push({name:'csrf_value',value:getValueKey()});
        return obj;
    }
    function setObj(str){
        var obj = jQuery.parseJSON(str);
        setNameKey(obj.csrf_name);
        setValueKey(obj.csrf_value);
    }
    return {
        'setNameKey': setNameKey,
        'setValueKey': setValueKey,
        'getFormObj' : getFormObj,
        'setObj' : setObj
    };
})();

$( document ).ajaxComplete(function( event, jqXHR, settings ) {
  csrf.setObj(jqXHR.getResponseHeader('X-CSRF-Token'));
});
$( document ).ajaxStart(function() {
  loadingBox.open();
});

var loadingBox = (function(){
    function open(){
        $('#loadingBox').openModal({
            dismissible: false,
            starting_top: '30%',
            ending_top: '30%',
            in_duration : 500,
            out_duration : 100
        });
    }
    function close(){
        $('#loadingBox').closeModal();
    }
    return {
        'open': open,
        'close': close
    };
})();

var loginForm = (function () {
    var $form =  $.jshook('loginForm');
    var $btn =  $.jshook('loginBtn');
    var $loginMsg =  $.jshook('loginMsg');
    
    function submit(){
        var data = (csrf.getFormObj($form.serializeArray()));
        $.ajax({
            beforeSend: function () {
                $btn.prop("disabled", true);
                $loginMsg.html('').addClass('hide');
            },
            url: '/ajax/system/login',
            type: 'POST',
            data: data,
            dataType: "json"
        }).done(function (data, textStatus, jqXHR) {
            if ( data.status_code === 2081 ) {
                //success
                window.location.replace("/login-ed");
                return;
            }
            //fail
            $btn.prop("disabled", false);
            loadingBox.close();
            $loginMsg.html(data.errors.title).removeClass('hide');
        }).fail(function (jqXHR, textStatus, errorThrown) {
            //$btn.prop("disabled", false);
        });
    }
    return {
        'submit': submit
    };
})();

//helper
function debounce(func, wait, immediate) {
    var timeout;
    return function () {
        var context = this, args = arguments;
        var later = function () {
            timeout = null;
            if (!immediate)
                func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow)
            func.apply(context, args);
    };
}
