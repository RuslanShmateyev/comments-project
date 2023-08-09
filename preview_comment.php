<?php
require_once 'visualize_comment.php';
$data = array(array(
     'username' => $_GET['username'], 
     'email' => $_GET['email'], 
     'text' => $_GET['text'], 
     'path_to_file' => $_GET['path_to_file'],
     'date' => date('Y-m-d'), 
     'status' => 'accepted'
));

$gen_comments = gen_comments($data);
echo $gen_comments[0];