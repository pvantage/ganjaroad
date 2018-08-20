@extends('emails/layouts/default')

@section('content')
	<h1>Hello {!! $data['user_name'] !!},</h1>
	<p>@lang('classified/emails.classifieds-reminder-body', array('days' => $data['days'], 'title' => $data['title']))</p>

    <p>
        We are excited to announce that we have reached our goals for viewership and users! Ganjaroad.comis now viewed in 16 countries, has over 5000 users and is getting 1000s of views per day. Our social media is also growing! We have over 5000 followers each on Instagram and Facebook. To help our community get noticed we are launching a large marketing campaign through magazines, trade shows, event sponsorships, social media, email and billboards. Our community is now the go-to Worldwide resource for all things cannabis!
    </p>

    <p>
        We have started charging for all advertisements now and wanted to make sure that you knew how to add your credit card to your existing ads so that your free ads don’t disappear! Simply use <a href="https://ganjaroad.com/freeads">THIS LINK</a> to view your ads, choose how long you want your ad placed, sign our updated marketing agreement, and enter your credit card information. Remember that all Classified Ads are automatically moved to the top of the page in your city’s category every two weeks, regardless of the time frame you choose for payments. All ads are continually billed as automatic recurring payments until you unpublish the ad using the (x) next to the ad in your account. <br>
        Here are all of our ad payment options:
    </p>

    <p>
        ~Classified Ads (cost per each city in which the ad is placed, you can place one ad in multiple cities, if desired, with discounted rates and you can choose to pay for 6 or 12 months to receive additional discounts)<br>
        $ 5.00 Every 2 Weeks for 1-4 cities per each city<br>
        $ 4.50 Every 2 Weeks for 5-9 cities per each city<br>
        $ 4.00 Every 2 Weeks for 10-19 cities per each city<br>
        $ 3.00 Every 2 Weeks for 20+ cities per each city<br>
        10% discount for 6 month payment<br>
        15% discount for 12 month payment<br>
    </p>

    <p>
        ~Provider ads (cost per location)<br>
        $215/month for Dispensaries/Storefronts<br>
        $100/month for Delivery Services<br>
        $100/month for Doctor Offices<br>
        $75/month for Grow Stores<br>
    </p>

    <p>
        ~Banner Ads (shown site-wide on random rotation)<br>
        $250/month for top, bottom, or right side placement
    </p>

    <p>We appreciate your contribution to our global Ganjaroad.com community and look forward to helping you get your cannabis business noticed! Please feel free to contact us at anytime with questions or issues, we are here to help!</p>

	<p>Greenest regards,<br/>
	The <a href="ganjaroad.com">ganjaroad.com</a> Team<br/>
    <a href="mailto:info@ganjaroad.com">info@ganjaroad.com</a>
	(888) 904-WEED (9333) -toll free</p>
@stop
