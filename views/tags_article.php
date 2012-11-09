<html>
	<head>
		<title>Daptags article example</title>
	</head>

	<body>
		
		<?php 
		echo $article->title.'<br/><br/>';
		echo $article->content.'<br/><br/>';
		foreach($tags as $tag)
		{
			echo anchor('tag/'.urlencode($tag), $tag).'&nbsp;';
		}
		?>

	</body>
</html>