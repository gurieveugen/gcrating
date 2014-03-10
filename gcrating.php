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
		// Hooks
		// =========================================================
		add_action('wp_ajax_rate', array($this, 'rateAJAX'));
		add_action('wp_ajax_nopriv_rate', array($this, 'rateAJAX'));

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
		$p        = get_post($post_id);
		$rate     = get_post_meta($post_id, 'rate', true);
		$rate_val = intval($rate['value']);
		
		$str = '';
		$str.= '<section id="rate-block" class="rate-block" data-id="'.$post_id.'" data-ip="'.$this->getIP().'">';
		$str.= '	<p>'.sprintf($this->l('block_title'), $p->post_title).'</p>';
		$str.= '	<div class="cf">';
		$str.= $this->getStars($rate_val);
		$str.= '		<button type="button" class="btn" id="rate-button"><span>'.$this->l('button').'</span></button>';
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
		$str = '<ul class="star-rating" id="star-rating" data-star="'.$rating.'" data-star-old="'.$rating.'">';
		for ($i = 1; $i <= 5; $i++) 
		{ 
			if($i <= $rating) $str.= '<li class="active" data-index="'.$i.'"></li>';
			else $str.= '<li data-index="'.$i.'"></li>';
		}
		$str.= '</ul>';
		return $str;
	}

	/**
	 * Get IP address visitor
	 * @return string
	 */
	public function getIP() 
	{
	    $ip = $_SERVER['REMOTE_ADDR'];
	 
	    if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
	    {
	        $ip = $_SERVER['HTTP_CLIENT_IP'];
	    } 
	    else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) 
	    {
	        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    }
	 
	    return $ip;
	}

	/**
	 * Rate post
	 */
	public function rateAJAX()
	{	
		$id           = intval($_POST['id']);
		$ip           = $_POST['ip'];
		$rate_val_new = floatval($_POST['rate']);
		$rate         = get_post_meta($id, 'rate', true);
		$rate_val_old = floatval($rate['value']);

		if(!isset($rate['ip']) || !in_array($ip, $rate['ip']))
		{			
			$rate['value'] = ( $rate_val_old + $rate_val_new ) / 2;
			$json['msg']   = 'OK';
			$json['rate']  = $rate['value'];
			$rate['ip'][]  = $ip;

			update_post_meta($id, 'rate', $rate);
		}
		else
		{
			$json['msg']   = 'exist';
		}

		echo json_encode($json);
		die();
	}
}
// =========================================================
// LAUNCH
// =========================================================
$GLOBALS['gcrating'] = new GCRating();
