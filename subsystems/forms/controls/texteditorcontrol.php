<?php

##################################################
#
# Copyright (c) 2004-2011 OIC Group, Inc.
# Written and Designed by James Hunt
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################
/** @define "BASE" "../../.." */

if (!defined('EXPONENT')) exit('');

/**
 * Text Editor Control
 *
 * @package Subsystems-Forms
 * @subpackage Control
 */
class texteditorcontrol extends formcontrol {

	var $cols = 45;
	var $rows = 5;
	
	function name() { return "Text Area"; }
	function isSimpleControl() { return true; }
	function getFieldDefinition() {
		return array(
			DB_FIELD_TYPE=>DB_DEF_STRING,
			DB_FIELD_LEN=>10000);
	}
	
	function __construct($default="",$rows = 5,$cols = 38) {
		$this->default = $default;
		$this->rows = $rows;
		$this->cols = $cols;
		$this->required = false;
		$this->maxchars = 0;
	}

	function controlToHTML($name) {
		$html = "<textarea class=\"textarea\" id=\"$name\" name=\"$name\"";
		$html .= " rows=\"" . $this->rows . "\" cols=\"" . $this->cols . "\"";
		if ($this->accesskey != "") $html .= " accesskey=\"" . $this->accesskey . "\"";
		if (!empty($this->class)) $html .= " class=\"" . $this->class . "\"";
		if ($this->tabindex >= 0) $html .= " tabindex=\"" . $this->tabindex . "\"";
		if ($this->maxchars != 0) {
			$html .= " onkeydown=\"if (this.value.length > $this->maxchars ) {this.value = this.value.substr(0, $this->maxchars );}\"";
			$html .= " onkeyup=\"if (this.value.length > $this->maxchars ) {this.value = this.value.substr(0, $this->maxchars );}\"";
		}
		if ($this->disabled) $html .= " disabled";
		if (@$this->required) {
			$html .= ' required="'.rawurlencode($this->default).'" caption="'.rawurlencode($this->caption).'" ';
		}
		$html .= ">";
		$html .= htmlentities($this->default,ENT_COMPAT,LANG_CHARSET);
		$html .= "</textarea>";
		return $html;
	}
	
	function form($object) {
//		if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
		require_once(BASE."subsystems/forms.php");
//		exponent_forms_initialize();
	
		$form = new form();
		
		if (!isset($object->identifier)) {
			$object->identifier = "";
			$object->caption = "";
			$object->default = "";
			$object->rows = 20;
			$object->cols = 60;
			$object->maxchars = 0;
		} 
		
		$i18n = exponent_lang_loadFile('subsystems/forms/controls/texteditorcontrol.php');
		
		$form->register("identifier",$i18n['identifier'],new textcontrol($object->identifier));
		$form->register("caption",$i18n['caption'], new textcontrol($object->caption));
		$form->register("default",$i18n['default'],  new texteditorcontrol($object->default));
		$form->register("rows",$i18n['rows'], new textcontrol($object->rows,4,false,3,"integer"));
		$form->register("cols",$i18n['cols'], new textcontrol($object->cols,4, false,3,"integer"));
		$form->register("submit","",new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		return $form;
	}
	
	function update($values, $object) {
		if ($object == null) $object = new texteditorcontrol();
		if ($values['identifier'] == "") {
			$i18n = exponent_lang_loadFile('subsystems/forms/controls/texteditorcontrol.php');
			$post = $_POST;
			$post['_formError'] = $i18n['id_req'];
			exponent_sessions_set("last_POST",$post);
			return null;
		}
		$object->identifier = $values['identifier'];
		$object->caption = $values['caption'];
		$object->default = $values['default'];
		$object->rows = intval($values['rows']);
		$object->cols = intval($values['cols']);
		$object->maxchars = intval($values['maxchars']);
		$object->required = isset($values['required']);
		
		return $object;
	
	}
	
	static function parseData($original_name,$formvalues,$for_db = false) {
		return str_replace(array("\r\n","\n","\r"),'<br />', htmlspecialchars($formvalues[$original_name])); 
	}
	
}

?>
