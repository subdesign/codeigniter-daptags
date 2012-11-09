<html>
	<head>
		<title>Daptags article example</title>
	</head>

	<body>
		
		<?php 
		foreach($articles as $article)
		{
			echo $article->title.'<br/><br/>';
			echo $article->content.'<br/><br/>';
			echo anchor('article/'.$article->id, 'Read more..');
			echo '<hr/>';
		}
		?>

	</body>
</html>