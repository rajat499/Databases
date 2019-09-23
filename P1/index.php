<html>
	<head>
		<title> Upload csv file </title>
		<style>
		body{
			text-align: center;
		}
		</style>
	</head>
	<body>	
		<br>
		<form action="./import_csv.php" method="POST">
			Enter name of the csv file to upload:
			<input type="text" name="file_path" id="fileToUpload" required="required">
			<input type="submit" value="Submit" name="submit">
		</form>
		
		<div>
		<br>
			Click on the Information you want to view.<br><br>
			<a href="./tables.php?table=USERS"> Users Information </a><br>
			<a href="./tables.php?table=EVENTS"> Events Information </a><br>
			<a href="./tables.php?table=ROLES"> Role Of People in Events </a><br>
		</div>
	</body>
</html>
