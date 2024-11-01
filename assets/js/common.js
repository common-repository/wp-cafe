"use strict";

if (typeof wpc_form_client_data !== "undefined") {
    var wpc_form_data = JSON.parse(wpc_form_client_data);
}

/**
 * Reservation script here
 * Same task of Frontend and Backend  
 */

// Get weekly schedule day array
const get_weekly_schedule=( day_arr )=>{
    var day = jQuery.map(day_arr, function (value, index) {
        return object_key_name(value);
        
    });

    return day;
}

// Get weekly schedule day no array
const get_weekly_day_no =( disable_weekly_arr )=>{
    var disable_arr = [];
    if ( ( jQuery.isArray( disable_weekly_arr ) || typeof disable_weekly_arr === "object") && disable_weekly_arr.length>0 ) {
        jQuery.each( disable_weekly_arr, function( index , data ){
            if (data == "Sat") {
                disable_arr.push(6);
            }
            if (data == "Sun") {
                disable_arr.push(0);
            }
            if (data == "Mon") {
                disable_arr.push(1);
            }
            if (data == "Tue") {
                disable_arr.push(2);
            }
            if (data == "Wed") {
                disable_arr.push(3);
            }
            if (data == "Thu") {
                disable_arr.push(4);
            }
            if (data == "Fri") {
                disable_arr.push(5);
            }
        } )
    }

    return disable_arr;
}

// get weekly schedule 
const wpc_weekly_schedule_time = (weekly_schedule_arr, selected_day, wpc_weekly_schedule_start_time, wpc_weekly_schedule_end_time) => {
    // default response
    var response = {
        success: false,
        wpc_start_time: '',
        wpc_end_time: ''
    };
    var day = get_weekly_schedule( weekly_schedule_arr );

    if (jQuery.inArray(selected_day, day) !== -1) {
        for (let index = 0; index < weekly_schedule_arr.length; index++) {
            const element = weekly_schedule_arr[index];
            var key = object_key_name(element);
            for (let i = 0; i < key.length; i++) {
                const element = key[i];
                if (selected_day == element) {
                    response.success = true;
                    response.wpc_start_time = wpc_weekly_schedule_start_time[index];
                    response.wpc_end_time = wpc_weekly_schedule_end_time[index];
                }
            }
        }
    }

    return response;
}

// change date format to expected format
const wpc_flatpicker_date_change =(selectedDates,format)=>{
    var wpc_new_selected_date = "";
    if (jQuery.isArray(selectedDates)) {
        var wpc_date_ar = selectedDates.map(date => flatpickr.formatDate(date, format));
    }
    
    if (wpc_date_ar.length == 1 ) {
        wpc_new_selected_date = wpc_date_ar.toString();
    } else {
        wpc_new_selected_date = wpc_date_ar;
    }

    return wpc_new_selected_date;
}

// if get value 0 turn into time
function reserv_time_picker( data , format = "h:i A" ) {
    if ( 0 == data.val() && format == "h:i A" ) {
        data.val( '12:00 AM' )
    }
    else if( 0 == data.val() && format == "H:i" ){
        data.val( '00:00' )
    }
    data.timepicker('hide');
}

/**
 * Change format according to moment 
 * @param {*} wpc_date_format 
 * @returns 
 */
 function changing_format( get_date , wpc_date_format) {
              var result_date = "";
    if ( wpc_date_format !=="") {
        var change_date_format = "YYYY-MM-DD";
        
        if (wpc_date_format =="y/m/d" ) {
            change_date_format = "YY/M/D";
        }
        else if (wpc_date_format =="Y-m-d" ) {
            change_date_format = "YYYY-MM-DD";
        }
        else if (wpc_date_format =="F j, Y" || wpc_date_format =="j F Y" ) {
            change_date_format = "MMMM Do , YYYY";
        }
        else if(wpc_date_format =="m/d/Y"){
            change_date_format = "M/D/Y";
        }
        else if(wpc_date_format =="d/m/Y"){
            change_date_format = "D/M/Y";
        }
        else if (wpc_date_format =="d-m-Y" ) {
            change_date_format = "D-M-Y";
        }
        else if(wpc_date_format =="m-d-Y"){
            change_date_format = "M-D-Y";
        }
        else if(wpc_date_format =="Y.m.d"){
            change_date_format = "Y.M.D";
        }
        else if(wpc_date_format =="m.d.Y"){
            change_date_format = "M.D.Y";
        }
        else if(wpc_date_format =="d.m.Y"){
            change_date_format = "D.M.Y";
        }

        var date = moment(get_date, change_date_format );
        result_date = date.format('YYYY-MM-DD');
    }

    return result_date;
}


//====================== Reservation form validation start ================================= //
function validation_checking( $, input_arr , button_class , error_class , disable_class , key_parent) {
    var in_valid = [];    
    $.each(input_arr, function (index, item) {
		let value = $(`#${item}`)
        var $this = $(`#${item}`); 
		let type  = $this.prop('type');	

        switch ( type ) {
            // TODO merge text|email|tel as their edge-case is same
            case 'text' :
            case 'email':
            case 'tel':
                // add error class in input
                if ( typeof $this.val() ==="undefined" ||  $this.val() =="" ) {
					if ( $this.length > 1 ) {
						$.each($this,function(i,item){
							var inner_this = $(this);
							if ( inner_this.attr('type') == "text") {
								inner_this.addClass(error_class);
								in_valid.push(item);
							}
						})
					} else {
						$this.addClass(error_class);
						in_valid.push(item);
					}
                }
                break;
            case 'number' :
                // add error class in input
                if ( typeof $(this).val() ==="undefined" || parseInt( $(this).val() ) == 0 ) {
                    $(this).addClass(error_class);
                    in_valid.push(item);
                }
                break;
			case 'select-one':
				if ( typeof $this.val() ==="undefined" ||  $this.val() =="") {
					$this.addClass(error_class);
					in_valid.push(item);
				}
				break;

			case 'checkbox':
				if ( typeof value.attr('checked') ==="undefined" ) {
					value.addClass(error_class);
					in_valid.push(item);
				}
				break;

            default:
                break;
        }

        input_change_validation($ , key_parent , value  , type , error_class , button_class , disable_class )

    });

    // check if value already exist in input
    if (in_valid.length>0) {
        $(button_class).prop('disabled', true).addClass(disable_class);
    } else {
        $(button_class).prop('disabled', false).removeClass(disable_class);
    }
}

/**
 * Get validation message
 */
function get_error_message($, type, value , error_class, element='' ) {

    var response = {
        error_type: "no_error",
        message: "success",
    };
    if (value.length == 0) {
        $(this).addClass(error_class);
    } else {
        $(this).removeClass(error_class);
    }

    var wpc_form_data = JSON.parse(wpc_form_client_data);
    var wpc_error_msg = wpc_form_data.wpc_validation_message;

    switch (type) {
        case 'email':
            //TODO use short regex for email validation
            const re = /\S+@\S+\.\S+/;
            if (value.length !== 0) {
                if (re.test(String(value).toLowerCase()) == false) {
                    response.error_type = "not-valid";
                    response.message = wpc_error_msg.email;
                }
            } else {
                response.error_type = "empty";
                response.message = wpc_error_msg.error_text;
            }
            break;
        case 'tel':
            var phone_no = /^[+]*[0-9]*$/g;

            if (value.length === 0) {
                response.error_type = "empty";
                response.message = wpc_error_msg.error_text;
            } else if (value.length > 14) {
                response.error_type = "not-valid";
                response.message    = wpc_error_msg.phone.phone_invalid;
            } else if ( !value.match( phone_no ) ){
                response.error_type = "not-valid";
                response.message    = wpc_error_msg.phone.number_allowed;
            }
            break;
        case 'text':
            if (value.length === 0) {
                response.error_type = "empty";
                response.message = wpc_error_msg.error_text;
            }
            break;
        case 'number':
            var selected_val = parseInt(value);

            var min_attr = parseInt( $(element).attr('min') );
            var max_attr = parseInt( $(element).attr('max') );

            if (selected_val == 0) {
                response.error_type = "empty";
                response.message = wpc_error_msg.table_layout.empty;
            } else {
                if (selected_val < min_attr) {
                    response.error_type = "min-under";
                    response.message = wpc_error_msg.table_layout.min_invalid + min_attr;
                }
    
                if (selected_val > max_attr) {
                    response.error_type = "max-over";
                    response.message = wpc_error_msg.table_layout.max_invalid + max_attr;
                }
            }

            break;
        case 'select':
            if (value.length === 0) {
                response.error_type = "empty";
                response.message = wpc_error_msg.error_text;
            }
        default:
            break;
    }

    return response;
}

/**
 * Disable button on check input
 */
function button_disable( $ , button_class, error_class , disable_class ) {
    var length = $( error_class ).length;
    var button_submit = $( button_class );
    if (length == 0) {
        button_submit.prop('disabled', false).removeClass( disable_class );
    } else {
        button_submit.prop('disabled', true).addClass( disable_class );
    }
}

/**
 * On key up value check
 */

function input_change_validation($ , key_parent , element , type , error_class , button_class , disable_class ) {

    if ( type =="select-one" ) {
        $( element ).on("change", function () {
            var $this = $(this);
            var response = get_error_message( $ , type , $this.val() ,'wpc_booking_error');
            var id       = $this.attr("id");
            $("." + id ).html("");
            if (typeof response !== "undefined" && response.message !== 'success') {
                $("." + id).html(response.message);
                $this.addClass( error_class + ' wpc_has_error' );
            } else {
                $this.removeClass( error_class + ' wpc_has_error' );
            }
            button_disable( $ , button_class, "."+error_class , disable_class  );
        });

    }
	else if ( type =="number" ) {
        $( element ).on("change", function () {
            var $this = $(this);
            var response = get_error_message( $ , type , $this.val() ,'wpc_booking_error', element);
            var id       = $this.attr("id");

            $("." + id ).html("");
            if (typeof response !== "undefined" && response.message !== 'success') {
                if( id != 'wpc-party' ){
                    $("." + id).html(response.message);
                } else {
                    $(".wpc_error_message").css('display','block').html(response.message);
                }
                $this.addClass( error_class + ' wpc_has_error' );
            } else {
                $this.removeClass( error_class + ' wpc_has_error' );
                if( id == 'wpc-party' ){
                    $(".wpc_error_message").css('display','none').html('');
                }
            }

            button_disable( $ , button_class, "."+error_class , disable_class  );
        });
    }
	else if ( type == "checkbox") {
        $( element ).on("change", function () {
			let $this   = $(this);
			let $parent   = $this.parent();
            var checked = $parent.find(':checkbox:checked');
            var field_type = $parent.data('validation');
			var required_checkbox = $parent.find("input[type='checkbox']"); 
			if ( field_type == "required") {
				if ( checked.length > 0  ) {
					required_checkbox.removeClass(error_class)
				} else {
					required_checkbox.addClass(error_class)
				}
			} 

            button_disable( $ , button_class, "."+error_class , disable_class  );
        });
	}
	else {
        $( element ).on("keyup", element , function () {
            var $this    = $(this);
            var response = get_error_message( $ , type , $this.val() ,'wpc_booking_error');
            var id       = $this.attr("id");

            $("." + id).html("");
            if (typeof response !== "undefined" && response.message !== 'success') {
                $("." + id).html(response.message);
                $this.addClass( error_class + ' wpc_has_error' );
            } else {
                $this.removeClass( error_class + ' wpc_has_error' );
            }
            button_disable( $ , button_class, "."+error_class , disable_class  );
        });
    }

}

//====================== Reservation form validation end ================================= //


//====================== Reservation form actions start ================================= //

/**
 * Reservation weekly, daily schedule for single slot and multi-slot
 */

// Convert for settings time
const convert24_format =( time )=>{
    var response = "";
    if ( typeof time !=="undefined" && time !=="" ) {
        if( jQuery.isArray( time.split(' ') ) ){
            response = moment(time, ["h:mm A"]).format("HH:mm");
        }
    }

    return response;
}

// convert time 12 to 24
const convert_time12to24 =(time12h)=> {
    if ( typeof time12h !=="undefined" ) {
        return moment(time12h, ["h:mm A"]).format("HH:mm");
    }
}

// convert time 24 to 12
const convert_time24to12 =(time)=> {
    return moment(time,'HH:mm').format('h:mm A');
}

// calculate time difference
const time_diff =( wpc_end_time,last_time )=>{
    var response = "00:00";
    var dt = new Date();
    var current_date = dt.getFullYear() + "/" + (dt.getMonth() + 1) + "/" + dt.getDate();
    var now  = ""+ current_date +" "+ wpc_end_time +"";
    var then = ""+ current_date +" "+ last_time +"";

    response = moment.utc(moment(now,"DD/MM/YYYY HH:mm").diff(moment(then,"DD/MM/YYYY HH:mm"))).format("HH:mm")

    return response ;
}

// check late booking
const time_diff_for_before_after_equal = (select_time, last_booking_time) => {
    var last_booking_time = last_booking_time == "00:00" ? "24:00": last_booking_time;
    var data      = "unknown";
    var startTime = moment.duration(select_time).asSeconds();
    var endTime   = moment.duration(last_booking_time).asSeconds();

    if (moment(startTime).isBefore(moment(endTime))) {
        data = "early";
    } else if (moment(startTime).isSame(moment(endTime))) {
        data = "equal";
    } else if (moment(startTime).isAfter(moment(endTime))) {
        data = "late";
    }

    return data;
}

//add one minute with the existing time
const add_minute =(time , timeFormat)=> {
    let new_time= time.split(":");
    let new_hour = new_time[0];
    let minute = new_time[1].split(" ");
    let new_minute = parseInt(minute[0]);
    let am_pm='';

    if (timeFormat == '24') {
        time = convert_time12to24(time);
        am_pm = '';
    }else{
        am_pm = ' ' + minute[1];
    }

    new_minute = (new_minute < 10) ? ("0"+ ++new_minute) : ++new_minute;
    new_time = new_hour +':'+ new_minute + am_pm;
    return new_time;
}

//generate time list based on multi slot
const multislot_time_picker =(startTime, endTime, timeFormat )=> {
    let multislotData = [];
    let multi_time_excludes = [];
    for (let i = 0; i < startTime.length; i++) {
        if ( timeFormat == '24') {
            startTime[i]    = convert_time12to24(startTime[i]);
            endTime[i]      = convert_time12to24(endTime[i]);
        }
        //add one minute to the last element of the array
        let start_time = add_minute(endTime[i] , timeFormat);

        // Don't add to the array for last element
        if( i < startTime.length-1 ){
            multi_time_excludes.push([
                start_time,
                startTime[i+1]
            ]);
        }
    }

    multislotData['multi_time_excludes'] = multi_time_excludes;

    if (timeFormat == '24') {
        multislotData['wpc_start_time'] = convert_time12to24(startTime[0]);
        multislotData['wpc_end_time']   = convert_time12to24(endTime[ endTime.length - 1]);

    } else {
        multislotData['wpc_start_time'] = startTime[0];
        multislotData['wpc_end_time'] = endTime[ endTime.length - 1];
    }
    
    return multislotData;
}

// get object key name
function object_key_name(obj){
    return Object.keys(obj);
}

// check multi slot , weekly and all day schedule
const wpc_weekly_daily_schedule =( $, wpc_booking_form_data, selected_day )=> {
    var obj={};var multislotData =[];    

    // check multislot weekly and all day schedule
    if( typeof wpc_booking_form_data.reser_multi_schedule !== 'undefined' && wpc_booking_form_data.reser_multi_schedule == "on" ){
        if ( wpc_booking_form_data.multi_diff_weekly_schedule.length <= 0 && ( typeof wpc_booking_form_data.multi_start_time[0] !=='undefined' && wpc_booking_form_data.multi_start_time[0] !=="") &&  
        ( typeof wpc_booking_form_data.multi_end_time[0] !=='undefined' &&  wpc_booking_form_data.multi_end_time[0] !=="" )  ) {
            // generate time list based on all day multislot start and end time which will be excluded from the timepicker time list for creating disableTimeRange array
            multislotData = multislot_time_picker( wpc_booking_form_data.multi_start_time, wpc_booking_form_data.multi_end_time , wpc_booking_form_data.wpc_time_format );
            obj.multi_start_time= wpc_booking_form_data.multi_start_time;
            obj.multi_end_time  = wpc_booking_form_data.multi_end_time;
            obj.multi_schedule_name  = wpc_booking_form_data.multi_schedule_name;

        }else{
            // multislot schedule for different day 
            var multislot_day_object = wpc_booking_form_data.weekly_multi_diff_times;
            
            var day = object_key_name( multislot_day_object );
            
            if (jQuery.inArray(selected_day, day) !== -1 ) {
                const element = multislot_day_object[selected_day]

                let week_start_time = element.map(function(value,index){ return value.start_time });
                let week_end_time   = element.map(function(value,index){ return value.end_time });
                let week_schedule_name   = element.map(function(value,index){ return value.schedule_name });

                multislotData = multislot_time_picker( week_start_time ,week_end_time , wpc_booking_form_data.wpc_time_format );
           
                obj.multi_start_time= week_start_time;
                obj.multi_end_time  = week_end_time;
                obj.multi_schedule_name  = week_schedule_name;
                
            }

        }

        obj.schedule_type   = "multipleslot";

        if ( typeof multislotData.wpc_start_time !=="undefined" ) {
            obj.wpc_start_time  = multislotData['wpc_start_time'];
            obj.wpc_end_time    = multislotData['wpc_end_time'];
            obj.response_type   = "success";
            wpc_booking_form_data.multi_time_excludes = multislotData['multi_time_excludes'];
            $('#wpc_booking_date').removeClass("wpc_booking_error");
            
            button_disable( $ , '.reservation_form_submit', ".wpc_booking_error" , "wpc_reservation_form_disabled" , ".wpc_reservation_table" );   
        }else{
            obj.response_type   = "error";
        }
    }
    //multislot end
    else{
        // time range for all day
        if (wpc_booking_form_data.wpc_all_day_start_time != "" && wpc_booking_form_data.wpc_all_day_end_time != "") {
            var wpc_all_day_start_time = wpc_booking_form_data.wpc_all_day_start_time;
            var wpc_all_day_end_time = wpc_booking_form_data.wpc_all_day_end_time;

            if (wpc_booking_form_data.wpc_time_format == '24') {
                obj.wpc_start_time  = convert_time12to24(wpc_all_day_start_time);
                obj.wpc_end_time    = convert_time12to24(wpc_all_day_end_time);
            } else {
                obj.wpc_start_time  = wpc_all_day_start_time;
                obj.wpc_end_time    = wpc_all_day_end_time;
            }

            obj.schedule_type   = "allday";
            obj.response_type   = "success";

            $('#wpc_booking_date').removeClass("wpc_booking_error");
            button_disable( $ , '.reservation_form_submit', ".wpc_booking_error" , "wpc_reservation_form_disabled" , ".wpc_reservation_table" );
        }
        else {
            // time range for weekly
            var weekly_schedule_arr = wpc_booking_form_data.wpc_weekly_schedule;
            var weekly_start_time = wpc_booking_form_data.wpc_weekly_schedule_start_time;
            var weekly_end_time = wpc_booking_form_data.wpc_weekly_schedule_end_time;
            var response = wpc_weekly_schedule_time(weekly_schedule_arr, selected_day, weekly_start_time, weekly_end_time);

            if (response.success == true) {
                $("#wpc_from_time").prop('disabled', false);
                $("#wpc_to_time").prop('disabled', false);
                if (wpc_booking_form_data.wpc_time_format == '24') {
                    obj.wpc_start_time  = convert_time12to24(response.wpc_start_time);
                    obj.wpc_end_time    = convert_time12to24(response.wpc_end_time);
                } else {
                    obj.wpc_start_time  = response.wpc_start_time;
                    obj.wpc_end_time    = response.wpc_end_time;
                }

                obj.schedule_type       = "weekly";
                obj.response_type       = "success";

                $('#wpc_booking_date').removeClass("wpc_booking_error");
                button_disable( $ , '.reservation_form_submit', ".wpc_booking_error" , "wpc_reservation_form_disabled" , ".wpc_reservation_table" );
            } else {
                obj.wpc_start_time  = "";
                obj.wpc_end_time    = "";
                obj.schedule_type   = "";
                obj.response_type   = "clear_date";
            }
        }

    }

    return obj;
}

// check late booking
const wpc_check_late_booking =( $, selected_time , last_booking_time , min , from_time , to_time , wpc_time_format , end_id )=> {

    var time_diff_latebooked = time_diff_for_before_after_equal( selected_time , last_booking_time );
    var error_message        = jQuery('.wpc_error_message');

    error_message.html("");

    if ( time_diff_latebooked == 'late' ) {
        var get_end_time = to_time;
        if( wpc_time_format == "h:i A" ) {
            get_end_time = convert_time24to12( to_time  );
        }
        var last_booked_message = $(".late_booking").data("late_booking");

        var response1 = last_booked_message.replace("{last_time}", get_end_time );
        var response2 = response1.replace("{last_min}", min );

        from_time.val(""); end_id.val("");
        from_time.addClass("wpc_booking_error");
        end_id.addClass("wpc_booking_error");
        button_disable( $ , '.reservation_form_submit', ".wpc_booking_error" , "wpc_reservation_form_disabled" , ".wpc_reservation_table" );
        $(".wpc_success_message").css("display","none").html("")
        error_message.css("display", "block");
        error_message.html( response2 );
        
    } else {
        error_message.css("display", "none");
        from_time.removeClass("wpc_booking_error");
        button_disable( $ , '.reservation_form_submit', ".wpc_booking_error" , "wpc_reservation_form_disabled" , ".wpc_reservation_table" );
    }
}

// If select time first check date 
function check_date_field($, wpc_error_message , from_time , to_time  ){
    // Check if date field empty
    wpc_error_message.css('display','none').html( "" );
    var date_class = $('#wpc_booking_date');
    var check_date = date_class.val();

    if ( check_date == "" ) {
        date_class.addClass('error');
        var date_missing = $(".date_missing").data("date_missing");
        wpc_error_message.css('display','block').html( date_missing );
        $('.wpc-validate-msg').css('display','block').html( date_missing );
        from_time.val("");
        to_time.val("");
        
        return;
    }
}

function multislot_time_range_validation($,multi_schedule,input_value,wpc_time_format) {
            
    var multi_start = multi_schedule.start;
    var multi_end   = multi_schedule.end;
    var multi_form_time = $("#wpc_from_time").val();
    var multi_to_time   = $("#wpc_to_time").val();

    if ( multi_schedule.start !=="" && multi_form_time !=="" && multi_to_time !=="" ) {

        var format      = "HH:mm";
        if ( wpc_time_format == '24') {
            format = ['HH:mm'];
        }else{
            format = ["h:mm A"];
        }
        var get_index   = "";
        var get_valid_i = "";

        for (let index  = 0; index < multi_start.length; index++) {
            var pair_time_start = multi_start[index];
            var pair_time_end   = multi_end[index];

            var time    = moment(input_value,format),
            beforeTime  = moment(pair_time_start,format),
            afterTime   = moment(pair_time_end,format);

            if (time.isBetween(beforeTime, afterTime)) {
                get_index = index;
            }

        }
        
    }
}

/*
* All algorithm to fetch time range 
* Based on date
*/

function get_time_range_based_on_date( $ , param_obj , obj ) {
    var wpc_start_time          = '';
    var wpc_end_time            = '';
    var multi_schedule          = { start : "" , end : "" };

    var wpc_time_format         = param_obj.wpc_time_format;
    var from_time               = param_obj.from_time;
    var to_time                 = param_obj.to_time;
    var schedule_message        = $(".wpc_success_message");
    // Show message that there is no schedule
    param_obj.wpc_error_message.css('display','none').html('');

    $('.wpc-validate-msg').css('display','none').html('');

    if ( param_obj.wpc_booking_form_data !== null  ) {

        var wpc_new_selected_date = wpc_flatpicker_date_change(param_obj.selectedDates, "Y-m-d");

        $('.wpc_check_booking_date').attr('data-wpc_check_booking_date',wpc_new_selected_date);

        if ( param_obj.wpc_booking_form_data.wpc_today ==  wpc_new_selected_date  && obj.booking_form_type =="frontend" && obj.reserve_status.status == "closed") {
            // Show message that there is no schedule
            param_obj.wpc_error_message.css('display','block').html( obj.reserve_status.message );

            return;
        }

        var response        = {};
        var exception_date  = param_obj.wpc_booking_form_data.wpc_exception_date;

        if (typeof exception_date === "undefined") {

            /**********************
            // Check exception date
            *************************/
          var get_date_data = change_date_format(param_obj.selectedDates,param_obj.wpc_date_format);

            var wpc_new_selected_date = wpc_flatpicker_date_change(param_obj.selectedDates, "D");

            response        = wpc_weekly_daily_schedule( $, param_obj.wpc_booking_form_data , wpc_new_selected_date );
            wpc_start_time  = typeof response.wpc_start_time !=="undefined" ? response.wpc_start_time : "";
            wpc_end_time    = typeof response.wpc_end_time !=="undefined" ? response.wpc_end_time : "";

        }
        else {
            if (exception_date.length > 0 && exception_date[0] !== '') {

                if ($.inArray(wpc_new_selected_date, exception_date) !== -1) {
                    // Selected date is an exception date , so find exception start and end time
                    var index = exception_date.indexOf(wpc_new_selected_date);
                    wpc_start_time  = param_obj.wpc_booking_form_data.wpc_exception_start_time[index];
                    wpc_end_time    = param_obj.wpc_booking_form_data.wpc_exception_end_time[index];
                    response.response_type = "success";

                    $('#wpc_booking_date').removeClass("wpc_booking_error");
                    button_disable( $ , '.reservation_form_submit', ".wpc_booking_error" , "wpc_reservation_form_disabled" , ".wpc_reservation_table" );
                }
                else {
                    // Selected date is not an exception date , so find schedule from weekly or daily start and end time
                    var wpc_new_selected_date = wpc_flatpicker_date_change(param_obj.selectedDates, "D");
                    response        = wpc_weekly_daily_schedule( $, param_obj.wpc_booking_form_data , wpc_new_selected_date);
                    wpc_start_time  = typeof response.wpc_start_time !=="undefined" ? response.wpc_start_time : "";
                    wpc_end_time    = typeof response.wpc_end_time !=="undefined" ? response.wpc_end_time : "";
                }

            }
            else {
                // Selected date is not an exception date , so find schedule from weekly or daily start and end time
                var wpc_new_selected_date = wpc_flatpicker_date_change(param_obj.selectedDates, "D");
                response        = wpc_weekly_daily_schedule( $, param_obj.wpc_booking_form_data, wpc_new_selected_date);
                wpc_start_time  = typeof response.wpc_start_time !=="undefined" ? response.wpc_start_time : "";
                wpc_end_time    = typeof response.wpc_end_time !=="undefined" ? response.wpc_end_time : "";

            }

        }

        if ( response !== "undefined" && response.response_type !== "undefined" &&  response.response_type == "clear_date" ) {

            // Clear date value and make disable
            $("#wpc_booking_date").val("");
            $('#wpc_booking_date').addClass("wpc_booking_error");

            // Show message that there is no schedule
            param_obj.wpc_error_message.css('display','block').html( param_obj.no_schedule_message );

            $("#wpc_from_time").prop('disabled', true);
            $("#wpc_to_time").prop('disabled', true);

        }
        else {

            // Enable date value and set start and end time to time picker
            $("#wpc_from_time").prop('disabled', false);
            $("#wpc_to_time").prop('disabled', false);

            if ( wpc_time_format !== "H:i" ) {
                wpc_start_time = convert_time12to24(wpc_start_time);
                wpc_end_time = convert_time12to24(wpc_end_time);

            }

            var checking_time       = check_time_range_validation( wpc_start_time , wpc_end_time , param_obj.selectedDates , null );
            var disable_time_rage   = param_obj.wpc_booking_form_data.multi_time_excludes;

            if ( checking_time.flag == "start_from_current" ) {
                var start     = moment(new Date());
                var step_data = typeof param_obj.wpc_booking_form_data.reserv_time_interval !=="undefined" ?
                param_obj.wpc_booking_form_data.reserv_time_interval : 30 ;
                var remainder = step_data - (start.minute() % 30);
                var get_start = moment(start).add(remainder, "minutes").format("hh:mm A");
                wpc_start_time = get_start;
            }
            else if(  checking_time.flag == "disable_time"  ){
                disable_time_rage = [ [wpc_start_time, checking_time.end_time ] ];
            }

            from_time.timepicker('option', 'minTime', wpc_start_time);
            from_time.timepicker('option', 'maxTime', wpc_end_time );

            // Disable time for multi slot and current date past time
            from_time.timepicker('option', 'disableTimeRanges', disable_time_rage );

            to_time.timepicker('option', 'minTime', wpc_start_time);
            to_time.timepicker('option', 'maxTime', wpc_end_time);

            // Disable time for multi slot and current date past time
            to_time.timepicker('option', 'disableTimeRanges', disable_time_rage );

            // Show Booking schedule according to date
            schedule_message.css('display','none').html( "" );

            if ( response !== "undefined" && response.schedule_type !== "undefined" && response.schedule_type !== "" && param_obj.wpc_error_message.html()=="" ){

                var start           = schedule_message.data("start");
                var end             = schedule_message.data("end");
                var late_booking    = schedule_message.data("late_booking");

                // show selected time slot
                if (response.schedule_type !=="multipleslot") {

                    var start_time  = wpc_time_format == 'H:i' ? convert_time12to24( wpc_start_time ) : convert_time24to12( wpc_start_time );
                    var end_time    = wpc_time_format == 'H:i' ? convert_time12to24( wpc_end_time ) : convert_time24to12( wpc_end_time );

                    if ( typeof start_time !==":undefined" &&  start_time !=="" && typeof end_time !==":undefined" &&  end_time !=="" ) {
                        schedule_message.css('display','block').html( start+" "+ start_time +"."+" "+end+" "+  end_time +". "+late_booking );
                    }
                }
                else {

                    if (response.response_type =="error") {
                        param_obj.wpc_error_message.css('display','block').html( param_obj.no_schedule_message );

                        return;
                    }

                    var message = "";

                    if ( response.multi_start_time.length > 0) {

                        // send multi slot time to check block validation
                        multi_schedule = {  start : response.multi_start_time , end : response.multi_end_time };

                        for (let index = 0; index < response.multi_start_time.length; index++) {
                            var start_shce  = response.multi_start_time[index];
                            var end_shce    = response.multi_end_time[index];
                            var schedule_no    = response.multi_schedule_name[index];

                            var start_time  = wpc_time_format == 'H:i' ? convert_time12to24( start_shce ) :  start_shce ;
                            var end_time    = wpc_time_format == 'H:i' ? convert_time12to24( end_shce ) :  end_shce ;

                            if ( start_time !==":undefined" &&  start_time !=="" && end_time !==":undefined" &&  end_time !=="" ) {                                
                                message    += "<span><strong>"+ schedule_no +"</strong> "+ start +" "+ start_time +" "+end+" "+end_time+"</span>";
                            }
                        }
                        var late_booking_message = late_booking !=="" ? "<span>"+ late_booking +"</span>" : "";
                        schedule_message.css('display','block').html( message + late_booking_message  );

                    }
                }

            }
            else{
                param_obj.wpc_error_message.css('display','block').html( param_obj.no_schedule_message );
            }
        }

    }
    else {
        $('#wpc_booking_date').removeClass("wpc_booking_error");
        // Show message that there is no schedule
        param_obj.wpc_error_message.css('display','block').html( param_obj.no_schedule_message );

    }

    return { wpc_start_time : wpc_start_time , wpc_end_time : wpc_end_time , multislot: multi_schedule }
}

/*
* Reservation form multislot seat capacity option generate
*/
function week_diff_seat_capacity_options_generate($, wpc_booking_form_data){
    if( wpc_booking_form_data.reser_multi_schedule == "on" ){

        let selected_date = moment( $('.wpc_check_booking_date').attr('data-wpc_check_booking_date') ).format('ddd');

        let wpc_format_time = 'HH:mm';
        if (wpc_booking_form_data.wpc_time_format == '24') {
            wpc_format_time = ['HH:mm'];
        }else{
            wpc_format_time = ["h:mm A"];
        }

        let wpc_from_time = moment( $('#wpc_from_time').val(), wpc_format_time );

        var seat_count = 0;

        let get_seat_capacity = new Promise( (resolve, reject) => {
            //check if different weekday based multi schedule is available or not
            if( Object.keys(wpc_booking_form_data.weekly_multi_diff_times).length > 0 ){
                //get the keys of the selected date array i.e. start_time, end_time, seat_capacity key
                const seat_capacity_by_day = Object.keys(wpc_booking_form_data.weekly_multi_diff_times)
                    .filter(key => selected_date.includes(key))
                    .reduce((obj, key) => {
                        return wpc_booking_form_data.weekly_multi_diff_times[key];
                    }, {});

                // get the seat_capacity based on start_time and end_time of the multislot 

                $.isArray(seat_capacity_by_day) && seat_capacity_by_day.map( (capacity) => {
                    const startTime = moment( capacity.start_time, ['h:mm A'] );
                    const endTime = moment( capacity.end_time, ['h:mm A'] );
                    if (wpc_from_time.isBetween(startTime-1, endTime+1)) {
                        seat_count = capacity.seat_capacity;
                        resolve();
                        return;
                    }
                });

            }else{

                wpc_booking_form_data.multi_start_time.map( (capacity, key) => {
                    let startTime = moment( wpc_booking_form_data.multi_start_time[key], wpc_format_time);
                    let endTime = moment( wpc_booking_form_data.multi_end_time[key], wpc_format_time);

                    if (wpc_from_time.isBetween(startTime-1, endTime+1)) {
                        seat_count = wpc_booking_form_data.multi_seat_capacity[key];
                        resolve();
                        return;
                    }
                });
            }
        });

        get_seat_capacity.then( (message) => {
            let optionElements = '';
            for(var i = 1; i <= seat_count; i++) {
                optionElements += `<option value="${i}">${i}</option>`;
            }

            $('.party.wpc-reservation-field #wpc-party').addClass('wpc_booking_error')
                .find('option').not(':first').remove().end()
                .after(optionElements);
        });

    }
}

/**
 * Reservation opening and ending time checking 
 */
function reservation_opening_ending_compare( $, params ) {
    var wpc_diff_data = "";
    if ( typeof params !=="undefined" && params.wpc_from_time !== "" && params.wpc_to_time !== "" ) {
        if ( params.wpc_time_format !== 'H:i' ) {
            let from_time = convert_time12to24( params.wpc_from_time );
            let to_time = convert_time12to24( params.wpc_to_time );
            wpc_diff_data = time_diff_for_before_after_equal(from_time, to_time);
        } else {
            wpc_diff_data = time_diff_for_before_after_equal( params.wpc_from_time, params.wpc_to_time );
        }

        var wpc_validate_msg1 = $('.wpc-validate-msg1');
        if (wpc_diff_data == 'late' || wpc_diff_data == 'equal') {
            params.log_message.fadeOut('slow');
            params.log_message.html('');
            $(".wpc_success_message").css("display", "none").html("");
            params.error_message.fadeIn('slow');
            params.error_message.css("display", "block");
            params.error_message.html( params.error_message.data('time_compare') );
            params.from_id.val(""); 
            params.to_id.val("");
            params.from_id.addClass('error wpc_booking_error');
            params.to_id.addClass('error wpc_booking_error');
            wpc_validate_msg1.fadeIn('slow');
            wpc_validate_msg1.html( params.error_message.data('time_compare') );
            button_disable( $ , '.reservation_form_submit', ".wpc_booking_error" , "wpc_reservation_form_disabled" , ".wpc_reservation_table" );   

        } else {
            params.error_message.css("display", "none");
            wpc_validate_msg1.css("display", "none");
            wpc_validate_msg1.html(' ');
            params.error_message.html('');

            params.from_id.removeClass('error wpc_booking_error');
            params.to_id.removeClass('error wpc_booking_error');
        }
    }
}

//====================== Reservation form actions end ================================= //

// check if start time is before current time or not
function check_time_range_validation( start_time , end_time , selected_day , time_format ) {
    var flag            = false;
    var start           = start_time;
    var end             = end_time;
    var now_time        = moment(new Date()).format("HH:mm");    
    var current_time    = moment.duration( now_time ).asSeconds();

    start   = convert_time12to24(start_time);
    end     = convert_time12to24(end_time);

    start           = moment.duration(start).asSeconds();
    end             = moment.duration(end).asSeconds();

    var today       = moment(new Date()).format("YYYY-MM-DD");
    var select      = wpc_flatpicker_date_change( selected_day , "Y-m-d" );

    if ( ( today == select ) &&  moment(start).isBefore(moment(current_time)) && moment(current_time).isBefore(moment(end)) ){
        flag            = "start_from_current";
    }
    
    else if ( ( today == select ) && ( moment(end).isBefore(moment(current_time)) ) ) {
        flag = "disable_time";
        end = end + 2
    }
    
    return {flag : flag , end_time : end };
}

/**
 * All reservation action
 */
 function reservation_form_actions( $ , obj ) {
    // declare class 
    var from_time = $('#wpc_from_time');
    var to_time   = $('#wpc_to_time');

    // get data from enqueue
    var wpc_booking_form_data;

    var wpc_date_format         = 'Y-m-d';

    var wpc_time_format         = 'H:i';
    var no_schedule_message     = ''; // No schedule is set from admin
    var disable_weekly_arr      = [];
    var step                    = 30;
    var is_pro_active           = false;

		var wpc_ajax_url = "";
		if ( typeof wpc_form_client_data !== "undefined" && wpc_form_client_data !== null ) {
				var client_data = JSON.parse(wpc_form_client_data);
				var wpc_ajax_url= ( client_data !== null &&  typeof client_data.wpc_ajax_url !== "undefined" && client_data.wpc_ajax_url !==null ) ? client_data.wpc_ajax_url : "" ;
		}

    if (typeof obj.wpc_form_client_data !== "undefined") {

        var wpc_form_data =  obj.wpc_form_client_data ;
        if ( typeof wpc_form_data ==="undefined" ) {
            wpc_booking_form_data = null;
        }else{
            wpc_booking_form_data = wpc_form_data;
            no_schedule_message   = wpc_booking_form_data !== null ? wpc_booking_form_data.no_schedule_message : "No schedule is set from admin";
            is_pro_active         = wpc_booking_form_data !== null ? wpc_booking_form_data.is_pro_active :  false ;
        }
    }
    // time format
    if (wpc_booking_form_data === null  ) {

        wpc_time_format = 'H:i';
        wpc_date_format = 'Y-m-d';
    }
    else {
        step                =  wpc_booking_form_data.reserv_time_interval;
        if (typeof wpc_booking_form_data.wpc_time_format == "undefined" || wpc_booking_form_data.wpc_time_format == null || wpc_booking_form_data.wpc_time_format == "") {
            wpc_time_format = 'H:i';
        } else {
            wpc_time_format = wpc_booking_form_data.wpc_time_format;
        }
        // date format
        if (wpc_booking_form_data.wpc_date_format != "") {
            wpc_date_format = wpc_booking_form_data.wpc_date_format;
        } else {
            wpc_date_format = 'Y-m-d'
        }

        // set no schedule message
        if ( wpc_booking_form_data.reserve_dynamic_message !=="" ) {
            no_schedule_message = wpc_booking_form_data.reserve_dynamic_message;
        }
        if (typeof wpc_booking_form_data.wpc_weekly_schedule !== "undefined" &&  wpc_booking_form_data.wpc_weekly_schedule !== "" && wpc_booking_form_data.reser_multi_schedule ==="") {

            var get_weekly_ar  = get_weekly_schedule(wpc_booking_form_data.wpc_weekly_schedule );
            disable_weekly_arr = get_weekly_day_no( get_weekly_ar );
        }else if ( wpc_booking_form_data.reser_multi_schedule ==="on" && typeof wpc_booking_form_data.weekly_multi_diff_times !== "undefined" && wpc_booking_form_data.weekly_multi_diff_times !== "") {
            disable_weekly_arr = get_weekly_day_no( Object.keys(wpc_booking_form_data.weekly_multi_diff_times) );
        }
    }

    var wpc_start_time      = '';
    var wpc_end_time        = '';
    var multi_schedule      = { start : "" , end : "" };

    var wpc_error_message   = $('.wpc_error_message');

    //  from time
    from_time.timepicker({
        timeFormat: wpc_time_format,
        dynamic: true,
        listWidth: 1,
        step: step, // 30 minutes.
		disableTextInput: true,
    });

    [from_time,to_time].map((item,index)=>{
        (item).on('focus',function(e){
            var get_date = $('#wpc_booking_date').val();
            get_date = changing_format(get_date, wpc_date_format );
            if ( get_date !=="" ) {
                var param_obj = {
                    wpc_error_message       : $('.wpc_error_message'),
                    selectedDates           : [new Date(get_date + "T00:00:00")],
                    wpc_booking_form_data   : wpc_booking_form_data,
                    wpc_time_format         : wpc_time_format,
                    from_time               : from_time,
                    to_time                 : to_time,
                    no_schedule_message     : no_schedule_message,
                }
				/**
				 * Get time range
				 */
                get_time_range_based_on_date( $ , param_obj , obj );

            }

        });
    });

    from_time.on('changeTime',function(){
        //check for 00 time
        reserv_time_picker( $(this) , wpc_time_format )
        // check if date is selected
        check_date_field($, wpc_error_message , from_time , to_time  );

        /********************** 
        // check late-booking 
        *************************/
        var input_value = $(this).val();

        if ( input_value !== null) {
            // the input field
            var selected_time = $(this).val();

            // custom event for get time using for react
            const startTimeEvent = new CustomEvent("getCalendarFromTime",  {
                detail: { name:  selected_time }
            });
                // To trigger the Event
            document.dispatchEvent(startTimeEvent);

            if ( typeof wpc_booking_form_data !== null ) {
				// check seat capacity
				if ( is_pro_active == true ) {
					var type = "";
					if ( $("#wpc-canvas-container").length > 0 ) {
						type = "table_layout";
					}
					var get_date = $('#wpc_booking_date').val();
					var wpc_booking_select_date = changing_format(get_date, wpc_date_format );
					check_seat_capacity( $ , wpc_ajax_url , wpc_booking_select_date , input_value , $("#wpc_to_time").val(), type );
				}

                var wpc_late_bookings = wpc_booking_form_data.wpc_late_bookings;
                // late bookings
                if ( wpc_end_time !== 'undefined') {
                    wpc_end_time    = convert24_format(wpc_end_time);
                    selected_time   = convert24_format(selected_time); 
                }

                if (typeof wpc_late_bookings !== 'undefinded' && wpc_late_bookings == '15') {
                    var last_booking_time = time_diff(wpc_end_time, "00:14");
                    if (selected_time !== '' && selected_time !== 'undefined') {
                        wpc_check_late_booking( $ , selected_time, last_booking_time, 15 , from_time , wpc_end_time , wpc_time_format , $("#wpc_to_time") );
                    }
                } else if (typeof wpc_late_bookings !== 'undefinded' && wpc_late_bookings == '30') {

                    var last_booking_time = time_diff(wpc_end_time, "00:29");
                    if (selected_time !== '' && selected_time !== 'undefined') {
                        wpc_check_late_booking( $ ,selected_time, last_booking_time, 30 , from_time , wpc_end_time, wpc_time_format, $("#wpc_to_time")  );
                    }
                } else if (wpc_late_bookings != 'undefinded' && wpc_late_bookings == '45' ) {
                    var last_booking_time = time_diff(wpc_end_time, "00:44");
                    if ( selected_time !== '' && selected_time !== 'undefined' ) {
                        wpc_check_late_booking($, selected_time, last_booking_time, 45 , from_time , wpc_end_time , wpc_time_format, $("#wpc_to_time")   );
                    }
                } else {
                    from_time.removeClass("wpc_booking_error");
                    button_disable( $ , '.reservation_form_submit', ".wpc_booking_error" , "wpc_reservation_form_disabled" , ".wpc_reservation_table" );
                }
                /* WeekDay multislot seat capacity variation for reservation form Guest Number field */
                week_diff_seat_capacity_options_generate($, wpc_booking_form_data)
            }
            else {
                from_time.removeClass("wpc_booking_error");
            }
        }

        if ( typeof wpc_from_time !== "undefined" && typeof wpc_to_time !== "undefined"  &&  $(this).val().length > 1  && $("#wpc_to_time").val().length >1 ) {
            var obj = {
                from_id         : $("#wpc_from_time"),
                to_id           : $("#wpc_to_time"),
                wpc_from_time   : input_value,
                wpc_to_time     : $("#wpc_to_time").val(),
                wpc_time_format : wpc_booking_form_data.wpc_time_format,
                log_message     : $('.wpc_log_message'),
                error_message   : wpc_error_message,
            };
            // compare from and to time
            reservation_opening_ending_compare( $, obj );

        }

        // multi slot time schedule validation
        if ( multi_schedule.start !=="" ) {
            multislot_time_range_validation( $ , multi_schedule , input_value , wpc_booking_form_data.wpc_time_format );
        }

    });

    //  To
    to_time.timepicker({
        timeFormat  : wpc_time_format,
        listWidth   : 1,
        step        : step,
        dynamic     : true,
		disableTextInput: true,
    });

    to_time.on('changeTime',function(){
        reserv_time_picker( $(this), wpc_time_format )
        // check if date is selected
        check_date_field( $, wpc_error_message , from_time , to_time );
        var input_value     = $(this).val();
        if ( input_value !== null) {
            // custom event for get time using for react
            const endTimeEvent = new CustomEvent("getCalendarToTime",  {
                detail: { name:  input_value }
            });
			// To trigger the Event
            document.dispatchEvent(endTimeEvent);

            to_time.removeClass("wpc_booking_error");
            button_disable( $ , '.reservation_form_submit', ".wpc_booking_error" , "wpc_reservation_form_disabled" , ".wpc_reservation_table" );
        }

        if ( typeof wpc_from_time !== "undefined" && typeof wpc_to_time !== "undefined"  && $(this).val().length > 1 && $("#wpc_from_time").val().length > 1  ) {
            var obj = {
                from_id         : $("#wpc_from_time"),
                to_id           : $("#wpc_to_time"),
                wpc_from_time   : $("#wpc_from_time").val(),
                wpc_to_time     : input_value,
                wpc_time_format : wpc_booking_form_data.wpc_time_format,
                log_message     : $('.wpc_log_message'),
                error_message   : $('.wpc_error_message'),
            };
            // compare from and to time 
            reservation_opening_ending_compare( $, obj );
        }

        // multi slot time schedule validation
        if ( multi_schedule.start !=="" ) {
            multislot_time_range_validation( $ , multi_schedule , input_value , wpc_booking_form_data.wpc_time_format );
        }

    });

    /**********************
    // early bookings
    *************************/

    var reserv_form_local = "en";
    if ( wpc_booking_form_data !== null ) {
        var wpc_early_bookings  = wpc_booking_form_data.wpc_early_bookings;
        var wpc_max_day         = '';
        reserv_form_local       = typeof wpc_booking_form_data.reserv_form_local !=="undefined" ? wpc_booking_form_data.reserv_form_local : "en";

        wpc_max_day = wpc_booking_form_data.wpc_max_day ? new Date(wpc_booking_form_data.wpc_max_day) : null;

    }

    // Change from and to time based on date .
    if ( obj.wpc_booking_date.length > 0) {
        // disabling date
        var disable_date = [];
        if(wpc_booking_form_data !== null && typeof wpc_booking_form_data.wpc_holiday_date !== 'undefined' ){
            if( wpc_booking_form_data.wpc_holiday_date !== ""){
                disable_date = wpc_booking_form_data.wpc_holiday_date;
            }
        }

        var disable_weekly_data = function name(date) {
            if ( wpc_booking_form_data === null || (typeof disable_weekly_arr == 'undefined' || disable_weekly_arr.length === 0 ) ) {
                return false;
            } else {
                var selected_date = date.toLocaleDateString('en-CA');
                var exception_date_disable = ( wpc_booking_form_data !== null && typeof wpc_booking_form_data.wpc_exception_date !=="undefined" ) ? wpc_booking_form_data.wpc_exception_date : [];
                return ( ($.inArray( date.getDay() , disable_weekly_arr ) == -1 ) && ($.inArray( selected_date , exception_date_disable ) == -1 ) );
            }
        }

        disable_date.push(disable_weekly_data);

		/**
		 * On date change
		 */
        obj.wpc_booking_date.flatpickr({
            dateFormat  : wpc_date_format,
            minDate     : "today",
            maxDate     : wpc_max_day,
            position    : "below",
            inline      : obj.inline_value,
            locale      : reserv_form_local,
            disable     : disable_date,
            onChange    : function (selectedDates, dateStr, instance) {

			var selected_date = wpc_flatpicker_date_change(selectedDates, "Y-m-d");
			obj.wpc_booking_date.attr("data-wpc_booking_date",selected_date);

			if ( is_pro_active ) {
				var check_start_time = $('#wpc_from_time').val();
				var type = "";
				if ( $("#wpc-canvas-container").length > 0 ) {
					type = "table_layout";
				}
				// check seat capacity
				if ( is_pro_active == true && check_start_time !=="" ) {
					check_seat_capacity( $ , wpc_ajax_url , selected_date , $('#wpc_from_time').val() , $('#wpc_to_time').val() , type );
				}
				// custom event for get date in react
					// To assign event
				const startEvent = new CustomEvent("getCalendarDate",  {
					detail: { name:  selected_date }
				});
				// To trigger the Event
				document.dispatchEvent(startEvent);
			}

			wpc_error_message.css('display','none').html( "" );

			var param_obj = {
					wpc_error_message       : wpc_error_message,
					selectedDates           : selectedDates,
					wpc_booking_form_data   : wpc_booking_form_data,
					wpc_time_format         : wpc_time_format,
					from_time               : from_time,
					to_time                 : to_time,
					no_schedule_message     : no_schedule_message,
			}

			var data = get_time_range_based_on_date( $ , param_obj , obj );
			wpc_start_time  = data.wpc_start_time;
			wpc_end_time    = data.wpc_end_time;
			wpc_end_time    = data.wpc_end_time;
			multi_schedule  = { start : data.multislot.start ,end: data.multislot.end }

            }

        });

    }

    return true;
}

/**
 * Check reservation seat capacity
 * @param {*} $ 
 * @param  {...any} res 
 * @returns 
 */
function check_seat_capacity($,...res) {
	var type          = typeof res[4] === "undefined" ? '' : res[4];
	// check selected date seat capacity
	if ( typeof res[0] === "undefined" || type == "table_layout" ) {
		return;
	}

	var wpc_party = $("#wpc-party");
    var $key_div = wpc_party.parents(".wpc-reservation-field");
	var selected_date = typeof res[1] === "undefined" ? '' : res[1];
	var from_time     = typeof res[2] === "undefined" ? '' : res[2];
	var to_time       = typeof res[3] === "undefined" ? '' : res[3];

	var data = {
		action              : 'wpc_seat_capacity',
		selected_date       : selected_date,
		from_time           : from_time,
		to_time             : to_time,
		type                : type,
		wpc_action          : 'wpc_seat_status',
        _wpc_nonce          : wpc_form_data._nonces.wpc_seat_capacity_nonce
	}

	$.ajax({
		url: res[0],
		method: 'post',
		data: data,
        beforeSend: function () {
            $key_div.addClass("loading");
        },
		success: function (response) {
			$key_div.removeClass("loading");
			if ( response?.data?.data ) {
				wpc_party.empty();
				if ( response.data.data.status == "closed" ) {
					$(".wpc_success_message").css('display','none').html("");
					$('.wpc_error_message').css('display','block').html( response.data.data.message );
					$("#wpc_from_time").val("");
					$("#wpc_to_time").val("");
					return;
				}else{
					var guest_size = response.data.data.max_capacity;
					$('<option>').val('').text(wpc_form_data.wpc_form_dynamic_text.wpc_guest_count).appendTo('#wpc-party');
					for (let index = 1; index <= guest_size; index++) {
						$('<option>').val(index).text(index).appendTo('#wpc-party');
					}
				}
			}
		},
		});
}
