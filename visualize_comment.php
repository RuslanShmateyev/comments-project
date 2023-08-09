<?php

function gen_comments($data, $access = 'user') {
    $comments = [];

    if ($access == 'admin') {
		foreach ($data as $comment) {
			if (isset($comment["username"]) && isset($comment["email"]) && isset($comment["text"]) && isset($comment["date"])) {
                $isAccepted = $comment['status'] == 'accepted' ? true : false;
                $isDeclined = $comment['status'] == 'declined' ? true : false;
				$image = $comment["path_to_file"] ?: false;
				$wasUpdated = $comment['wasUpdated'] ? ' <br> <br> <span style="font-size: 12px;"> Было изменено модератором </span>' : '';
				$imageTag = $image ? '<img src="upload/'. $image .'" alt="img does not exists">' : '';
				$block = '<div class="add_comment_form" id="' . $comment['id'] .'" style="width: 700px; display: flex;">
				<div class="preview_comment">
					<div class="comment_header">
						<p class="fields">'. $comment['username'] .'</p>
						<p class="fields">'. $comment['email'] .'</p>
						<p class="fields" style="margin-right: 0px">'. $comment['date'] .'</p>
					</div>
					<div class="comment_content"> <p class="comment_p">'. $comment['text'] . $wasUpdated .'</p>
                        <div class="buttons">
                            <div class="form_radio_btn" style="background: lightgreen;">
                                <input id="accepted_' . $comment['id'] .'" onclick="changeStatus(this, \'accepted\')" type="radio" name="' . $comment['id'] .'" value="1" ' . ($isAccepted ? 'checked="true"' : '') .'>
                                <label for="accepted_' . $comment['id'] .'">Принять</label>
                            </div>
                            <div class="form_radio_btn" style="background: red;">
                                <input id="declined_' . $comment['id'] .'" onclick="changeStatus(this, \'declined\')" type="radio" name="' . $comment['id'] .'" value="0" ' . ($isDeclined ? 'checked="true"' : '') .'>
                                <label for="declined_' . $comment['id'] .'">Отклонить</label>
                            </div>
                            <div class="form_radio_btn" style="background: lightblue;">
                                <button id="update_' . $comment['id'] .'" style="display: none;" onclick=openModal(this);></button>
                                <label for="update_' . $comment['id'] .'">Редактировать</label>
                            </div>
                        </div>
                    </div>
                </div>
            '. $imageTag .'
			</div>';
				array_push($comments, $block);
			}
    }

        return $comments;
    }

    foreach ($data as $comment) {
        if (isset($comment["username"]) && isset($comment["email"]) && isset($comment["text"]) && isset($comment["date"])) {
            $image = $comment["path_to_file"] ?: false;
            $wasUpdated = $comment['wasUpdated'] ? ' <br> <br> <span style="font-size: 12px;"> Было изменено модератором </span>' : '';
            $imageTag = $image ? '<img src="upload/'. $image .'" alt="img does not exists">' : '';
            $block = '<div class="add_comment_form" style="width: 700px; display: flex;">
            <div class="preview_comment">
                <div class="comment_header">
                    <p class="fields">'. $comment['username'] .'</p>
                    <p class="fields">'. $comment['email'] .'</p>
                    <p class="fields" style="margin-right: 0px">'. $comment['date'] .'</p>
                </div>
                <div class="comment_content"> <p>'. $comment['text'] . $wasUpdated .'</p></div>
            </div>
            '. $imageTag .'
        </div>';
            array_push($comments, $block);
        }
    }
    
    return $comments;
}