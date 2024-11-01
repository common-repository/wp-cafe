<?php
    $location_latitude  = '';
    $location_longitude = '';
    $default_address    = esc_html__( 'Mohammadpur Town Hall Kancha Bazar', 'wpcafe' );
?>
<div class="wpc_loc_address_wrap">
    <div>
        <input id="wpc_loc_address" class="wpc_loc_address" type="text" name="wpc_loc_address" value="<?php echo esc_attr( $default_address ); ?>" placeholder="<?php echo esc_html__('Enter your address', 'wpcafe'); ?>">
        <a href="#" id="wpc_loc_my_position" class="wpc_loc_my_position">
            <?php echo esc_html__('Get my Position', 'wpcafe'); ?>
        </a>
        <button class="button button-success wpc_loc_address_search"><?php echo esc_html__('Search Location', 'wpcafe'); ?></button>
    </div>
    </div>
        <button class="button button-success wpc_opt_delivery"><?php echo esc_html__('Delivery', 'wpcafe'); ?></button>
        <button class="button button-success wpc_opt_pickup"><?php echo esc_html__('Pickup', 'wpcafe'); ?></button>
    </div>
</div>

<div class="wpc-front-map" data-lat="<?php echo esc_attr( $location_latitude ); ?>" data-long="<?php echo esc_attr( $location_longitude ); ?>" data-zoom="14" data-radius="6371">
    <div id="wpc-front-map-container"></div>
</div>

<div class="wpc-location-result">
    
</div>
