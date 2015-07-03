<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>CoU Itemficator</title>
	<style>
		body {
			font-family: sans-serif;
		}

		input[type="url"] {
			width: 500px;
		}

		textarea {
			width: 100%;
			height: 400px;
			resize: vertical;
		}
	</style>
</head>
<body>
	<h1>CoU Itemficator</h1>
	<p>Converts <a href="http://www.glitchthegame.com/encyclopedia/">Glitch Encyclopedia</a> entries into JSON. <strong>This program makes calls to the Glitch encyclopedia every time it runs. Please do not abuse it!</strong> <a href="https://github.com/ChildrenOfUr/itemficator">View source on GitHub</a></p>
	<form id="input" action="" method="GET">
		<fieldset>
			<input type="url" name="url" placeholder="URL (http://glitchthegame.com/... or http://www.glitchthegame.com/...)" required>
			<label><input type="checkbox" name="tool">Tool(s)</label>
			<label><input type="checkbox" name="category">Category</label>
			<button type="submit">Convert</button>
		</fieldset>
		<br>
	</form>
	<textarea disabled><?php include_once("itemficator.php"); ?></textarea>
</body>
</html>