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

function isOnLine(e) {
    if (navigator.onLine) {
        console.log('online');
    } else {
        e.preventDefault();
        console.log('offline');
    }
}
window.addEventListener('online', isOnLine);
window.addEventListener('offline', isOnLine);

var animMove = (function () {
    function enter() {
        $(this).find('.cat-div-2').stop().slideDown(1000);
    }
    function leave() {
        $(this).find('.cat-div-2').stop().slideUp(1000);
    }
    return {
        'enter': enter,
        'leave': leave
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
