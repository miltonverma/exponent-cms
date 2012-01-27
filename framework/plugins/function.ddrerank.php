<?php

##################################################
#
# Copyright (c) 2004-2011 OIC Group, Inc.
# Written and Designed by Phillip Ball
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
 * Smarty plugin
 * @package Smarty-Plugins
 * @subpackage Function
 */

/**
 * Smarty {ddrerank} function plugin
 *
 * Type:     function<br>
 * Name:     ddrerank<br>
 * Purpose:  display item re-ranking popup
 *
 * @param         $params
 * @param \Smarty $smarty
 * @return bool
 */
function smarty_function_ddrerank($params,&$smarty) {
    global $db;
	$loc = $smarty->getTemplateVars('__loc');
	
    $badvals = array("[", "]", ",", " ", "'", "\"", "&", "#", "%", "@", "!", "$", "(", ")", "{", "}");
    $uniqueid = str_replace($badvals, "", $loc->src).$params['id'];
    $controller = !empty($params['controller']) ? $params['controller'] : $loc->mod;
    
    if ($params['sql']) {
        $sql = explode("LIMIT",$params['sql']);
        $params['items'] = $db->selectObjectsBySQL($sql[0]);
    } else {
        if ($params['items'][0]->id) {
            $model = empty($params['model']) ? $params['items'][0]->classname : $params['model'] ;
	        $only = !empty($params['only']) ? ' AND '.$params['only'] : '';
            $obj = new $model();
            $params['items'] = $obj->find('all',"location_data='".serialize($loc)."'".$only,"rank");
        } else {
            $params['items'] = array();
        }
    }
    
    if (count($params['items'])>=2) {
        expCSS::pushToHead(array(
		    //"corecss"=>"rerankpanel,panels",
		    "corecss"=>"rerank,panel",
		    )
		);
        
        $sortfield = empty($params['sortfield']) ? 'title' : $params['sortfield']; //what was this even for?
   
        // attempt to translate the label
        if (!empty($params['label'])) {
            $params['label'] = gt($params['label']);
        }
        echo '<a id="rerank'.$uniqueid.'" class="reranklink" href="#">'.gt("Order").' '.$params['label'].'</a>';

        $html = '
        <div id="panel'.$uniqueid.'" class="exp-skin-panel exp-skin-rerank hide">
            <div class="yui3-widget-hd">Order '.$params['label'].'</div>
            <div class="yui3-widget-bd">
            <form method="post" action="'.URL_FULL.'">
            <input type="hidden" name="model" value="'.$model.'" />
            <input type="hidden" name="controller" value="'.$controller.'" />
            <input type="hidden" name="lastpage" value="'.curPageURL().'" />
            <input type="hidden" name="src" value="'.$loc->src.'" />';
            if (!empty($params['items'])) {
                // we may need to pass through an ID for some reason, like a category ID for products
                $html .= ($params['id']) ? '<input type="hidden" name="id" value="'.$params['id'].'" />' : '';
                $html .= '<input type="hidden" name="action" value="manage_ranks" />
                <ul id="listToOrder'.$uniqueid.'" style="'.((count($params['items']<12))?"":"height:350px").';overflow-y:auto;">
                ';
                $odd = "even";
                foreach ($params['items'] as $item) {
                    $html .= '
                    <li class="'.$odd.'">
                    <input type="hidden" name="rerank[]" value="'.$item->id.'" />
                    <div class="fpdrag"></div>';
        			//Do we include the picture? It depends on if there is one set.
                    $html .= ($item->expFile[0]->id && $item->expFile[0]->is_image) ? '<img class="filepic" src="'.URL_FULL.'thumb.php?id='.$item->expFile[0]->id.'&w=16&h=16&zc=1">' : '';
                    $html .= '<span class="label">'.(!empty($item->$sortfield) ? substr($item->$sortfield, 0, 40) : gt('Untitled')).'</span>
                    </li>';
                    $odd = $odd == "even" ? "odd" : "even";
                }
                $html .='</ul>
                    <div class="yui3-widget-ft">
                    <button type="submit" class="awesome small '.BTN_COLOR.'">'.gt('Save').'</button>
                    </div>
                    </form>
                    </div>
                </div>
                ';
            } else {
                $html .='<strong>'.gt('Nothing to re-rank').'</strong>
            
                    </div>
                </div>
                ';
            }
            
        echo $html;
    
        $script = "
        YUI(EXPONENT.YUI3_CONFIG).use('node','dd','dd-plugin','panel', function(Y) {
            var panel = new Y.Panel({
                srcNode:'#panel".$uniqueid."',
                width        : 500,
                visible      : false,
                zIndex       : 50,
                centered     : false,
                render       : 'body',
                // plugins      : [Y.Plugin.Drag]
            }).plug(Y.Plugin.Drag);
            
            panel.dd.addHandle('.yui3-widget-hd');
            
            var panelContainer = Y.one('#panel".$uniqueid."').get('parentNode');
            panelContainer.addClass('exp-panel-container');
            Y.one('#panel".$uniqueid."').removeClass('hide');
                        
            Y.one('#rerank".$uniqueid."').on('click',function(e){
                e.halt();
                panel.show();
                panel.set('centered',true);
            });

            //Static Vars
            var goingUp = false, lastY = 0;

            // the list
            var ul = '#listToOrder".$uniqueid."';

            //Get the list of li's in the lists and make them draggable
            var lis = Y.Node.all('#listToOrder".$uniqueid." li');
//            lis.each(function(v, k) {
                // var dragItem = new Y.DD.Drag({
                //     node: v,
                //     target: {
                //         padding: '0 0 0 0'
                //     }
                // }).plug(Y.Plugin.DDProxy, {
                //     moveOnEnd: false
                // }).plug(Y.Plugin.DDConstrained, {
                //     constrain2node: ul,
                //     stickY:true
                // }).plug(Y.Plugin.DDNodeScroll, {
                //     node: ul
                // }).addHandle('.fpdrag');

                var dragItems = new Y.DD.Delegate({
                    container: ul,
                    nodes: 'li',
                    target: {
                        padding: '0 0 0 0'
                    }
                })
                
                dragItems.dd.plug(Y.Plugin.DDConstrained, {
                    constrain2node: ul,
                    stickY:true
                }).plug(Y.Plugin.DDProxy, {
                    moveOnEnd: false
                }).plug(Y.Plugin.DDConstrained, {
                    constrain2node: ul,
                    stickY:true
                }).plug(Y.Plugin.DDNodeScroll, {
                    node: ul
                }).addHandle('.fpdrag');

                dragItems.on('drop:over', function(e) {
                    //Get a reference to out drag and drop nodes
                    var drag = e.drag.get('node'),
                        drop = e.drop.get('node');

                    //Are we dropping on a li node?
                    if (drop.get('tagName').toLowerCase() === 'li') {
                        //Are we not going up?
                        if (!goingUp) {
                            drop = drop.get('nextSibling');
                        }
                        //Add the node to this list
                        e.drop.get('node').get('parentNode').insertBefore(drag, drop);
                        //Resize this nodes shim, so we can drop on it later.
                        e.drop.sizeShim();
                    }
                });
                //Listen for all drag:drag events
                dragItems.on('drag:drag', function(e) {
                    //Get the last y point
                    var y = e.target.lastXY[1];
                    //is it greater than the lastY var?
                    if (y < lastY) {
                        //We are going up
                        goingUp = true;
                    } else {
                        //We are going down..
                        goingUp = false;
                    }
                    //Cache for next check
                    lastY = y;
                    Y.DD.DDM.syncActiveShims(true);
                });
                //Listen for all drag:start events
                dragItems.on('drag:start', function(e) {
                    //Get our drag object
                    var drag = e.target;
                    //Set some styles here
                    drag.get('node').setStyle('opacity', '.25');
                    drag.get('dragNode').addClass('rerank-proxy').set('innerHTML', drag.get('node').get('innerHTML'));
                    drag.get('dragNode').setStyles({
                        opacity: '.5'
                        // borderColor: drag.get('node').getStyle('borderColor'),
                        // backgroundColor: drag.get('node').getStyle('backgroundColor')
                    });
                });
                //Listen for a drag:end events
                dragItems.on('drag:end', function(e) {
                    var drag = e.target;
                    //Put out styles back
                    drag.get('node').setStyles({
                        visibility: '',
                        opacity: '1'
                    });
                });
                //Listen for all drag:drophit events
                dragItems.on('drag:drophit', function(e) {
                    var drop = e.drop.get('node'),
                        drag = e.drag.get('node');

                    //if we are not on an li, we must have been dropped on a ul
                    if (drop.get('tagName').toLowerCase() !== 'li') {
                        if (!drop.contains(drag)) {
                            drop.appendChild(drag);
                        }
                    }
                });
//            });

            //Create simple targets for the 2 lists..
            var tar = new Y.DD.Drop({
                node: ul
            });        
        });
        
        ";
        
        if (!expTheme::inPreview()) {
            expJavascript::pushToFoot(array(
                "unique"=>$uniqueid,
                "yui3mods"=>1,
                "content"=>$script,
             ));
        }
        
    }
}

?>