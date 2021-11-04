<?php
/*
	/* Pic2Shop PRO scanning API @author Ivijan-Stefan Stipic <creativform@gmail.com> @version 1.0.0 

  	Copyright (c) 2009-2017 osConcert

	Released under the GNU General Public License
*/

// Set flag that this is a parent file

define( '_FEXEC', 1 );
require('includes/application_top.php');

define('TEXT_SCAN_TICKETS','Scan the tickets with ');
define('TEXT_NO_ADMISSION','No admission tickets valid for ');
define('TEXT_TICKET_VALID','Ticket valid only for ');
define('TEXT_ALREADY_SCANNED','Already scanned on ');
define('TEXT_NOT_FOUND','Not found ');
define('TEXT_TICKET_OK','Ticket ok!');
define('TEXT_SCAN_NEXT','SCAN NEXT ');
define('TEXT_WRONG_FORMAT','Wrong Format!');
define('TEXT_NOT_EXIST','Does not exist!');
//if(!defined('DEBUG'))define('DEBUG', 'false');
/** ENABLE DEBUG MODE **/
/**/  //$debug = DEBUG; /**/
/**/  $debug = false; /**/
/** ENABLE DEBUG MODE **/

$class='';
$message='' . TEXT_SCAN_TICKETS . '<a href="" target="_blank" rel="nofollow" id="pic2Shop">pic2Shop PRO</a>';

// Barcode
$barcode=filter_input(INPUT_GET, 'barcode', FILTER_SANITIZE_STRING, array(
	'options' => array('default' => false)
));

// Location
$location=filter_input(INPUT_GET, 'location', FILTER_SANITIZE_STRING, array(
	'options' => array('default' => false)
));

$time=time();
$save = false;

if(false !== $barcode)
{	
	if(preg_match("/(\d{1,11})_(\d{1,11})_(\d{1,11})/", $barcode, $part))
	{
		function int($string){
			return (int) $string;
		}
		function ucfirst_all($string){
			$x = preg_split( "/(\s|\W)/",$string);
			$x = array_map('strtolower',$x);
			$x = array_map('ucfirst',$x);
			return join(' ',$x);
		}
		
		unset($part[0]);
		$part = array_map('int',$part);
		
		if(is_int($part[1]) && is_int($part[2]) && is_int($part[3]))
		{
			$code = (object) array(
				'orders_id'		=> $part[1],
				'products_id'	=> $part[2],
				'quantity'		=> $part[3],
				'barcode'		=> join('_',$part)
			);
			//below added orders_products_status
			$query = tep_db_query(sprintf("
				SELECT
					`qr`.`orders_id`,
					`qr`.`barcode_id` AS `barcode_id`,
					`qr`.`scanned` AS `scanned`,
					`qr`.`scanned_date` AS `scanned_date`,
					`qr`.`location` AS `location`,
					`qr`.`data` AS `data`,
                   	`op`.`events_type`,
					`op`.`products_name`,
					`op`.`discount_type`,
					`op`.`products_quantity`,
					`op`.`orders_products_status`
				FROM
					`orders_barcode` `qr`
                    JOIN `orders_products` `op` ON `op`.`products_id` = `qr`.`products_id`
				WHERE
					`qr`.`orders_id` = '%d'
				AND
					`qr`.`products_id` = '%d'
				AND
					`qr`.`barcode` = '%s'
				AND
					`op`.`orders_products_status` = '3'
				AND
					`op`.`products_quantity` > '0'
                		AND
                    `op`.`orders_id` = `qr`.`orders_id`
				",
				$code->orders_id,
				$code->products_id,
				$code->barcode
			));
			$return = tep_db_fetch_array($query);
			if(NULL !== $return && false !== $return)
			{
				if((int)$return['scanned'] === 0 && (int)$return['scanned_date'] === 0 || ($return['scanned_date'] + (10)) > $time && $return['location'] == $location)
				{
					$order_sql="SELECT
						`o`.`orders_id`,
						`op`.`products_id`,
						`op`.`orders_products_id`,
						`o`.`reference_id`,
						`op`.`events_id`,
						`o`.`customers_name`,
						`op`.`categories_name`,
						`op`.`products_name`,
						`op`.`concert_venue`,
						`op`.`concert_date`,
						`op`.`concert_time`,
						`o`.`billing_name`,
						`op`.`products_model`,
						`op`.`events_type`,
						`op`.`discount_type`,
						`op`.`products_price`,
						`op`.`final_price`,
						`o`.`payment_method`,
						`o`.`date_purchased`,
						`op`.`discount_text`
					FROM
						`orders` `o`,
						`orders_products` `op`
					WHERE
						`o`.`orders_id` IN(" . $code->orders_id . ")
					AND
						`op`.`products_id` IN(" . $code->products_id . ")
					AND
						`o`.`orders_id` = `op`.`orders_id`";

					$order_query=tep_db_query($order_sql);
					$data = ((object)tep_db_fetch_array($order_query));
					
					
					// Get data
			//		$JSON = json_decode($return['data']);
					
					// format date
					$concert_date = join(' ', array_filter(array($data->concert_date,$data->concert_time)));
					
					$date = $data->products_model;
					// try to use date for matching
					try {
						$cupon_date = new DateTime(str_replace('/','.',$data->products_model));
						$cupon_date = $cupon_date->getTimestamp();
						
						//define('PLUS_TIME','+2 hours');
						//define('MINUS_TIME','-3 hours');
						$PLUS_TIME = PLUS_TIME;
						$MINUS_TIME = MINUS_TIME;						
						if($time >= strtotime($PLUS_TIME,$cupon_date))
						{
							$class='fail';
							// Cupon expired
							$message=sprintf(
								'' . TEXT_NO_ADMISSION . '<br><span>%s</span><br><span>%s</small>',
								$data->products_name,
								$date
							);
						}
						else if( $time < strtotime($PLUS_TIME,$cupon_date) && $time >= strtotime($MINUS_TIME,$cupon_date) )
						{
							if((int)$return['scanned'] === 0 && (int)$return['scanned_date'] === 0)
							{
								$save = true;
							}
                            if($return['events_type'] == 'G')
							{
                                $class='extra';
                            }else{
                                $class='success';
							}
							if(($return['events_type'] == 'P')&&($return['discount_type'] == 'C')) 
							{
							$class='like';
							}else{
							$class='success';
							}	
							// barcode confirmed
							$message=sprintf(
								'<h1>' . TEXT_TICKET_OK . '</h1>
								<h3>%s</h3>
								<div>%s</div>
								<div>Order ID: %s</div>
								<div>%s</div>
								<div>%s</div>',
								ucfirst_all($data->billing_name),
								$data->categories_name,
								$data->orders_id,
								$concert_date,//presents the date from Concert Date and Time not DateID
								$data->products_name
							);
						}
						else
						{
							$class='fail';
							// This cupon will be available in
							$message=sprintf(
								'' . TEXT_TICKET_VALID . '<span>%s %s</span>',
								$data->categories_name,
								$concert_date
							);
						}
					// No date do other stuffs
					} catch (Exception $e) 
					{
						if((int)$return['scanned'] === 0 && (int)$return['scanned_date'] === 0)
						{
							$save = true;
						}
						if($return['events_type'] == 'G')
						{
                            $class='extra';
                        }else{
                            $class='success';
						}
						if(($return['events_type'] == 'P')&&($return['discount_type'] == 'C')) 
						{
							$class='like';
						}else{
							$class='success';
						}
						// barcode confirmed
						$message=sprintf(
							'<h1>' . TEXT_TICKET_OK . '</h1>
							<h3>%s</h3>
							<div>%s</div>
							<div>%s</div>
							<div>%s</div>
							<div>%s</div>',
							ucfirst_all($data->billing_name),
							$data->categories_name,
							$data->concert_venue,
							$concert_date,
							$data->products_name
						);
					}
				}
				else
				{
					$date_format="d/m/Y H:i";
					$class='warning';
					// Already
					$message=sprintf(
						'<h1>' . TEXT_ALREADY_SCANNED . '<span>%s</span></h1><span>%s</span>',
						$data->orders_id,
						join(" ", array_filter(array(
							date($date_format,$return['scanned_date']),
							$return['location']
						)))
					);
				}
			}
			else
			{
				$class='fail';
				// Not exists
				//$message='<h1>' . TEXT_NOT_FOUND . '</h1>';
				$message='<h1>' . TEXT_NOT_EXIST . '</h1>';
			}
		}
		else
		{
			$class='fail';
			// Wrong format
			//$message='<h1>' . TEXT_NOT_FOUND . '</h1>';
			$message='<h1>' . TEXT_WRONG_FORMAT . '</h1>';
		}
	}
	else
	{
		$class='fail';
		// Wrong format
		//$message='<h1>' . TEXT_NOT_FOUND . '</h1>';
		$message='<h1>' . TEXT_WRONG_FORMAT . '</h1>';
	}
}
else
{
	/*$class='fail';
	$message='<h1>' . TEXT_SCAN_NEXT . '</h1>';*/
}

if($save && !$debug)
{
	$t = tep_db_query(sprintf("
		UPDATE `%s` SET
			`scanned_date` = '%s', `scanned` = '%d', `location` = '%s'
		WHERE
			`barcode_id` = '%s'",
			'orders_barcode',
		$time,
		1,
		$location,
		$return['barcode_id']
	));
}
$message.='<div><a href="p2spro://scan?formats=QR&callback='.urlencode('http'.(isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 's' : '').'://'.@$_SERVER['HTTP_HOST']. DIR_WS_HTTP_CATALOG .basename(__FILE__, '.php').'.php?barcode=CODE&location='.$location).'" id="new" rel="nofollow">' . TEXT_SCAN_NEXT . '</a></div>';

?><!DOCTYPE html>
<html>

<head>
    <meta name="robots" content="noindex,nofollow">
    <meta name="googlebot" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?php echo TEXT_SCAN_TICKETS; ?></title>
    <script>
        /* Pic2Shop PRO scanning API @author Ivijan-Stefan Stipic <creativform@gmail.com> @version 1.0.0 */ 
        ! function(t, e, o, i, a, n) {
            window.onload = function() {
                var t = document.body,
                    e = document.getElementById(o),
                    i = document.getElementById("new"),
                    s = navigator.userAgent || navigator.vendor || window.opera;
                if (t && t.addEventListener("touchstart", function() {}, !1), e && s) i.style.display = "none", /android/gi.test(s) && !window.MSStream ? e.href = "https://market.android.com/details?id=com.visionsmarts.pic2shoppro" : /iPad|iPhone|iPod/gi.test(s) && !window.MSStream ? e.href = "https://itunes.apple.com/app/pic2shop-pro/id382585125?mt=8" : (e.href = "http://www.pic2shop.com/pro_version.html", i.href = "javascript:void(0);");
                else if ("fail" == t.id || "success" == t.id || "extra" == t.id)
                    if (/android/gi.test(s) && !window.MSStream) {
                        var d = document.createElement("video"),
                            p = "fail" == t.id ? a : n;
                        d.autoPlay = !1, d.controls = !0, d.preload = "auto", d.loop = !1, d.muted = !0, d.style.position = "absolute", d.style.top = "-9999%", d.style.left = "-9999%", d.style.zIndex = "-1", d.id = "video";
                        for (key in p)
                            if (/video/gi.test(key) && "probably" == d.canPlayType(key)) return d.type = key, d.src = p[key], t.appendChild(d), void setTimeout(function() {
                                d.muted = !1, d.play()
                            }, 10)
                    } else {
                        var c = new Audio,
                            p = "fail" == t.id ? a : n;
                        for (key in p)
                            if (/audio/gi.test(key) && "probably" == c.canPlayType(key)) return void
                            function(t) {
                                new Audio(t).play()
                            }(p[key])
                    }
            }
        }(0, 0, "pic2Shop", 0, {
            "audio/mpeg": "<?php echo HTTP_SERVER . DIR_WS_HTTP_CATALOG; ?>sfx/not_ok.mp3",
            "audio/wav": "<?php echo HTTP_SERVER . DIR_WS_HTTP_CATALOG; ?>sfx/not_ok.wav",
            "audio/ogg": "<?php echo HTTP_SERVER . DIR_WS_HTTP_CATALOG; ?>sfx/not_ok.ogg",
            "video/mp4; codecs=avc1.42E01E,mp4a.40.2": "<?php echo HTTP_SERVER . DIR_WS_HTTP_CATALOG; ?>sfx/not_ok.mp4"
        }, {
            "audio/mpeg": "<?php echo HTTP_SERVER .DIR_WS_HTTP_CATALOG; ?>sfx/ok.mp3",
            "audio/wav": "<?php echo HTTP_SERVER . DIR_WS_HTTP_CATALOG; ?>sfx/ok.wav",
            "audio/ogg": "<?php echo HTTP_SERVER . DIR_WS_HTTP_CATALOG; ?>sfx/ok.ogg",
            "video/mp4; codecs=avc1.42E01E,mp4a.40.2": "<?php echo HTTP_SERVER . DIR_WS_HTTP_CATALOG; ?>sfx/ok.mp4"
        });
    </script>
    <style>
        #message,
        a:hover {
            color: #fff
        }
        #new,
        body {
            color: #FFF
        }
        #message,
        #new {
            transition: ease-in-out .2s
        }
        html {
            height: 100%;
            margin: 0;
            padding: 3%
        }
        body {
            position: relative;
            width: 100%;
            min-height: 100%;
            background: #69F;
            overflow: hidden;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px
        }
        body#success {
            background: #0C0
        }
        body#fail {
            background: #C00
        }
        body#warning {
            background: #F60
        }
        body#extra {
            background: #2E8B57
        }
		body#like {
            background: #008080
        }
        a,
        a:active,
        a:link,
        a:visited {
            color: #FFC
        }
        #message {
            position: absolute;
            z-index: 1;
            top: 40%;
            left: 50%;
            width: 99%;
            padding: 32px 15px;
            transform: translate(-50%, -50%);
            -webkit-transform: translate(-50%, -50%);
            -moz-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            -o-transform: translate(-50%, -50%);
            font-size: 2em;
            text-align: center;
            font-weight: 700
        }
        #message>small {
            font-size: .5em
        }
        #new {
            display: table;
            margin: 32px auto 0;
            padding: 24px 48px;
            background: 0 0;
            border: 4px solid #FFF;
            font-size: 1em;
            text-decoration: none
        }
        #new:active,
        #new:focus,
        #new:hover {
            background: #FFF;
            color: #555
        }
        body#warning #new:active,
        body#warning #new:focus,
        body#warning #new:hover {
            color: #F60
        }
        body#fail #new:active,
        body#fail #new:focus,
        body#fail #new:hover {
            color: #C00
        }
        body#success #new:active,
        body#success #new:focus,
        body#success #new:hover {
            color: #0C0
        }
        body#extra #new:active,
        body#extra #new:focus,
        body#extra #new:hover {
            color: #2E8B57
        }
        @media all and (max-width: 768px) {
            #message>span {
                display: block;
                width: 100%
            }
        }
        @media all and (max-width: 552px) {
            #message {
                font-size: 1.8em
            }
            #new {
                font-size: .9em
            }
        }
        @media all and (max-width: 332px) {
            #message {
                font-size: 1.5em
            }
            #new {
                font-size: .6em
            }
        }
    </style>
</head>

<body id="<?php echo $class; ?>">
    <div id="message" class="<?php echo $class; ?>">
        <?php echo $message; ?>
    </div>
</body>

</html>