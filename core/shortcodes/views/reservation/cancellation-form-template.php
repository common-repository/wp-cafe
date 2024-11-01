<?php if (!is_admin()) { ?>
    <div class='wpc-reservation-form '>
        <form method='post' class='wpc_reservation_form wpc_reservation_cancel_form' action=''>
            <input type='hidden' name='wpc_action' value='wpc_cancellation' />
                    <div class='wpc-row'>
                        <div class='wpc-col-md-6'>
                            <div class='wpc-reservation-field invoice'>
                                <label for='wpc-invoice'><?php echo esc_html__('Invoice Number','wpcafe')?> <small class='wpc_required'>*</small></label>
                                <input type='text' name='wpc_reservation_invoice' class='wpc-invoice wpc-form-control' id='wpc-invoice' value='' required aria-required='true'>
                                <div class="wpc-invoice wpc_danger_text"></div>
                            </div>
                        </div>
                        <div class='wpc-col-md-6'>
                            <div class='wpc-reservation-field email'>
                                <label for='wpc-email'><?php echo esc_html__('Email','wpcafe')?>   <small class='wpc_required'>*</small></label>
                                <input type='email' name='wpc_cancell_email' class='wpc_cancell_email wpc-form-control' id='wpc-cancell-email' value='' required aria-required='true'>
                                <div class="wpc-cancell-email wpc_danger_text"></div>
                            </div>
                        </div>
                    </div>
                    <div class='wpc-reservation-field phone'>
                        <label for='wpc-phone'><?php echo esc_html__('Phone', 'wpcafe'); ?></label>
                        <input type='tel' name='wpc_cancell_phone' class='wpc_cancell_phone wpc-form-control' id='wpc-cancell-phone' value=''>
                    </div>
                    <div class='wpc-reservation-field area message'>
                        <label for='wpc-message'><?php echo esc_html__('Message','wpcafe')?> </label>
                        <textarea name='wpc_message' class='wpc_cancell_message wpc-form-control' id='wpc-cancell-message'></textarea>
                    </div>
                <button type='submit' class='cancell_form_submit wpc-btn'><?php echo esc_html( $cancel_button_text); ?></button>
                <span id='wpc_book_table'><?php echo esc_html( $booking_button_text ); ?></span>
        </form>
    </div>
<?php
 }
?>