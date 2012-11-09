<html>
	<head></head>
	<body>
		<?php 
		echo form_open('daptagstest/edit/'.$article->id);

		echo form_label('Title', 'title').'<br/>';
		echo form_input('title', $article->title).'<br/>';
		
		echo form_label('Tags (eg.: tag1, tag2, tag3)', 'tags').'<br/>';
		echo form_input('tags', $article->tags).'<br/>';

		echo form_label('Content', 'content').'<br/>';
		echo form_textarea('content', $article->content).'<br/>';

		echo form_submit('submit', 'Submit');

		echo form_close();

		echo validation_errors();
		?>
	</body>
</html>