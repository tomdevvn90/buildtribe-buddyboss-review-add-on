<?php

//Template review
add_action( 'bp_profile_header_meta' , 'buildtribe_bp_profile_header_meta' , 999 );
function buildtribe_bp_profile_header_meta(){

  //Get data
  $user_id = bp_displayed_user_id();
  $is_show_bbr = get_user_meta($user_id,'is_show_bbr',true);
  $badge_image_id = get_user_meta($user_id,'badge_image_id',true);
  $bbr_criteria = unserialize(get_user_meta($user_id,'bbr_criteria',true));
  $overall_rating = get_user_meta($user_id,'bbr_overall_rating',true);
  $last_review_date = get_user_meta($user_id,'bbr_last_review_date',true);
  $link_order_review = get_user_meta($user_id,'bbr_link_order_review',true);
  $text_order_review = get_user_meta($user_id,'bbr_text_order_review',true);
  $verified_member = get_user_meta($user_id,'bp_verified_member',true);
  $link_information = get_user_meta($user_id,'bbr_link_information',true);
  $text_information = get_user_meta($user_id,'bbr_text_information',true);

  $enable_review_date = get_user_meta($user_id,'bbr_enable_review_date',true);
  $enable_overall_rating = get_user_meta($user_id,'bbr_enable_overall_rating',true);
  $enable_criteria = get_user_meta($user_id,'bbr_enable_criteria',true);
  $enable_order_review = get_user_meta($user_id,'bbr_enable_order_review',true);

  //Write template review at here.
  if($is_show_bbr && $verified_member):
  ?>
  <div class="bbr-verified-review-info bbr-verified">
      <div class="bbr-container">

          <?php if($badge_image_id): ?>
          <div class="bbr-verified--badge">
            <?php $imag_url = wp_get_attachment_image_url($badge_image_id,'full '); ?>
            <img src="<?php echo $imag_url; ?>" alt="Badge Verified" class="badge-verified">
          </div>
          <?php endif; ?>

          <div class="bbr-verified--infor">
            <?php if($last_review_date && $enable_review_date): ?>
            <div class="bbr-verified--date-review ekit-wid-con">
                <?php if($link_information): ?>
                  <a href="<?php echo $link_information; ?>" class="link-infor <?php echo $text_information ? 'bbr-tooltip' : ''; ?>" <?php echo $text_information ? 'data-tooltip="'.$text_information.'"' : ''; ?>>
                    <span class="glow-btn"><span class="dashicons dashicons-info-outline"></span></span>
                  </a>
                <?php endif; ?>
                <?php echo __('Last Review '); echo date('d/m/Y', $last_review_date); ?>
            </div>
            <?php endif; ?>
            <div class="bbr-verified--content-infor">
              <?php if(!empty($bbr_criteria) && $enable_criteria): ?>
                <div class="bbr-verified--criteria">
                    <div class="bbr-verified--criteria-list">
                        <?php
                          foreach ($bbr_criteria as $key => $criteria) {
                            ?>
                              <div class="bbr-pie">
                                <div class="bbr-pie--circle animate" style="--p:<?php echo $criteria['percent'] ?>;">
                                  <span class="bbr-pie--value"><?php echo $criteria['percent'] ?>%</span>
                                </div>
                                <div class="bbr-pie--text">
                                    <?php echo $criteria['name']; ?>
                                </div>
                              </div>
                            <?php
                          }
                         ?>
                    </div>
                </div>
              <?php endif; ?>

                <?php if($overall_rating && $enable_overall_rating): ?>
                  <div class="bbr-verified--overall-rating bbr-overall-rating">
                     <div class="bbr-overall-rating--number">
                       <?php echo $overall_rating; ?>
                     </div>
                     <div class="bbr-overall-rating--stars">
                       <div class="bbr-rating">
                         <?php for ($x = 1; $x <= 5; $x++) {
                               $width = ($overall_rating) ? (($overall_rating - $x) > 0 ? 100 : (1 - ($x - $overall_rating))*100) : 0;
                               echo '<span class="ico-star"><i class="_inner-star" style="width:'.$width.'%"></i></span>';
                         } ?>
                       </div>
                     </div>
                     <div class="bbr-overall-rating--text">
                       <?php echo __("Out of 5",'bbr-verified') ?>
                     </div>
                  </div>
                <?php endif; ?>

                <?php if($link_order_review && $enable_order_review): ?>
                <div class="bbr-verified--btn">
                    <a href="<?php echo $link_order_review;  ?>" class="bbr-btn"><?php echo $text_order_review; ?></a>
                </div>
                <?php endif; ?>
            </div>
          </div>
      </div>
  </div>
  <?php
  endif;
}

 ?>
