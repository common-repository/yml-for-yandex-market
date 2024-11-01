<?php
/**
 * Traits Vendorcode for variable products
 *
 * @package                 YML for Yandex Market
 * @subpackage              
 * @since                   0.1.0
 * 
 * @version                 4.7.2 (16-09-2023)
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

trait YFYM_T_Variable_Get_Vendorcode {
	/**
	 * Get `vendorcode` tag
	 * 
	 * @param string $tag_name - Optional
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	public function get_vendorcode( $tag_name = 'vendorCode', $result_xml = '' ) {
		$tag_value = '';

		$yfym_vendorcode = common_option_get( 'yfym_vendorcode', false, $this->get_feed_id(), 'yfym' );
		switch ( $yfym_vendorcode ) { /* disabled, sku, post_meta, germanized, id */
			case "disabled": // выгружать штрихкод нет нужды		
				break;
			case "sku": // выгружать из артикула
				$tag_value = $this->get_offer()->get_sku();
				if ( empty( $tag_value ) ) {
					$tag_value = $this->get_product()->get_sku();
				}
				break;
			default:
				$tag_value = apply_filters(
					'y4ym_f_variable_tag_value_switch_barcode',
					$tag_value,
					[ 
						'product' => $this->get_product(),
						'offer' => $this->get_offer(),
						'switch_value' => $yfym_vendorcode
					],
					$this->get_feed_id()
				);
				if ( $tag_value == '' ) {
					$yfym_vendorcode = (int) $yfym_vendorcode;
					$tag_value = $this->get_offer()->get_attribute( wc_attribute_taxonomy_name_by_id( $yfym_vendorcode ) );
					if ( empty( $tag_value ) ) {
						$tag_value = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $yfym_vendorcode ) );
					}
				}
		}

		$tag_value = apply_filters(
			'y4ym_f_variable_tag_value_vendorcode',
			$tag_value,
			[ 
				'product' => $this->get_product(),
				'offer' => $this->get_offer()
			],
			$this->get_feed_id()
		);
		if ( ! empty( $tag_value ) ) {
			$tag_name = apply_filters(
				'y4ym_f_variable_tag_name_vendorcode',
				$tag_name,
				[ 
					'product' => $this->get_product(),
					'offer' => $this->get_offer()
				],
				$this->get_feed_id()
			);
			// ! обернул $tag_value в htmlspecialchars т.к у нас могут быть амперсанды
			$result_xml = new Get_Paired_Tag( $tag_name, htmlspecialchars( $tag_value ) );
		}

		$result_xml = apply_filters(
			'y4ym_f_variable_tag_vendorcode',
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