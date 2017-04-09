<?php

/**
 * Socrata driver for dataset wrapper
 * @author James Clark
 * @version 1.0.0
 *
 */

include "DataAPIInterface.php";

class APIDataGovDriver implements DataAPIInterface
{
	private $socrata = NULL;
	private $application_key;
    private $user_name;
    private $password;
	private $root_url;
	
    private $dataset_id;
	private $select_statement = "";
	private $where_statement = "";
    private $order_statement = "";
	private $limit = 0;

	public function __construct($location, $app_key, $username = "", $password = "")
	{
		$this->root_url = $location;
        $this->application_key = $app_key;
        $this->user_name = $username;
        $this->password = $password;
            
		$this->dataset_id = "";
        $this->select_statement = "";
        $this->where_statement = "";
        $this->order_statement = "";
		$this->limit = 0;
	}

	/**
     * Returns data from query built by select(), where(), from(), limit(), order()
     */
    
    public function get() 
	{
    }

	public function select($fields = "") 
	{
		#NYI
	}
    
    public function from($resource_name)
    {
        $this->dataset_id = $resource_name;
    }

	public function where($criteria = "") 
    {
        #$this->where_statement = $criteria;
        #NYI
    }

	public function limit($count)
    {
        #NYI
    }
    
    public function order($by, $ascend = TRUE)
    {
        #NYI
    }
    
    public function location($url)
    {
        /*
        if ($url != $this->root_url)
        {    
            $this->root_url = $url;
            $this->dataset_id = "";
        }
        */
        #NYI
    }
    
    public function full_url()
    {
        return "";
    }
        
}