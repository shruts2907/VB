<?php
/*
    This script is a helper class for using the databases
*/

require("config.inc.php");

class Database {

	// Store the single instance of Database
	private static $m_pInstance; 

	private $server   = ""; //database server
	private $user     = ""; //database login name
	private $pass     = ""; //database login password
	private $database = ""; //database name
	private  $mysqli;  //mysqli object
	private  $error;    //error

	//number of rows affected by SQL query
	private $affected_rows = 0;
	//last insert id
	private $results = array(); //results of query


	#-#############################################
	# desc: constructor
	# usage: $db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
	#             or $db = new Database(); and will using info from config file
	private function __construct($server=DB_SERVER, $user=DB_USER, $pass=DB_PASS, $database=DB_DATABASE){
	    $this->server=$server;
	    $this->user=$user;
	    $this->pass=$pass;
	    $this->database=$database;
		//connect
		$this->mysqli = @new mysqli($server, $user, $pass, $database);

		if (mysqli_connect_errno()) 
		{
	    			$this->error = "Connect failed: ".mysqli_connect_error();
	    			echo $this->error;
	    			die();
	    }
	    else $this->error = "";
	}#-#constructor()

	#-#############################################
	# desc: close the connection
	function close() {
	    if(!@mysqli_close($this->mysqli)){
	        $this->error="Connection close failed.";
	    }
	    else $this->error="";
	}#-#close()

	#########################################
	//function select
	#if prepared query false then insert else 
	#update
	########################################
	function doSelect($query,$arg,$param){

	 	$count = count($arg);
	 	$strLen = strlen($param);
	 	
	 	if($strLen !== $count){
	 		echo "Error: No of argument and parameter mismatch";
	 		die();
	 	}
	 	
	 	$result="";   
		$counter = $count-1;          
		foreach( $arg as $key => $value ){
			if($key != $counter){
				$result .=$value.",";
			}else{
		    	$result .= $value;
			}
		}
	 	
		$stmt = @$this->mysqli->prepare($query);
		$stmt->bind_param($param,$result);
		$stmt->execute();
		$stmt->store_result();
		
		if ($stmt->num_rows == 0) //not a duplicate
		{
			return true;
		}
		else
		{
			return false;
		}
		
		$stmt->close();
	}
	//insert query
	#####################################################
	#Do Insert and Update query here
	#$arg array of aruments to be used in query
	#$param type of arguments to be bind to the query
	#####################################################
	function doInsert($query,$arg,$param){
		$list = array();
		//create the datatypes and array of values for query and binding params
		$i=0;
		
		foreach($arg as $val)
		{
			$bind_name = 'bind' . $i;       //give them an arbitrary name
			$$bind_name = $val;            //add the parameter to the variable variable
			$list[] =&$$bind_name;
			$i++;
		}
		//array unshift will append param before arrguments
		//eg("sss",$arg1,$arg2);
		array_unshift($list,$param);
		
		$stmt = @$this->mysqli->prepare($query);
		
		//php array call back funtion
		call_user_func_array(array($stmt,'bind_param'),$list);
		$stmt->store_result();
		$good=$stmt->execute();
		if ($good){
			return true;
		}else
		{
			return false;
		}
		$stmt->close();
	}

	//display query
	##################################
	#function to fetch data from table
	##################################
	function display(){
		$query = "SELECT * FROM uploadTable";
		$stmt = @$this->mysqli->query($query);
		$result = array();
		
		if ($stmt->num_rows > 0) //not a duplicate
		{
			
			// now get the records
			while ($row = $stmt->fetch_assoc()){
				
				$new = array();
				foreach ($row as $key=>$value){
					$new[$key] = $value;
				}
				array_push($result,  $new);
			}//end of while
	    	
		}
		
		return $result;
		
	}


	//singleton function
	#############################################
	# create instance of a database class
	############################################
	public static function getInstance()
	{
	    if (!self::$m_pInstance)
	    {
	        self::$m_pInstance = new Database();
	    }

	    return self::$m_pInstance;
	} 

}//CLASS Database
###################################################################################################

?>