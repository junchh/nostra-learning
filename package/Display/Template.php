<?php 
/** Simple PHP Template Engine
 *
 * @author Choi Junho H <xerosym@yahoo.com>
 * @created 7/12/2015
 * @version 2.0
 *
 */

class DisplayException extends Exception 
{
	const UNKNOWN_ERROR = 'UNKNOWN_ERROR';
	const FILE_NOT_FOUND = 'FILE_NOT_FOUND';
	
	/**
	 *
	 * Constructor
	 *
	 * @param $type
	 * @param $filename
	 * @param $code
	 * @param $previous
	 *
	 * @return void
	 *
	 */
	public function __construct($type, $filename = '', $code = 0, Exception $previous = null)
	{
		
		switch($type){
			case self::FILE_NOT_FOUND:
				$message = 'File not found! filename: ' . $filename;
				break;

			case self::UNKNOWN_ERROR:
			default:
				$message = 'Unknown error encountered!';
				break;
		}
		parent::__construct($message, $code, $previous);
	}
}

class Template 
{

	private $name;
	private $var;
	private $filename = [];
	private $file = [];
	private $regex = 
	[
		'/(?:[\s]+)?({for\s\$[a-zA-Z_][a-zA-Z0-9_]+\sin\s\$[a-zA-Z_][a-zA-Z0-9_]+})([\s\S][^{]+)({\/for})/',
		'/{for\s(\$[a-zA-Z_][a-zA-Z0-9_]+)\sin\s(\$[a-zA-Z_][a-zA-Z0-9_]+)}/'
	];

	private $i = 0;
	
	/**
	 *
	 * Constructor
	 *
	 * @param $name
	 *
	 * @return void
	 *
	 */
	public function __construct($name)
	{
		$this->name = $name;
		$this->var = new stdClass;
	}
	
	/** 
	 *
	 * loadFile Method
	 *
	 * @param files 
	 *
	 * @desc this method will load template files and store it into a property as an array
	 *
	 */
	public function loadFile()
	{
		$param = func_get_args();
		try {
			if(count($param) == 1){
				$this->addFile($param[0]);
			} elseif(count($param) > 1){
				foreach($param as $value){
					$this->addFile($value);
				}
			} else {
				throw new DisplayException(DisplayException::UNKNOWN_ERROR);
			}
		}
		catch(DisplayException $e){
			echo $e->getMessage();
			exit();
		}
	}
	
	/** 
	 *
	 * setVar Method
	 *
	 * @param $name
	 * @param $value
	 *
	 * @desc set a value to a variable within the template
	 *
	 */
	public function setVar($name, $value)
	{
		$this->var->$name = $value;
	}
	
	/** 
	 *
	 * display method
	 *
	 * @desc display the template with a key of zero and analyze every template file and interpret the syntax in it
	 *
	 */
	public function display()
	{
		foreach($this->file as $key => $value){
			$this->analyze($key);
		}
		
		foreach($this->var as $name => $value){
			try {
			if(!(gettype($value) == 'array' || gettype($value) == 'integer' || gettype($value) == 'double' || gettype($value) == 'float' || gettype($value) == 'object')){
					if(preg_match('/(?:@template->)([a-zA-Z]+)/', $value, $arr)){
						if(array_key_exists($arr[1], $this->file)){
							$this->var->$name = $this->file[$arr[1]];
						} else {
							throw new DisplayException(DisplayException::FILE_NOT_FOUND, $arr[1]);
						}
					}
				}
			}
			catch(DisplayException $e){
				echo $e->getMessage();
			}
			$this->replaceValue($name);
		}
		return $this->file[array_keys($this->file)[0]];
	}
	
	public function getTemplateName(){
		return $this->name;
	}
	
	/** 
	 *
	 * analyze Method
	 *
	 * @param $key
	 *
	 * @desc analyze a template and interpret the syntax in it
	 *
	 */
	private function analyze($key)
	{
		$group = new \stdClass;
		if(preg_match_all($this->regex[0], $this->file[$key], $group->one, PREG_SET_ORDER)){
			foreach($group->one as $values){
				if(preg_match($this->regex[1], $values[1], $group->two)){
					
					$value[0] = str_replace('$', '', $group->two[2]);
					$value[1] = str_replace('$', '', $group->two[1]);
					
					$text = '';
					foreach($this->var->$value[0] as $values2){
						$text .= str_replace($group->two[1], $values2, $values[2]);
					}
					$this->file[$key] = str_replace($values[2], $text, $this->file[$key]);
					$this->file[$key] = str_replace($values[1], '', $this->file[$key]);
                    $this->file[$key] = str_replace($values[3], '', $this->file[$key]);
				}
			}
		}
	}
	
	/** 
	 *
	 * addFile Method
	 *
	 * @param $filename
	 *
	 * @desc add file to property
	 *
	 */
	private function addFile($filename)
	{
		$file = $this->createDir($filename);
		if($this->templateExists($file)){
			$this->file[$filename] = file_get_contents($file);
			$this->i++;
			$this->filename[] = $filename;
		} else {
			throw new DisplayException(DisplayException::FILE_NOT_FOUND, $file);
		}
	}
	
	/** 
	 *
	 * replaceValue Method
	 *
	 * @param $name
	 *
	 * @desc replace the value in the template
	 *
	 */
	private function replaceValue($name)
	{
		
		/* Check if the property type is not an array or object */
		if(!(gettype($this->var->$name) == 'array' || gettype($this->var->$name) == 'object')){
			$needle = '{' . $name . '}';
			$file_keys = array_keys($this->file);
			for($i = 0; $i < count($this->file); $i++){
				$this->file[$file_keys[$i]] = str_replace($needle, $this->var->$name, $this->file[$file_keys[$i]]);
			}
		}
	}
	
	/** 
	 *
	 * createDir Method
	 *
	 * @param $file
	 *
	 * @desc add a directory to a file 
	 *
	 */
	private function createDir($file)
	{
		$dir = 'template/' . $this->name . '/template-' . $file . '.tpl';
		return $dir;
	}

	/** 
	 *
	 * templateExists Method
	 *
	 * @param $template
	 *
	 * @desc check if template exists
	 *
	 */
	private function templateExists($template)
	{
		if(file_exists($template)){
			return true;
		} else {
			return false;
		}
	}
}