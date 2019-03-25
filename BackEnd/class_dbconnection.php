<?php

namespace PDOWrapper;
require("class_log.php");
use \PDO;

class DBConnection {
    
    //database configuration parameters
    protected $_config;
    
    //object, query variable
    private $sQuery;
    
    //boolean, connection is good
    private $connected = false;
    
    # @array, The parameters of the SQL query
	private $parameters;
    
    //connection
    public $dbc;
    
    /* __construct
      Open's the connection
      @param $config is an array of connection parameteres
    */
    
    public function __construct(array $config) {
        $this->_config = $config;
        $this->PDOConnection();
        $this->parameters = array();
    }
    
    /* __destruct
       Close the connection
    */
    
    public function CloseConnection() {
        $this->dbc = NULL;
    }
    
    /* function PDOConnection
       Try to get a connection to the database with PDO
    */
    
    private function PDOConnection() {
        //check for connection 
        if ($this->dbc == NULL) {
            //create connection
            $dsn = "" .
                $this->_config['driver'] .
                ":host=" . $this->_config['host'] .
                ";dbname=" . $this->_config['dbname'] . 
                ";charset=" . $this->_config['charset'];
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            
            try {
                $this->dbc = new PDO($dsn, $this->_config['username'], $this->_config['password'], $options);
                
                $this->connected = true;
                
            } catch (Exception $e) {
                echo __LINE__.$e->getMessage();
            }
        }
        
    }
    
   /* Every method which needs to execute a SQL query uses this method.
	*	
	*	1. If not connected, connect to the database.
	*	2. Prepare Query.
	*	3. Parameterize Query.
	*	4. Execute Query.	
	*	5. On exception : Write Exception into the log + SQL query.
	*	6. Reset the Parameters.
	*/	
		private function Init($query,$parameters = "")
		{
		# Connect to database
		if(!$this->connected) { $this->PDOConnection(); }
		try {
				# Prepare query
				$this->sQuery = $this->dbc->prepare($query);
				
				# Add parameters to the parameter array	
				$this->bindMore($parameters);
				# Bind parameters
				if(!empty($this->parameters)) {
					foreach($this->parameters as $param)
					{
						$parameters = explode("\x7F",$param);
						$this->sQuery->bindParam($parameters[0],$parameters[1]);
					}		
				}
				# Execute SQL 
				$this->success = $this->sQuery->execute();		
			}
			catch(PDOException $e)
			{
					# Write into log and display Exception
					$this->ExceptionLog($e->getMessage(), $query );
			}
			# Reset the parameters
			$this->parameters = array();
		}
		
       /**
	*	@void 
	*
	*	Add the parameter to the parameter array
	*	@param string $para  
	*	@param string $value 
	*/	
		public function bind($para, $value)
		{	
			$this->parameters[sizeof($this->parameters)] = ":" . $para . "\x7F" . utf8_encode($value);
		}
       /**
	*	@void
	*	
	*	Add more parameters to the parameter array
	*	@param array $parray
	*/	
		public function bindMore($parray)
		{
			if(empty($this->parameters) && is_array($parray)) {
				$columns = array_keys($parray);
				foreach($columns as $i => &$column)	{
					$this->bind($column, $parray[$column]);
				}
			}
		}
    
    public function Query($query, $params = null, $fetchmode = PDO::FETCH_ASSOC) {
        //clean up whitespace
        $squery = trim($query);
        
        $this->Init($query,$params);
        //
        $stmtType = explode(' ', $query);
        
        //Get Statement Type
        $statement = strtolower($stmtType[0]);
        
        if ($statement === 'select' || $statement === 'show') {
            return $this->sQuery->fetchAll($fetchmode);
        }
        
        elseif ($statement === 'insert' || $statement === 'update' || $statment === 'delete') {
            return $this->sQuery->rowCount();
        }
        
        else {
            return NULL;
        }
    }
    
    public function lastInsertId() {
			return $this->dbc->lastInsertId();
    }
        
    /**
	*	Returns an array which represents a column from the result set 
	*
	*	@param  string $query
	*	@param  array  $params
	*	@return array
	*/	
		public function column($query,$params = null)
		{
			$this->Init($query,$params);
			$Columns = $this->sQuery->fetchAll(PDO::FETCH_NUM);		
			
			$column = null;
			foreach($Columns as $cells) {
				$column[] = $cells[0];
			}
			return $column;
			
		}	
       /**
	*	Returns an array which represents a row from the result set 
	*
	*	@param  string $query
	*	@param  array  $params
	*   	@param  int    $fetchmode
	*	@return array
	*/	
		public function row($query,$params = null,$fetchmode = PDO::FETCH_ASSOC)
		{				
			$this->Init($query,$params);
			return $this->sQuery->fetch($fetchmode);			
		}
       /**
	*	Returns the value of one single field/column
	*
	*	@param  string $query
	*	@param  array  $params
	*	@return string
	*/	
		public function single($query,$params = null)
		{
			$this->Init($query,$params);
			return $this->sQuery->fetchColumn();
		}
       /**	
	* Writes the log and returns the exception
	*
	* @param  string $message
	* @param  string $sql
	* @return string
	*/
	private function ExceptionLog($message , $sql = "")
	{
		$exception  = 'Unhandled Exception. <br />';
		$exception .= $message;
		$exception .= "<br /> You can find the error back in the log.";
		if(!empty($sql)) {
			# Add the Raw SQL to the Log
			$message .= "\r\nRaw SQL : "  . $sql;
		}
			# Write into log
			$this->log->write($message);
		throw new Exception($message);
		#return $exception;
	}			
        
}

?>
