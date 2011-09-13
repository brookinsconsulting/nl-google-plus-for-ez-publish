<div class="gplus-box">
	{if is_set($items[0]['actor']['image']['url'])}	
		<img src="{concat($items[0]['actor']['image']['url'],'?sz=48')}" height="48" width="48" class="feed-author" />
	{/if}
	
	<img src={"google-plus.png"|ezimage} height="48" width="48" class="googleplus-logo" />
	<div class="feed-title"><strong>{$activities['title']}</strong></div>
	<div class="clr"></div>
		
	<ul class="activities">
	{foreach $activities['items'] as $activity}
		<li>
			{if is_set($activity['object']['attachments'])}
				{foreach $activity['object']['attachments'] as $attachment}
					{if $attachment['objectType']|eq('photo')}
						<img src="{$attachment['image']['url']}" height="{$attachment['image']['height']}" width="{$attachment['image']['width']}" />
						{break}
					{/if}	
				{/foreach}
			{/if}
			<div class="title">
				<a href="{$activity['object']['url']}">{$activity['title']}</a>
			</div>
			<span class="author">
				<a href="{$activity['actor']['url']}">{$activity['actor']['displayName']}</a>
			</span>
			
			<span class="date"> - {*date('d/m/Y h:i',strtotime($activity['published']))*}</span>
			
			<div class="replies">{$activity['object']['replies']['totalItems']} {if $activity['object']['replies']['totalItems']|gt(1)}replies{else}reply{/if}</div>
			<div class="resharers">{$activity['object']['resharers']['totalItems']} {if $activity['object']['resharers']['totalItems']|gt(1)}reshares{else}reshare{/if}</div>
			<div class="plusoners"><g:plusone size="small" href="{$activity['object']['url']}"></g:plusone></div>
			<div class="clr"></div>
		</li>
	{/foreach}
	</ul>
</div>