<?php
    require_once 'mysql.php';
    require_once 'visualize_comment.php';

    $data = get_comments(false);
    $comments = gen_comments($data);

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тестовое задание</title>
    <link rel="stylesheet" href="main.css">

    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
</head>
<body>
    <div class="body">
    <div class="comments">
        <div class="comment_text">Комментарии</div>
		<div class="sort" style="font-size: 20px;">
			<label for="sort">Выберите сортировку:</label>
			<select id="sort" name="sort" style="width: 100px;height: 25px;color: white;/*! background: darkblue; */background-color: rgb(13, 17, 23);border-color: rgb(13, 17, 23);">
				<option value="date">По дате</option>
				<option value="name">По имени</option>
				<option value="email">По почте</option>
			</select>
		</div>
		<div id="comments">
		<?php
        foreach ($comments as $comment) {
            echo $comment;
        }
        ?>
		</div>
    </div>
        <!-- form for adding comment -->
        <div class="comment_text">Добавить комментарий</div>

        <form id="add_comment_form" class="add_comment_form" method="POST">
            <div class="header">
                <div>
                    <button type="button" id="edit" class="selected" onClick="set_edit()">Редактирование</button>
                    <button type="button" id="view" class="not_selected" onClick="set_view()">Предварительный просмотр</button>
                </div>
            </div>
            <div class="main">
                <div class="edit_block" id="edit_block">
                    <input placeholder="Введите ваше имя" type="text" name="username" id="username" class="input_text">
                    <input placeholder="Введите ваш email" type="text" name="email" id="email" class="input_text">
                    <textarea placeholder="Оставьте комментарий" name="comment" id="comment"></textarea>
                    <input type="file" name="file" id="file" accept=".jpg, .gif, .png" class="input_file" onChange="upload_file()">
                </div>
                <div class="view_block" id="view_block" hidden></div>
            </div>
            <div class="submit">
                <button type="submit" class="sub_button">Подтвердить</button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#add_comment_form').submit(function(e) {
                e.preventDefault();
                const file = document.getElementById('file').files[0];
                console.log($(this).serialize());
                const path_to_file = file !== undefined ? `&path_to_file=${file.name}` : "";
                console.log($(this).serialize() + path_to_file);
                $.ajax({
                    type: "POST",
                    url: 'add_comment.php',
                    data: $(this).serialize() + path_to_file,
                    error: (err, strerr) => {
                        console.log('err');
                        console.log(strerr);
                    },
                    success: function(response)
                    {
                        console.log(response);
                    }
                });
				document.getElementById('add_comment_form').reset();
            });
			
			$('select').on('change', function() {
				$.ajax({
				type: "GET",
				url: 'get_comments_with_sort.php?sort=' + document.getElementById('sort').value,
				success: function(response)
				{
					document.querySelector('#comments').innerHTML = response;
					console.log(response);
				}
			});
			});
			
			$.ajax({
				type: "GET",
				url: 'preview_comment.php',
				success: function(response)
				{
					document.querySelector('#view_block').innerHTML += response.replace('width: 700px; display: flex;', 'width: 700px; display: flex;margin-left: 0;').replace('preview_comment"', 'preview_comment" style="width: 493px;"');
					console.log(response);
				}
			});
        });

        function upload_file() {
            const file = document.getElementById('file').files[0];

            console.log(file.size);
            if (!check_file_size(file.size)) {
				document.getElementById('file').value = '';
				alert('Пожалуйста выберите картинку, которая весит меньше 2МБ.');
				return;
			}

            let formData = new FormData(); 
            formData.append("file", file);
            console.log(formData);
            $.ajax({
                    type: "post",
                    url: 'upload.php',
                    data: formData,
                    contentType: false,
                    processData: false,
                    error: (err, strerr) => {
                        console.log('err');
                        console.log(strerr);
                    },
                    success: function(response)
                    {
                        console.log('reso');
                        console.log(response);
                    }
            });
        }

        function check_file_size(number) {
            if (number < 1048576) {
                return true;
            } else {
                return false;
            }
        }

        function set_edit() {
            const edit_button = document.getElementById('edit');
            const view_button = document.getElementById('view');

            //set selected
            edit_button.classList.add('selected');
            edit_button.classList.remove('not_selected');
            view_button.classList.add('not_selected');
            view_button.classList.remove('selected');

            //toogle visible
            document.getElementById('edit_block').hidden = false;
            document.getElementById('view_block').hidden = true;
        }

        function set_view() {
			const username = document.querySelector('#username').value;
			const email = document.querySelector('#email').value;
			const text = document.querySelector('#comment').value;
			const file = document.querySelector('#file').files[0] ? `path_to_file=${document.querySelector('#file').files[0].name}` : '';
			$.ajax({
				type: "GET",
				url: `preview_comment.php?username=${username}&email=${email}&text=${text.replaceAll('\n', '<br>')}&${file}`,
				success: function(response)
				{
					document.querySelector('#view_block').innerHTML = response.replace('width: 700px; display: flex;', 'width: 700px; display: flex;margin-left: 0;').replace('preview_comment"', 'preview_comment" style="width: 493px;"');
					console.log(response);
				}
			});
			
            const edit_button = document.getElementById('edit');
            const view_button = document.getElementById('view');

            //set selected
            view_button.classList.add('selected');
            view_button.classList.remove('not_selected');
            edit_button.classList.add('not_selected');
            edit_button.classList.remove('selected');

            //toogle visible
            document.getElementById('view_block').hidden = false;
            document.getElementById('edit_block').hidden = true;
        }
    </script>
</body>
</html>
