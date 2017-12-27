var selectedCoin = "Bitcoin";
var selected_coin = 'ctr';
var coin_info = {ctr:"Centra", btc:"Bitcoin", eth:"Ethereum", xrp:"Ripple", ltc:"Litecoin", dash:"Dash", xmr:"Monero", zec:"Zcash", usdt:"USDT"};
var flat_fee_info = {ctr:0.003, btc:0.0004, eth:0.00215, xrp:0.509, ltc:0.003, dash:0.03, xmr:0.003, zec:0.0001, usdt:5};
var selected_erc20_coin='PLU';
var address;
$(document).ready(function(){

    $('#selectedCoin_span').html(coin_info[selected_coin]);
    $('#coin_unit').html(selected_coin.toUpperCase());
    $('#flat_fee').html(flat_fee_info[selected_coin]);
    // Define Event
    $('.centra-a-link').click(doOnSelectCoin);
    $('.erc20').click(doOnSelectERC20Token);
    $('#withdraw_amount').keyup(doOnKeyUpWithdrawAmount);
    $('#btn_create_new_address').click(doOnCreateNewAddress);
    $('#btn_erc20_create_new_address').click(doOnCreateERC20NewAddress);
    setReceiveAssetValue();
    reSizeERC20AssetHeight();
    $(window).resize(function(event){
        reSizeERC20AssetHeight();
    });
    hideSpin(true);
});

function doOnSelectCoin() {
    selected_coin = $(this).attr('coin');
    $('#selectedCoin_span').html(coin_info[selected_coin]);
    $('#coin_unit').html(selected_coin.toUpperCase());
    $('#flat_fee').html(flat_fee_info[selected_coin]);
    setReceiveAssetValue();
    getUserWalletInfo();
}
function getUserWalletInfo() {
    hideSpin(false);
    $.post('/userwalletinfo/'+selected_coin.toUpperCase(), {type:'CENT'}, function(resp){
        resp_data = JSON.parse(resp);
        if ( resp_data.address != 'FAIL' ) {
            $('.centra-img-qrcode').attr('src', resp_data.qr_link);
            $('#deposit_wallet_address').val(resp_data.address);
        }
        else {
            $('.centra-img-qrcode').attr('src', resp_data.qr_link);
            $('#deposit_wallet_address').val('You didn\'t created wallet address yet.');
        }
        hideSpin(true);
    });
}
function doOnSelectERC20Token() {
    address = $(this).attr('address');
    selected_erc20_coin = $(this).attr('coin');
    $('#selectedERCCoin_span').html(selected_coin.toUpperCase());
    $('#deposit_erc20_wallet_address').val('');
    $('.centra-erc20-img-qrcode').removeAttr('src');
}
function doOnKeyUpWithdrawAmount(event) {
    setReceiveAssetValue();
}
function setReceiveAssetValue() {
    var val = parseFloat($('#withdraw_amount').val());
    var flat_fee = parseFloat(flat_fee_info[selected_coin]);
    var real_amount = val-flat_fee;
    $('#real_receive_amount').html(real_amount);
}
function doOnCreateNewAddress() {
    hideSpin(false);
    $.post('/generate/'+selected_coin.toUpperCase(), {type:'CENT'}, function(resp){
        resp_data = JSON.parse(resp);
        if ( resp_data.address != 'FAIL' ) {
            hideSpin(true);
            $('.centra-img-qrcode').attr('src', resp_data.qr_link);
            $('#deposit_wallet_address').val(resp_data.address);
        }
    });

}
function doOnCreateERC20NewAddress() {

    $('#deposit_erc20_wallet_address').val(address);
    $.post('genqrcode', {wallet_address:address, coin:selected_erc20_coin, type:'ETH'}, function(resp){
        $('.centra-erc20-img-qrcode').attr('src', resp);
    });
}
function reSizeERC20AssetHeight() {
    var total_Height = parseFloat($(window).height());
    $('.centra-scroll').css('height', (total_Height-120)+'px');
}
function hideSpin( hiddenState ) {
    if( hiddenState ) {
        $('#deposit_area').css("display", '');
        $('#deposit_spin').css("display", 'none');
    }
    else {
        $('#deposit_area').css("display", 'none');
        $('#deposit_spin').css("display", '');
    }
}