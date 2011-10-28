<?php

/*****************************************************************
******************************************************************
*****MySQLConnection class functions in create and close connection
to MySQL server, and all kinds of query
******************************************************************
******************************************************************/
class MySQLConnection
{
	var $host = '';
	var $user = '';
	var $password = '';
	var $myCon;
	var $dbName = '';
	
	//Constructor
	function MySQLConnection($hostInput,$userInput,$passwordInput,$database)
	{
		$this->host = $hostInput;
		$this->user = $userInput;
		$this->password = $passwordInput;
		$this->dbName = $database;
	}
	
	//Create connection
	function getConnection()
	{
		$con = mysql_connect($this->host,$this->user,$this->password);
		if (!$con)
		  {
		  die('Could not connect: ' . mysql_error());
		  }
		  
		//echo 'Successfully connect to MySQL!';
		echo '<br/>';
		$this->myCon = $con;
	}
	
	//Implememnt Query
	function implementQuery($query)
	{
		mysql_select_db($this->dbName, $this->myCon);

		//$query="SELECT Email FROM $table";
		$result = mysql_query($query);
		return $result;

	}
	
	//Implememnt Insertion and Update
	function implementInsert($query)
	{
		mysql_select_db($this->dbName, $this->myCon);
		
		mysql_query($query);

	}
	
	//Close connection
	function closeConnection()
	{
		mysql_close($this->myCon);
	}
	
} //end of class

?>

<?php
//test 
//$sqlCon = new MySQLConnection('localhost','root','','maildb');
//
//$sqlCon->getConnection();
//
//$testQuery = "INSERT INTO InComingMail(sendToAddress,sendToName,sendToOtherAddress,sendToOtherName,sendFromAddress,sendFromName,receiveDate,mailSubject,mailBody)
//VALUES('aj23cj@gmail.com','CJ','yilinwang99@gmail.com','Yilin','aj22cj@gmail.com','Jian Chen','2011-10-18','testMail','Hey, this is a test mail!');";
//$sqlCon->implementInsert($testQuery);
//$sqlCon->closeConnection();
?>