<?php
/**
 * Class BBR_Verified_Review_Meta_Box
 *
 * @author  themosaurus
 * @since   1.0.0
 * @package bp-verified-member/admin/meta-box
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'BBR_Verified_Review_Meta_Box' ) ) :
	/**
	 * Class BBR_Verified_Review_Meta_Box.
	 *
	 * @author themosaurus
	 * @package bp-verified-member/admin/meta-box
	 */
	class BBR_Verified_Review_Meta_Box {
		/**
		 * BBR_Verified_Review_Meta_Box constructor.
		 */
		public function __construct() {
			$this->name         = 'bbr_verified_review_meta_box';
			$this->nonce_name   = "{$this->name}_nonce";
			$this->nonce_action = plugin_basename( __FILE__ );

      add_action( 'admin_enqueue_scripts', array( $this, 'bbr_admin_enqueue'  ) );
			add_action( 'bp_members_admin_user_metaboxes', array( $this, 'add_meta_box'  ), 10 );
			add_action( 'profile_update',    array( $this, 'save_meta_box' ), 10 );

		}

    function bbr_admin_enqueue(){
        if(isset($_GET['page']) && $_GET['page'] == 'bp-profile-edit'){
          wp_enqueue_media();
          wp_enqueue_script('admin-bbr', BBR_REVIEW_URI . 'assets/js/admin.js', array('jquery'), BBR_REVIEW_VER , true);
        }
    }

		/**
		 * Add meta box.
		 *
		 * @since 1.0.0
		 */
		public function add_meta_box() {
			add_meta_box(
				$this->name,
				esc_html__( 'Verified Settings', 'bp-verified-member' ),
				array( $this, 'render_meta_box' ), // callback
				get_current_screen()->id
			);
		}


		/**
		 * Render meta box.
		 *
		 * @since 1.0.0
		 */
		public function render_meta_box() {

			if ( ! empty( $_GET['user_id'] ) && is_numeric( $_GET['user_id'] ) ) {
				$user_id = intval( $_GET['user_id'] );
			} else {
				$user_id = get_current_user_id();
			}

			//Get data
			$is_show_bbr = get_user_meta($user_id,'is_show_bbr',true);
			$badge_image_id = get_user_meta($user_id,'badge_image_id',true);
			$bbr_criteria = unserialize(get_user_meta($user_id,'bbr_criteria',true));
			$overall_rating = get_user_meta($user_id,'bbr_overall_rating',true);
			$last_review_date = get_user_meta($user_id,'bbr_last_review_date',true);
			$next_review_date = get_user_meta($user_id,'bbr_next_review_date',true);
			$link_order_review = get_user_meta($user_id,'bbr_link_order_review',true);
			$text_order_review = get_user_meta($user_id,'bbr_text_order_review',true);
			$link_information = get_user_meta($user_id,'bbr_link_information',true);
			$text_information = get_user_meta($user_id,'bbr_text_information',true);

			$enable_review_date = get_user_meta($user_id,'bbr_enable_review_date',true);
			$enable_overall_rating = get_user_meta($user_id,'bbr_enable_overall_rating',true);
			$enable_criteria = get_user_meta($user_id,'bbr_enable_criteria',true);
			$enable_order_review = get_user_meta($user_id,'bbr_enable_order_review',true);

			?>
			<style media="screen">
				.bbr-container-field.badge-field{
					display: flex;
					align-items: center;
				}
				.badge-field .insert-my-media,
				.badge-field .remove-my-media{
					margin-left: 10px;
				}
				.bbr-container-field.badge-criteria .container .insert-my-media{
		      padding: 0 5px;
		      display: inline-flex;
		      max-height: 30px;
		      min-width: 42px;
		      justify-content: center;
		    }
		    .bbr-container-field.badge-criteria .container:not(:last-child){
		      margin-bottom: 5px;
		    }
		    .bbr-add{
		      margin-top: 5px;
		    }
			</style>
			<table class="form-table">
				<tbody>
				<tr class="bbr-field-wrap">
					<th scope="row"><?php esc_html_e( 'Show/Hide this template?', 'bp-verified-member' ); ?></th>
					<td>
							<div class="bbr-container-field bbr-field">
								 <input type='checkbox' class='is_show_bbr' name='is_show_bbr' value="1" <?php checked( $is_show_bbr , 1 ); ?>>
							</div>
					</td>
				</tr>
				<tr class="bbr-field-wrap">
					<th scope="row"><?php esc_html_e( 'Badge Image', 'bp-verified-member' ); ?></th>
					<td>
							<div class="bbr-container-field badge-field">
								<?php
									$text_btn = $badge_image_id ? 'Change Image' : 'Upload Image';
									$imag_url = wp_get_attachment_image_url($badge_image_id,'full ');
								 ?>
								 <?php if($imag_url): ?>
									 <img src="<?php echo $imag_url; ?>" alt="Badge Image" width="100">
								 <?php endif; ?>
								<input type='hidden' class='badge_image' name='badge_image_id' value="<?php echo $badge_image_id; ?>">
								<a href='javascript:;' class='insert-my-media button'><?php echo $text_btn; ?></a>
								<?php if($badge_image_id): ?>
		              <a href='javascript:;' class='remove-my-media button'>Remove</a>
								<?php endif; ?>
							</div>
					</td>
				</tr>
				<tr class="bbr-field-wrap">
					<th scope="row"><?php esc_html_e( 'Review Date', 'bp-verified-member' ); ?></th>
					<td>
							<div class="bbr-container-field overall-rating-field">
								<p>
									<input type="checkbox" name="bbr_enable_review_date" value="1" <?php checked( $enable_review_date , 1 ); ?>> ON/OFF
								</p>
								 <p>
									 Last review:
									 <input type="date" name="bbr_last_review_date" value="<?php echo date('Y-m-d',$last_review_date); ?>" style="width:150px;">
								 </p>
								 <p>
									 Next review:
									 <input type="date" name="bbr_next_review_date" value="<?php echo date('Y-m-d',$next_review_date); ?>" style="width:150px;">
								 </p>
								 <p>
									 <input type="text" name="bbr_link_information" value="<?php echo $link_information; ?>" placeholder="Set link (i)" style="width:300px;">
									 <strong>Note:</strong> Auto remove (i) if not set this link
								 </p>
								 <p>
									 <textarea name="bbr_text_information" rows="3" cols="100"><?php echo $text_information; ?></textarea>
								 </p>
							</div>
					</td>
				</tr>
				<tr class="bbr-field-wrap">
					<th scope="row"><?php esc_html_e( 'Overall Rating', 'bp-verified-member' ); ?></th>
					<td>
							<div class="bbr-container-field overall-rating-field">
									<p>
										<input type="checkbox" name="bbr_enable_overall_rating" value="1" <?php checked( $enable_overall_rating , 1 ); ?>> ON/OFF
									</p>
								 <p><input type="number" name="bbr_overall_rating" value="<?php echo $overall_rating; ?>" placeholder="Only enter 3.0 to 5.0" size="1" maxlength="1" min="3" max="5" step="0.1" style="width:300px;"></p>
							</div>
					</td>
				</tr>
				<tr class="bbr-field-wrap">
					<th scope="row"><?php esc_html_e( 'Add Criteria', 'bp-verified-member' ); ?></th>
					<td>
							<p>
								<input type="checkbox" name="bbr_enable_criteria" value="1" <?php checked( $enable_criteria , 1 ); ?>> ON/OFF
							</p>
							<p>
								<div class="bbr-container-field badge-criteria">
										<?php foreach ($bbr_criteria as $key => $criteria) {
												?>
												<div class="container">
						                <input type='text' placeholder='Name' name='bbr_criteria[<?php echo $key; ?>][name]' class='name' value="<?php echo $criteria['name']; ?>">
						                <input type='text'placeholder='URL' name='bbr_criteria[<?php echo $key; ?>][percent]' class='percent' value="<?php echo $criteria['percent']; ?>">
						                <button type='button' id='remove'>-</button>
						            </div>
												<?php
										} ?>
								</div>
							</p>
							<p>
								<button type='button' id='add' class="bbr-add">+</button>
							</p>
					</td>
				</tr>
				<tr class="bbr-field-wrap">
					<th scope="row"><?php esc_html_e( 'Set Link Order a Review', 'bp-verified-member' ); ?></th>
					<td>
						<p>
							<input type="checkbox" name="bbr_enable_order_review" value="1" <?php checked( $enable_order_review , 1 ); ?>> ON/OFF
						</p>
						<p>
							<div class="bbr-container-field overall-rating-field">
								 <p><input type="text" name="bbr_link_order_review" placeholder="Link Order Review" value="<?php echo $link_order_review; ?>" style="width:400px;"></p>
								 <p><input type="text" name="bbr_text_order_review" placeholder="Text Order Review" value="<?php echo $text_order_review; ?>" style="width:400px;"></p>
							</div>
						</p>
					</td>
				</tr>
				</tbody>
			</table>
			<?php wp_nonce_field( $this->nonce_action, $this->nonce_name );
		}

		/**
		 * Save meta data.
		 *
		 * @since 1.0.0
		 */
		public function save_meta_box() {

			if ( ! empty( $_GET['user_id'] ) && is_numeric( $_GET['user_id'] ) ) {
				$user_id = intval( $_GET['user_id'] );
			} else {
				$user_id = get_current_user_id();
			}

			//Meta field

			if ( isset($_POST[ 'is_show_bbr' ]) ) {
				update_user_meta( $user_id, 'is_show_bbr' , $_POST[ 'is_show_bbr' ] );
			}else{
				update_user_meta( $user_id, 'is_show_bbr' , '' );
			}

			if ( isset($_POST[ 'bbr_enable_review_date' ]) ) {
				update_user_meta( $user_id, 'bbr_enable_review_date' , $_POST[ 'bbr_enable_review_date' ] );
			}else{
				update_user_meta( $user_id, 'bbr_enable_review_date' , '' );
			}

			if ( isset($_POST[ 'bbr_enable_criteria' ]) ) {
				update_user_meta( $user_id, 'bbr_enable_criteria' , $_POST[ 'bbr_enable_criteria' ] );
			}else{
				update_user_meta( $user_id, 'bbr_enable_criteria' , '' );
			}

			if ( isset($_POST[ 'bbr_enable_order_review' ]) ) {
				update_user_meta( $user_id, 'bbr_enable_order_review' , $_POST[ 'bbr_enable_order_review' ] );
			}else{
				update_user_meta( $user_id, 'bbr_enable_order_review' , '' );
			}

			if ( isset($_POST[ 'bbr_enable_overall_rating' ]) ) {
				update_user_meta( $user_id, 'bbr_enable_overall_rating' , $_POST[ 'bbr_enable_overall_rating' ] );
			}else{
				update_user_meta( $user_id, 'bbr_enable_overall_rating' , '' );
			}

			if ( isset($_POST[ 'badge_image_id' ]) ) {
				update_user_meta( $user_id, 'badge_image_id' , $_POST[ 'badge_image_id' ] );
			}

			if ( isset($_POST[ 'bbr_criteria' ]) ) {
				update_user_meta( $user_id, 'bbr_criteria' , serialize($_POST[ 'bbr_criteria' ]) );
			}

			if ( isset($_POST[ 'bbr_overall_rating' ]) ) {
				update_user_meta( $user_id, 'bbr_overall_rating' , $_POST[ 'bbr_overall_rating' ] );
			}

			if ( isset($_POST[ 'bbr_last_review_date' ]) ) {
				update_user_meta( $user_id, 'bbr_last_review_date' , strtotime($_POST[ 'bbr_last_review_date' ]) );
			}

			if ( isset($_POST[ 'bbr_next_review_date' ]) ) {
				update_user_meta( $user_id, 'bbr_next_review_date' , strtotime($_POST[ 'bbr_next_review_date' ]) );
			}

			if ( isset($_POST[ 'bbr_link_order_review' ]) ) {
				update_user_meta( $user_id, 'bbr_link_order_review' , $_POST[ 'bbr_link_order_review' ] );
			}

			if ( isset($_POST[ 'bbr_text_order_review' ]) ) {
				update_user_meta( $user_id, 'bbr_text_order_review' , $_POST[ 'bbr_text_order_review' ] );
			}

			if ( isset($_POST[ 'bbr_link_information' ]) ) {
				update_user_meta( $user_id, 'bbr_link_information' , $_POST[ 'bbr_link_information' ] );
			}

			if ( isset($_POST[ 'bbr_text_information' ]) ) {
				update_user_meta( $user_id, 'bbr_text_information' , $_POST[ 'bbr_text_information' ] );
			}

		}

		/**
		 * Check if meta box can be saved
		 *
		 * @return bool
		 */
		private function can_save() {
			return (
				isset( $_POST['save'] ) &&
				isset( $_POST[ $this->nonce_name ] ) &&
				wp_verify_nonce( $_POST[ $this->nonce_name ], $this->nonce_action )
			);
		}
	}

endif;

return new BBR_Verified_Review_Meta_Box();
