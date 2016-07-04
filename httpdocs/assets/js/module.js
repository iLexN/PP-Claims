var passwordModule = (function () {
    'use strict';
    var password1 = '';
    var password2 = '';
    function isLenghtPass(passLenght) {
        return this.password1.length < passLenght;
    }
    function hasUpperCase() {
        return this.password1.match(/[A-Z]/);
    }
    function hasLowerCase() {
        return this.password1.match(/[a-z]/);
    }
    function hasNumber() {
        return this.password1.match(/\d/);
    }
    function isPassSame() {
        return (this.password1 === this.password2);
    }
    return {
        isLenghtPass: isLenghtPass,
        hasUpperCase: hasUpperCase,
        hasLowerCase: hasLowerCase,
        hasNumber: hasNumber,
        isPassSame: isPassSame
    };
}());