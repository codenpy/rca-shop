<?php

/**
 * Overdue Order - Manual Email.
 * 
 * @class SUMOSubscriptions_Manual_Overdue_Order_Email
 */
class SUMOSubscriptions_Manual_Overdue_Order_Email extends SUMO_Abstract_Subscription_Email {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id             = 'subscription_overdue_order_manual' ;
		$this->name           = 'overdue' ;
		$this->customer_email = true ;
		$this->title          = __( 'Subscription Payment Overdue - Manual', 'sumosubscriptions' ) ;
		$this->description    = addslashes( __( 'Subscription Payment Overdue - Manual emails are sent to the customer and the amount for the subscription renewal has not been paid within the overdue period.', 'sumosubscriptions' ) ) ;

		$this->template_html  = 'emails/subscription-overdue-order-manual.php' ;
		$this->template_plain = 'emails/plain/subscription-overdue-order-manual.php' ;

		$this->subject = __( '[{site_title}] - Subscription Payment Overdue', 'sumosubscriptions' ) ;
		$this->heading = __( 'Subscription Payment Overdue', 'sumosubscriptions' ) ;

		$this->supports = array( 'mail_to_admin', 'upcoming_mail_info' ) ;

		// Call parent constuctor
		parent::__construct() ;
	}

}

return new SUMOSubscriptions_Manual_Overdue_Order_Email() ;
