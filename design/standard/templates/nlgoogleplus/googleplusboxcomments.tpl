<div class="gplus-box">
	<img src={"google-plus.png"|ezimage} height="48" width="48" class="googleplus-logo" />
	<div class="feed-title">
		{if $title|ne('')}
			<strong>{$title}</strong>
		{else}
			<strong>{$comments['title']}</strong>
		{/if}
	</div>
	<div class="clr"></div>
		
	<ul class="activities">
	{foreach $comments['items'] as $comment}
		<li>
			
			<span class="author">
				{if is_set($comment['actor']['image']['url'])}
					<img src="{$comment['actor']['image']['url']}" height="58" width="58" />
				{/if}	
				<a href="{$comment['actor']['url']}">{$comment['actor']['displayName']}</a>
			</span>
			
			<span class="date"> - {strtotime($comment['published'])|l10n( 'shortdatetime' )}</span>
			<div class="replies">{$comment['object']['content']}</div>
			<div class="clr"></div>
		</li>
	{/foreach}
	</ul>
</div>