var pair1=undefined, pair2='';
var asset_pair_arr = [];
$(document).ready(function(){
    $("div.pair1 .zInput").click(function(){
        pair1 = $(this).attr('coin');
        $(this).removeClass('zSelected');
        $theClickedButton = $(this);

        //move up the DOM to the .zRadioWrapper and then select children. Remove .zSelected from all .zRadio
        $theClickedButton.parent().children().removeClass("zSelected");
        $theClickedButton.addClass("zSelected");
        $theClickedButton.find(":radio").prop("checked", true).change();

        $('.zCheckbox1').css('display', '');
        $('#'+pair1).css('display', 'none');

        $.get('/getofferasset/'+pair1, function(resp){
            resp = JSON.parse(resp);
            var arr = [];
            $("div.pair2 .zInput").removeClass("zSelected");
            resp.map(function(ar){
                $('#'+ar.offer_asset).addClass("zSelected");
            });
        });
    })

    $("div.pair2 .zInput").click(function(){
        if ( pair1 == undefined ) {
            alert('Please choice main asset.');
            return;
        }
        pair2 = $(this).attr('coin');
        //$(this).removeClass('zSelected');
        $theClickedButton = $(this);

        //move up the DOM to the .zRadioWrapper and then select children. Remove .zSelected from all .zRadio
        //$("div.pair2 .zInput").removeClass("zSelected");
        $theClickedButton.find(':checkbox').each(function () { this.checked = !this.checked; $(this).change()});
        $theClickedButton.toggleClass("zSelected");
        pair2 = $theClickedButton.attr('coin');
        var param = {};
        if ( $theClickedButton.hasClass('zSelected') ) {
            param = {status: 'insert', want_asset: pair1, offer_asset: pair2};
        }
        else {
            param = {status: 'delete', want_asset: pair1, offer_asset: pair2};
        }
        $.post('pairregister', param, function(resp){
            console.log(resp);
        });
    });
});