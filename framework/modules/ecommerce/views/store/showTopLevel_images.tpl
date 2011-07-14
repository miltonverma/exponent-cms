{*
 * Copyright (c) 2007-2008 OIC Group, Inc.
 * Written and Designed by Adam Kessler
 *
 * This file is part of Exponent
 *
 * Exponent is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * GPL: http://www.gnu.org/licenses/gpl.txt
 *
 *}

{css unique="home" link="`$asset_path`css/storefront.css" corecss="tables"}

{/css}

{css unique="home" link="`$asset_path`css/ecom.css"}

{/css}

<div class="module store showall">
    {if $moduletitle}<h1>{$moduletitle}</h1>{/if}
    <h1>{$current_category->title}</h1>

    {if $current_category->id}
    {permissions}
        {if $permissions.edit == 1}
            {icon class="edit" action=edit module=storeCategory id=$current_category->id title="Edit `$current_category->title`" text="Edit this Store Category"}{br}
        {/if}
        {*if $permissions.manage == 1}
            {icon class="configure" action=configure module=storeCategory id=$current_category->id title="Configure `$current_category->title`" text="Configure this Store Category"}{br}
        {/if*}
        {*if $permissions.manage == 1}
            {icon class="configure" action=configure module=ecomconfig hash="#tab2" title="Configure Categories Globally" text="Configure Categories Globally"}{br}
        {/if*}
        {if $permissions.edit == 1 && $config.orderby=="rank"}
            {ddrerank label="Products" sql=$rerankSQL model="product" controller="storeCategory" id=$current_category->id}
        {/if}
    {/permissions}
    {/if}
    
    <div class="bodycopy">{$current_category->body}</div>

    {if $categories|@count > 0}
    <hr/>
    <div class="cats">
    <h2>Browse Our Store:</h2>
        {counter assign="ipcr" name="ipcr" start=1}
        {foreach name="cats" from=$categories item="cat"}
            {if $cat->is_active==1 || $user->isAdmin()}
            
                {if $smarty.foreach.cats.first || $open_c_row}
                    <div class="category-row">
                    {assign var=open_c_row value=0}
                {/if}
                        
                <div class="cat{if $cat->is_active!=1} inactive{/if} clearfix">
                
                    {permissions}
                    <div class="item-actions">
                        {if $permissions.edit == 1}
                            {icon controller=storeCategory action=edit record=$cat title="Edit `$cat->title`"}
                        {/if}
                        {if $permissions.delete == 1}
                            {icon controller=storeCategory action=delete record=$cat title="Delete `$cat->title`" onclick="return confirm('Are you sure you want to delete this category?');"}
                        {/if}
                    </div>
                    {/permissions}
            
                    <a href="{link controller=store action=showall title=$cat->sef_url}" class="cat-img-link">
                        {if $cat->expFile[0]->id}
                            {img file_id=$cat->expFile[0]->id w=100 class="cat-image"}
                        {else}
                            {img file_id=$cat->getFirstImageId() w=100 class="cat-image"}
                        {/if}
                    </a>
                
                    <h3>
                        <a href="{link controller=store action=showall title=$cat->sef_url}">
                            {$cat->title}
                        </a>
                    </h3>

                </div>

                {if $smarty.foreach.cats.last || $ipcr%2==0}
                    </div>
                    {assign var=open_c_row value=1}
                {/if}
                {counter name="ipcr"}

            {/if}
        {/foreach}

        {* close the row if left open. might happen for non-admins *}
        {if $open_c_row==0}
            </div>
            {assign var=open_c_row value=1}
        {/if}
    </div>
    {else}
    <!--hr/-->
    <h2>All Products Under {$current_category->title}</h2>
        
        
    {$page->links}
    {control type="dropdown" name="sortme" items=$page->sort_dropdown default=$defaultSort}
    
    {script unique="sort-submit"}
    {literal}
    YUI({ base:EXPONENT.URL_FULL+'external/yui3/build/',loadOptional: true}).use('node', function(Y) {
        Y.all('select[name="sortme"]').on('change',function(e){
            window.location = e.target.get('value');
        });

    });
    {/literal}
    {/script}
   
    <div class="products">
        {foreach from=$page->records item=listing name=listings}
        
        {if $smarty.foreach.listings.iteration%3==0}
            {assign var="positioninfo" value=" last-in-row"}
        {else}
            {assign var="positioninfo" value=""}
        {/if}
        
        <div class="product{$positioninfo}">{include file=$listing->getForm('storeListing')}</div>
        
        {if $positioninfo!="" || $smarty.foreach.listings.last==true}
            <div class="break">&nbsp;</div>
        {/if}
        
        {/foreach}
    </div>
    {control type="dropdown" name="sortme" items=$page->sort_dropdown default=$defaultSort}    

    {$page->links}
    
    {permissions level=$smarty.const.UILEVEL_NORMAL}
        {if $permissions.edit == 1}
            {icon class="add" action=create title="Add a new product" text="Add a New Product"}
      {/if}
    {/permissions}
    {/if} 
</div>
