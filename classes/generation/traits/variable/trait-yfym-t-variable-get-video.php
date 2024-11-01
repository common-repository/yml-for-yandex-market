<?php
/**
 * Traits Video for variable products
 *
 * @package                 YML for Yandex Market
 * @subpackage              
 * @since                   0.1.0
 * 
 * @version                 4.1.0 (22-11-2023)
 * @author                  Maxim Glazunov
 * @link                    https://icopydoc.ru/
 * @see                     
 *
 * @depends                 classes:    Get_Paired_Tag
 *                          traits:     
 *                          methods:    get_product
 *                                      get_offer
 *                                      get_feed_id
 *                          functions:  common_option_get
 *                          constants:  
 */
defined( 'ABSPATH' ) || exit;

trait YFYM_T_Variable_Get_Video {
	/**
	 * Summary of get_video
	 * 
	 * @param string $tag_name - Optional
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	public function get_video( $tag_name = 'video', $result_xml = '' ) {
		$tag_value = '';

		$yfym_video = common_option_get( 'yfym_video', false, $this->get_feed_id(), 'yfym' );
		if ( ( get_post_meta( $this->get_product()->get_id(), '_yfym_video_url', true ) !== '' )
			&& ( $yfym_video === 'enabled' ) ) {
			$tag_value = get_post_meta( $this->get_product()->get_id(), '_yfym_video_url', true );
		}

		$tag_value = apply_filters(
			'y4ym_f_variable_tag_value_video',
			$tag_value,
			[ 
				'product' => $this->get_product(),
				'offer' => $this->get_offer()
			],
			$this->get_feed_id()
		);
		if ( $tag_value !== '' ) {
			$tag_name = apply_filters(
				'y4ym_f_variable_tag_name_video',
				$tag_name,
				[ 
					'product' => $this->get_product(),
					'offer' => $this->get_offer()
				],
				$this->get_feed_id()
			);
			$result_xml = new Get_Paired_Tag( $tag_name, htmlspecialchars( $tag_value ) );
		}

		$result_xml = apply_filters(
			'y4ym_f_variable_tag_video',
			$result_xml,
			[ 
				'product' => $this->get_product(),
				'offer' => $this->get_offer()
			],
			$this->get_feed_id()
		);
		return $result_xml;
	}
}