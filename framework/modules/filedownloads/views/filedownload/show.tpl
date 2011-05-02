{*
 * Copyright (c) 2007-2011 OIC Group, Inc.
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

<div class="module filedownload show">
	<div class="item">
        {if $record->expFile.preview[0] != ""}
            {img class="preview-img" file_id=$record->expFile.preview[0]->id square=150}
        {/if}
        {if $record->title}<h2>{$record->title}</h2>{/if}
        <span class="label size">File Size:</span>
        <span class="value">{$record->expFile.downloadable[0]->filesize|kilobytes}Kb</span>
        &nbsp;&nbsp;
        <span class="label downloads"># Downloads:</span>
        <span class="value">{$record->downloads}</span>
        <div class="bodycopy">
            {$record->body}
        </div>
        <a class="download" href="{link action=downloadfile fileid=$record->id}">Download</a>
        {clear}
        {permissions}
			<div class="item-actions">
				{if $permissions.edit == 1}
					{icon action=edit record=$record title="Edit this file"|gettext}
				{/if}
				{if $permissions.delete == 1}
					{icon action=delete record=$record title="Delete this file" onclick="return confirm('Are you sure you want to delete this file?');"}
				{/if}
			</div>
        {/permissions}  
        
        {if $config.usescomments == true}
            {comments content_type="filedownload" content_id=$record->id title="Comments"}
        {/if}  
	</div>		
</div>
