<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 경고메세지를 경고창으로
function alert($title,$msg,$url='')
{
 
	$CI =& get_instance();

	if (!$msg) $msg = '올바른 방법으로 이용해 주십시오.';

    echo "<!doctype html>";
    echo "<html lang='en'>";
    echo "<head>";
    echo "  <meta charset='utf-8'>";
    echo "  <meta name='viewport' content='width=device-width, initial-scale=1'>";
    echo "  <title>jQuery UI Dialog - Modal message</title>";
    echo "  <link rel='stylesheet' href='//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css'>";
    echo "  <link rel='stylesheet' href='/resources/demos/style.css'>";
    echo "  <script src='https://code.jquery.com/jquery-1.12.4.js'></script>";
    echo "  <script src='https://code.jquery.com/ui/1.12.1/jquery-ui.js'></script>";
    echo "  <script>";
    echo "  $( function() {";
    echo "    $('#msg').attr('title','".$title."');";
    echo "    $('#msg').html('".$msg."');";
    echo "    $( '#msg' ).dialog({";
    echo "      modal: true,";
    echo "     buttons: {";
    echo "        Ok: function() {";
    echo "          $( this ).dialog( 'close' );";
    if ($url)
        echo "location.href = '".$url."';";
	else
		echo "history.go(-1);";

   
    echo "       }";
    echo "     }";
    echo "   });";
    echo " } );";
    echo "  </script>";
    echo "</head>";
    echo "<body>";

    echo "<div id='msg' title=''>";

    echo "</div>";



    echo "</body>";
    echo "</html>";
    
	exit;
}

// 경고메세지 출력후 창을 닫음
function alert_close($msg)
{
	$CI =& get_instance();

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\">";
	echo "<script type='text/javascript'> alert('".$msg."'); window.close(); </script>";
	exit;
}

// 경고메세지만 출력
function alert_only($msg)
{
	$CI =& get_instance();

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\">";
	echo "<script type='text/javascript'> alert('".$msg."'); </script>";
	exit;
}

function error_msg($errorcd){
	$error_string = "";
	switch($errorcd){
		case 1:
			$error_string = "User Name is not found";
			break;
	}
	exit;

}
?>