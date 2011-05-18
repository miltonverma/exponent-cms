<?php

##################################################
#
# Copyright (c) 2004-2011 OIC Group, Inc.
# Written and Designed by Adam Kessler
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

class snippetController extends expController {
	//protected $basemodel_name = '';
//    public $basemodel_name = 'text';
    public $basemodel_name = 'snippet';
	public $useractions = array(
		'showall'=>'Copy and Display Snippet',
		'showall_highlight'=>'Highlight and Display Snippet'
	);
	public $codequality = 'stable';

	function name() { return $this->displayname(); } //for backwards compat with old modules
	function displayname() { return "Code Snippets"; }
	function description() { return "Use this to put snippets of code, i.e. Javascript, embedded video, etc, on your site."; }
	function author() { return "Adam Kessler - OIC Group, Inc"; }
	function hasSources() { return true; }
	function hasViews() { return true; }
	function hasContent() { return true; }
	function supportsWorkflow() { return false; }
	function isSearchable() { return false; }	
	
	public function showall() {
	    expHistory::set('viewable', $this->params);
		$where = $this->aggregateWhereClause();
		$order = 'rank ASC';
//		$items = $this->text->find('all', $where, $order);
		$items = $this->snippet->find('all', $where, $order);
		assign_to_template(array('items'=>$items));
	}	

	public function showall_highlight() {
	    expHistory::set('viewable', $this->params);
		$where = $this->aggregateWhereClause();
		$order = 'rank ASC';
//		$items = $this->text->find('all', $where, $order);
		$items = $this->snippet->find('all', $where, $order);
		foreach ($items as $item) {
			$item->body = highlight_string($item->body, true); 
		}
		assign_to_template(array('items'=>$items));
	}	
}

?>
