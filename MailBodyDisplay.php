<?php

/**********************************************************************
***********************************************************************
*****MailBodyDisplay renders the details of the email
***********************************************************************
***********************************************************************/

session_start();
include("MySQLServerConnectionManager.php");
include("LoginInfo.php");

//Create MySQL server conncetion
$sqlCon = new MySQLConnection($host_sql,$user_sql,$password_sql,$dbName);
$sqlCon->getConnection();

$statement = "SELECT * FROM InComingMail WHERE mailID = ".$_POST[mailID];
$result = $sqlCon->implementQuery($statement);

echo "
<title>Mail Detail</title>
<head><a href = 'IncomingMailDisplay.php'> Back to Mail List </a></head>
";

echo "<body>
			<div>";
if($result)
{
	while($row = mysql_fetch_array($result))
	  {
			echo "<p> To: ".$row['sendToAddress']."</p>";
			echo "<p> From: ".$row['sendFromAddress']."</p>";
			$_SESSION['sendFromAddress'] = $row["sendFromAddress"];
			echo "<p> Sender: ".$row['sendFromName']."</p>";
			echo "<p> Date: ".$row['receiveDate']."</p>";
			echo "<p> Subject: ".$row['mailSubject']."</p>";
			echo "<p> *****************************************************************************************</p>";
			echo "<p>".$row['mailBody']."</p>";
		}
}
else
{
	echo "Refresh is not a valid action in this page! Please back to the mail list and select again";
}
	
echo "
</div>
<form action='sendReward.php'>
<input type='submit' value='Send Reward'/>
</form>
</body>";

$sqlCon->closeConnection();
?>

