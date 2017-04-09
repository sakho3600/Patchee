<?php

interface DataAPIInterface
{
    function get();
    
    function select($fields = "");
    
    function from($resource_name);
    
    function where($criteria = "");
    
    function limit($count);
    
    function order($by, $ascend = TRUE);
    
    function location($url);
    
    function full_url();
}