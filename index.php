<head>
</head>

<body>
	<link href='css/styles.css' rel='stylesheet'>
	<div class='box'>
		<br />
		<h1>ASA config parser</h1>
		<br />
		<form enctype='multipart/form-data' action='parse.php' method='POST'>
		
			<input type='hidden' name='MAX_FILE_SIZE' value='200000' />
			<input name='userfile' type='file' /><br />
			
			<div class='wrapper small'>
			<div class='table'><div class='row'>
			
			<div class='cell small'><input type='checkbox' id='empty' name='empty'>
			<label for='empty'>Show empty</label></div>
			
			<div class='cell small'><input type='checkbox' id='nat' name='nat' checked>
			<label for='nat'>NAT</label></div>
			
			<div class='cell small'><input type='checkbox' id='acl' name='acl' checked>
			<label for='acl'>ACL</label></div>
			
			</div></div></div>
			<input type='submit' value='Parse' />
		</form>
	</div>
</body>
