
<span class="well span6 offset3">
	<h3><?=h($story['Story']['subject'])?></h3>
	<p>
	<? if ($story['Story']['as-a']) {?>
		<ul>
		<li><?=__("<strong>As a:</strong> %s", $story['Story']['as-a'])?></li>
		<li><?=__("<strong>I want:</strong> %s", $story['Story']['i-want'])?></li>
		<li><?=__("<strong>So that:</strong> %s", $story['Story']['so-that'])?></li>
		</ul>
	<? } else { ?>
		<?=h($story['Story']['description'])?>
	<? } ?>
	</p>
</span>
