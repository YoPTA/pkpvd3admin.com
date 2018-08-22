function getRand(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function strlen(string)
{
    return string.length;
}

function createRandomPasswordEx(minlength, usenumeric, useuppercase, uselowercase, usespecialchar, specialchar)
{
    if (typeof usenumeric != 'boolean')
    {
        usenumeric = usenumeric || true;
    }
    if (typeof useuppercase != 'boolean')
    {
        useuppercase = useuppercase || true;
    }
    if (typeof uselowercase != 'boolean')
    {
        uselowercase = uselowercase || true;
    }
    if (typeof usespecialchar != 'boolean')
    {
        usespecialchar = usespecialchar || true;
    }
    specialchar = specialchar || '!@#$%^&*()_-+={[}],.?';

    var numeric = '123456789'; // Удалены: 0
    var uppercase = 'ABCDEFGHJKLMNOPQRSTUVWXYZ'; // Удалены: I
    var lowercase = 'abcdefghijkmnopqrstuvwxyz'; // Удалены: l

    if (!usenumeric && !useuppercase && !usespecialchar && !usespecialchar)
    {
        usenumeric = true;
    }

    var result = '';
    while (strlen(result) < minlength)
    {
        var type = getRand(0, 3);
        if (usenumeric && type == 0)
        {
            result = result + numeric[getRand(0, strlen(numeric) - 1)];
        }

        if (useuppercase && type == 1)
        {
            result = result + uppercase[getRand(0, strlen(uppercase) - 1)];
        }
        if (uselowercase && type == 2)
        {
            result = result + lowercase[getRand(0, strlen(lowercase) - 1)];
        }
        if (usespecialchar && type == 3)
        {
            result = result + specialchar[getRand(0, strlen(specialchar) - 1)];
        }
    }

    return result;
}	