<?php
class GCRatingsPage{
    //                          __              __      
    //   _________  ____  _____/ /_____ _____  / /______
    //  / ___/ __ \/ __ \/ ___/ __/ __ `/ __ \/ __/ ___/
    // / /__/ /_/ / / / (__  ) /_/ /_/ / / / / /_(__  ) 
    // \___/\____/_/ /_/____/\__/\__,_/_/ /_/\__/____/  
    const PARENT_PAGE = '';
    const LABEL_KEY   = 'ratings';    
    //                    __  __              __    
    //    ____ ___  ___  / /_/ /_  ____  ____/ /____
    //   / __ `__ \/ _ \/ __/ __ \/ __ \/ __  / ___/
    //  / / / / / /  __/ /_/ / / / /_/ / /_/ (__  ) 
    // /_/ /_/ /_/\___/\__/_/ /_/\____/\__,_/____/
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_plugin_page'));  

        add_action('wp_ajax_resetratings', array($this, 'resetRatingsAJAX'));
        add_action('wp_ajax_nopriv_resetratings', array($this, 'resetRatingsAJAX'));    
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        add_menu_page( __('Ratings report'), __('Ratings report'), 'administrator', __FILE__, array($this, 'create_admin_page'), 'favicon.ico');
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>Ratings report</h2>
            <table class="gctable">
                <col width="50"> 
                <col width="500"> 
                <col width="50"> 
                <col width="50">   
                <col width="50"> 
                <thead>                    
                    <tr>
                        <th>#</th>
                        <th><?php _e('Title'); ?></th>
                        <th><?php _e('Rating (SUM)'); ?></th>
                        <th><?php _e('Rating (AVG)'); ?></th>
                        <th><?php _e('Count rates'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $posts = $this->getAllRatings();
                    $i = 1;
                    foreach ($posts as $key => $value) 
                    {
                        $sum = array_sum($value->rate);
                        $cnt = count($value->rate);
                        $avg = $sum/$cnt;
                        ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><a href="<?php echo get_permalink($value->ID); ?>"><?php echo strip_tags($value->post_title); ?></a></td>
                            <td><?php echo $sum; ?></td>
                            <td><?php echo $avg; ?></td>
                            <td><?php echo $cnt; ?></td>
                        </tr>
                        <?php
                    }
                    ?>                         
                </tbody>
            </table>
            <button class="button" type="button" onclick="resetRatings(); return false;">Reset counts</button>
        </div>
        <?php
    }

    /**
     * Get all ratings
     * @return array
     */
    public function getAllRatings()
    {
        $args = array(
            'posts_per_page'   => 1000,
            'offset'           => 0,
            'category'         => '',
            'orderby'          => 'post_date',
            'order'            => 'DESC',
            'include'          => '',
            'exclude'          => '',
            'meta_key'         => '',
            'meta_value'       => '',
            'post_type'        => 'tool',
            'post_status'      => 'publish',
            'suppress_filters' => true,
            'meta_query' => array(
                array(
                    'key'     => 'rate',
                    'value'   => array(''),
                    'compare' => 'NOT IN')));
        $posts = get_posts($args);

        array_map(array($this, 'setRate'), $posts);
        return $posts;
    }

    /**
     * Reset all ratings
     */
    public function resetRatingsAJAX()
    {
        $ratings = $this->getAllRatings();
        array_map(array($this, 'reset'), $ratings);

        echo json_encode(array('empty' => true));
        die();
    }

    /**
     * Delete rate meta from post
     * @param  object $el
     */
    public function reset(&$el)
    {
        delete_post_meta($el->ID, 'rate');
    }

    /**
     * Set rate to post element
     * @param  object $el 
     */
    public function setRate(&$el)
    {
        $el->rate = get_post_meta($el->ID, 'rate', true); 
    }
}
// =========================================================
// LAUNCH
// =========================================================
$GLOBALS['gcrating']['page'] = new GCRatingsPage();