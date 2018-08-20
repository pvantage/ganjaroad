<?php

namespace App\Helpers;

use DateTime;
use Lang;
use Carbon\Carbon;
use View;
use DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Session;
use Log;
use Request;
use Form;
use Route;
use Mail;
use Artisan;

use GeoIP;
use Sentinel;
use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Exceptions\CtctException;

use App\Settings;
use App\User;
use App\Nearme;
use App\NearmeCategory;
use App\Classified;
use App\ClassifiedCategory;
use App\Blog;
use App\BlogCategory;
use App\Page;
use App\Ads;
use App\Block;
use App\Plans;
use App\PaymentItems;
use App\ReportedItems;
use App\Menu;
use App\Cart;
use App\Refunds;
use App\Payments;
use App\Helpers\HelloSign as HelloSignHelper;
use App\Helpers\CacheHelper;

class Template
{
	public static function findArrayKey($array, $keySearch) {
		foreach ($array as $key => $item) {
			if ($key == $keySearch) {
				return $item;
			}
			else {
				if (is_array($item) && Template::findArrayKey($item, $keySearch)) {
				   return $item;
				}
			}
		}

		return false;
	}

	public static function checkIfVaultHasAdConnected($vault)
	{
		$classifieds = Classified::where('vault_id', $vault->id)->get();
		$nearme = Nearme::where('vault_id', $vault->id)->get();
		$ads = Ads::where('vault_id', $vault->id)->get();

		if(count($classifieds) > 0 || count($nearme) > 0 || count($ads) > 0) {
			return true;
		}
		return false;
	}

	public static function getRelatedAdNames($vault)
	{
		$classifieds = Classified::where('vault_id', $vault->id)->get();
		$nearme = Nearme::where('vault_id', $vault->id)->get();
		$ads = Ads::where('vault_id', $vault->id)->get();

		$result = '';
		$objects = array_merge($classifieds->all(), $nearme->all(), $ads->all());
		$i = 0;
		$len = count($objects);
		foreach($objects as $object) {
			if($i == $len - 1) {
				$result .= $object->title;
			} else {
				$result .= $object->title.', ';
			}
			$i++;
		}
		return $result;
	}

    public static function isSerialized($data, $strict = true) {
        // if it isn't a string, it isn't serialized.
        if ( ! is_string( $data ) ) {
            return false;
        }
        $data = trim( $data );
        if( 'N;' == $data ) {
            return true;
        }
        if( strlen( $data ) < 4 ) {
            return false;
        }
        if( ':' !== $data[1] ) {
            return false;
        }
        if( $strict ) {
            $lastc = substr( $data, -1 );
            if( ';' !== $lastc && '}' !== $lastc ) {
                return false;
            }
        } else {
            $semicolon = strpos( $data, ';' );
            $brace = strpos( $data, '}' );
            // Either ; or } must exist.
            if(false === $semicolon && false === $brace)
                return false;
            // But neither must be in the first X characters.
            if(false !== $semicolon && $semicolon < 3)
                return false;
            if (false !== $brace && $brace < 4)
                return false;
        }

        $token = $data[0];
        switch ($token) {
            case 's' :
                if ( $strict ) {
                    if ( '"' !== substr( $data, -2, 1 ) ) {
                        return false;
                    }
                } elseif ( false === strpos( $data, '"' ) ) {
                    return false;
                }
                // or else fall through
            case 'a' :
            case 'O' :
                return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
            case 'b' :
            case 'i' :
            case 'd' :
                $end = $strict ? '$' : '';
            return (bool) preg_match( "/^{$token}:[0-9.E-]+;$end/", $data );
        }

        return false;
    }

    public static function isAdminRoute() {
        if(Request::is('admin/*') || Request::is('admin')) {
            return true;
        }

        return false;
    }

	public static function getDisplayedJSDateTimeFormat() {
		return 'MM/DD/YYYY HH:mm:ss';
	}

    public static function getDisplayedJSDateFormat() {
		return 'MM/DD/YYYY';
	}

	public static function getDisplayedDateTimeFormat() {
		return 'm/d/Y H:i:s';
	}

    public static function getDisplayedDateFormat() {
		return 'm/d/Y';
	}

	public static function getSqlDateTimeFormat() {
		return 'Y-m-d H:i:s';
	}

    public static function getSqlDateFormat() {
		return 'Y-m-d';
	}

    public static function formatDiffDate($date) {
		$formated = Carbon::parse($date);
        if($formated) {
            return $formated->diffForHumans();
        }

        return '';
	}

    public static function formatDate($date) {
		$formated = Carbon::parse($date);
        if($formated) {
            return $formated->format(Template::getDisplayedDateFormat());
        }

        return '';
	}

    public static function formatDateTime($date) {
		$formated = Carbon::parse($date);
        if($formated) {
            return $formated->format(Template::getSqlDateTimeFormat());
        }

        return '';
	}

    public static function nearmeEditFormatDate($date) {
		$formated = Carbon::parse($date);
        if($formated) {
            return $formated->format('M d, Y');
        }

        return '';
	}

    public static function replaceDates($request) {
        //$input = array_map('trim', $request->all());
        $input = (array)$request->all();
        if(isset($request['published_from']) && $request['published_from']) {
            $date = DateTime::createFromFormat(Template::getDisplayedDateTimeFormat(), $request['published_from']);
            $input['published_from'] = $date->format(Template::getSqlDateTimeFormat());
            $request->replace($input);
        } elseif(isset($request['published_from'])) $request['published_from'] = NULL;

        if(isset($request['published_to']) && $request['published_to']) {
            $date = DateTime::createFromFormat(Template::getDisplayedDateTimeFormat(), $request['published_to']);
            $input['published_to'] = $date->format(Template::getSqlDateTimeFormat());
            $request->replace($input);
        } elseif(isset($request['published_to'])) $request['published_to'] = NULL;

        if(isset($request['dob']) && $request['dob']) {
            $date = DateTime::createFromFormat(Template::getDisplayedDateFormat(), $request['dob']);
            $input['dob'] = $date->format(Template::getSqlDateFormat());
            $request->replace($input);
        } elseif(isset($request['dob'])) $request['dob'] = NULL;

        if(isset($request['active_to']) && $request['active_to']) {
            $date = DateTime::createFromFormat(Template::getDisplayedDateTimeFormat(), $request['active_to']);
            $input['active_to'] = $date->format(Template::getSqlDateTimeFormat());
            $request->replace($input);
        } elseif(isset($request['active_to'])) $request['active_to'] = NULL;

        return $request;
    }

	public static function includeDateScript() {
		$script = '
		<link type="text/css" rel="stylesheet" href="'.asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css').'" />
		<script src="'.asset('assets/vendors/moment/js/moment.min.js').'" type="text/javascript"></script>
		<script src="'.asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js').'" type="text/javascript"></script>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				if(jQuery(".dob .datetime-input").length) {
                    var d = new Date();
                    d.setFullYear(d.getFullYear() - 18);
                    jQuery(".datetime-input").datetimepicker({
                        format: "'.Template::getDisplayedJSDateFormat().'",
                        maxDate: d,
                        useCurrent: false,
                        viewMode: "years"
                    });
                    jQuery(".datetime-input").each(function() {
                        jQuery(this).datetimepicker("setDate", jQuery(this).val());
                    });
                } else if(jQuery(".datetime-input").length) {
                    jQuery(".datetime-input").datetimepicker({
                        format: "'.Template::getDisplayedJSDateTimeFormat().'"
                    });
                }
			});
		</script>';

		return $script;
	}

    public static function getSetting($key)
    {
        $config = Settings::where('code', $key)->firstOrFail();
        if($config->type == 'cron') {
            $values = unserialize($config->value);
            switch($values['select']) {
                case('w'):
                    $date = Carbon::today()->subWeeks($values['input']);
                    break;
                case('m'):
                    $date = Carbon::today()->subMonths($values['input']);
                    break;
                case('y'):
                    $date = Carbon::today()->subYears($values['input']);
                    break;
                default:
                    $date = Carbon::today()->subDays($values['input']);
            }

            return $date;
        } elseif($config->type == 'image') {
            $url = Template::getSettingsImageUrl().$config->value;
            return $url;
        } else return $config->value;
    }

    public static function getCountriesList($first_option = true) {

        $countries_list = array(
			"AF" => "Afghanistan",
			"AL" => "Albania",
			"DZ" => "Algeria",
			"AS" => "American Samoa",
			"AD" => "Andorra",
			"AO" => "Angola",
			"AI" => "Anguilla",
			"AR" => "Argentina",
			"AM" => "Armenia",
			"AW" => "Aruba",
			"AU" => "Australia",
			"AT" => "Austria",
			"AZ" => "Azerbaijan",
			"BS" => "Bahamas",
			"BH" => "Bahrain",
			"BD" => "Bangladesh",
			"BB" => "Barbados",
			"BY" => "Belarus",
			"BE" => "Belgium",
			"BZ" => "Belize",
			"BJ" => "Benin",
			"BM" => "Bermuda",
			"BT" => "Bhutan",
			"BO" => "Bolivia",
			"BA" => "Bosnia and Herzegowina",
			"BW" => "Botswana",
			"BV" => "Bouvet Island",
			"BR" => "Brazil",
			"IO" => "British Indian Ocean Territory",
			"BN" => "Brunei Darussalam",
			"BG" => "Bulgaria",
			"BF" => "Burkina Faso",
			"BI" => "Burundi",
			"KH" => "Cambodia",
			"CM" => "Cameroon",
			"CA" => "Canada",
			"CV" => "Cape Verde",
			"KY" => "Cayman Islands",
			"CF" => "Central African Republic",
			"TD" => "Chad",
			"CL" => "Chile",
			"CN" => "China",
			"CX" => "Christmas Island",
			"CC" => "Cocos (Keeling) Islands",
			"CO" => "Colombia",
			"KM" => "Comoros",
			"CG" => "Congo",
			"CD" => "Congo, the Democratic Republic of the",
			"CK" => "Cook Islands",
			"CR" => "Costa Rica",
			"CI" => "Cote d'Ivoire",
			"HR" => "Croatia (Hrvatska)",
			"CU" => "Cuba",
			"CY" => "Cyprus",
			"CZ" => "Czech Republic",
			"DK" => "Denmark",
			"DJ" => "Djibouti",
			"DM" => "Dominica",
			"DO" => "Dominican Republic",
			"EC" => "Ecuador",
			"EG" => "Egypt",
			"SV" => "El Salvador",
			"GQ" => "Equatorial Guinea",
			"ER" => "Eritrea",
			"EE" => "Estonia",
			"ET" => "Ethiopia",
			"FK" => "Falkland Islands (Malvinas)",
			"FO" => "Faroe Islands",
			"FJ" => "Fiji",
			"FI" => "Finland",
			"FR" => "France",
			"GF" => "French Guiana",
			"PF" => "French Polynesia",
			"TF" => "French Southern Territories",
			"GA" => "Gabon",
			"GM" => "Gambia",
			"GE" => "Georgia",
			"DE" => "Germany",
			"GH" => "Ghana",
			"GI" => "Gibraltar",
			"GR" => "Greece",
			"GL" => "Greenland",
			"GD" => "Grenada",
			"GP" => "Guadeloupe",
			"GU" => "Guam",
			"GT" => "Guatemala",
			"GN" => "Guinea",
			"GW" => "Guinea-Bissau",
			"GY" => "Guyana",
			"HT" => "Haiti",
			"HM" => "Heard and Mc Donald Islands",
			"VA" => "Holy See (Vatican City State)",
			"HN" => "Honduras",
			"HK" => "Hong Kong",
			"HU" => "Hungary",
			"IS" => "Iceland",
			"IN" => "India",
			"ID" => "Indonesia",
			"IR" => "Iran (Islamic Republic of)",
			"IQ" => "Iraq",
			"IE" => "Ireland",
			"IL" => "Israel",
			"IT" => "Italy",
			"JM" => "Jamaica",
			"JP" => "Japan",
			"JO" => "Jordan",
			"KZ" => "Kazakhstan",
			"KE" => "Kenya",
			"KI" => "Kiribati",
			"KP" => "Korea, Democratic People's Republic of",
			"KR" => "Korea, Republic of",
			"KW" => "Kuwait",
			"KG" => "Kyrgyzstan",
			"LA" => "Lao People's Democratic Republic",
			"LV" => "Latvia",
			"LB" => "Lebanon",
			"LS" => "Lesotho",
			"LR" => "Liberia",
			"LY" => "Libyan Arab Jamahiriya",
			"LI" => "Liechtenstein",
			"LT" => "Lithuania",
			"LU" => "Luxembourg",
			"MO" => "Macau",
			"MK" => "Macedonia, The Former Yugoslav Republic of",
			"MG" => "Madagascar",
			"MW" => "Malawi",
			"MY" => "Malaysia",
			"MV" => "Maldives",
			"ML" => "Mali",
			"MT" => "Malta",
			"MH" => "Marshall Islands",
			"MQ" => "Martinique",
			"MR" => "Mauritania",
			"MU" => "Mauritius",
			"YT" => "Mayotte",
			"MX" => "Mexico",
			"FM" => "Micronesia, Federated States of",
			"MD" => "Moldova, Republic of",
			"MC" => "Monaco",
			"MN" => "Mongolia",
			"MS" => "Montserrat",
			"MA" => "Morocco",
			"MZ" => "Mozambique",
			"MM" => "Myanmar",
			"NA" => "Namibia",
			"NR" => "Nauru",
			"NP" => "Nepal",
			"NL" => "Netherlands",
			"AN" => "Netherlands Antilles",
			"NC" => "New Caledonia",
			"NZ" => "New Zealand",
			"NI" => "Nicaragua",
			"NE" => "Niger",
			"NG" => "Nigeria",
			"NU" => "Niue",
			"NF" => "Norfolk Island",
			"MP" => "Northern Mariana Islands",
			"NO" => "Norway",
			"OM" => "Oman",
			"PK" => "Pakistan",
			"PW" => "Palau",
			"PA" => "Panama",
			"PG" => "Papua New Guinea",
			"PY" => "Paraguay",
			"PE" => "Peru",
			"PH" => "Philippines",
			"PN" => "Pitcairn",
			"PL" => "Poland",
			"PT" => "Portugal",
			"PR" => "Puerto Rico",
			"QA" => "Qatar",
			"RE" => "Reunion",
			"RO" => "Romania",
			"RU" => "Russian Federation",
			"RW" => "Rwanda",
			"KN" => "Saint Kitts and Nevis",
			"LC" => "Saint LUCIA",
			"VC" => "Saint Vincent and the Grenadines",
			"WS" => "Samoa",
			"SM" => "San Marino",
			"ST" => "Sao Tome and Principe",
			"SA" => "Saudi Arabia",
			"SN" => "Senegal",
			"SC" => "Seychelles",
			"SL" => "Sierra Leone",
			"SG" => "Singapore",
			"SK" => "Slovakia (Slovak Republic)",
			"SI" => "Slovenia",
			"SB" => "Solomon Islands",
			"SO" => "Somalia",
			"ZA" => "South Africa",
			"GS" => "South Georgia and the South Sandwich Islands",
			"ES" => "Spain",
			"LK" => "Sri Lanka",
			"SH" => "St. Helena",
			"PM" => "St. Pierre and Miquelon",
			"SD" => "Sudan",
			"SR" => "Suriname",
			"SJ" => "Svalbard and Jan Mayen Islands",
			"SZ" => "Swaziland",
			"SE" => "Sweden",
			"CH" => "Switzerland",
			"SY" => "Syrian Arab Republic",
			"TW" => "Taiwan, Province of China",
			"TJ" => "Tajikistan",
			"TZ" => "Tanzania, United Republic of",
			"TH" => "Thailand",
			"TG" => "Togo",
			"TK" => "Tokelau",
			"TO" => "Tonga",
			"TT" => "Trinidad and Tobago",
			"TN" => "Tunisia",
			"TR" => "Turkey",
			"TM" => "Turkmenistan",
			"TC" => "Turks and Caicos Islands",
			"TV" => "Tuvalu",
			"UG" => "Uganda",
			"UA" => "Ukraine",
			"AE" => "United Arab Emirates",
			"GB" => "United Kingdom",
			"US" => "United States",
			"UM" => "United States Minor Outlying Islands",
			"UY" => "Uruguay",
			"UZ" => "Uzbekistan",
			"VU" => "Vanuatu",
			"VE" => "Venezuela",
			"VN" => "Viet Nam",
			"VG" => "Virgin Islands (British)",
			"VI" => "Virgin Islands (U.S.)",
			"WF" => "Wallis and Futuna Islands",
			"EH" => "Western Sahara",
			"YE" => "Yemen",
			"ZM" => "Zambia",
			"ZW" => "Zimbabwe"
        );

        $countries = array();
        foreach($countries_list as $key => $country) {
            $countries[$key] = Lang::get('countries.'.$key);
        }

        if($first_option) {
            $countries[0] = Lang::get('countries.select_country');
        }

        return $countries;
    }

    public static function getCurrencySymbol()
    {
		return '$';
	}

    public static function convertPrice($price)
    {
        if(is_numeric($price)) {
            $price = number_format($price, 2, '.', ' ');
            $currency = Template::getCurrencySymbol();

            return $currency. ' '.$price;
        }

        return $price;
	}

    public static function getCurrentLocation()
    {
        $location = GeoIP::getLocation()->toArray();
        $updated_location = session('current_location');

        if($updated_location) {
            $location = array_replace($location, $updated_location);
        }

        return $location;
    }

    public static function getNearMeCurrentHeader()
    {
        $location = Template::getCurrentLocation();
        if($location['iso_code'] == 'US') {
            $loc = array();
            if($location['city']) $loc[] = $location['city'];
            if($location['state']) $loc[] = $location['state'];
            if($loc) $address = implode(', ', $loc);
            else $address = 'US';
        } elseif($location['city']) $address = $location['city'];
        elseif($location['state']) $address = $location['state_name'];
        else $address = $location['country'];

        return $address;
    }

    public static function getNearMeCategories()
    {
        $nearme = NearmeCategory::where('published', '=', 1)->orderBy('position')->get();

        return $nearme;
    }

    /**
     * Get near me from category.
     *
     * @param int $categoryId
     * @param int $limit
     *
     * @return mixed
     */
    public static function getNearmeFromCategory($categoryId = 0, $limit = 3)
    {
        $where = 'WHERE deleted_at IS NULL AND published = 1 AND paid = 1 AND active = 1 AND active_to >= NOW()';

        if (Template::getSetting('nearme_approval')) {
            $where .= ' AND approved = 1';
        }

        if ($categoryId) {
            $where .= ' AND category_id = ' . $categoryId;
        }

        $limitSql = '';

        if ($limit) {
            $limitSql = ' LIMIT ' . $limit;
        }

        $location = Template::getCurrentLocation();
        $lat = $location['lat'];

        return DB::select(
            DB::raw(
                'SELECT *, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( lattitude ) )'
                . ' * cos( radians( longitude ) - radians(' . $location['lon'] . ') ) + sin( radians(' . $lat . ') )'
                . ' * sin( radians(lattitude) ) ) )'
                . ' AS distance FROM nearme ' . $where . ' ORDER BY distance' . $limitSql
            )
        );
    }

    public static function adEditLink($id, $slug = false)
    {
        if(!$slug) {
            $ad = Ads::findOrFail($id);
            $slug = $ad->slug;
        }

        return url('adedit', $slug);
    }

    public static function nearMeLink($id, $slug = false)
    {
        if(!$slug) {
            $nearme = Nearme::findOrFail($id);
            $slug = $nearme->slug;
        }

        return url('nearme', $slug);
    }

    public static function nearMeEditLink($id, $slug = false)
    {
        if(!$slug) {
            $nearme = Nearme::findOrFail($id);
            $slug = $nearme->slug;
        }

        return url('nearmeedit', $slug);
    }

    public static function classifiedEditLink($id, $slug = false)
    {
        if(!$slug) {
            $classified = Classified::findOrFail($id);
            $slug = $classified->slug;
        }

        return url('classifiededit', $slug);
    }

    public static function nearMeCategoryLink($id, $slug = false)
    {
        if(!$slug) {
            $nearme = NearmeCategory::findOrFail($id);
            $slug = $nearme->slug;
        }

        return url('nearmecategory', $slug);
    }

    public static function getNearMeCategoryImageDir()
    {
        $folderName = '/uploads/nearmecategory/';

        return $folderName;
    }

    public static function getAdsImageDir()
    {
        $folderName = '/uploads/ads/';

        return $folderName;
    }

    public static function getTempImageDir()
    {
        $folderName = '/uploads/temp/';

        return $folderName;
    }

    public static function getNearMeImageDir()
    {
        $folderName = '/uploads/nearme/';

        return $folderName;
    }

    public static function getClassifiedsImageDir()
    {
        $folderName = '/uploads/classified/';

        return $folderName;
    }

    public static function getBlogImageDir()
    {
        $folderName = '/uploads/blog/';

        return $folderName;
    }

    public static function getBlogImageDefault()
    {
        $img = '/uploads/blog/default.png';

        return $img;
    }

    public static function getSettingsImageDir()
    {
        $folderName = '/uploads/site/';

        return $folderName;
    }

    public static function getReportImageDir()
    {
        $folderName = '/uploads/reports/';

        return $folderName;
    }

    public static function getSettingsImageUrl()
    {
        $folderName = url('uploads/site');

        return $folderName.'/';
    }

    public static function classifiedsCategoryLink($id, $slug = false)
    {
        if(!$slug) {
            $classifiedcategory = ClassifiedCategory::findOrFail($id);
            $slug = $classifiedcategory->slug;
        }

        return url('classifieds', $slug);
    }

    public static function classifiedsLink($id, $slug = false)
    {
        if(!$slug) {
            $classified = Classified::findOrFail($id);
            $slug = $classified->slug;
        }

        return url('classified', $slug);
    }

    public static function blogLink($id, $slug = false)
    {
        if(!$slug) {
            $blog = Blog::findOrFail($id);
            $slug = $blog->slug;
        }

        return url('blogitem', $slug);
    }

    public static function pageLink($id, $url = false)
    {
        if(!$url) {
            $page = Page::findOrFail($id);
            $url = $page->url;
        }

        return url($url);
    }

    public static function blogTagLink($tag = '')
    {
        return url('blog/'.mb_strtolower($tag).'/tag');
    }

    public static function blogCategoryLink($id, $slug = false)
    {
        if(!$slug) {
            $category = BlogCategory::findOrFail($id);
            $slug = $category->slug;
        }

        return url('blogcategory', $slug);
    }

    public static function formatDistance($distance)
    {
        $formated = (float)$distance; //miles

        return number_format((float)$distance, 1).' m';
    }

    public static function getDefaultLattitude()
    {
        return 32.75025;
    }

    public static function getDefaultLongitude()
    {
        return -117.06831;
    }

    public static function getClassifiedsCityDropdownOptions()
    {
        $location = Template::getCurrentLocation();
        /*if (isset($location['iso_code']) && isset($location['state'])) {
            $result = ClassifiedCategory::where('country', 'LIKE', $location['iso_code'])
                    ->where('state', 'LIKE', $location['state']);
            if($result->exists()) {
                $category_id = $result->first()->id;
            }
        }

        if (isset($location['iso_code']) && $location['iso_code'] != 'US' && !$category_id) { //USA have states so we need get it
            $result = ClassifiedCategory::where('country', 'LIKE', $location['iso_code']);
            if($result->exists()) {
                $category_id = $result->first()->id;
            }
        }

        if(!$category_id) $category_id = $default_category_id;

        */

        try {
            $result = ClassifiedCategory::selectRaw(
                DB::raw('*, ( 3959 * acos( cos( radians(' . $location['lat'] . ') ) * cos( radians( lattitude ) ) * cos( radians( longitude ) - radians(' . $location['lon'] . ') ) + sin( radians(' . $location['lat'] .') ) * sin( radians(lattitude) ) ) ) AS distance')
            )->where('home', '=', 1)->where('published', '=', 1)->groupBy('id')->orderBy('distance')->firstOrFail();

        } catch (ModelNotFoundException $e) {
            $result = Template::getDefaultClassifiedsCategory();
        }

        $category_id = $result->id;
        $list = ClassifiedCategory::where('parent', '=', $result->parent)->where('published', '=', 1)->orderBy('position')->get();
        session(['current_city' => $category_id]);

        $categories_list = array();
        foreach($list as $category) {
            $categories_list[$category->id] = $category->title;
        }

        return $categories_list;
    }

    public static function getDefaultClassifiedsCategory()
    {
        $default_category_id = 45;
        $lat = Template::getDefaultLattitude();
        $lon = Template::getDefaultLongitude();

        try {
            $result = ClassifiedCategory::selectRaw(
                DB::raw('*, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( lattitude ) ) * cos( radians( longitude ) - radians(' . $lon . ') ) + sin( radians(' . $lat .') ) * sin( radians(lattitude) ) ) ) AS distance')
            )->where('home', '=', 1)->where('published', '=', 1)->groupBy('id')->orderBy('distance')->firstOrFail();

        } catch (ModelNotFoundException $e) {
            $result = new \stdClass();
            $result->id = $default_category_id;
            $result->parent = $default_category_id;
        }

        return $result;
    }

    public static function getClassifiedsCategoryByParent($parent = false)
    {
        if(!$parent) $parent = session('current_city');

        $result = ClassifiedCategory::where('parent', '=', $parent)->where('published', 1)->orderBy('position');

        return $result->get();
    }

    public static function getBlogNews()
    {
        $news_category = 4;
        $limit = 5;

        $items = Blog::where(function($query)
        {
            $query->where('published_from', '<=', Carbon::now());
            $query->orWhereNull('published_from');
        })->where(function($query)
        {
            $query->where('published_to', '>', Carbon::now());
            $query->orWhereNull('published_to');
        })->where('published', '=', 1)->where('blog_category_id', '=', $news_category)->take($limit);

        return $items->get();

    }

    public static function createShortDescription($html, $readme_indicator = false)
    {
        if($html) {
            libxml_use_internal_errors(true);
            $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
            $dom = new \DOMDocument();
            $dom->loadHTML($html);
            $firstParagraph = '';

            if($readme_indicator) {
                /*$parts = explode('<div title="Print Page Break"', $html);
                if($parts && count($parts) > 1) {
                    $firstParagraph = Template::closetags($parts[0]);
                }*/
                $res = $dom->getElementsByTagName('div');
                foreach($res as $paragraph) {
                    if($paragraph->getAttribute('title') == 'Print Page Break') {
                        if($paragraph->previousSibling) {
                            $firstParagraph = Template::getElemetSiblings($paragraph);
                            //$firstParagraph = mb_convert_encoding($paragraph->previousSibling->nodeValue, 'HTML-ENTITIES', 'UTF-8');
                            break;
                        }
                    }
                }
            } else {
                $res = $dom->getElementsByTagName('p');
                foreach($res as $paragraph) {
                    $text = Template::cutSringAfter($paragraph->nodeValue);
                    $firstParagraph = mb_convert_encoding($text, 'HTML-ENTITIES', 'UTF-8');
                    break;
                }

                if(!$firstParagraph) {
                    $res = $dom->getElementsByTagName('div');
                    foreach($res as $paragraph) {
                        $text = Template::cutSringAfter($paragraph->nodeValue);
                        $firstParagraph = mb_convert_encoding($text, 'HTML-ENTITIES', 'UTF-8');
                        break;
                    }
                }
            }
            if($firstParagraph) return $firstParagraph;
            else return Template::cutSringAfter(strip_tags($html));
        } else return '';
    }

    public static function cutSringAfter($text)
    {
        $max_characters = 250;
        if(strlen($text) > $max_characters) {
            $text = substr($text, 0, $max_characters).' ...';
        }

        return $text;
    }

    public static function getElemetSiblings($domdocument)
    {
        $html = '';
        if($domdocument->previousSibling) {
            $html .= $domdocument->previousSibling->ownerDocument->saveHTML($domdocument->previousSibling);
            $html .= Template::getElemetSiblings($domdocument->previousSibling);
        }

        if(!trim($html) && $domdocument->parentNode) {
            $html = '';
            $parents = array_reverse(Template::getElemetSiblingsAsArray($domdocument->parentNode));
            foreach($parents as $parent) {
                if($value = trim($parent))
                    $html .= $value;
            }
        }

        return $html;
    }

    public static function getElemetSiblingsAsArray($domdocument)
    {
        $array = array();
        if($domdocument->previousSibling) {
            $array = array_merge($array, array($domdocument->previousSibling->ownerDocument->saveHTML($domdocument->previousSibling)));
            $array = array_merge($array, Template::getElemetSiblingsAsArray($domdocument->previousSibling));
        } elseif($domdocument->parentNode) {
            $array = array_merge($array, Template::getElemetSiblingsAsArray($domdocument->parentNode));
        }

        return $array;
    }

    /*public static function getDomAllParents($domdocument)
    {
        $html = '';
        if($domdocument->parentNode) {
            $html .= $domdocument->parentNode->ownerDocument->saveHTML($domdocument->parentNode);
            if($domdocument->previousSibling) {
                $html .= Template::getElemetSiblings($domdocument->parentNode);
            }
        }

        return $html;
    }*/

    //remove readmore
    public static function getDescription($html)
    {
        if($html) {
            libxml_use_internal_errors(true);
            $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
            $dom = new \DOMDocument();
            $dom->loadHTML($html);

            $res = $dom->getElementsByTagName('div');
            foreach($res as $paragraph) {
                if($paragraph->getAttribute('title') == 'Print Page Break') {
                    $paragraph->parentNode->removeChild($paragraph);
                    $html = $dom->saveHTML();
                }
            }
        }

        return $html;
    }

    //not used
    public static function closetags($html)
    {
        preg_match_all('#<(?!meta|img|br|hr|input\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
        $openedtags = $result[1];
        preg_match_all('#</([a-z]+)>#iU', $html, $result);
        $closedtags = $result[1];
        $len_opened = count($openedtags);
        if (count($closedtags) == $len_opened) {
            return $html;
        }
        $openedtags = array_reverse($openedtags);
        for ($i=0; $i < $len_opened; $i++) {
            if (!in_array($openedtags[$i], $closedtags)) {
                $html .= '</'.$openedtags[$i].'>';
            } else {
                unset($closedtags[array_search($openedtags[$i], $closedtags)]);
            }
        }
        return $html;
    }

    public static function generateAdd($position)
    {
        $ad = false;
        if(Request::is('admin/*') && $ad = Route::current()->getParameter('ads')) {

        } else {
            $ads = Ads::getActive()->whereIn('position_id', function($query) use ($position){
                $query->select('id')->from('ads_positions')->where('slug', 'LIKE', $position)->where('published', '=', 1)->whereNull('deleted_at');
            });

            if($ads->count()) {
                $ads_list = $ads->get()->toArray();
                $rand_key = array_rand($ads_list, 1);

                $add_id = $ads_list[$rand_key]['id'];
                $ad = Ads::find($add_id);
            }
        }

        if($ad) {
            $link = Template::generateAddlink($ad->id);
            $ad->increment('views');

            $view = 'ads/'.$position;
            if(!View::exists($view)){
                $view = 'ads/default';
            }

            return View::make($view, compact('ad', 'link'));
        }

        return '';
    }

    public static function generateAddlink($ad_id)
    {
        return url('add/link', $ad_id);
    }

    public static function getBlogCategories()
    {
        $categories = BlogCategory::all();

        return $categories;
    }

    public static function renderFooterBlock($slug)
    {
        return CacheHelper::getFooterBlockCache($slug);
    }

    public static function renderRightBlock()
    {
        $html = '';
        $ids = explode(',', Template::getSetting('right_col_block_id'));
        if($ids) {
            foreach($ids as $id) {
                $html .= Template::getRightBlock($id);
            }
        }

        return $html;
    }

    public static function getRightBlock($id)
    {

        $html = '';
        try {
            $block = Block::where('id', '=', $id)->where('published', '=', 1)->firstOrFail();

            $html .= '<div class="block-wrapper '.$block->slug.'"><h3 class="block-title">'.$block->title.'</h3>'
                    .'<div class="content">'.$block->content.'</div></div>';

        } catch (ModelNotFoundException $e) {
            return '';
        }

        return $html;
    }

    public static function renderBlock($slug, $show_title = true)
    {
        try {
            $block = Block::where('slug', 'LIKE', $slug)->where('published', '=', 1)->firstOrFail();
            $html = '';

            if($show_title) $html .= '<h2 class="title-block">'.$block->title.'</h2>';
            $html .= '<div class="block-content">'.$block->content.'</div>';

        } catch (ModelNotFoundException $e) {
            return '';
        }

        return $html;
    }

    public static function renderRawBlock($slug)
    {
        try {
            $block = Block::where('slug', 'LIKE', $slug)->where('published', '=', 1)->firstOrFail();
            $html = $block->content;
        } catch (ModelNotFoundException $e) {
            return '';
        }

        return $html;
    }

    public static function generateClassifiedsCategories($categories, $query = false)
    {
        $html = '';
        if($categories) {
            foreach($categories as $category) {
                if($query) {
                    $count = CacheHelper::countClassifiedSubcategories($category, $query);

                    if($count) {
                        $html .= '<li><a href="'.Template::classifiedsCategoryLink($category->id, $category->slug).'">'.$category->title.'</a>';

                        $html .= '<span class="open-link">';
                        $html .= '<a href="'.route('type/stype/query/squery/category/scategory', ['classifieds', $query, $category->id]).'">'.Lang::get('front/general.search_view_all').' ('.$count.') '.Lang::get('front/general.search_results').'</a>';
                        $html .= '</span>';
                    }

                } else $html .= '<li><a href="'.Template::classifiedsCategoryLink($category->id, $category->slug).'">'.$category->title.' ('.$category->classifieds()->count().')'.'</a>';
                if(isset($category['childrens'])) {
                    $html .= '<ul>';
                    $html .= Template::generateClassifiedsCategories($category['childrens'], $query);
                    $html .= '</ul>';
                }
                $html .= '</li>';
            }
        }

        return $html;
    }

    public static function renderCategoryChildren($category_id, $level)
    {
        try{
            $category = ClassifiedCategory::findOrFail($category_id);
            $categories = ClassifiedCategory::where('published', '=', 1)->where('parent', '=', $category->parent)->get();
        } catch (ModelNotFoundException $e) {
            return false;
        }
        //$categories = ClassifiedCategory::getChildrens($parent->id);

        $html = '';
        if($categories) {
            $html = '<div class="form-group category-select-wrapper"><select name="categories[]" class="form-control select2 category-select" data-level="'.$level.'" autocomplete="off">';
            foreach($categories as $category) {
                if((int)$category_id == (int)$category->id) {$selected = ' selected="selected"';}
                else $selected = '';

                $html .= '<option value="'.$category->id.'"'.$selected.'>'.$category->title.'</option>';

            }
            $html .= '</select></div>';
        }

        return $html;

    }

    public static function getClassifiedsParentCategories($category_id)
    {
        $parents = Template::getClassifiedsParentCategory($category_id, array());

        if(count($parents)) {
            return ClassifiedCategory::whereIn('id', $parents)->get();
        }

        return array();
    }

    public static function getClassifiedsParentCategory($category_id, $parents)
    {
        try {
            $category = ClassifiedCategory::where('id', '=', $category_id)->firstOrFail();
            $parents[] = $category->parent;
        } catch (ModelNotFoundException $e) {
            return false;
        }

        $up = array();
        if($category->parent) {
            $up = Template::getClassifiedsParentCategory($category->parent, false);
        }

        if($up) return array_merge($parents, $up);
        else return $parents;
    }

    public static function getClassifiedSortedCategories($categories_array)
    {
        try{
            $categories = ClassifiedCategory::whereIn('id', $categories_array)->orderBy('parent')->get();
        } catch (ModelNotFoundException $e) {
            return false;
        }

        $sorted = array();
        if($categories) {
            $parent_array = array();
            $i = 0;
            foreach($categories as $category) {
                if(++$i == 1) $first = $category;
                $parent_array[$category->parent] = $category;
            }
            $sorted[] = $first;
            $sorted = Template::getChildrenByParent($parent_array, $first->id, $sorted);
        }

        return $sorted;
    }

    public static function getChildrenByParent($categories, $parent, &$result)
    {
        if(isset($categories[$parent])) {
            $current = $categories[$parent];
            $result[] = $current;
            if(isset($categories[$current->id])) Template::getChildrenByParent($categories, $current->id, $result);
        }

        return $result;
    }

    public static function getUserNotPaidItems()
    {
        $user_id = Sentinel::getUser()->id;
        $items = array();

        //get in cart items
        $types = array();
        $in_cart = Session::get('cart', array());
        foreach($in_cart as $cart_item) {
            $type = $cart_item['type'];
            $id = $cart_item['item_id'];

            $types[$type][] = $id;
        }

        //get ads
        $ads = Ads::where('user_id', '=', $user_id)->where('paid', '=', 0);
        if(isset($types['ads'])) $ads->whereNotIn('id', $types['ads']);

        if($ads->count()) {
            foreach($ads->get() as $item) {
                $items['ads'][$item->id] = $item;
            }
        }

        //get nearme
        $nearme = Nearme::where('user_id', '=', $user_id)->where('paid', '=', 0)->where('active', '=', 1);
        if(isset($types['nearme'])) $nearme->whereNotIn('id', $types['nearme']);

        if($nearme->count()) {
            foreach($nearme->get() as $item) {
                $items['nearme'][$item->id] = $item;
            }
        }

        //get classifieds
        $classifieds = Classified::where('user_id', '=', $user_id)->where('paid', '=', 0)->where('active', '=', 1);
        if(isset($types['classifieds'])) $nearme->whereNotIn('id', $types['classifieds']);

        if($classifieds->count()) {
            foreach($classifieds->get() as $item) {
                $items['classifieds'][$item->id] = $item;
            }
        }

        return $items;
    }

    public static function loadItemByType($type, $item_id)
    {
        $item = false;
        try {
            switch($type) {
                case('ads'):
                    $item = Ads::where('id', '=', $item_id)->firstOrFail();
                    break;

                case('nearme'):
                    $item = Nearme::where('id', '=', $item_id)->firstOrFail();
                    break;

                case('classified'):
                case('classifieds'):
                    $item = Classified::where('id', '=', $item_id)->firstOrFail();
                    break;
            }
        } catch (ModelNotFoundException $e) {
            return false;
        }

        return $item;
    }

    public static function getAddToCartQty($type, $item)
    {
        return Cart::getAddToCartQty($type, $item);
    }

    public static function getItemPrice($type, $item)
    {
        return Plans::getItemPrice($type, $item);
    }

    public static function renderTotal($show_currency = true)
    {
        return Cart::renderTotal($show_currency);
    }

    public static function newsletterSignup($request)
    {
        $api_key = env('CONSTANT_CONTACT_API_KEY', 'Api key');
        $access_token = env('CONSTANT_CONTACT_ACCESS_TOKEN', 'Access Token');
        $list_id = env('CONSTANT_CONTACT_LIST_ID', 'List Id');

        if($email = $request->get('email')) {
            $cc = new ConstantContact($api_key);
            try {
                $response = $cc->contactService->getContacts($access_token, array('email' => $email));
                if (empty($response->results)) {
                    $contact = new Contact();
                    $contact->addEmail($email);
                    $contact->addList($list_id);
                    if($fname = $request->get('first_name')) $contact->first_name = $fname;
                    if($lname = $request->get('last_name')) $contact->last_name = $lname;

                    $returnContact = $cc->contactService->addContact($access_token, $contact);

                    return true;
                } else { //update
                    $contact = $response->results[0];
                    if ($contact instanceof Contact) {
                        $contact->addList($list_id);
                        if($fname = $request->get('first_name')) $contact->first_name = $fname;
                        if($lname = $request->get('last_name')) $contact->last_name = $lname;

                        $returnContact = $cc->contactService->updateContact($access_token, $contact);

                        return true;
                    }
                }
            } catch (CtctException $ex) {
                foreach ($ex->getErrors() as $error) {
                    Log::error('Constant Contact signup faild with error:');
                    Log::error($error->error_message);
                }
            }
        }

        return false;
    }

    public static function newsletterUpdateContact($request)
    {
        $api_key = env('CONSTANT_CONTACT_API_KEY', 'Api key');
        $access_token = env('CONSTANT_CONTACT_ACCESS_TOKEN', 'Access Token');
        $list_id = env('CONSTANT_CONTACT_LIST_ID', 'List Id');

        if($email = $request->get('email')) {
            $cc = new ConstantContact($api_key);
            try {
                $response = $cc->contactService->getContacts($access_token, array('email' => $email));
                if (!empty($response->results)) {
                    $contact = $response->results[0];

                    if ($contact instanceof Contact) {
                        $contact->addList($list_id);
                        if($fname = $request->get('first_name')) $contact->first_name = $fname;
                        if($lname = $request->get('last_name')) $contact->last_name = $lname;

                        $returnContact = $cc->contactService->updateContact($access_token, $contact);

                        return true;
                    }
                }
            } catch (CtctException $ex) {
                foreach ($ex->getErrors() as $error) {
                    Log::error('Constant Contact update faild with error:');
                    Log::error($error->error_message);
                }
            }
        }

        return false;
    }

    public static function getMinAgeSelect()
    {
        return array(
            '18' => '18+',
            '21' => '21+',
        );
    }

    public static function getOpenHours()
    {
        return array(
            'sunday',
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday'
        );
    }

    public static function renderHours($hours)
    {
        $hours = unserialize($hours);
        $html = '';
        if($hours && is_array($hours)) {
            foreach($hours as $key => $hour) {
                $am = $hour['am'];
                $pm = $hour['pm'];
                if($am || $pm) {
                    $html .= '<div class="row">';
                    $html .= '<div class="col-md-3">'.Lang::get('front/general.'.$key).':</div>';

                    $html .= '<div class="col-md-9">';
                    if($am) $html .= '<span class="hour">'.$am.'</span>';
                    if($am && $pm) $html .= '<span class="hours-separator">-</span>';
                    if($pm) $html .= '<span class="hour">'.$pm.'</span>';

                    $html .= '</div></div>';
                }
            }
        }
        return $html;
    }

    public static function renderWyswingEditor($content = '', $key = 'content')
    {
        /**/
        $path = realpath(public_path('assets/vendors/cuteeditor/include_CuteEditor.php'));
        $editor = env('DEFAULT_EDITOR');
        if(Form::old($key)) $content = Form::old($key);

        if($path && $editor == 'CuteEditor') {
            include_once($path);

            $editor = new \CuteEditor();
            $editor->ID = $key;
            $editor->Text = $content;
            $editor->EditorBodyStyle = 'font:normal 12px arial;';
            if(Template::isAdminRoute()) {
                 $editor->EnableStripScriptTags = false;
				 $editor->AutoConfigure = 'Admin';
            } else {
                //$editor->AutoConfigure = 'Minimal';
                $editor->ShowBottomBar=false;
                $editor->TemplateItemList="G_start,Bold,Italic,Underline,Separator,Holder,JustifyLeft,JustifyCenter,JustifyRight,Holder,G_end";
                $editor->UseHTMLEntities = false;
                $editor->AllowPasteHtml = false;
                $editor->EnableStripScriptTags = true;
            }
            //$editor->EditorWysiwygModeCss = asset('assets/css/editor.css');
            return $editor->Draw();
        } else {
            $js = '<textarea name="'.$key.'" id="'.$key.'">'.$content.'</textarea>';
            $js .= '<link href="'.asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css').'" rel="stylesheet" type="text/css"/>';
            $js .= '<link href="'.asset('assets/css/pages/editor.css').'" rel="stylesheet" type="text/css"/>';

            $js .= '<script src="'.asset('assets/vendors/tinymce/tinymce.min.js').'" type="text/javascript"></script>';
            $js .= '<script type="text/javascript">';
            $js .= 'tinymce.init({
                selector: "#'.$key.'",
                plugins: [
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime table contextmenu paste code"
                ],
                toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
            });';
            $js .= '</script>';

           return $js;
        }

        return false;
    }

    public static function enableBlogComments($category_id)
    {
        if($category_id == 4) { //news
            return (bool)Template::getSetting('blog_news_comments');
        }

        return (bool)Template::getSetting('blog_comments');
    }

    public static function getMarketingAgreementTitle()
    {
        $id = Template::getSetting('marketing_agreement_page_id');

        try{
            $page = Page::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return '';
        }

        return $page->title;
    }

    public static function getMarketingAgreementContent()
    {
        $id = Template::getSetting('marketing_agreement_page_id');

        try{
            $page = Page::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return '';
        }

        return $page->content;
    }

    public static function getRecurringPopupTitle()
    {
        $id = Template::getSetting('recurring_popup_page_id');
        try{
            $page = Page::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return '';
        }

        return $page->title;
    }

    public static function getRecurringPopupContnet()
    {
        $id = Template::getSetting('recurring_popup_page_id');

        try{
            $page = Page::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return '';
        }

        return $page->content;
    }

    public static function getStateList($first_option = true)
    {
        $states = array(
            'AL' => 'Alabama',
            'AK' => 'Alaska',
            'AZ' => 'Arizona',
            'AR' => 'Arkansas',
            'CA' => 'California',
            'CO' => 'Colorado',
            'CT' => 'Connecticut',
            'DE' => 'Delaware',
            'FL' => 'Florida',
            'GA' => 'Georgia',
            'HI' => 'Hawaii',
            'ID' => 'Idaho',
            'IL' => 'Illinois',
            'IN' => 'Indiana',
            'IA' => 'Iowa',
            'KS' => 'Kansas',
            'KY' => 'Kentucky',
            'LA' => 'Louisiana',
            'ME' => 'Maine',
            'MD' => 'Maryland',
            'MA' => 'Massachusetts',
            'MI' => 'Michigan',
            'MN' => 'Minnesota',
            'MS' => 'Mississippi',
            'MO' => 'Missouri',
            'MT' => 'Montana',
            'NE' => 'Nebraska',
            'NV' => 'Nevada',
            'NH' => 'New Hampshire',
            'NJ' => 'New Jersey',
            'NM' => 'New Mexico',
            'NY' => 'New York',
            'NC' => 'North Carolina',
            'ND' => 'North Dakota',
            'OH' => 'Ohio',
            'OK' => 'Oklahoma',
            'OR' => 'Oregon',
            'PA' => 'Pennsylvania',
            'RI' => 'Rhode Island',
            'SC' => 'South Carolina',
            'SD' => 'South Dakota',
            'TN' => 'Tennessee',
            'TX' => 'Texas',
            'UT' => 'Utah',
            'VT' => 'Vermont',
            'VA' => 'Virginia',
            'WA' => 'Washington',
            'WV' => 'West Virginia',
            'WI' => 'Wisconsin',
            'WY' => 'Wyoming'
        );

        if($first_option) {
            $states[0] = Lang::get('countries.select_state');
        }

        return $states;
    }

    public static function matchState($state)
    {
        $states = Template::getStateList(false);

        if(array_key_exists($state, $states)) return $states[$state];
        elseif(in_array($state, $states)) return $state;
        else return '';
    }

    public static function getUploadFileMaxSize()
    {
        return 2048; //kilobytes
    }

    public static function getClassifiedPublisedTime() {
        $config = Settings::where('code', 'classifieds_expire')->firstOrFail();
        $values = unserialize($config->value);
        $type = false;

        $values['input'] = (float)$values['input'];
        switch($values['select']) {
            case('w'):
                if($values['input'] < 2) $type = Lang::get('general.week');
                else $type = Lang::get('general.week');
                break;
            case('m'):
                if($values['input'] < 2) $type = Lang::get('general.month');
                else $type = Lang::get('general.months');
                break;
            case('y'):
                if($values['input']  < 2) $type = Lang::get('general.year');
                else $type = Lang::get('general.years');
                break;
        }

        if($type) return $values['input'].' '.$type;
        else return '';
    }

    public static function getReasonReported($item_id)
    {
        $report = ReportedItems::where('item_id', '=', $item_id)->where('status', '=', 0);
        if($report->count()) {
            $reasons = $report->lists('reason')->toArray();

            return implode('<br/>', $reasons);
        }

        return false;
    }

    public static function getNearmeCategoriesLinks()
    {
        $links = '';
        $link = url('newnearme');
        $categories = Template::getNearMeCategories();
        $i = 0;
        foreach($categories as $category) {
            $links .= '<a href="'.$link.'?category='.$category->id.'">'.$category->title.'</a>';
            if(++$i < $categories->count()) $links .= ', ';
        }

        return $links;
    }

    public static function getMenuTargetArray()
    {
        $targets = array(
            '_self' => Lang::get('menu/title.self'),
            '_blank' => Lang::get('menu/title.blank'),
        );

        return $targets;
    }

    public static function getMenu()
    {
        $menu = Menu::where('published', '=', 1)->where('parent', '=', 0)->orderBy('position');
        if($menu->count()) {
            return true;
        }

        return false;
    }

    public static function renderMenu($parent = 0, $level = 0)
    {
        $html = '';
        $menus = Menu::where('published', '=', 1)->where('parent', '=', $parent)->orderBy('position');
        foreach($menus->get() as $menu) {
            $styles = '';
            if($menu->style) $styles = 'style="'.$menu->style.'"';

            if($menu->haveChildrens()) {
                if($level > 0) return $html;

                $html .= '<li class="dropdown">'.
                    '<a href="'.$menu->url.'" '.$styles.' class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.$menu->title.'</a>'.
                    '<ul class="dropdown-menu">';
                        $html .= Template::renderMenu($menu->id, ++$level);
                    $html .= '</ul>'.
                '</li>';
            } else {
                //$html .= '<li class="active">'.
                $html .= '<li>'.
                    '<a href="'.$menu->url.'" '.$styles.' target="'.$menu->target.'">'.$menu->title.'</a>'.
                '</li>';
            }
            $level = 0;
        }

        return $html;
    }

    public static function getClassifiedCitiesList($category_id, $skip = array(), $show_empty = false, $one_dimension = true)
    {
        $drop_down_cities = array();
        $categories = ClassifiedCategory::where('home', '=', 1)->get();
        $selected_category = ClassifiedCategory::find($category_id);

        if($categories->count() && $selected_category) {
            if($show_empty) {
                if($one_dimension) {
                    $drop_down_cities[0] = Lang::get('classified/form.select_city');
                } else {
                    $drop_down_cities[] = array(
                        'id' => 0,
                        'title' => Lang::get('classified/form.select_city')
                    );
                }
            }

            foreach($categories as $category) {
                if(in_array($category->id, $skip)) continue; //remove already selected

                //make sure requested cateogry exist, to speed up can be removed
                // @todo: commenting out because of super poor performance
                //if($category->haveSchemaChildren($selected_category->schema_id)) {
                    if($one_dimension) {
                        $drop_down_cities[$category->id] = $category->title;
                    } else {
                        $drop_down_cities[] = array(
                            'id' => $category->id,
                            'title' => $category->title,
                            'state_id' => $category->parentCategory()->id,
                            'state_title' => $category->parentCategory()->title,
                        );
                    }
                //}
            }
        }

        return $drop_down_cities;
    }

    public static function getClaimUserLink($user_id)
    {
        $link = '';
        $user = Sentinel::findById($user_id);
        $current_user = Sentinel::getUser();
        if(!$user->isSuperAdmin()) {
            if($user->cantClaim()) {
                $link = '<span class="claim claimed">'.Lang::get('claim/form.cant_claim').'</a>';
            } elseif($user->claimedByCurrentUser()) {
                $link = '<a href="'.route('remove-claim', $user).'" class="claim" data-toggle="modal" data-target="#claim_confirm">'.Lang::get('claim/form.claimed_by_current_user').'</a>';

            } elseif($user->alreadyClaimed()) {
                if($current_user->hasAccess(['unclaim'])) {
                    $claimed_by = $user->claimedBy();
                    if($claimed_by) {
                        $user_name = $claimed_by->getFullName();
                    } else $user_name = Lang::get('general.user_not_exist');
                    $link .= '<a href="'.route('remove-claim', $user).'" class="claim" data-toggle="modal" data-target="#claim_confirm">'.Lang::get('claim/form.already_claimed', ['username' => $user_name]).'</a>';
                } else $link = '<span class="claim claimed">'.Lang::get('claim/form.already_claimed', ['username' => $user->getFullName()]).'</a>';
            } else {
                if($current_user->id != $user->id && $current_user->hasAccess(['claim'])) {
                    $link = '<a href="'.route('confirm-claim', $user).'" class="claim" data-toggle="modal" data-target="#claim_confirm">'.Lang::get('claim/form.claim_user').'</a>';
                }
            }
        }

        return $link;
    }

    public static function getCopyright()
    {
        $string = 'Copyright year International Access Media, Inc.';
        $now = Carbon::now();
        $year = $now->year;
        $copyright = str_replace('year',$year, $string);

        return $copyright;
    }

    public static function isDeveloper()
    {
		$payment_enabled = self::getSetting('enable_payments');

		if($payment_enabled) {
			return true;
		} else {
			$ip = Request::ip();

	        $env = env('DEVELOPER_IPS');
	        $ips = explode(',', $env);

	        if(in_array($ip, $ips)) {
	            return true;
	        }
		}

        return false;
    }

    public static function getUserCurrentCountryCode()
    {
        $location = Template::getCurrentLocation();
        if($location && $location['iso_code']) return $location['iso_code'];

        return '';
    }

    public static function getUserCurrentState()
    {
        $location = Template::getCurrentLocation();
        if($location && $location['state']) return $location['state'];

        return '';
    }

    public static function getUserCurrentCity()
    {
        $location = Template::getCurrentLocation();
        if($location && $location['city']) return $location['city'];

        return '';
    }

    public static function getAdminBaseUrl()
    {

    }

    public static function getItemAdminLink($type, $item_id)
    {
        $url = '';
        $item = Template::loadItemByType($type, $item_id);
        if($item) {
            switch($type) {
                case('ads'):
                    $url = route('update/ads', ['ads' => $item]);
                    break;

                case('nearme'):
                    $url = route('update/nearme', ['nearme' => $item]);
                    break;

                case('classified'):
                case('classifieds'):
                    $url = route('update/classified', ['classified' => $item]);
                    break;
            }
        }

        if($url) return url($url);
        else return '#';
    }

    public static function getItemMaxRefund($transaction_id, $item)
    {
        return Refunds::getItemTransactionMaxRefund($transaction_id, $item);
    }

    public static function getReportsUserRoles($user_id)
    {

        $roles_array = array();
        try {
            $user = User::where('id', '=', $user_id)->firstOrFail();
            //$roles = $user->with('roles')->firstOrFail();
            $roles = $user->getRoles()->lists('name', 'id')->all();
        } catch (ModelNotFoundException $e) {
            return $roles_array;
        }

        if($roles) {
            foreach($roles as $key => $role) {
                $roles_array[$key] = $role;
            }
        }

        return $roles_array;
    }

    public static function getReporstFilterRanges()
    {
        $ranges = array(
            '0' => Lang::get('sales/title.select_option'),
            'current_month' => Lang::get('sales/title.current_month'),
            'last_month' => Lang::get('sales/title.last_month'),
            'current_year' => Lang::get('sales/title.current_year'),
            'last_year' => Lang::get('sales/title.last_year'),
            'custom' => Lang::get('sales/title.custom_range'),
        );

        return $ranges;
    }

    //google location to array
    public static function getNewLocationArray($search_location)
    {
        $new_location = array();
        $new_location['postal_code'] = '';
        $address_components = $search_location->address_components;

        //clear out all
        $new_location['city'] = $new_location['state'] =  $new_location['country'] = $new_location['iso_code'] = '';

        foreach($address_components as $address) {
            if(in_array('locality', $address->types)) {
                $city = $address->long_name;
                $new_location['city'] = $city;
            } elseif(in_array('administrative_area_level_1', $address->types)) {
                $state = $address->short_name;
                $new_location['state'] = $state;
            } elseif(in_array('country', $address->types)) {
                $country = $address->long_name;
                $new_location['country'] = $country;

                $country_code = $address->short_name;
                $new_location['iso_code'] = $country_code;
            }
        }

        $formatted_address = $search_location->formatted_address;
        $response_location = $search_location->geometry->location;
        $lat = $response_location->lat;
        $lng = $response_location->lng;

        $new_location['lat'] = $lat;
        $new_location['lon'] = $lng;

        return $new_location;
    }

    public static function getPaymentTotal($payment_id)
    {
        $payments = Payments::where('id', $payment_id)->firstOrFail();
        $total = $payments->items->sum('paid');

        return $total;
    }

    public static function getAdsTypes($show_blank = false)
    {
        $types = array('ads' => Lang::get('general.ads'), 'nearme' => Lang::get('general.nearme'), 'classifieds' => Lang::get('general.classifieds'));

        if($show_blank) {
            $blank = array(0 => Lang::get('general.choose_ad_type'));
            $types = array_merge($blank, $types);
        }

        return $types;
    }

    public static function validateSignature($check_signature_id)
    {
        $user = Sentinel::getUser();
        if($user) {
            $signature = HelloSignHelper::getSignatureFromDb($check_signature_id, $user->id);
            if($signature && $signature->signed) return true;
        }

        if(Session::has('signature_items')) {
            $cart = Cart::getCart();
            $cart_signature = Session::get('signature_items', array());
            if($cart_signature && isset($cart_signature['signature'])) {
                $signature_request_id = $cart_signature['signature']['request_id'];
                $signature_id = $cart_signature['signature']['signature_id'];
                unset($cart_signature['signature']);

                if($cart == $cart_signature && $check_signature_id == $signature_id) return true;
            }
        }

        return false;
    }

    public static function signedAgreement()
    {
        $session_signature = HelloSignHelper::getSessionSignature();
        if($session_signature) {
            return Template::validateSignature($session_signature);
        }

        return false;
    }

    public static function hasRecurringItem()
    {
        return Cart::hasRecurringItem();
    }

    public static function sendUnpublishedEmail($object)
    {
        $author = $object->author;
		$to = $author->email;
		$subject = trans('emails/general.ad-unpublished.subject');
		$data = array();
		$data['item'] = $object;

		Mail::send('emails.ad-unpublished', compact('data'), function ($m) use ($data, $to, $subject) {
			$m->from(env('MAIL_USERNAME', ''), env('MAIL_SENDER', ''));
			$m->to($to, trans('general.site_name'));
			$m->subject($subject);
		});
    }

    /**
     * @param string $name
     * @param integer $discount
     * @return string
     */
    public static function getDiscountOptionName($name, $discount)
    {
        if($discount > 0) {
            return "$name ($discount% discount)";
        }
        return "$name";
    }
}
