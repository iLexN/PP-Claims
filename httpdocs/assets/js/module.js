var passwordModule = (function () {
    'use strict';
    var password1,password2;
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


var passwordControl = (function () {
    'use strict';
    var $jsPassInfo,$btn,$length,$letter,$num,$passSame;
    var validCssClass,inValidCssClass;

    function setting(obj) {
        $jsPassInfo = obj.jsPassInfo;
        $btn = obj.btn;
        $length = obj.length;
        $letter = obj.letter;
        $num = obj.num;
        $passSame = obj.passSame;
        validCssClass = obj.validClass;
        inValidCssClass = obj.invalidClass;
    }
    function setP1(val) {
        passwordModule.setP1(val);
    }
    function setP2(val) {
        passwordModule.setP2(val);
    }
    function valid($obj) {
        $obj.removeClass(inValidCssClass).addClass(validCssClass);
    }
    function inValid($obj) {
        $obj.removeClass(validCssClass).addClass(inValidCssClass);
    }
    function check() {
        if (passwordModule.isLenghtPass(8)) {
            valid($length);
        } else {
            inValid($length);
        }
        if (passwordModule.hasUpperCase() && passwordModule.hasLowerCase()) {
            valid($letter);
        } else {
            inValid($letter);
        }
        if (passwordModule.hasNumber()) {
            valid($num);
        } else {
            inValid($num);
        }
        if (passwordModule.isPassSame()) {
            valid($passSame);
        } else {
            inValid($passSame);
        }
        if ($jsPassInfo.find('li').hasClass('invalid') === false) {
            $btn.prop("disabled", false);
        } else {
            $btn.prop("disabled", true);
        }
    }
    function infoShow() {
        $jsPassInfo.show();
    }
    function infoHide() {
        $jsPassInfo.hide();
    }
    return {
        setting: setting,
        setP1: setP1,
        setP2: setP2,
        check: check,
        infoShow: infoShow,
        infoHide: infoHide
    };
})();