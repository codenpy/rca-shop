<?php
/**
 * Global Shop Discount for WooCommerce - Group Section Settings
 *
 * @version 1.4.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Global_Shop_Discount_Settings_Group' ) ) :

class Alg_WC_Global_Shop_Discount_Settings_Group extends Alg_WC_Global_Shop_Discount_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 */
	function __construct( $id ) {
		$admin_titles   = get_option( 'alg_wc_global_shop_discount_admin_title', array() );
		$this->id       = 'group_' . $id;
		$this->desc     = ( isset( $admin_titles[ $id ] ) && '' !== $admin_titles[ $id ] ? $admin_titles[ $id ] : sprintf( __( 'Discount Group #%d', 'global-shop-discount-for-woocommerce' ), $id ) );
		$this->group_nr = $id;
		parent::__construct();
	}

	/**
	 * maybe_convert_and_update_option_value.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function maybe_convert_and_update_option_value( $is_multiselect, $options ) {
		foreach ( $options as $option ) {
			$value = get_option( $option, array() );
			foreach ( $value as $k => &$v ) {
				if ( ! $is_multiselect ) {
					if ( is_array( $v ) ) {
						$v = implode( ',', $v );
					}
				} else {
					if ( is_string( $v ) ) {
						$v = $this->convert_string_to_array( $v );
					}
				}
			}
			update_option( $option, $value );
		}
	}

	/**
	 * convert_string_to_array.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function convert_string_to_array( $value ) {
		return ( '' === $value ? array() : array_map( 'trim', explode( ',', $value ) ) );
	}

	/**
	 * get_settings_as_multiselect_or_text.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function get_settings_as_multiselect_or_text( $values, $multiselect_options, $is_multiselect ) {
		$prev_desc = ( isset( $values['desc'] ) ? $values['desc'] . ' ' : '' );
		return ( $is_multiselect ?
			array_merge( $values, array(
				'type'     => 'multiselect',
				'default'  => array(),
				'class'    => 'chosen_select',
				'options'  => $multiselect_options,
			) ) :
			array_merge( $values, array(
				'type'     => 'text',
				'default'  => '',
				'class'    => 'widefat',
				'desc'     => $prev_desc . __( 'Enter comma separated list of IDs.', 'global-shop-discount-for-woocommerce' ),
			) )
		);
	}

	/**
	 * get_terms.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function get_terms( $args ) {
		if ( ! is_array( $args ) ) {
			$_taxonomy = $args;
			$args = array(
				'taxonomy'   => $_taxonomy,
				'orderby'    => 'name',
				'hide_empty' => false,
			);
		}
		global $wp_version;
		if ( version_compare( $wp_version, '4.5.0', '>=' ) ) {
			$_terms = get_terms( $args );
		} else {
			$_taxonomy = $args['taxonomy'];
			unset( $args['taxonomy'] );
			$_terms = get_terms( $_taxonomy, $args );
		}
		$_terms_options = array();
		if ( ! empty( $_terms ) && ! is_wp_error( $_terms ) ){
			foreach ( $_terms as $_term ) {
				$_terms_options[ $_term->term_id ] = $_term->name . ' (#' . $_term->term_id . ')';
			}
		}
		return $_terms_options;
	}

	/**
	 * get_products.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 *
	 * @todo    [later] use `wc_get_products()`
	 */
	function get_products( $products = array(), $post_status = 'any', $block_size = 1024, $add_variations = false ) {
		$offset = 0;
		while( true ) {
			$args = array(
				'post_type'      => 'product',
				'post_status'    => $post_status,
				'posts_per_page' => $block_size,
				'offset'         => $offset,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'fields'         => 'ids',
			);
			$loop = new WP_Query( $args );
			if ( ! $loop->have_posts() ) {
				break;
			}
			foreach ( $loop->posts as $post_id ) {
				$products[ $post_id ] = get_the_title( $post_id ) . ' (#' . $post_id . ')';
				if ( $add_variations ) {
					$_product = wc_get_product( $post_id );
					if ( $_product->is_type( 'variable' ) ) {
						foreach ( $_product->get_children() as $child_id ) {
							$products[ $child_id ] = get_the_title( $child_id ) . ' (#' . $child_id . ')';
						}
					}
				}
			}
			$offset += $block_size;
		}
		return $products;
	}

	/**
	 * is_multiselect.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function is_multiselect() {
		return ( 'yes' === get_option( 'alg_wc_global_shop_discount_multiselect_options', 'yes' ) );
	}

	/**
	 * maybe_add_current_values.
	 *
	 * @version 1.2.1
	 * @since   1.2.1
	 *
	 * @todo    [maybe] better title, e.g. `get_the_title()` etc.?
	 */
	function maybe_add_current_values( $values, $option_id, $title ) {
		if ( is_array( $values ) ) {
			$current_values = get_option( $option_id, array() );
			if ( ! empty( $current_values[ $this->group_nr ] ) && is_array( $current_values[ $this->group_nr ] ) ) {
				$_current_values = array();
				foreach ( $current_values[ $this->group_nr ] as $value ) {
					$_current_values[ $value ] = $title . ' #' . $value;
				}
				$values = array_replace( $_current_values, $values );
			}
		}
		return $values;
	}

	/**
	 * get_settings.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 *
	 * @todo    [next] add `alg_wc_global_shop_discount_start_date` and `alg_wc_global_shop_discount_end_date`
	 * @todo    [next] better multi-language (i.e. `get_products()` and `get_terms()` for all languages, instead of `is_multiselect` solution)? See e.g.:
	 *          https://wpml.org/forums/topic/how-to-filter-get_terms-function-my-own-language/
	 *          https://wpml.org/forums/topic/get-all-terms-of-all-languages-outside-loop/
	 *          https://polylang.pro/doc/developpers-how-to/
	 * @todo    [maybe] (desc) Admin title: better desc?
	 * @todo    [maybe] (desc) `alg_wc_global_shop_discount_dates_incl`: better desc and notes?
	 * @todo    [maybe] add `alg_wc_global_shop_discount_dates_excl`?
	 */
	function get_settings() {

		$is_multiselect = $this->is_multiselect();
		$i              = $this->group_nr;

		$settings = array(
			array(
				'title'    => $this->desc,
				'type'     => 'title',
				'id'       => "alg_wc_global_shop_discount_general_options_{$i}",
			),
			array(
				'title'    => __( 'Enabled', 'global-shop-discount-for-woocommerce' ),
				'desc_tip' => sprintf( __( 'Enabled/disables discount group #%d.', 'global-shop-discount-for-woocommerce' ), $i ),
				'desc'     => '<strong>' . __( 'Enable', 'global-shop-discount-for-woocommerce' ) . '</strong>',
				'id'       => "alg_wc_global_shop_discount_enabled[{$i}]",
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Admin title (optional)', 'global-shop-discount-for-woocommerce' ),
				'desc_tip' => __( 'Visible only to admin.', 'global-shop-discount-for-woocommerce' ),
				'id'       => "alg_wc_global_shop_discount_admin_title[{$i}]",
				'default'  => sprintf( __( 'Discount Group #%d', 'global-shop-discount-for-woocommerce' ), $i ),
				'type'     => 'text',
			),
			array(
				'title'    => __( 'Type', 'global-shop-discount-for-woocommerce' ),
				'desc_tip' => __( 'Can be fixed or percent.', 'global-shop-discount-for-woocommerce' ),
				'id'       => "alg_wc_global_shop_discount_coefficient_type[{$i}]",
				'default'  => 'percent',
				'type'     => 'select',
				'class'    => 'chosen_select',
				'options'  => array(
					'percent' => __( 'Percent', 'global-shop-discount-for-woocommerce' ),
					'fixed'   => __( 'Fixed', 'global-shop-discount-for-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Value', 'global-shop-discount-for-woocommerce' ),
				'desc_tip' => __( 'Must be negative number.', 'global-shop-discount-for-woocommerce' ),
				'id'       => "alg_wc_global_shop_discount_coefficient[{$i}]",
				'default'  => 0,
				'type'     => 'number',
				'custom_attributes' => array( 'max' => 0, 'step' => 0.0001 ),
			),
			array(
				'title'    => __( 'Date(s)', 'global-shop-discount-for-woocommerce' ),
				'desc'     => '<a href="https://wpfactory.com/item/global-shop-discount-for-woocommerce/#section-date-format-and-examples" target="_blank">' .
						__( 'Accepted date format and examples', 'global-shop-discount-for-woocommerce' ) . '</a>.' . ' ' .
					sprintf( __( 'Current date: %s', 'global-shop-discount-for-woocommerce' ),
						'<code>' . date( 'd.m.Y H:i:s', current_time( 'timestamp' ) ) . '</code>' ),
				'desc_tip' => __( 'Set active date(s) for the current discount group.', 'global-shop-discount-for-woocommerce' ) . ' ' .
					__( 'Ignored if empty.', 'global-shop-discount-for-woocommerce' ),
				'id'       => "alg_wc_global_shop_discount_dates_incl[{$i}]",
				'default'  => '',
				'type'     => 'text',
			),
			array(
				'title'    => __( 'Product scope', 'global-shop-discount-for-woocommerce' ),
				'desc_tip' => __( 'Possible values: all products, only products that are already on sale, only products that are not on sale.', 'global-shop-discount-for-woocommerce' ),
				'id'       => "alg_wc_global_shop_discount_product_scope[{$i}]",
				'default'  => 'all',
				'type'     => 'select',
				'class'    => 'chosen_select',
				'options'  => array(
					'all'              => __( 'All products', 'global-shop-discount-for-woocommerce' ),
					'only_on_sale'     => __( 'Only products that are already on sale', 'global-shop-discount-for-woocommerce' ),
					'only_not_on_sale' => __( 'Only products that are not on sale', 'global-shop-discount-for-woocommerce' ),
				),
			),
			array(
				'type'     => 'sectionend',
				'id'       => "alg_wc_global_shop_discount_general_options_{$i}",
			),
		);

		$products = ( $is_multiselect ? $this->get_products() : false );
		$this->maybe_convert_and_update_option_value( $is_multiselect, array( 'alg_wc_global_shop_discount_products_incl', 'alg_wc_global_shop_discount_products_excl' ) );
		$settings = array_merge( $settings, array(
			array(
				'title'    => __( 'Products', 'global-shop-discount-for-woocommerce' ),
				'type'     => 'title',
				'id'       => "alg_wc_global_shop_discount_products_options_{$i}",
			),
			$this->get_settings_as_multiselect_or_text(
				array(
					'title'    => __( 'Include', 'global-shop-discount-for-woocommerce' ),
					'desc_tip' => __( 'Set this field to apply discount to selected products only. Leave blank to apply to all products.', 'global-shop-discount-for-woocommerce' ),
					'id'       => "alg_wc_global_shop_discount_products_incl[{$i}]",
				),
				$this->maybe_add_current_values( $products, 'alg_wc_global_shop_discount_products_incl', __( 'Product', 'global-shop-discount-for-woocommerce' ) ),
				$is_multiselect
			),
			$this->get_settings_as_multiselect_or_text(
				array(
					'title'    => __( 'Exclude', 'global-shop-discount-for-woocommerce' ),
					'desc_tip' => __( 'Set this field to NOT apply discount to selected products. Leave blank to apply to all products.', 'global-shop-discount-for-woocommerce' ),
					'id'       => "alg_wc_global_shop_discount_products_excl[{$i}]",
				),
				$this->maybe_add_current_values( $products, 'alg_wc_global_shop_discount_products_excl', __( 'Product', 'global-shop-discount-for-woocommerce' ) ),
				$is_multiselect
			),
			array(
				'type'     => 'sectionend',
				'id'       => "alg_wc_global_shop_discount_products_options_{$i}",
			),
		) );

		$all_taxonomies = array_combine( get_object_taxonomies( 'product', 'names' ), wp_list_pluck( get_object_taxonomies( 'product', 'objects' ), 'label' ) );
		$taxonomies     = get_option( 'alg_wc_global_shop_discount_taxonomies', array( 'product_cat', 'product_tag' ) );
		foreach ( $taxonomies as $taxonomy ) {
			$terms = ( $is_multiselect ? $this->get_terms( $taxonomy ) : false );
			$id    = alg_wc_global_shop_discount()->core->get_taxonomy_option_id( $taxonomy );
			$this->maybe_convert_and_update_option_value( $is_multiselect, array( "alg_wc_global_shop_discount_{$id}_incl", "alg_wc_global_shop_discount_{$id}_excl" ) );
			$settings = array_merge( $settings, array(
				array(
					'title'    => ( isset( $all_taxonomies[ $taxonomy ] ) ? $all_taxonomies[ $taxonomy ] : $taxonomy ),
					'type'     => 'title',
					'id'       => "alg_wc_global_shop_discount_{$taxonomy}_options_{$i}",
				),
				$this->get_settings_as_multiselect_or_text(
					array(
						'title'    => __( 'Include', 'global-shop-discount-for-woocommerce' ),
						'desc_tip' => __( 'Set this field to apply discount to selected products only. Leave blank to apply to all products.', 'global-shop-discount-for-woocommerce' ),
						'id'       => "alg_wc_global_shop_discount_{$id}_incl[{$i}]",
					),
					$this->maybe_add_current_values( $terms, "alg_wc_global_shop_discount_{$id}_incl", __( 'Term', 'global-shop-discount-for-woocommerce' ) ),
					$is_multiselect
				),
				$this->get_settings_as_multiselect_or_text(
					array(
						'title'    => __( 'Exclude', 'global-shop-discount-for-woocommerce' ),
						'desc_tip' => __( 'Set this field to NOT apply discount to selected products. Leave blank to apply to all products.', 'global-shop-discount-for-woocommerce' ),
						'id'       => "alg_wc_global_shop_discount_{$id}_excl[{$i}]",
					),
					$this->maybe_add_current_values( $terms, "alg_wc_global_shop_discount_{$id}_excl", __( 'Term', 'global-shop-discount-for-woocommerce' ) ),
					$is_multiselect
				),
				array(
					'type'     => 'sectionend',
					'id'       => "alg_wc_global_shop_discount_{$taxonomy}_options_{$i}",
				),
			) );
		}

		return $settings;
	}

}

endif;
