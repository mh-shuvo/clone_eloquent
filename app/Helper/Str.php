<?php

namespace App\Helper;

class Str{


	public static function getClassNameFromNamespace($classNamespace){
		return (new \ReflectionClass($classNamespace))->getShortName();
	}

	
	public static function generatePluralFromSingular($singularText){
		return $singularText.'s';
	}



}