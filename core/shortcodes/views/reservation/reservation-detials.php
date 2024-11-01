<!-- Reservation booking detials -->
<ul>
<?php foreach( $reservation_arr as $key => $value) : 
    if (  $key == 'wpc_check_start_time' ) {
        if ( $show_form_field =="on" || $show_to_field =="on" ) { ?>
    <li> 
        <strong class='wpc-user-field-info'><?php echo esc_html($value); ?></strong>
        <?php if ( $key == 'wpc_check_start_time' ) { ?>
        <span class='<?php echo esc_attr($key) ?>'></span>  <?php echo esc_html($dash) ; echo  ( $show_to_field == 'on' ) ?  " <span class='wpc_check_end_time'></span>" : "" ?>
        <?php } else{ ?>
                <span class='<?php echo esc_attr($key) ?>'></span>
        <?php } ?>
    </li>
        
<?php } }
else { ?>
    <li id="<?php echo esc_attr($key) ?>"> 
        <strong class='wpc-user-field-info'><?php echo esc_html($value); ?></strong>
        <span class='<?php echo esc_attr($key) ?>'></span>
    </li>
<?php }
endforeach;?>
</ul>

<div class='wpc_log_message'></div>

