var pair1='', pair2='';
$(document).ready(function(){
    $('#div_saving').css('display', 'none');
    $('#div_alert').css('display', 'none');
    $("div.pair1 .zInput").click(function(){
        pair1 = $(this).attr('coin');
        if ( pair1 == pair2 ) {
            pair1 = '';
            $('#img_pair1').removeAttr('src');
            $(this).removeClass('zSelected');
        }
        else {
            $('#img_pair1').attr('src', $(this).attr('icon'));
            $('input[name="want_asset"]').val(pair1);
        }


    })

    $("div.pair2 .zInput").click(function(){
        pair2 = $(this).attr('coin');
        if ( pair1 == pair2 ) {
            pair2='';
            $('#img_pair2').removeAttr('src');
            $(this).removeClass('zSelected');
        }
        else{
            $('#img_pair2').attr('src', $(this).attr('icon'));
            $('input[name="offer_asset"]').val(pair2);
        }
    });
    $('#btn_save').click(function(){
        if ( pair1=='' ) {
            $('#div_alert').css('display', '');
            window.setTimeout(function(){
                $('#div_alert').fadeOut(2000);
            }, 3000);
            return;
        }
        if ( pair2=='' ) {
            $('#div_alert').css('display', '');
            window.setTimeout(function(){
                $('#div_alert').fadeOut(2000);
            }, 3000);
            return;
        }
        $('#div_form').submit();
    });
});