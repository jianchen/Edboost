<?php
/**********************************************************************
***********************************************************************
*****IncomingMailDisplay displays the list of mails
***********************************************************************
***********************************************************************/
include("MySQLServerConnectionManager.php");
include("LoginInfo.php");

//Create MySQL server conncetion
$sqlCon = new MySQLConnection($host_sql,$user_sql,$password_sql,$dbName);
$sqlCon->getConnection();

$statement = "SELECT * FROM InComingMail";
$result = $sqlCon->implementQuery($statement);

//Display
echo "
<title> Mail List </title>


<body>

<h1 align='center'> Incoming Mails </h1>
<form action='IncomingMailManager.php'> <input type='submit' value='Receive Mail' /> </form>
<form action='MailBodyDisplay.php' method='POST'>
<table  width='100%' border='1'>
<tr>
	<th width='5%'></th>
	<th width='35%'> From </th>
	<th width='60%'> Subject </th>
</tr>
";

while($row = mysql_fetch_array($result))
  {
  	echo "
  	<tr>
  	<td  align='center'><input type='radio' name='mailID' value=". $row['mailID'] ." />"."</td>
  	<td>".$row['sendFromAddress']. "</td>" .
  	"<td>".$row['mailSubject']."</td>
  	</tr>";

  }


echo "

</table>
<p align='right'><input type='submit' value='View Detail' /></p>
</form>
</body>
";


$sqlCon->closeConnection();

?>