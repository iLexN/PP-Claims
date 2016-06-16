var passwordModule = {
    isLenghtPass: function (val, passLenght) {
        return val.length < passLenght;
    },
    hasUpperCase: function (val) {
        return val.match(/[A-Z]/);
    },
    hasLowerCase: function (val) {
        return val.match(/[a-z]/);
    },
    hasNumber: function (val) {
        return val.match(/\d/);
    },
    isPassSame: function (val1, val2) {
        return (val1 === val2);
    }
};