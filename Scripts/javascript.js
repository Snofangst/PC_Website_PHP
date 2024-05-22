function Comma(Num) {
    Num += '';
    Num = Num.replace('.', '');
    Num = Num.replace('.', '');
    Num = Num.replace('.', '');
    Num = Num.replace('.', '');
    Num = Num.replace('.', '');
    Num = Num.replace('.', '');
    x = Num.split(',');
    x1 = x[0];
    x2 = x.length > 1 ? ',' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1))
        x1 = x1.replace(rgx, '$1' + '.' + '$2');
    return x1 + x2;
}
function ignoreQuote(input)
{
    var n=input.value.replaceAll(/['"]/g,"");
    input.value=n;
}