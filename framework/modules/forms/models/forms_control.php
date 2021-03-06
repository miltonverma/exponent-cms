<?php

##################################################
#
# Copyright (c) 2004-2013 OIC Group, Inc.
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

/**
 * @subpackage Models
 * @package    Modules
 */

class forms_control extends expRecord {
//	public $table = 'text';
//    public $has_one = array(
//        'forms'
//    );
    public $default_sort_field = 'rank';


//    protected $attachable_item_types = array(
//        'content_expFiles'=>'expFile'
//    );

#	public $validates = array(
#		'presence_of'=>array(
#			'body'=>array('message'=>'Body is a required field.'),
#		));

    /**
     * __construct forms_control item...needs special grouping we can have duplicates across modules

     * @param array $params
     */
    public function __construct($params=array()) {
        parent::__construct($params);
        $this->grouping_sql = " AND forms_id='".$this->forms_id."'";
    }

    /**
     * beforeValidation we can have duplicate forms_control across modules
     */
    public function beforeValidation() {
        $this->grouping_sql = " AND forms_id='".$this->forms_id."'";
        parent::beforeValidation();
    }

    public function beforeSave() {
        $this->grouping_sql = " AND forms_id='".$this->forms_id."'";
        parent::beforeSave();
    }

}

?>