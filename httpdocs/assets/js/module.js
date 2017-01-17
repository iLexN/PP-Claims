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
    var $formBtn = $.jshook('formBtn');
    function check() {
        if (navigator.onLine) {
            return true;
        } else {
            return false;
        }
    }
    function ui() {
        if (check()) {
            $msgDiv.addClass('hide');
            $formBtn.removeClass('disabled');
        } else {
            $msgDiv.removeClass('hide');
            $formBtn.addClass('disabled');
        }
    }
    function init() {
        ui();
        window.addEventListener('online', ui);
        window.addEventListener('offline', ui);
    }
    init();
    return {
        'check': check,
        'ui': ui
    };
})();

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
    function getFormObj(obj) {
        obj.push({name: 'csrf_name', value: getNameKey()});
        obj.push({name: 'csrf_value', value: getValueKey()});
        return obj;
    }
    function setObj(str) {
        var obj = jQuery.parseJSON(str);
        if (obj === null) {
            return;
        }
        setNameKey(obj.csrf_name);
        setValueKey(obj.csrf_value);
    }
    return {
        'setNameKey': setNameKey,
        'setValueKey': setValueKey,
        'getFormObj': getFormObj,
        'setObj': setObj
    };
})();
//
$(document).ajaxComplete(function (event, jqXHR, settings) {
    csrf.setObj(jqXHR.getResponseHeader('X-CSRF-Token'));
});
$(document).ajaxStart(function () {
    loadingBox.open();
});

var loadingBox = (function () {
    var $m = $('#loadingBox');
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
    }
    init();
    return {
        'open': open,
        'close': close
    };
})();
//saveClaimBox
var saveClaimBox = (function () {
    var $m = $('#saveClaim');
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
    }
    init();
    return {
        'open': open,
        'close': close
    };
})();

function activeNav(hookName) {
    $.jshook(hookName).addClass('nav_active');
}

//helper
var debounce = function (func, wait) {
    // we need to save these in the closure
    var timeout, args, context, timestamp;
    return function () {
        // save details of latest call
        context = this;
        args = [].slice.call(arguments, 0);
        timestamp = new Date();
        // this is where the magic happens
        var later = function () {
            // how long ago was the last call
            var last = (new Date()) - timestamp;
            // if the latest call was less that the wait period ago
            // then we reset the timeout to wait for the difference
            if (last < wait) {
                timeout = setTimeout(later, wait - last);
                // or if not we can null out the timer and run the latest
            } else {
                timeout = null;
                func.apply(context, args);
            }
        };
        // we only need to set the timer now if one isn't already running
        if (!timeout) {
            timeout = setTimeout(later, wait);
        }
    }
};

//right_side
pinTop = (function () {
    function setWidth(){
        $.jshook('pushpin').width($.jshook('pushpin').parent().width());
    }
    function init() {
        setWidth();
        $.jshook('pushpin').pushpin({
                top: 190
        });
        $(window).resize(debounce(function(){
            setWidth();
        },500));
    }
    return {
        'init' : init
    };
})();

var BreadCrumb = (function () {
    function addActiveClass(hook) {
        $.jshook(hook).addClass('active');
    }
    function setUrl(hook, url) {
        $.jshook(hook).attr('href', url);
    }
    return {
        'addActiveClass': addActiveClass,
        'setUrl': setUrl
    };
})();

$(function () {
    Vue.config.devtools = true;
    WebFont.load({
        google: {
            families: ['Open Sans:300,300i,400,400i,600,600i,700,700i,800,800i', 'Material Icons']
        }
    });
});
