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
 * @subpackage Upgrade
 * @package Installation
 */

/**
 * This is the class upgrade_container2
 */
class upgrade_container2 extends upgradescript {
	protected $from_version = '0.0.0';  // version number lower than first released version, 2.0.0
	protected $to_version = '2.2.0';  // containermodule was upgraded to containerController in 2.2.0
    public $priority = 1; // set this to the highest priority

	/**
	 * name/title of upgrade script
	 * @return string
	 */
	static function name() { return "Update containermodule entries to containerController 2.0"; }

	/**
	 * generic description of upgrade script
	 * @return string
	 */
	function description() { return "Prior to v2.1.1, containers were managed in an 'old school' method.  This script updates container references to the 2.0 type."; }

	/**
	 * additional test(s) to see if upgrade script should be run
	 * @return bool
	 */
	function needed() {
        return true;
	}

	/**
	 * converts references to the containermodule and all old school type references to the new naming scheme (sans Controller)
	 * @return bool
	 */
	function upgrade() {
	    global $db;

        $count = 0;
        foreach ($db->selectObjects('sectionref',"module='containermodule'") as $sr) {
            $sr->module = 'container2';  // containermodule is now container2 controller
            $db->updateObject($sr,'sectionref');
            $count++;
	    }
        foreach ($db->selectObjects('sectionref',"module LIKE '%Controller%'") as $sr) {
            $sr->module = expModules::getModuleName($sr->module);  // convert module name to 2.0 style
            $db->updateObject($sr,'sectionref');
            $count++;
	    }
        foreach ($db->selectObjects('container',"external LIKE '%containermodule%'") as $co) {
            $loc = expUnserialize($co->external);
            $loc->mod = 'container2';  // containermodule is now container2 controller
            $co->external = serialize($loc);
            $co->view_data = null;
            $db->updateObject($co,'container');
            $count++;
	    }
        foreach ($db->selectObjects('container',"external LIKE '%Controller%'") as $co) {
            $loc = expUnserialize($co->external);
            $loc->mod = expModules::getModuleName($loc->mod);  // convert module name to 2.0 style
            $co->external = serialize($loc);
            $co->view_data = null;
            $db->updateObject($co,'container');
            $count++;
	    }
        foreach ($db->selectObjects('container',"internal LIKE '%containermodule%'") as $co) {
            $loc = expUnserialize($co->internal);
            $loc->mod = 'container2';  // containermodule is now container2 controller
            $co->internal = serialize($loc);
            $co->action = 'showall';
            if ($co->view == 'Default') {
                $co->view = 'showall';
            } else {
                $co->view = 'showall_'.$co->view;
            }
            $co->view_data = null;
            $db->updateObject($co,'container');
            $count++;
	    }
        // fix 2.2alpha1/2 not setting action/view
        foreach ($db->selectObjects('container',"internal LIKE '%container2%'") as $co) {
            $co->action = 'showall';
            if (strstr($co->view,'showall') === false) {
                if ($co->view == 'Default') {
                    $co->view = 'showall';
                } else {
                    $co->view = 'showall_'.$co->view;
                }
            }
            $co->view_data = null;
            $db->updateObject($co,'container');
	    }
        foreach ($db->selectObjects('container',"internal LIKE '%Controller%'") as $co) {
            $loc = expUnserialize($co->internal);
            $loc->mod = expModules::getModuleName($loc->mod);  // convert module name to 2.0 style
            $co->internal = serialize($loc);
            $co->view_data = null;
            $db->updateObject($co,'container');
	    }
        // adjust container ranks
        $rank = 1; // 2.0 index starts at 1, not 0 like old school
        $ext = $null = serialize(null);
        foreach ($db->selectObjects('container',null,'external, rank') as $co) {
            if ($co->external != $ext) {
                $rank = 1; // 2.0 index starts at 1, not 0 like old school
                $ext = $co->external;
            }
            if ($co->external != $null) {
                $co->rank = $rank++; // 2.0 index starts at 1, not 0 like old school
            } else {
                $co->rank = 0;  // top level containers have a rank of 0
            }
            $db->updateObject($co,'container');
	    }
        foreach ($db->selectObjects('grouppermission',"module = 'containermodule'") as $gp) {
            $gp->module = 'container2';  // containermodule is now container2 controller
            $db->updateObject($gp,'grouppermission',"module = 'containermodule' AND source = '".$gp->source."' AND permission = '".$gp->permission."'",'gid');
            $count++;
	    }
        foreach ($db->selectObjects('grouppermission',"module LIKE '%Controller%'") as $gp) {
            $old_gp_mod = $gp->module;
            $gp->module = expModules::getModuleName($gp->module);  // convert module name to 2.0 style
            $db->updateObject($gp,'grouppermission',"module = '". $old_gp_mod . "' AND source = '".$gp->source."' AND permission = '".$gp->permission."'",'gid');
            $count++;
	    }
        foreach ($db->selectObjects('userpermission',"modul e= 'containermodule'") as $up) {
            $up->module = 'container2';  // containermodule is now container2 controller
            $db->updateObject($up,'userpermission',"module = 'containermodule' AND source = '".$up->source."' AND permission = '".$up->permission."'",'uid');
            $count++;
	    }
        foreach ($db->selectObjects('userpermission',"module LIKE '%Controller%'") as $up) {
            $old_up_mod = $up->module;
            $up->module = expModules::getModuleName($up->module);  // convert module name to 2.0 style
            $db->updateObject($up,'userpermission',"module = '". $old_up_mod . "' AND source = '".$up->source."' AND permission = '".$up->permission."'",'gid');
            $count++;
	    }
        foreach ($db->selectObjects('modstate',"module = 'containermodule'") as $ms) {
            if (!empty($ms)) {
                $ms->module = 'container2';  // containermodule is now container2 controller
                $db->updateObject($ms,'modstate',"module='containermodule'",'module');
                $count++;
            }
	    }
        foreach ($db->selectObjects('modstate',"module LIKE '%Controller%'") as $ms) {
            if (!empty($ms)) {
                $old_ms_mod = $ms->module;
                $ms->module = expModules::getModuleName($ms->module);  // convert module name to 2.0 style
                $db->updateObject($ms,'modstate',"module='" . $old_ms_mod . "'",'module');
                $count++;
            }
	    }

        // delete old containermodule assoc files (moved or deleted)
        $oldfiles = array (
            'framework/core/definitions/container.php',
            'framework/core/definitions/modstate.php',
            'framework/core/models-1/container.php',
        );
		// check if the old file exists and remove it
        foreach ($oldfiles as $file) {
            if (file_exists(BASE.$file)) {
                unlink(BASE.$file);
            }
        }
		// delete old containermodule folder
        if (expUtil::isReallyWritable(BASE."framework/modules-1/container/")) {
            expFile::removeDirectory(BASE."framework/modules-1/container/");
        }

        return ($count?$count:gt('No')).' '.gt('old containermodule type references updated to container2 type.');
	}
}

?>