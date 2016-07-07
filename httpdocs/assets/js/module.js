var passwordModule = (function () {
    'use strict';
    var password1, password2;
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
})();

//helper
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
