<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly
}

/**
 * Handle WC Product
 * 
 * @class SUMO_Subscription_WC_Product
 */
class SUMO_Subscription_WC_Product {

	public $product ;

	public function __construct( $product ) {
		if ( is_numeric( $product ) ) {
			$this->product    = wc_get_product( $product ) ;
			$this->product_id = absint( $product ) ;
		} elseif ( $product instanceof WC_Product ) {
			$this->product    = $product ;
			$this->product_id = $this->get_id() ;
		}
	}

	public function get_id() {
		return $this->product->get_id() ;
	}

	public function get_parent_id() {
		return $this->product->get_parent_id() ;
	}

	public function get_type() {
		return $this->product->get_type() ;
	}

	public function get_price() {
		return $this->product->get_price() ;
	}
	
	public function is_downloadable() {
		return $this->product->is_downloadable() ;
	}

	public function is_virtual() {
		return $this->product->is_virtual() ;
	}

	public function exists() {
		return $this->product ? true : false ;
	}

}
