function changeDateFormat(date) {
    return dateString = new Date(date.getTime() - (date.getTimezoneOffset() * 60000 ))
                    .toISOString().split("T")[0];
}

function validationEmails (email1, email2, email3, email4) {
    aaa = [email1, email2, email3, email4];
    var duplicate = [];
    var emails = [];

    if (email1 != "" && email1 != undefined) {
        if (emails.indexOf(email1) == -1)
            emails.push(email1);
    }

    if (email2 != "" && email2 != undefined) {
        if (emails.indexOf(email2) == -1)
            emails.push(email2);
        else {
            duplicate = [emails.indexOf(email2)+1, 2];
            return duplicate;
        }
    }

    if (email3 != "" && email3 != undefined) {
        if (emails.indexOf(email3) == -1)
            emails.push(email3);
        else {
            duplicate = [emails.indexOf(email3)+1, 3];
            return duplicate;
        }
    }

    if (email4 != "" && email4 != undefined) {
        if (emails.indexOf(email4) == -1)
            emails.push(email4);
        else {
            duplicate = [emails.indexOf(email4)+1, 4];
            return duplicate;
        }
    }

    return 'success';
}

function validateDate(date) {
    var regex=new RegExp("([0-9]{4}[-](0[1-9]|1[0-2])[-]([0-2]{1}[0-9]{1}|3[0-1]{1})|([0-2]{1}[0-9]{1}|3[0-1]{1})[-](0[1-9]|1[0-2])[-][0-9]{4})");
    var dateOk=regex.test(date);
    if(dateOk)
        return true;
    else
        return false;
}

$("input[type=search], #search-model-number").keypress(function(e) {
    var noUseList = '!@#$%^&*(){}[];<>';
    if (noUseList.search(e.key) != -1) {
        e.preventDefault();
    }
})

function convertNumberFormat(number) {
    return new Intl.NumberFormat('en-IN', { maximumSignificantDigits: 3 }).format(number)
}


