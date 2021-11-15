<?php

get_header();

?>
<div class="container mt-5 mb-5 text-center">
	<div class="row justify-content-center">
		<div class="col col-md-9">
			<?php breadcrumbs() ?>

			<img class="mt-5 mb-5" src="<?= get_template_directory_uri() ?>/images/404/404%20Graphic.svg" alt="404" style="width: 100%;">

			<h1 class="display-4 arvo">
				Research is underway.
			</h1>
			<h2 class="arvo" style="font-size: 2rem">
				Let's get you back on track.
			</h2>

			<form method="get" action="/">
				<div class="input-group mt-5 mb-5">
					<input class="form-control" name="s" type="text" placeholder="Tell us more about what you're looking for...">
					<div class="input-group-append">
						<button class="btn btn-primary" type="submit">Search</button>
					</div>
				</div>
			</form>

			We want to hear from you! Please leave comments and feedback here:
			<a href="mailto:grad_web@ucf.edu">grad_web@ucf.edu</a>

		</div>
	</div>
</div>

<?php

get_footer();
