<!-- -------------------------------------------------------------
COMP353, Section F, Prof BC DESAI, Project-1
COMP353- Group 11
27771223	Soumayyah AHMED	so_ahmed@encs.concordia.ca
40012133	Florin POENARIU f_poenar@encs.concordia.ca
40024628	Avnish PATEL av_pate@encs.concordia.ca
40036565	Sadia Anowara SMITHA s_smitha@encs.concordia.ca
40150463	Rajat JAISWAL r_jais@encs.concordia.ca
------------------------------------------------------------- -->

<!-- Main Page on the webpage -->

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
			<input type="text" name="file_name" id="fileToUpload" required="required">
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
