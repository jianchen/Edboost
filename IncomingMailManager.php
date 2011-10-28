<?php

/*******************************************************************************
********************************************************************************
****The IncomingMailManager inserts all the new incoming mails into the database
********************************************************************************
********************************************************************************/

include("IncomingMail.php");
include("MySQLServerConnectionManager.php");
include("LoginInfo.php");

//Create MySQL server conncetion
$sqlCon = new MySQLConnection($host_sql,$user_sql,$password_sql,$dbName);
$sqlCon->getConnection();

//Creating a object of reciveMail Class
$receiver= new receiveMail($userName,$pwd,$emailAddress,$mailServer,'pop3','995',true);
//Connect to the Mail Box
$receiver->connect();       
//Get Total Number of Unread Email in mail box
$tot=$receiver->getTotalMails();

//echo "Total Mails:: $tot<br>";
echo "<head><a href = 'IncomingMailDisplay.php'> Back to Mail List </a></head> <br/><br/>";
echo "<body>";
echo $tot." new mail(s) has/have been added to the mail list. Return to Mail List page to see";
echo "</body>";

//Insert mail data into database
if($tot > 0)
{
	for($i=$tot;$i>0;$i--)
	{
		//Get Header Info Return Array Of Headers **Array Keys are (subject,to,toOth,toNameOth,from,fromName)
		$head=$receiver->getHeaders($i);  
		
		$statement = "INSERT INTO 
									InComingMail(sendToAddress,sendToName,sendToOtherAddress,sendToOtherName,sendFromAddress,sendFromName,receiveDate,mailSubject,mailBody)
									VALUES('".$head['to']."','Edboost','".$head['toOth']."','".$head['toNameOth']."','".$head['from']."','".$head['fromName']."','".$head['date']."','".$head['subject']."','".$receiver->getBody($i).
									"');";
		
		echo $statement;
		//implement query
		$sqlCon->implementInsert($statement);
	}
}

$sqlCon->closeConnection();
$receiver->close_mailbox(); 

?>