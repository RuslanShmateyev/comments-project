<?php
    require_once 'mysql.php';
    require_once 'visualize_comment.php';

    $data = get_comments(true);
    $comments = gen_comments($data, 'admin');
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
<section class="modal hidden">
	<div class="modal_header">
		<div class="hidden" id="id_of_comment"></div>
		    <span onclick="closeModal()" class="close">&times;</span>
		<p> Редактирование элемента </p>
	  </div>
	  <div>
		<textarea style="width: 450px;min-height: 153px;" id="modal_textarea"></textarea>
	  </div>

	  <button style="background: black;color: white;" id="submit_changes" onclick="changeText();closeModal();">Submit</button>
	</section>

    <div class="body">
        <div class="comments">
            <div class="comment_text">Комментарии</div>

            <?php
            foreach ($comments as $comment) {
                echo $comment;
            }
            ?>
        </div>
    </div>

    <script>
        function openModal(button) {
            console.log('open modal');
			console.log(button);
			const text = button.parentElement.parentElement.parentElement.parentElement.querySelector('.comment_p').innerText;
			const id = document.querySelector('#id_of_comment');
            console.log(text);
			const textarea = document.querySelector('#modal_textarea');
			textarea.value = text;
			id.innerText = button.parentElement.parentElement.parentElement.parentElement.parentElement.id;
			
			const modal = document.querySelector('.modal');
			
			modal.classList.remove('hidden');
        }
		
		function closeModal(button) {
            console.log('close modal');
			const modal = document.querySelector('.modal');
			
			modal.classList.add('hidden');
        }
		
		function changeStatus(button, status) {
			const id = button.parentElement.parentElement.parentElement.parentElement.parentElement.id;
			console.log(button.parentElement.parentElement.parentElement.parentElement.parentElement);
			
			$.ajax({
				type: "POST",
				url: 'update_comment.php',
				data: {id, status},
				success: function(response)
				{
					console.log(response);
				}
			});
		}
		
		function changeText() {	
			const id = document.querySelector('#id_of_comment').innerText;
			const text = document.querySelector('#modal_textarea').value;
			
			console.log(text);
			const comment = document.getElementById(id);
			console.log(comment);
			
			comment.querySelector(".comment_p").innerHTML = text;
			
			$.ajax({
				type: "POST",
				url: 'update_comment.php',
				data: {id, text},
				success: function(response)
				{
					console.log(response);
				}
			});
		}
    </script>
</body>
</html>
