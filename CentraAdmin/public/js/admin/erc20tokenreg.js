$(document).ready(function() {
    $('#btn_save').click(doOnSaveTokenInfo);
});
function doOnSaveTokenInfo() {
    var _name = $('#name').val();
    var _decimal = $('#decimal').val();
    var _addr = $('#addr').val();
    var post_param = {name: _name, decimal: _decimal, addr: _addr};
    if ( _name == '' ) {
        alert('Please input token name');
        return;
    }
    if ( _decimal == '' ) {
        alert('Please input token decimal');
        return;
    }
    if ( _addr == '' ) {
        alert('Please input token address');
        return;
    }
    $.post('/regerc20token', post_param, function(resp){
        if ( resp == 'success' ) {
            loadErc20Tokens();
        }
    });
}
function loadErc20Tokens() {
    $.get('/geterc20tokens', function(resp){
        datas = JSON.parse(resp);
        for(i=0;i<datas.length;i++) {

        }
    });
}