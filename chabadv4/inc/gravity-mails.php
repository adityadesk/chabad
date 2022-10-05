<?php

class GW_Manual_Notifications {

    private static $instance = null;

    public static function get_instance() {
        if( null == self::$instance )
            self::$instance = new self;
        return self::$instance;
    }

    private function __construct() {

	    add_filter( 'gform_notification_events', array( $this, 'add_manual_notification_event' ) );

		add_filter( 'gform_before_resend_notifications', array( $this, 'add_notification_filter' ) );
		
		add_filter( 'init', array( $this, 'send_notifications' ) );


    }

	public function add_notification_filter( $form ) {
		add_filter( 'gform_notification', array( $this, 'evaluate_notification_conditional_logic' ), 10, 3 );
		return $form;
	}

	public function add_manual_notification_event( $events ) {
		$events['reminder'] = __( 'Reminder' );
		$events['feedback'] = __( 'Feedback' );
		return $events;
	}

	public function evaluate_notification_conditional_logic( $notification, $form, $entry ) {

		// if it fails conditional logic, suppress it
		if( $notification['event'] == 'manual' && ! GFCommon::evaluate_conditional_logic( rgar( $notification, 'conditionalLogic' ), $form, $entry ) ) {
			add_filter( 'gform_pre_send_email', array( $this, 'abort_next_notification' ) );
		}

		return $notification;
	}

	public function abort_next_notification( $args ) {
		remove_filter( 'gform_pre_send_email', array( $this, 'abort_next_notification' ) );
		$args['abort_email'] = true;
		return $args;
	}

	public function send_notifications( ) {
		if(isset($_GET['send_event_reminder'])){
			$search_criteria['field_filters'][] = array( 'key' => '101', 'value' =>  date('Y-m-d', strtotime(' +1 day')) );
			$entries = GFAPI::get_entries( 4, $search_criteria );
		    $form = GFAPI::get_form( 4 );
			if($entries ){
				foreach($entries as $entry){
					GFAPI::send_notifications( $form, $entry,'reminder' );
				}
			}
		}
		if(isset($_GET['send_event_feedback'])){
			$search_criteria['field_filters'][] = array( 'key' => '102', 'value' =>  date('Y-m-d') );
			$entries = GFAPI::get_entries( 4, $search_criteria );
		    $form = GFAPI::get_form( 4 );
			if($entries ){
				foreach($entries as $entry){
					GFAPI::send_notifications( $form, $entry,'feedback' );
				}
			}
			
		 
		}
	}

}

function gw_manual_notifications() {
    return GW_Manual_Notifications::get_instance();
}

gw_manual_notifications();