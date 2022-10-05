<?php

function acf_load_chabad_crm_event_id( $field ) {
	$choices = chabad_get_donation_event_list();
    // loop through array and add to field 'choices'
    if( is_array($choices) ) {
        foreach( $choices as $key => $choice ) {
            $field['choices'][ $key ] = $choice;
        }
    }
    // return the field
    return $field;
    
}

add_filter('acf/load_field/name=chabad_crm_event_id', 'acf_load_chabad_crm_event_id');