{*
 * Copyright (c) 2007-2011 OIC Group, Inc.
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

{css unique="manage_groups" corecss="tables"}

{/css}

<div class="module users manage">
    <div class="info-header">
        <div class="related-actions">
            {help text="Get Help Managing Users" module="manage-users"}
        </div>
        <h1>{$moduletitle|default:"Manage Users"}</h1>
    </div>
	<p>
        From here, you can create, modify and remove normal user accounts. 
        You will not be able to create, modify or remove administrator accounts (these options will be disabled).
    </p>
	<div class="module-actions">
		{icon class=add module=users action=create text="Create a New User"|gettext title="Create a New User"|gettext alt="Create a New User"|gettext}
	</div>
    {pagelinks paginate=$page top=1}
	<table class="exp-skin-table">
	    <thead>
			<tr>
				{$page->header_columns}
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$page->records item=user name=listings}
			<tr class="{cycle values="odd,even"}">
				<td>{$user->username}</td>
				<td>{$user->firstname}</td>
				<td>{$user->lastname}</td>
				<td>
				{if $user->is_acting_admin == 1}
				    <img src="{$smarty.const.ICON_RELATIVE|cat:'toggle_on.png'}">
				{/if}
				</td>
			    <td>
			        {permissions level=$smarty.const.UILEVEL_PERMISSIONS}
						<div class="item-actions">
							{icon class=edit action=edituser record=$user}
							{icon class="password" action=change_password record=$user title="Change this users password" text="Password"}
							{icon action=delete record=$user title="Delete" onclick="return confirm('Are you sure you want to delete this user?');"}
						</div>
                    {/permissions}
			    </td>
			</tr>
			{foreachelse}
			    <td colspan="{$page->columns|count}">No Data.</td>
			{/foreach}
		</tbody>
	</table>
    {pagelinks paginate=$page bottom=1}
</div>
