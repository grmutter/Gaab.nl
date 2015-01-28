<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package alexandria
 */
?>
	<div style="border-left: 1px solid #D0D0D0; margin-top: 0px;" id="secondary" class="widget-area" role="complementary">



		<?php do_action( 'alexandria_before_sidebar' ); ?>
<aside style="padding: 30px 5px 5px 15px;">
<div><a style="margin: 0 0 10px 0; padding: 30px 150px 30px 0; display: inline-block; font-size: 1.3em; background: url('http://www.gaab.nl/wp-content/themes/alexandria/images/portfolio-big.png') no-repeat; background-position: 140px 0; color: #FF0000; text-decoration: none;" href="http://www.gaab.nl/index.php/portfolio/"><h3><strong>BEKIJK MIJN PORTFOLIO</strong></h3></a></div>
</aside>
<br>
<aside style="padding: 15px;">
<h3>Designstudio Gaab</h3>
<p>Driekoningenstraat 7, 6828 EL Arnhem</p>

<div><a style="margin: 0 0 10px 0; padding: 6px 12px 6px 35px; display: inline-block; font-size: 14px; background: #000 url('http://www.gaab.nl/wp-content/themes/alexandria/images/phone-icon.png') no-repeat 2px; color: #ffffff; text-decoration: none;" href="callto:0630051833">+31 (0)6 300 51 833</a></div>
<div><a style="margin: 0 0 10px 0; padding: 6px 12px 6px 35px; display: inline-block; font-size: 14px; background: #000 url('http://www.gaab.nl/wp-content/themes/alexandria/images/mail-icon.png') no-repeat 2px; color: #ffffff; text-decoration: none;" href="mailto:info@gaab.nl">info@gaab.nl</a></div>
<div><a style="margin: 0 0 10px 0; padding: 6px 12px 6px 35px; display: inline-block; font-size: 14px; background: #000 url('http://www.gaab.nl/wp-content/themes/alexandria/images/facebook-icon.png') no-repeat 2px; color: #ffffff; text-decoration: none;" href="http://www.facebook.com/DesignstudioGaab" target="_blanc">DesignstudioGaab</a></div>
<div><a style="margin: 0 0 10px 0; padding: 6px 12px 6px 35px; display: inline-block; font-size: 14px; background: #000 url('http://www.gaab.nl/wp-content/themes/alexandria/images/linkedin-icon.png') no-repeat 2px; color: #ffffff; text-decoration: none;" href="http://nl.linkedin.com/pub/gabriël-mutter/63/2a7/737/" target="_blanc">Gabriël Mutter</a></div>
</aside>
</br>
<aside style="padding: 15px;">
<h3>Vind Gaab leuk</h3>

<div class="fb-like-box" data-href="https://www.facebook.com/DesignstudioGaab" data-height="300px" data-colorscheme="light" data-show-faces="true" data-header="false" data-stream="false" data-show-border="false"></div>
</aside>
<!--		<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>

			<aside id="search" class="widget widget_search">
				<?php get_search_form(); ?>
			</aside>

			<aside id="archives" class="widget">
				<h1 class="widget-title"><?php _e( 'Archives', 'alexandria' ); ?></h1>
				<ul>
					<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
				</ul>
			</aside>

			<aside id="meta" class="widget">
				<h1 class="widget-title"><?php _e( 'Meta', 'alexandria' ); ?></h1>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<?php wp_meta(); ?>
				</ul>
			</aside>
-->
		<?php endif; // end sidebar widget area ?>

	</div><!-- #secondary -->
