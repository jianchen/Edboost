<?php

/**********************************************************************
***********************************************************************
*****Send rewards to the registered member and mark this member in 
the database as rewarded='Yes'
***********************************************************************
***********************************************************************/

session_start();

include("MySQLServerConnectionManager.php");
include("LoginInfo.php");

echo "
<title>Send Reward</title>
<head><a href = 'IncomingMailDisplay.php'> Back to Mail List </a></head>
";

//Create MySQL server conncetion
$sqlCon = new MySQLConnection($host_sql,$user_sql,$password_sql,$dbName);
$sqlCon->getConnection();

$statement = "SELECT * FROM AccountInfo WHERE emailAddress = '".$_SESSION['sendFromAddress']."'";
$result = $sqlCon->implementQuery($statement);


if($row = mysql_fetch_array($result))
{
	//$row = mysql_fetch_array($result);
	
	//check if the reward has been sent before
	if($row['rewarded'] == 'Yes')
	{
		echo "The reward has already been sent to this memeber, you cannot send again!";
	}
	else
	{
		$statement = "UPDATE AccountInfo SET rewarded='Yes' WHERE emailAddress='".$_SESSION['sendFromAddress']."'";
		$sqlCon->implementInsert($statement);
		
		echo "
		<body>
		<p>The reward has been sent to ".$_SESSION['sendFromAddress']." successfully!";
		echo "
		</p>
		</body>";
	}
}
else
{
	echo "This is not a registered member, cannot send reward to someone who is not a member!";
}



?>