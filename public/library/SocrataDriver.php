<?php

/**
 * Socrata driver for dataset wrapper
 * @author James Clark
 * @version 1.0.0
 *
 */

include "DataAPIInterface.php";
include "socrata.php";

class SocrataDriver implements DataAPIInterface
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
        
        $this->socrata = new Socrata($this->root_url, $this->application_key, $this->user_name, $this->password);
        
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
        $params = array();
        
        if (!empty($this->select_statement))
            $params["\$select"] = $this->select_statement;
        
        if (!empty($this->where_statement))
            $params["\$where"] = $this->where_statement;
        
        if (!empty($this->order_statement))
            $params["\$order"] = $this->order_statement;
        
        if ($this->limit > 0)
            $params["\$limit"] = $this->limit;
        
		$result = $this->socrata->get("/resource/".$this->dataset_id, $params);
        #print_r($result);
        
        return $result;
	}

	public function select($fields = "") 
	{
		$this->select_statement = $fields;

        return $this;
	}
    
    public function from($resource_name)
    {
        $this->dataset_id = $resource_name;

        return $this;
    }

	public function where($criteria = "") 
    {
        $this->where_statement = $criteria;

        return $this;
    }

	public function limit($count)
    {
        if ($count >= 0)
            $this->limit = $count;
        else $this->limit = 0;

        return $this;
    }
    
    public function order($by, $ascend = TRUE)
    {
        $this->order_statement = $by;

        return $this;
    }
    
    public function location($url)
    {
        if ($url != $this->root_url)
        {
            //Destroy underlying Socrata object
            $this->socrata = NULL;
        
            $this->root_url = $url;
            $this->dataset_id = "";
        
            $this->socrata = new Socrata($this->root_url, $this->application_key, $this->user_name, $this->password);
        }
    }
    
    public function full_url()
    {
        $params = array();
        
        if (!empty($this->select_statement))
            $params["\$select"] = $this->select_statement;
        
        if (!empty($this->where_statement))
            $params["\$where"] = $this->where_statement;
        
        if (!empty($this->order_statement))
            $params["\$order"] = $this->order_statement;
        
        if ($this->limit > 0)
            $params["\$limit"] = $this->limit;
        
		$url = $this->socrata->create_query_url("/resource/".$this->dataset_id, $params);
        
        return $url;
    }
}