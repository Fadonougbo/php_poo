<?php

namespace Utils\validation;

use Interfaces\FormValidatorInterface;
use Valitron\Validator;

class FormValidator implements FormValidatorInterface
{

    private Validator $validator;

    public array $rules=[];

    public function __construct(?array $parsedBody=[]) 
    {
        $this->validator=new Validator($parsedBody);
    }

    /**
     * 
     * @param string[] $names
     * @return FormValidator
     */
	public function required(string ...$names):self
    {
        
        $requiredList=array_map(function($el)
        {
            return [$el];
        },$names);
 
        $this->rules["required"]=$requiredList;

        return $this;

	}
    

	/**
     * [lengthMin description]
     * @param  array  $arr [description]
     * @return self      [description]
     */
	public function lengthMin(array $arr):self
    {
        
        $arrayKeyList=array_keys($arr);

        $list=array_map(function($el) use ($arr)
        {
            return [$el,$arr[$el]];
            
        },$arrayKeyList);
 
        $this->rules["lengthMin"]=$list;

        return $this;
	}

    public function dateFormat(array $arr):self
    {

         $list=array_map(function($el,$key)
        {
            return [$key,$el];
            
        },$arr,array_keys($arr));
 
        $this->rules["dateFormat"]=$list;

        return $this;

    }

    public function slug(string ...$names):self 
    {

        $requiredList=array_map(function($el)
        {
            return [$el];
        },$names);
 
        $this->rules["slug"]=$requiredList;

        return $this;
    }

    public function validate(): array|bool
    {
            $this->validator->rules($this->rules);

            return $this->validator->validate()?true:$this->validator->errors();
    }


}


?>