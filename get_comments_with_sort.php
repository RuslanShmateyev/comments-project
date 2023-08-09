<?php

require_once 'mysql.php';
require_once 'visualize_comment.php';


$data = get_comments(false, $_GET['sort']);
$comments = gen_comments($data);
foreach ($comments as $comment) {
	echo $comment;
}