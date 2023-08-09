<?php

print_r ($_POST);
require './mysql.php';

if (isset($_POST["status"]) && isset($_POST["id"])) {
    update_comment($_POST["id"], false, $_POST["status"]);
}

if (isset($_POST["text"]) && isset($_POST["id"])) {
    update_comment($_POST["id"], true, $_POST["text"]);
}