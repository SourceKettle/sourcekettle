<div class="well example">
	<div class="row-fluid overview">
		<div class="span4 <?= ($c == 1) ? '':'greyOut' ?>">
			<h3>Tasks</h3>
			<hr>

			<div class="row-fluid">
				<div class="span6">
					<ul class="unstyled">
						<li class="open-tasks"><a href="#">6 - Open Tasks</a></li>

						<li class="closed-tasks"><a href="#">1 - Closed Tasks</a></li>

						<li class="total-tasks"><a href="#">7 - Total Tasks</a></li>

						<li>14.3% complete</li>
					</ul>
					<a href="#">Create a task</a>
				</div>

				<div class="span6">
					<img src="http://0.chart.apis.google.com/chart?chbh=a&amp;chds=a&amp;chs=100x100&amp;chf=bg%2Cs%2C00000000&amp;cht=p&amp;chd=t:6,1&amp;chco=3266cc&amp;" width="75" height="75" alt="">
				</div>
			</div>
		</div>

		<div class="span4 <?= ($c == 2) ? '':'greyOut' ?>">
			<h3>Next Milestone</h3>
			<hr>

			<ul class="unstyled">
				<li><strong><a href="#">Sprint 1</a></strong></li>
				<br>
				<li>Due: 3001-01-01</li>
				<div class="progress progress-striped">
					<div class="bar" style="width: 50%;"></div>
				</div>
				<li><a href="#">Create a milestone</a></li>

			</ul>
		</div>

		<div class="span4 <?= ($c == 3) ? '':'greyOut' ?>">
			<h3>Quick Stats</h3>
			<hr>

			<ul class="unstyled">
				<li><strong><a href="#">2 users</a></strong> are working on this project.</li>

				<li>Last activity was <strong>17 hours, 58 minutes ago</strong>.</li>
			</ul>
		</div>
	</div>
</div>
