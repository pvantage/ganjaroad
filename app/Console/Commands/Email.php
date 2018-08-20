<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use Carbon\Carbon;
use Mail;
use Lang;

use App\Classified as AppClassified;
use App\Ads as AppAds;
use App\Nearme as AppNearme;
use App\Helpers\Template;

class Email extends Command
{
     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'email cron';
    protected $days = 3;
    protected $recurring_days = 3;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //nasty hack with sleep is because the hourly rate limit for email sending is 250 emails.
        //4 in one minute gives us 240 emails sent in 60 minutes
        //we should remove that when server could handle more than 250 emails per hour...
        //or dispatch the sending with the Job
        $diff = Carbon::now()->addDays($this->days);
        $start = clone $diff->startOfDay();
        $end = clone $diff->endOfDay();

        $classifieds = AppClassified::getActive()->where('active_to', '>=', $start)->where('active_to', '<=', $end)->where('recurring', '=', 0);
        if($classifieds->count()) {
			foreach($classifieds->get() as $classified) {
				$this->sendReminderEmail($classified);
                //nasty hack
                sleep(15);
			}
		}

        $ads = AppAds::getActive()->where('published_to', '>=', $start)->where('published_to', '<=', $end)->where('recurring', '=', 0);
        if($ads->count()) {
			foreach($ads->get() as $ad) {
				$this->sendReminderEmail($ad);
                //nasty hack
                sleep(15);
			}
		}

        $nearmes = AppNearme::getActive()->where('active_to', '>=', $start)->where('active_to', '<=', $end)->where('recurring', '=', 0);
        if($nearmes->count()) {
			foreach($nearmes->get() as $nearme) {
				$this->sendReminderEmail($nearme);
                //nasty hack
                sleep(15);
			}
		}

        /* recurring emails*/
        $diff = Carbon::now()->addDays($this->recurring_days);
        $start = clone $diff->startOfDay();
        $end = clone $diff->endOfDay();

        $ads = AppAds::getActive()->where('published_to', '>=', $start)->where('published_to', '<=', $end)->where('recurring', '=', 1);
        if($ads->count()) {
			foreach($ads->get() as $ad) {
                $this->sendRecurringEmail($ad);
                //nasty hack
                sleep(15);
			}
		}

        $nearmes = AppNearme::getActive()->where('active_to', '>=', $start)->where('active_to', '<=', $end)->where('recurring', '=', 1);
        if($nearmes->count()) {
			foreach($nearmes->get() as $nearme) {
                $this->sendRecurringEmail($nearme);
                //nasty hack
                sleep(15);
			}
		}

        $classifieds = AppClassified::getActive()->where('active_to', '>=', $start)->where('active_to', '<=', $end)->where('recurring', '=', 1);
        if($classifieds->count()) {
			foreach($classifieds->get() as $classified) {
				$this->sendRecurringEmail($classified);
                //nasty hack
                sleep(15);
			}
		}
    }

	private function sendReminderEmail($object)
	{
        $author = $object->author;
        $data = array();
        if(!$author) {
            $to = explode(',', Template::getSetting('report_issue_email'));
            $subject = trans('classified/emails.classifieds-reminder-admin');
            $data['user_name'] = 'Object author not exists';
        } else {
            $to = $author->email;
            $subject = trans('classified/emails.classifieds-reminder');
            $data['user_name'] = $author->first_name . ' ' . $author->last_name;
        }
        $data['days'] = $this->days;
        $data['title'] = $object->title;

        if($object->recurring == 0) {
            Mail::send('emails.reminder_free', compact('data'), function ($m) use ($data, $to, $subject) {
    			$m->from(env('MAIL_USERNAME', ''), env('MAIL_SENDER', ''));
    			$m->to($to, trans('general.site_name'));
    			$m->subject($subject);
    		});
        } else {
            Mail::send('emails.reminder', compact('data'), function ($m) use ($data, $to, $subject) {
    			$m->from(env('MAIL_USERNAME', ''), env('MAIL_SENDER', ''));
    			$m->to($to, trans('general.site_name'));
    			$m->subject($subject);
    		});
        }

	}

    private function sendRecurringEmail($item)
	{
        $author = $item->author;
        if(!$author) {
            $to = explode(',', Template::getSetting('report_issue_email'));
            $subject = trans('emails/general.recurring-expire.subject_admin');
        } else {
            $to = $author->email;
            $subject = trans('emails/general.recurring-expire.subject');
        }
		$data = array();
		$data['login_link'] = route('login');
		$data['title'] = $item->title;
        switch($item->getType()) {
            case('ads'): $data['type'] = Lang::get('general.ads_menu_item'); break;
            case('nearme'): $data['type'] = Lang::get('general.nearme_menu_item'); break;
            case('classified'): $data['type'] = Lang::get('general.classifieds_menu_item'); break;
        }

		Mail::send('emails.recurring-reminder', compact('data'), function ($m) use ($data, $to, $subject) {
			$m->from(env('MAIL_USERNAME', ''), env('MAIL_SENDER', ''));
			$m->to($to, trans('general.site_name'));
			$m->subject($subject);
		});
	}
}
