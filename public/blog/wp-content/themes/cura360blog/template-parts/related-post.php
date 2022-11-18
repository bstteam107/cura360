<?php
$related = new WP_Query(
	array(
		'category__in'   => wp_get_post_categories($post->ID),
		'posts_per_page' => 3,
		'post__not_in'   => array($post->ID)
	)
);
?>
<div class="sidebar">
	<div class="sidebar_top"></div>
	<div class="sidebar_item">
		<h3>Related Posts</h3>
		<nav aria-label="Recent Posts">
			<ul>
				<?php if ($related->have_posts()) {
					while ($related->have_posts()) {
						$related->the_post();
				?>
						<li>
							<a href="<?php the_permalink(); ?>" aria-current="page"><?php the_title(); ?></a>
							<span class="post-date"><?php the_date('M d, Y');?></span>
						</li>
				<?php
					}
					wp_reset_postdata();
				} ?>
			</ul>

		</nav>
	</div>
	<div class="sidebar_base"></div>
</div>
</div>