<?php 

namespace Utils\validation;

class FormErrorMessage 
{   
    /**
     * Send error essage
     * @param  string $keyName [description]
     * @param  array  $arr     [description]
     * @return string        
     */
    public function getErrorMessage(string $keyName,array $arr):string 
    {
        $message="";
        
        if (array_key_exists($keyName,$arr) && is_array($arr[$keyName]))
        {
            foreach($arr[$keyName] as $el)
            {
                $message.=<<<HTML
                        <p>$el</p>
                    HTML;
            }  
        } 
        
        return $message; 

    }
}

?>