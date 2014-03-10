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

	/**
	 * Get rating HTML block
	 * @param  integer $post_id
	 * @return string
	 */
	public function getRateBlock($post_id)
	{
		$p = get_post($post_id);
		
		$str = '';
		$str.= '<section class="rate-block">';
		$str.= '	<p>'.sprintf($this->l('block_title'), $p->post_title).'</p>';
		$str.= '	<div class="cf">';
		$str.= $this->getStars(4);
		$str.= '		<button type="button" class="btn"><span>'.$this->l('button').'</span></button>';
		$str.= '	</div>';
		$str.= '</section>';

		return $str; 
	}

	/**
	 * Get Stars from rating
	 * @param  integer $rating 
	 * @return string         
	 */
	public function getStars($rating)
	{
		$str = '<ul class="star-rating" id="star-rating" data-star="'.$rating.'">';
		for ($i = 1; $i <= 5; $i++) 
		{ 
			if($i <= $rating) $str.= '<li class="active" data-index="'.$i.'"></li>';
			else $str.= '<li data-index="'.$i.'"></li>';
		}
		$str.= '</ul>';
		return $str;
	}
}
// =========================================================
// LAUNCH
// =========================================================
$GLOBALS['gcrating'] = new GCRating();
