<?php

namespace App\Http\Controllers;

use DB;
use Excel;
use App\Ads;
use Datatables;
use App\Nearme;
use App\Classified;
use App\Helpers\Template;
use Illuminate\Http\Request;

class ReportsController extends WeedController
{
    public function adsReport()
    {
        return View('admin.reports.ads');
    }
    
    public function classifiedReport()
    {
        return View('admin.reports.classified');
    }
    
    public function nearmeReport()
    {
        return View('admin.reports.nearme');
    }
        
    public function adsDataList()
    {
        $query = Ads::reportsQuery();

        return Datatables::of($query)
            ->add_column('user_groups', function($object) {
                $roles = Template::getReportsUserRoles($object->user_id);
                return implode(', ', $roles);
            })->make(true);
    }

    /**
     * Classified data list.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function classifiedDataList(Request $request)
    {
        $query = Classified::query()
            ->join('users', 'users.id', '=', 'classifieds.user_id')
            ->select([
                'user_id',
                'title as classified_title',
                DB::raw("CONCAT(users.first_name,'-',users.last_name) as fullname"),
                'users.email as user_email',
                'users.state as user_state',
                'users.city as user_city',
                DB::raw("(SELECT value from classified_fields_values WHERE classified_id = classifieds.id AND code = 'state') AS classified_state"),
                DB::raw("(SELECT value from classified_fields_values WHERE classified_id = classifieds.id AND code = 'city') AS classified_city"),
                DB::raw("(SELECT value from classified_fields_values WHERE classified_id = classifieds.id AND code = 'phone') AS classified_phone"),
                DB::raw("(SELECT value from classified_fields_values WHERE classified_id = classifieds.id AND code = 'email') AS classified_email"),
            ])
        ;

        $search = array_get($request->get('search'), 'value');

        if ($search) {
            $query
                ->where(DB::raw("CONCAT(users.first_name,'-',users.last_name)"), 'like', '%' . $search . '%')
                ->orWhere('title', 'like', '%' . $search . '%')
                ->orWhere('users.email', 'like', '%' . $search . '%')
                ->orWhere(DB::raw("(SELECT value from classified_fields_values WHERE classified_id = classifieds.id AND code = 'email')"), 'like', '%' . $search . '%')
            ;
        }

        return Datatables::of($query)
            ->add_column('user_groups', function($object) {
                $roles = Template::getReportsUserRoles($object->user_id);
                return implode(', ', $roles);
            })->make(true)
        ;
    }
    
    public function nearmeDataList()
    {
        $query = Nearme::reportsQuery();

        return Datatables::of($query)
            ->add_column('user_groups', function($object) {
                $roles = Template::getReportsUserRoles($object->user_id);
                if($roles) return implode(', ', $roles);
                
                return '';
            })->make(true);
    }
    
    public function adsExportReport()
    {
        $ads = Ads::reportsQuery();
        $data = array();
        
        if($ads->count()) {
            $data = array();
            $i = 0;
            foreach($ads->get() as $object) {
                $roles = Template::getReportsUserRoles($object->user_id);
                $data[$i] = (array)$object;
                $data[$i]['user_groups'] = implode(', ', $roles);
                ++$i;
            }

            Excel::create('ads', function($excel) use($data) {
                $excel->sheet('ads', function($sheet) use($data) {
                    $sheet->fromArray($data);
                });
            })->export('csv');
        }
    }
    
    public function classifiedExportReport()
    {
        $nearmes = Classified::reportsQuery();
        $data = array();
        
        if($nearmes->count()) {
            $data = array();
            $i = 0;
            foreach($nearmes->get() as $object) {
                $roles = Template::getReportsUserRoles($object->user_id);
                $data[$i] = (array)$object;
                $data[$i]['user_groups'] = implode(', ', $roles);
                ++$i;
            }

            Excel::create('classified', function($excel) use($data) {
                $excel->sheet('classified', function($sheet) use($data) {
                    $sheet->fromArray($data);
                });
            })->export('csv');
        }
    }
    
    public function nearmeExportReport()
    {
        $nearmes = Nearme::reportsQuery();
        $data = array();
        
        if($nearmes->count()) {
            $data = array();
            $i = 0;
            foreach($nearmes->get() as $object) {
                $roles = Template::getReportsUserRoles($object->user_id);
                $data[$i] = (array)$object;
                $data[$i]['user_groups'] = implode(', ', $roles);
                ++$i;
            }

            Excel::create('nearme', function($excel) use($data) {
                $excel->sheet('nearme', function($sheet) use($data) {
                    $sheet->fromArray($data);
                });
            })->export('csv');
        }
    }
}
