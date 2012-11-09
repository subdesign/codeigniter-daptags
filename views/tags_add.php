<html>
	<head></head>
	<body>
		<?php 
		echo form_open('daptagstest/add');

		echo form_label('Title', 'title').'<br/>';
		echo form_input('title').'<br/>';
		
		echo form_label('Tags (eg.: tag1, tag2, tag3)', 'tags').'<br/>';
		echo form_input('tags').'<br/>';

		echo form_label('Content', 'content').'<br/>';
		echo form_textarea('content').'<br/>';

		echo form_submit('submit', 'Submit');

		echo form_close();

		echo validation_errors();
		?>
	</body>
</html>