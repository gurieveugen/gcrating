<?php
/*
Plugin Name: GC Ratings for WordPress
Plugin URI: http://www.gurievcreative.com
Description: Ratings for your WordPress posts
Version: 1.0
Author: Guriev Creative
Author URI: http://www.gurievcreative.com
*/
require_once dirname(__FILE__).'/gcbase.php';

class GCRating extends GCBase{	
	//                          __              __      
	//   _________  ____  _____/ /_____ _____  / /______
	//  / ___/ __ \/ __ \/ ___/ __/ __ `/ __ \/ __/ ___/
	// / /__/ /_/ / / / (__  ) /_/ /_/ / / / / /_(__  ) 
	// \___/\____/_/ /_/____/\__/\__,_/_/ /_/\__/____/  
	const LANG_FILE_EN = 'gcrating_en.php';                        

	//                    __  __              __    
	//    ____ ___  ___  / /_/ /_  ____  ____/ /____
	//   / __ `__ \/ _ \/ __/ __ \/ __ \/ __  / ___/
	//  / / / / / /  __/ /_/ / / / /_/ / /_/ (__  ) 
	// /_/ /_/ /_/\___/\__/_/ /_/\____/\__,_/____/  	
	public function __construct()
	{	
		parent::__construct(self::LANG_FILE_EN);
		// =========================================================
		// Add JAVA and CSS
		// =========================================================		
		// =========================================================
		// Fot theme
		// =========================================================
		if(is_admin())
		{			
			wp_enqueue_style('admin-styles', $this->plugin_url.'/css/admin_styles.css');
			wp_enqueue_style('font-awesome', $this->plugin_url.'/css/font-awesome.min.css');
		}
		// =========================================================
		// For Admin
		// =========================================================
		else
		{
			wp_enqueue_script('gcrating', $this->plugin_url.'/js/gcrating.js', array('jquery'));
		}		
		
		add_theme_support('post-thumbnails');		
	}

	public getRateBlock($post_id)
	{
		$p = get_post($post_id);
		?>
		<section class="rate-block">
			<p><?php prinf($this->l['block_title'], $p->post_title); ?></p>
			<div class="cf">
				<ul class="star-rating">
					<li class="active"></li>
					<li class="active"></li>
					<li class="active"></li>
					<li class="active"></li>
					<li></li>
				</ul>
				<button type="button" class="btn"><span>Rate</span></button>
			</div>
		</section>
		<?php
	}
}
// =========================================================
// LAUNCH
// =========================================================
$GLOBALS['gcrating'] = new GCRating();
