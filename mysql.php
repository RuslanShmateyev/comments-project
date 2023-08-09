<?php
$config = include('./config.php');

function connect() {
    $con = mysqli_connect($config['host'], $config['user'], $config['password'], $config['db']);

    if ($con == false){
        echo "Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error();
        exit;
    }

    mysqli_set_charset($con, "utf8");
    return $con;
}

function add_comment($user_name, $user_email, $text, $path_to_file = false) {
    $con = connect();
    $file = $path_to_file ? ', path_to_file = ?' : '';

    $query = $con->prepare('INSERT INTO comments SET username = ?, email = ?, text = ?, date = DATE(NOW()), status = ""' . $file);
	
    if ($path_to_file) {
        print_r([$user_name, $user_email, $text, $path_to_file]);
        $query->bind_param('ssss', $user_name, $user_email, $text, $path_to_file);
    } else {
        print_r([$user_name, $user_email, $text]);
        $query->bind_param('sss', $user_name, $user_email, $text);
    }

    $result = $query->execute();

    if ($result == false) {
        print("Произошла ошибка при выполнении запроса");
        return false;
    }

    $con->close();
    return true;
}

function get_comments($is_admin, $sort = 'date') {
    $con = connect();
	$status = $is_admin ? '' : ' WHERE status = "accepted"';
	$sorting = ' ORDER BY "' . $sort . '"';
	
    $sql = 'SELECT * FROM comments'.$status.$sorting;
	
    $result = $con->query($sql);
    $rows = [];

    while($row = $result->fetch_assoc()) {
        array_push($rows, $row);
    }
	
    $con->close();
    return $rows;
}

function update_comment($id, $isText, $data) {
    $con = connect();

    $query = $con->prepare('UPDATE comments SET ' . ($isText ? 'text' : 'status') . ' = ? WHERE id = ?');
    echo "sql = ";
    echo 'UPDATE comments SET ' . ($isText ? 'text' : 'status') . ' = ? WHERE id = ?';

    $query->bind_param('si', $data, $id);
    $result = $query->execute();

    if ($result == false) {
        print("Произошла ошибка при выполнении запроса");
        return false;
    }

    $con->close();
    return true;
}
