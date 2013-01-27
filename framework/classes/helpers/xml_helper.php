<?php

/**
* Small class for assisting in common XML-related tasks.
*/
class Xml_Helper
{

	/**
	* Recursive function to turn an associative array into XML
	* @param mixed $data The payload
	* @param ref SimpleXMLElement &$xml_obj The reference to the SimpleXML object
	*/
	public function array_to_xml($data, &$xml_obj)
	{
		foreach($data as $key => $val)
		{
			if(is_array($val))
			{
				if(!is_numeric($key))
				{
					$node = $xml_obj->addChild("$key");
					$this->array_to_xml($val, $node);
				}
				else
				{
					$this->array_to_xml($val, $xml_obj);
				}
			}
			else
			{
				$xml_obj->addChild("$key", "$val");
			}
		}
	}
}