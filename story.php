<?php
	
	// Story was ultimately never used
	
function load_story_element($current_room_level) {

if ($current_room_level <= 10) {
$rand = rand(0,13);
/* Love, birth, death */
if ($rand == 0) { $string = 'Does it hurt, sweetheart? No, it doesn\'t hurt daddy. You\'re being such a strong little girl.'; }
if ($rand == 1) { $string = 'We can\'t believe how young she is. It\'s just not right. I know... I know.'; }
if ($rand == 2) { $string = 'He\'s a single father, too. The mother died in childbirth, and now this.'; }
if ($rand == 3) { $string = 'I think I can help you with what ails your daughter. I believe I have the cure.'; }
if ($rand == 4) { $string = 'What do I have to do? You just have to visit this tower I own. Give it a little clean.'; }
if ($rand == 5) { $string = 'Really, that\'s all you want to save my daughters life? Oh, it\'s not a small job.'; }
if ($rand == 6) { $string = 'In order to save my daughter, any job is a small job, sir.'; }
if ($rand == 7) { $string = 'Daddy, where are you going? I have to go do something that\'ll make you all better.'; }
if ($rand == 8) { $string = 'OK, daddy, be careful. I will sweetheart. You be a good girl until I get back? I\'ll be strong, daddy.'; }
if ($rand == 9) { $string = 'My daddy is going to save my life! My daddy is the best daddy in the whole world!'; }
if ($rand == 10) { $string = 'I\'m afraid there\'s nothing we can do anymore. The cancer just isn\'t responding to the chemo.'; }
if ($rand == 11) { $string = 'Daddy, am I going to die? No sweetheart, you\'re not going to die. I won\'t let you.'; }
if ($rand == 12) { $string = 'Clean what exactly? Don\'t worry about that. I know you\'ll do what you have to.'; }
if ($rand == 13) { $string = '100 floors top to bottom. Can\'t be that hard, can it?'; }
}
if ($current_room_level <= 20 && $current_room_level >= 11) {
}
if ($current_room_level <= 30 && $current_room_level >= 21) {
}
if ($current_room_level <= 40 && $current_room_level >= 31) {
}
if ($current_room_level <= 50 && $current_room_level >= 41) {
}
if ($current_room_level <= 60 && $current_room_level >= 51) {
}
if ($current_room_level <= 70 && $current_room_level >= 61) {
}
if ($current_room_level <= 80 && $current_room_level >= 71) {
}
if ($current_room_level <= 90 && $current_room_level >= 81) {
}
if ($current_room_level <= 100 && $current_room_level >= 90) {
}
if ($current_room_level > 100) {
}


echo $string;
}

?>