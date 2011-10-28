<?php
//ReciveMail Class functions in handling incoming mails 

class receiveMail
{
	var $server='';
	var $username='';
	var $password='';
	var $marubox='';					
	var $email='';			
	
	//Constructor
	function receiveMail($username,$password,$EmailAddress,$mailserver='localhost',$servertype='pop',$port='110',$ssl = false) 
	{
		if($servertype=='imap')
		{
			if($port=='') $port='143'; 
			$strConnect='{'.$mailserver.':'.$port. '}INBOX'; 
		}
		else
		{
			$strConnect='{'.$mailserver.':'.$port. '/pop3'.($ssl ? "/ssl" : "").'}INBOX'; 
		}
		$this->server			=	$strConnect;
		$this->username			=	$username;
		$this->password			=	$password;
		$this->email			=	$EmailAddress;
	}
	
	//Connect To the Mail Box
	function connect() 
	{
		$this->marubox=@imap_open($this->server,$this->username,$this->password);
		
		if(!$this->marubox)
		{
			echo "Error: Connecting to mail server";
			exit;
		}
		//echo "Connect successfully to the server!";
		//echo "<br/>";
	}
	
	// Get Header info
	function getHeaders($mid) 
	{
		if(!$this->marubox)
			return false;

		$mail_header=imap_header($this->marubox,$mid);
		$sender=$mail_header->from[0];
		$sender_replyto=$mail_header->reply_to[0];
		if(strtolower($sender->mailbox)!='mailer-daemon' && strtolower($sender->mailbox)!='postmaster')
		{
			$mail_details=array(
					'from'=>strtolower($sender->mailbox).'@'.$sender->host,
					'fromName'=>$sender->personal,
					'toOth'=>strtolower($sender_replyto->mailbox).'@'.$sender_replyto->host,
					'toNameOth'=>$sender_replyto->personal,
					'subject'=>$mail_header->subject,
					'to'=>strtolower($mail_header->toaddress),
					'date'=>$mail_header->date
				);
		}
		return $mail_details;
	}
	
	//Get Part Of Message Internal Private Use
	function get_part($stream, $msg_number, $mime_type, $structure = false, $part_number = false) 
	{ 
		if(!$structure) { 
			$structure = imap_fetchstructure($stream, $msg_number); 
		} 
		if($structure) { 
			if($mime_type == $this->get_mime_type($structure))
			{ 
				if(!$part_number) 
				{ 
					$part_number = "1"; 
				} 
				$text = imap_fetchbody($stream, $msg_number, $part_number); 
				if($structure->encoding == 3) 
				{ 
					return imap_base64($text); 
				} 
				else if($structure->encoding == 4) 
				{ 
					return imap_qprint($text); 
				} 
				else
				{ 
					return $text; 
				} 
			} 
			if($structure->type == 1) /* multipart */ 
			{ 
				while(list($index, $sub_structure) = each($structure->parts))
				{ 
					if($part_number)
					{ 
						$prefix = $part_number . '.'; 
					} 
					$data = $this->get_part($stream, $msg_number, $mime_type, $sub_structure, $prefix . ($index + 1)); 
					if($data)
					{ 
						return $data; 
					} 
				} 
			} 
		} 
		return false; 
	} 
	
	//Get Mime type Internal Private Use
	function get_mime_type(&$structure) 
	{ 
		$primary_mime_type = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER"); 
		
		if($structure->subtype) { 
			return $primary_mime_type[(int) $structure->type] . '/' . $structure->subtype; 
		} 
		return "TEXT/PLAIN"; 
	} 
	
	//Get Total Number of Unread Email In Mailbox
	function getTotalMails() 
	{
		if(!$this->marubox)
			return false;

		//$headers=imap_headers($this->marubox);
		//return count($headers);
		$count = imap_num_msg($this->marubox);
		return $count;
	}
	
	
	// Get Message Body
	function getBody($mid) 
	{
		if(!$this->marubox)
			return false;

		$body = $this->get_part($this->marubox, $mid, "TEXT/HTML");
		if ($body == "")
			$body = $this->get_part($this->marubox, $mid, "TEXT/PLAIN");
		if ($body == "") { 
			return "";
		}
		return $body;
	}
	
	// Delete a Mail
	function deleteMails($mid) 
	{
		if(!$this->marubox)
			return false;
	
		imap_delete($this->marubox,$mid);
	}
	
	//Close Connection to the Mail Box
	function close_mailbox() 
	{
		if(!$this->marubox)
			return false;

		imap_close($this->marubox,CL_EXPUNGE);
	}
}
?>



