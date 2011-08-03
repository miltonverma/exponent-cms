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
/** @define "BASE" "../../../../.." */

if (!defined('EXPONENT')) exit('');

/**
 * Radio Button Control class
 *
 * An HTML Radio Button
 *
 * @package Subsystems-Forms
 * @subpackage Control
 */
class radiocontrol extends formcontrol {

    var $flip = false;
    var $nowrap = '';
    
    function name() { return "Radio Button"; }
    
    function __construct($default = false, $value = "", $groupname="radiogroup", $flip=false, $onclick="") {
        $this->default = $default;
        //$this->id = isset($this->id) ? $this->id : $this->name;
        $this->groupname = $groupname;
        $this->value = $value;
        $this->flip = $flip;
        $this->onclick = $onclick;
    }

    function toHTML($label,$name) {
        if (!empty($this->id)) {
		    $divID  = ' id="'.$this->id.'Control"';
		    $for = ' for="'.$this->id.'"';
		} else {
		    $divID  = '';
		    $for = ' for="'.$name.'"';
		}
        $html = '<div'.$divID.' class="radio control';
        $html .= (!empty($this->required)) ? ' required">' : '">';
        $html .= "<table border=0 cellpadding=0 cellspacing=0><tr>";

        if(empty($this->flip)){
            $html .= "<td class=\"input\">";
            $html .= isset($this->newschool) ? $this->controlToHTML_newschool($name, $label) : $this->controlToHTML($name);
            $html .="</td>";
            $html .= "<td><label".$for." class='label ".$this->nowrap."'>".$label."</label></td>";
        } else {
            $html .= "<td><label".$for." class='label ".$this->nowrap."'>".$label."</label></td>";
            $html .= "<td class=\"input\">";
            $html .= isset($this->newschool) ? $this->controlToHTML_newschool($name, $label) : $this->controlToHTML($name);
            $html .="</td>";
        }
        $html .= "</tr></table></div>";

        return $html;
    }
    
    function controlToHTML($name) {
        //echo "Here";
        $html = '<input class="radiobutton" type="radio" value="'.$this->value .'" name="' . $this->groupname . '"';
        if ($this->default) $html .= ' checked="checked"';
        if ($this->onclick != "") {
            $html .= ' onclick="'.$this->onclick.'"';
        }
        $html .= ' />';
        return $html;
    }
    
    function controlToHTML_newschool($name, $label) {
        $inputID  = (!empty($this->id)) ? ' id="'.$this->id.'"' : "";
        $this->name = empty($this->name) ? $name : $this->name;
        $this->id = empty($this->id) ? $name : $this->id;
        $html = '<input'.$inputID.' type="radio" name="' . $this->name . '" value="'.$this->value.'"';
        if (!empty($this->size)) $html .= ' size="' . $this->size . '"';
        if (!empty($this->checked)) $html .= ' checked="checked"';
        $this->class = !empty($this->class) ? $this->class : "";
        $html .= ' class="radio ' . $this->class . '"';
        if ($this->tabindex >= 0) $html .= ' tabindex="' . $this->tabindex . '"';
        if ($this->accesskey != "") $html .= ' accesskey="' . $this->accesskey . '"';
        if (!empty($this->filter)) {
            $html .= " onkeypress=\"return ".$this->filter."_filter.on_key_press(this, event);\" ";
            $html .= "onblur=\"".$this->filter."_filter.onblur(this);\" ";
            $html .= "onfocus=\"".$this->filter."_filter.onfocus(this);\" ";
            $html .= "onpaste=\"return ".$this->filter."_filter.onpaste(this, event);\" ";
        }
        if ($this->disabled) $html .= ' disabled';
        
        if (!empty($this->readonly)) $html .= ' readonly="readonly"';

        $caption = isset($this->caption) ? $this->caption : str_replace(array(":","*"), "", ucwords($label));
        if (!empty($this->required)) $html .= ' required="'.rawurlencode($this->default).'" caption="'.$caption.'" ';
        if (!empty($this->onclick)) $html .= ' onclick="'.$this->onclick.'" ';
        if (!empty($this->onchange)) $html .= ' onchange="'.$this->onchange.'" ';

        $html .= ' />';
        return $html;
    }
    
    function form($object) {
//        if (!defined("SYS_FORMS")) require_once(BASE."framework/core/subsystems-1/forms.php");
        require_once(BASE."framework/core/subsystems-1/forms.php");
//        exponent_forms_initialize();
    
        $form = new form();
        if (!isset($object->identifier)) {
            $object->identifier = "";
            $object->groupname = "";
            $object->caption = "";
            $object->default = false;
            $object->flip = false;
        } 
        $i18n = exponent_lang_loadFile('subsystems/forms/controls/radiocontrol.php');
        
        $form->register("groupname",$i18n['groupname'],new textcontrol($object->groupname));
        $form->register("caption",$i18n['caption'], new textcontrol($object->caption));
        $form->register("default",$i18n['default'], new checkboxcontrol($object->default,false));
        $form->register("flip",$i18n['flip'], new checkboxcontrol($object->flip,false));
        
        $form->register("submit","",new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
        
        return $form;
    }
    
    function update($values, $object) {
        if ($object == null) $object = new radiocontrol();
        if ($values['groupname'] == "") {
            $i18n = exponent_lang_loadFile('subsystems/forms/controls/radiocontrol.php');
            $post = $_POST;
            $post['_formError'] = $i18n['groupname_req'];
            exponent_sessions_set("last_POST",$post);
            return null;
        }
        $object->identifier = uniqid("");
        $object->groupname = $values['groupname'];
        $object->caption = $values['caption'];
        $object->default = isset($values['default']);
        $object->flip = isset($values['flip']);
        return $object;
    }
}

?>
