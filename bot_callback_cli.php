<?php 
swoole_timer_after(30000, function () {
	$options = getopt("k:c:m:");
    delet_message($options['k'],$options['c'],$options['m']);
});
function delet_message($token,$chat_id,$message_id) {
	$delete_callback = @file_get_contents("https://api.telegram.org/bot".$token."/deleteMessage?chat_id=".$chat_id."&message_id=".$message_id);
}
?> 