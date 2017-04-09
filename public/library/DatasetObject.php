<?php

/**
 * Abstraction layer for web data APIs
 */

include_once "DataAPIInterface.php";
include_once "../Config.php";


class DatasetObject
{
    private $driver = NULL;
    
    public function __construct($driver)
    {
        $this->driver = $driver;    
    }
    
    /*
     * Peforms a query on the underlying data API
     * @todo Result caching, sort result by SELECT order
     */
    
    /* Example JSON params
    
    '{
        "locaton":"data.colorado.gov",
        "from":"m279-sgtm",
        "select":"year, degreelevel, programname",
        "where":"year > '2012' AND degreelevel = 'bachelor'",
        "limit":50,
        "allow_cache":true
    }'
    
    */
    public function query($json_string) 
    {
        $params = json_decode($json_string, TRUE);
        
        $where = "";
        $select = "";
        $from = "";
        $order = "";
        $root_url = "";
        $limit = 0;
        
        //Use default caching options in Config
        $caching = Config::$use_cache;
        
        foreach ($params as $key => $value)
        {
            if ($key == "location")
                $root_url = $value;
            else if ($key == "from")
                $from = $value;
            else if ($key == "select")
                $select = $value;
            else if ($key == "where")
                $where = $value;
            else if ($key == "order")
                $order = $value;
            else if ($key == "limit")
                $limit = $value;
            else if ($key == "allow_cache") //Give option of overriding default for a single query
                $caching = $value;
        }
        
        //Build query
        $this->driver->location($root_url);
        $this->driver->from($from);
        $this->driver->where($where);
        $this->driver->select($select);
        $this->driver->order($order);
        $this->driver->limit($limit);
        
        //Check cache
        if (!$caching)
        {
            $result = $this->driver->get();
            $this->sort_field_order($result, $select);
            return json_encode($result);
        }
        else
        {
            $hashed_query = md5($this->driver->full_url());
            
            //Check if cache is stale or doesn't exist
            if (!$this->check_cache($hashed_query))
            {
                $result = $this->driver->get();
                $this->sort_field_order($result, $select);
                $json_result = json_encode($result);
                
                //Write results to cache
                $this->cache_results($hashed_query, $json_result);
                
                return $json_result;
            }
            else return $this->get_results_from_cache($hashed_query);
        }
    }
    
    /**
     * Checks if cache for hash exists or is stale
     * @return boolean true if cache is usable, false if stale or doesn't exist
     */
    private function check_cache($hash)
    {
        $cache_file = Config::$cache_path."/".$hash;
        
        if (file_exists($cache_file))
        {
            $handle = fopen($cache_file, 'r');
            $cache_time = fgets($handle);
            
            $cache_age = (time() - $cache_time) / 60;
            
            if ($cache_age > Config::$cache_time)
            {
                fclose($handle);
                unlink($cache_file);
                
                return false;
            }
            
            return true;
        }
        else return false;
    }
    
    private function get_results_from_cache($hash)
    {
        $cache_file = Config::$cache_path."/".$hash;
        $handle = fopen($cache_file, 'r');
        
        fgets($handle); //Read and throw away timestamp
        
        return fgets($handle);
    }
    
    private function cache_results($hash, $json_result)
    {
        $cache_file = Config::$cache_path."/".$hash;
        
        $handle = fopen($cache_file, 'w');
        fwrite($handle, time()."\n");
        fwrite($handle, $json_result);
        fclose($handle);
    }
    
    /**
     * Sorts field order according to select statement
     * Select statement is a comma separtated string of fields
     */
    private function sort_field_order(&$result, $select)
    {
        if (empty($select))
            return;
        
        $order = explode(",", str_replace(" ", "", $select));
        
        $new_row = array();
        
        foreach ($result as &$row)
        {    
            foreach ($order as $key)
            {
                $new_row[$key] = $row[$key];
            }
            
            $row = $new_row;
        }
    }
}