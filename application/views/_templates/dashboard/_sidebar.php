<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		<ul class="sidebar-menu" data-widget="tree">
			<li class="header">MAIN MENU</li>
			<!-- Optionally, you can add icons to the links -->
			<?php
			$page = $this->uri->segment(1);
			?>
			<li class="<?= $page === 'arsip' ? "active" : "" ?>">
				<a href="<?= base_url('arsip') ?>" rel="noopener noreferrer">
					<i class="fa fa-file"></i> <span>Arsip</span>
				</a>
			</li>
			<li class="<?= $page === 'about' ? "active" : "" ?>">
				<a href="<?= base_url('about') ?>" rel="noopener noreferrer">
					<i class="fa fa-info-circle"></i> <span>About</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- /.sidebar -->
</aside>