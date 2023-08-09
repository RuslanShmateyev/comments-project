<?php

print_r ($_POST);
require './mysql.php';

if (isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["comment"])) {
    if (isset($_POST["path_to_file"])) {
		add_comment($_POST["username"], $_POST["email"], $_POST["comment"], $_POST["path_to_file"]);
        exit;
    }
    add_comment($_POST["username"], $_POST["email"], $_POST["comment"]);
}
