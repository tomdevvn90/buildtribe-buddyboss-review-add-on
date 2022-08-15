<?php

//Backend Review
require_once BBR_REVIEW_PATH . 'backend/meta-box.php';


//Notification backend user need review
add_action( 'admin_menu', 'bbr_change_menu_label' );
function bbr_change_menu_label(){
  global $menu;
  remove_filter('pre_get_users', 'bbr_filter_users_by_order_review');
  $date_year_before = strtotime("-1 year", time());
  $args = array(
   'meta_query' => array(
      'relation' => 'AND',
       array(
           'key' => 'bp_verified_member',
           'value' => 1,
           'compare' => '=='
       ),
       array(
           'key' => 'is_show_bbr',
           'value' => 1,
           'compare' => '=='
       ),
       array(
         'relation' => 'OR',
         array(
             'key' => 'bbr_last_review_date',
             'compare' => 'NOT EXISTS'
         ),
         array(
             'key' => 'bbr_last_review_date',
             'value' =>  $date_year_before,
             'compare' => '<'
         ),
         array(
             'key' => 'bbr_next_review_date',
             'value' => time(),
             'compare' => '<'
         )
       )

   )
 );
 $users2 = get_users($args);
 $counts = count($users2);
 if($counts > 0){
   foreach ($menu as $key => $m) {
     if($menu[$key][0] == 'Users'){
        $menu[$key][0] = 'Users <span class="bbr-notification" title="Next review">'.$counts.'</span>';
     }
   }
 }
 add_filter('pre_get_users', 'bbr_filter_users_by_order_review');
}

//Hook add css admin in head tag
add_action('admin_head' , 'bbr_add_css_backend');
function bbr_add_css_backend(){
  ?>
  <style media="screen">
    #adminmenu .bbr-notification {
        display: inline-block;
        vertical-align: top;
        box-sizing: border-box;
        margin: 1px 0 -1px 2px;
        padding: 0 5px;
        min-width: 18px;
        height: 18px;
        border-radius: 9px;
        background-color: #d63638;
        color: #fff;
        font-size: 11px;
        line-height: 1.6;
        text-align: center;
        z-index: 26;
      }
  </style>
  <?php
}

//Filter for users need review
{
  //1. Add option filter
  add_action('restrict_manage_users', 'bbr_filter_by_review_date');
  function bbr_filter_by_review_date($which)
  {
     // template for filtering
     $st = '<select name="order_review_%s" style="float:none;margin-left:10px;">
        <option value="">%s</option>%s</select>';

     // generate options
     $order_review_top = $_GET['order_review_top'] ? $_GET['order_review_top'] : null;
     $options = '<option value="not" '.($order_review_top == 'not' ? 'selected="selected"' : '' ).'>Not Review</option>
     <option value="reviewed" '.($order_review_top == 'reviewed' ? 'selected="selected"' : '' ).'>Reviewed</option>
     <option value="nextreview" '.($order_review_top == 'nextreview' ? 'selected="selected"' : '' ).'>Next review</option>';

     // combine template and options
     $select = sprintf( $st, $which, __( '-- Order Review --' ), $options );

     // output <select> and submit button
     echo $select;

     submit_button(__( 'Filter' ), null, 'bbr_'.$which, false);
  }

  //Query User
  add_filter('pre_get_users', 'bbr_filter_users_by_order_review');
  function bbr_filter_users_by_order_review($query)
  {

     global $pagenow;
     if (is_admin() && 'users.php' == $pagenow) {
          // figure out which button was clicked. The $which in filter_by_job_role()
          $order_review_top = $_GET['order_review_top'] ? $_GET['order_review_top'] : null;
          if (!empty($order_review_top))
          {
           $v_order_review = !empty($order_review_top) ? $order_review_top : '';

           // change the meta query based on which option was chosen
           if($v_order_review == 'not'){
             $meta_query = array (
               'relation' => 'OR',
               array (
                 'key' => 'is_show_bbr',
                 'compare' => 'NOT EXISTS'
               ),
               array (
                 'key' => 'is_show_bbr',
                 'value' => '',
                 'compare' => '=='
               )
             );
           }

           if($v_order_review == 'reviewed'){
             $meta_query = array (array (
                'key' => 'is_show_bbr',
                'value' => 1,
                'compare' => '=='
             ));
           }

           if($v_order_review == 'nextreview'){
             $date_year_before = strtotime("-1 year", time());
             $meta_query = array (
                'relation' => 'AND',
                 array(
                     'key' => 'is_show_bbr',
                     'value' => 1,
                     'compare' => '=='
                 ),
                 array(
                   'relation' => 'OR',
                   array(
                       'key' => 'bbr_last_review_date',
                       'compare' => 'NOT EXISTS'
                   ),
                   array(
                       'key' => 'bbr_last_review_date',
                       'value' =>  $date_year_before,
                       'compare' => '<'
                   ),
                   array(
                       'key' => 'bbr_next_review_date',
                       'value' => time(),
                       'compare' => '<'
                   )
                 )
             );
           }

           //set query
           $query->set('meta_query', $meta_query);
          }
     }

  }

}

 ?>
