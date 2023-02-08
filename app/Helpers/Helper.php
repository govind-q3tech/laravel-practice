<?php

namespace App\Helpers;

use App\Models\Record;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use \App\Models\AttributeOption;
use \App\Models\Membership\Subscription;
use \App\Models\BusinessCategory;
use \App\Models\Membership\Plan;
use \App\Models\Attribute;
use Carbon\Carbon;
use Auth, DB, Session;


class Helper
{
    public static function imageUrlTimThumb($path, $width = NULL, $height = NULL, $quality = NULL, $crop = NULL)
    {
        if (!$width && !$height) {
            $url = $path;
        } else {
            $url = url('/') . '/timthumb.php?src=' . $path;
            if (isset($width)) {
                $url .= '&w=' . $width;
            }
            if (isset($height) && $height > 0) {
                $url .= '&h=' . $height;
            }
            if (isset($crop)) {
                $url .= "&zc=" . $crop;
            } else {
                $url .= "&zc=1";
            }
            if (isset($quality)) {
                $url .= '&q=' . $quality . '&s=1';
            } else {
                $url .= '&q=95&s=1';
            }
        }
        return $url;
    }



    public static function saveRecord($lisitngId = NULL, $type = NULL)
    {
        $requestData['type'] = $type;
        $requestData['listing_id'] = $lisitngId;
        $requestData['ipaddress'] = \App\Helpers\Helper::getIp();
        $requestData['status'] = 1;
        \App\Models\Record::create($requestData);
    }

    public static function getIp()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return request()->ip(); // it will return server ip when no client ip found
    }
    public static function getDays($day = null)
    {
        $days = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday'];
        if (!empty($day)  && isset($days[$day])) {
            return $days[$day];
        } else {
            return $days;
        }
    }

    public static function checkFirstPost()
    {
        $user = Auth::user();
        $listing = \App\Models\Listing::where("user_id", $user->id)->count();
        // after registrtion user has created the listing but not subscribe

        if ($listing == 1 && empty($user->is_subscribed) && is_numeric($user->plan_id)) {
            return true;
        }
        return false;
    }

    public static function getRating($listingRating = null)
    {
        if (empty($listingRating)) {
            return null;
        } else {
            $ratings = '<span class="star-rating">';
            foreach (range(1, 5) as $review) {
                if ($listingRating >= $review) {
                    $ratings .= '<i class="fas fa-star active"></i>';
                } else {
                    $ratings .= '<i class="fas fa-star"></i>';
                }
            }
            $ratings .= '</span>';
            $ratings .= '<span class="num-rating">' . ceil($listingRating) . '/5</span>';
            return $ratings;
        }
    }

    public static function hourArr()
    {
        $hourArr = [];

        for ($i = 0; $i < 24; $i++) {
            foreach (['00', 15, 30, 45] as $time) {
                $hourArr[$i . '.' . $time] = $i < 10 ? "0$i" . ':' . $time : $i . ':' . $time;
            }
        }
        return $hourArr;
    }

    public static function xml2array($xmlObject, $out = array())
    {
        foreach ((array) $xmlObject as $index => $node)
            $out[$index] = (is_object($node)) ? Helper::xml2array($node) : $node;
        return $out;
    }

    public static function otpGen()
    {
        return "1234";
        // return rand(1111,9999);
    }

    public static function sendOtpMessage($numbers, $message)
    {
        // Account details
        $apiKey = urlencode(config('constants.MESSAGE_API_KEY'));
        $ClientId = urlencode(config('constants.CLIENT_ID'));
        $apiEndpoint = urlencode(config('constants.SMS_API_ENDPOINT'));
        $SenderId = urlencode(config('constants.SENDER_ID'));
        // Message details

        $Message = rawurlencode($message);
        $MobileNumber = $numbers;
        echo $sendAPIUrl = $apiEndpoint . 'SendSMS';
        // Prepare data for POST request
        // SendSMS?ApiKey={ApiKey}&ClientId={ClientId}&SenderId={SenderId}&Message={Message}&MobileNumber={MobileNumber}&Is_Unicode={Is_Unicode}&Is_Flash={Is_Flash}&serviceId={serviceId}&CoRelator={CoRelator}&LinkId={LinkId}
        $data = array(
            'ApiKey' => $apiKey,
            'ClientId' => $ClientId,
            'SenderId' => $SenderId,
            'Message' => $Message,
            'MobileNumber' => $MobileNumber
        );
        // dd($data);
        // Send the POST request with cURL
        // $ch = curl_init($sendAPIUrl);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $response = curl_exec($ch);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $sendAPIUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $response = curl_exec($ch);


        curl_close($ch);
        // Process your response here
        $response = json_decode($response);
        return $response;
    }

    public static function sendOtp($mobile, $otp)
    {
        $numbers = $mobile;
        $message = 'Your OTP is ' . $otp;
        $response = self::sendOtpMessage($numbers, $message);
        dd($response);
        if ($response->status == "success") {
            return ["status" => 1, "message" => "OTP sent successfuly"];
        } else {
            if (isset($response->warnings)) return ["status" => 0, "message" => $response->warnings[0]->message];
            elseif (isset($response->errors)) return ["status" => 0, "message" => $response->errors[0]->message];
            else return ["status" => 0, "message" => "Something went wrong. Please try again."];
        }
    }

    public static function resendOtp($mobile, $otp)
    {
        $numbers = array($mobile);
        $message = 'Your OTP is ' . $otp;
        $response = self::sendOtpMessage($numbers, $message);
        if ($response->status == "success") {
            return ["status" => 1, "message" => "OTP resent successfuly"];
        } else {
            if (isset($response->warnings)) return ["status" => 0, "message" => $response->warnings[0]->message];
            elseif (isset($response->errors)) return ["status" => 0, "message" => $response->errors[0]->message];
            else return ["status" => 0, "message" => "Something went wrong. Please try again."];
        }
    }

    public static function time_elapsed_string($ptime)
    {
        $ptime = strtotime($ptime);
        $etime = time() - $ptime;
        if ($etime < 1) {
            return '0 seconds';
        }
        $a = array(
            365 * 24 * 60 * 60  =>  'year',
            30 * 24 * 60 * 60  =>  'month',
            24 * 60 * 60  =>  'day',
            60 * 60  =>  'hour',
            60  =>  'minute',
            1  =>  'second'
        );
        $a_plural = array(
            'year'   => 'years',
            'month'  => 'months',
            'day'    => 'days',
            'hour'   => 'hours',
            'minute' => 'minutes',
            'second' => 'seconds'
        );
        foreach ($a as $secs => $str) {
            $d = $etime / $secs;
            if ($d >= 1) {
                $r = round($d);
                return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
            }
        }
    }

    public static function fcmNotiEnable()
    {
?>
        <!-- The core Firebase JS SDK is always required and must be listed first -->
        <!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script> -->
        <!-- <script src="//www.gstatic.com/firebasejs/7.20.0/firebase-app.js"></script> -->
        <!-- <script src="//www.gstatic.com/firebasejs/7.20.0/firebase-messaging.js"></script> -->
        <script src="https://www.gstatic.com/firebasejs/4.8.1/firebase-app.js"></script>
        <script src="https://www.gstatic.com/firebasejs/4.8.1/firebase-messaging.js"></script>
        <script type="text/javascript">
            // Your web app's Firebase configuration
            var firebaseConfig = {
                apiKey: "AIzaSyBfJAEMdS4kID2V8Filu4LQRXcapm18Xlk",
                authDomain: "webnotification-a6447.firebaseapp.com",
                databaseURL: "https://webnotification-a6447.firebaseio.com",
                projectId: "webnotification-a6447",
                storageBucket: "webnotification-a6447.appspot.com",
                messagingSenderId: "373326283064",
                appId: "1:373326283064:web:a6b7794278a92ce58801cd"
            };
            // Initialize Firebase
            firebase.initializeApp(firebaseConfig);

            const messaging = firebase.messaging();

            window.addEventListener('load', function() {
                // Check that service workers are supported, if so, progressively
                // enhance and add push messaging support, otherwise continue without it.
                if ('serviceWorker' in navigator) {
                    navigator.serviceWorker.register('<?= url("firebase-messaging-sw.js") ?>')
                        .then((registration) => {
                            console.log(registration)
                        });
                } else {
                    console.warn('Service workers aren\'t supported in this browser.');
                }
            });


            function requestNotiPermission() {
                messaging.requestPermission()
                    .then(function() {
                        console.log('Notification permission granted.');
                        getRegToken();
                    })
                    .catch(function(err) {
                        console.log('Unable to get permission to notify.', err);
                    });
            }

            function getRegToken(argument) {
                messaging.getToken()
                    .then(function(currentToken) {
                        if (currentToken) {
                            var token = currentToken;
                            var device_id = '<?php echo md5($_SERVER['HTTP_USER_AGENT']); ?>';
                            $("input[name='token']").val(token)
                            $("input[name='device_id']").val(device_id)
                        } else {
                            console.log('No Instance ID token available. Request permission to generate one.');
                            //setTokenSentToServer(false);
                        }
                    })
                    .catch(function(err) {
                        console.log('An error occurred while retrieving token. ', err);
                        // setTokenSentToServer(false);
                    });
            }
        </script>
    <?php
    }

    public static function googlePlacePicker($field_id = "address", $autocomplete_id = 'autocomplete')
    {
    ?>
        <!-- <script src="//polyfill.io/v3/polyfill.min.js?features=default"></script> -->
        <!-- <script src="//maps.googleapis.com/maps/api/js?key=<?= config('get.GOOGLE_MAP_KEY') ?>&callback=initAutocomplete&libraries=places&v=weekly" defer ></script> -->
        <script>
            $(window).load(function() {
                initAutocompleteAddress();
            })

            "use strict";
            // This sample uses the Autocomplete widget to help the user select a
            // place, then it retrieves the address components associated with that
            // place, and then it populates the form fields with those details.
            // This sample requires the Places library. Include the libraries=places
            // parameter when you first load the API. For example:
            // <script
            // src="//maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
            // let placeSearch;
            let autocompleteAddress;
            const componentForm = {
                // street_number: "short_name",
                // route: "long_name",
                locality: "long_name",
                // administrative_area_level_1: "short_name",
                administrative_area_level_1: "long_name",
                country: "long_name",
                postal_code: "short_name",
            };

            function initAutocompleteAddress() {
                // Create the autocomplete object, restricting the search predictions to
                // geographical location types.
                autocompleteAddress = new google.maps.places.Autocomplete(
                    document.getElementById("<?= $autocomplete_id ?>"), {
                        types: ["geocode"],
                    }
                ); // Avoid paying for data that you don't need by restricting the set of
                // place fields that are returned to just the address components.

                autocompleteAddress.setFields(["address_component", 'geometry']); // When the user selects an address from the drop-down, populate the
                // address fields in the form.

                autocompleteAddress.addListener("place_changed", fillInAddressAddress);
            }

            function fillInAddressAddress() {
                // Get the place details from the autocomplete object.
                const place = autocompleteAddress.getPlace();

                for (const component in componentForm) {
                    document.getElementById(component).value = "";
                    document.getElementById(component).disabled = false;
                } // Get each component of the address from the place details,
                // and then fill-in the corresponding field on the form.

                for (const component of place.address_components) {
                    const addressType = component.types[0];

                    if (componentForm[addressType]) {
                        const val = component[componentForm[addressType]];
                        document.getElementById(addressType).value = val;
                    }
                }
                document.getElementById("<?= $field_id ?>_json").value = JSON.stringify(place.address_components);
                document.getElementById("lat").value = place.geometry.location.lat();
                document.getElementById("lng").value = place.geometry.location.lng();
            } // Bias the autocomplete object to the user's geographical location,
            // as supplied by the browser's 'navigator.geolocation' object.

            function geolocate() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition((position) => {
                        const geolocation = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };
                        const circle = new google.maps.Circle({
                            center: geolocation,
                            radius: position.coords.accuracy,
                        });
                        autocompleteAddress.setBounds(circle.getBounds());
                    });
                }
            }

            $('#<?= $autocomplete_id ?>').on("keypress", function(event) {
                if (event.which == 13) {
                    event.preventDefault();
                }
            });
        </script>
    <?php
    }

    public static function headerGooglePlacePicker($field_id = "post-code", $autocomplete_id = 'headerautocomplete')
    {
    ?>
        <script src="//polyfill.io/v3/polyfill.min.js?features=default"></script>
        <script src="//maps.googleapis.com/maps/api/js?key=<?= config('get.GOOGLE_MAP_KEY') ?>&callback=initAutocomplete&libraries=places&v=weekly" defer></script>
        <script>
            "use strict";
            // This sample uses the Autocomplete widget to help the user select a
            // place, then it retrieves the address components associated with that
            // place, and then it populates the form fields with those details.
            // This sample requires the Places library. Include the libraries=places
            // parameter when you first load the API. For example:
            // <script
            // src="//maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
            let placeSearch;
            let autocomplete;

            function initAutocomplete() {
                // Create the autocomplete object, restricting the search predictions to
                // geographical location types.
                autocomplete = new google.maps.places.Autocomplete(
                    document.getElementById("<?= $autocomplete_id ?>"), {
                        types: ["(regions)"],
                    }
                ); // Avoid paying for data that you don't need by restricting the set of
                // place fields that are returned to just the address components.

                autocomplete.setFields(["address_component", 'geometry']); // When the user selects an address from the drop-down, populate the
                // address fields in the form.

                autocomplete.addListener("place_changed", fillInAddress);
            }

            function fillInAddress() {
                // Get the place details from the autocomplete object.
                const place = autocomplete.getPlace();
                console.log("place", place)

                for (const component of place.address_components) {
                    const addressType = component.types[0];
                    if (addressType == "postal_code") {
                        const val = component['short_name'];
                        document.getElementById("<?= $autocomplete_id ?>").value = val;
                    }
                }
                // document.getElementById("lat").value = place.geometry.location.lat();
                // document.getElementById("lng").value = place.geometry.location.lng();
            } // Bias the autocomplete object to the user's geographical location,
            // as supplied by the browser's 'navigator.geolocation' object.

            $('#<?= $autocomplete_id ?>').on("keypress", function(event) {
                if (event.which == 13) {
                    event.preventDefault();
                }
            });
        </script>
<?php
    }

    public static function saveNotifications($data = array())
    {
        $notifications = new \Modules\UserManager\Entities\Notification();
        $notifications->user_id     =   $data['user_id'];
        $notifications->title       =   $data['title'];
        $notifications->message     =   $data['message'];
        $notifications->type        =   isset($data['type']) ? $data['type'] : null;;
        $notifications->type_id     =   isset($data['type_id']) ? $data['type_id'] : null;;
        $notifications->save();
        return;
    }
}
