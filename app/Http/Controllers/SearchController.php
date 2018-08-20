<?php

namespace App\Http\Controllers;

use App\Blog;
use App\Page;
use App\Classified;
use App\ClassifiedCategory;
use App\Nearme;
use App\NearmeCategory;
use App\Http\Requests;
use App\Http\Requests\SettingsRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Response;
use Sentinel;
use Request;
use Session;
use DB;

use GeoIP;
use App\Helpers\Template;

class SearchController extends Controller
{
    private $location;
	private $lat = 0;
    private $lng = 0;
    
    public function __construct()
    {
        $this->location = Template::getCurrentLocation();
        $this->lat = $this->location['lat'];
        $this->lng = $this->location['lon'];
    }
    
	public function blogsSearch($query, $category = 0)
    {
        return Blog::getActiveBlogs()->where(function ($blog) use ($query) {
            $blog->where('title','LIKE', '%'.$query.'%')
            ->orWhere('content', 'LIKE', '%'.$query.'%');
        })->where(function($query) use ($category) {
            if($category) $query->where('blog_category_id', '=', $category);
        })->groupBy('id');
    }
    
    public function pagesSearch($query)
    {
        return Page::where(function ($page) use ($query) {
            $page->where('title','LIKE', '%'.$query.'%')
                ->orWhere('content', 'LIKE', '%'.$query.'%');
        })->where('published', '=', 1)->groupBy('id');
    }
    
    public function classifiedsSearch($query, $category = 0)
    {
        return Classified::getActive()->selectRaw(
            DB::raw('*, ( 3959 * acos( cos( radians(' . $this->lat . ') ) * cos( radians( lattitude ) ) * cos( radians( longitude ) - radians(' . $this->lng . ') ) + sin( radians(' . $this->lat .') ) * sin( radians(lattitude) ) ) ) AS distance')
        )->whereHas('categories', function($query) use ($category){
            if($category) $query->where('category_id', '=', $category);
        })->where(function ($classified) use ($query) {
            $classified->where('title','LIKE', '%'.$query.'%')
                ->orWhere('content', 'LIKE', '%'.$query.'%')
            ->orwhereHas('classifiedfieldsvalues', function($classifiedfieldsvalues) use ($query) {
                $classifiedfieldsvalues->where('value', 'LIKE', '%'.$query.'%');
            });
        })->groupBy('id');
    }
    
    public function nearmeSearch($query, $category = 0)
    {
        return Nearme::getActive()->selectRaw(
                DB::raw('*, ( 3959 * acos( cos( radians(' . $this->lat . ') ) * cos( radians( lattitude ) ) * cos( radians( longitude ) - radians(' . $this->lng . ') ) + sin( radians(' . $this->lat .') ) * sin( radians(lattitude) ) ) ) AS distance')
            )->where(function($nearme) use ($query) {
            $nearme->where('title','LIKE', '%'.$query.'%')
                ->orWhere('content', 'LIKE', '%'.$query.'%');
            })->where(function($query) use ($category) {
                if($category) $query->where('category_id', '=', $category);
            })->groupBy('id');
    }
    
    public function nearmeGlobalSearch($query)
    {
        $categories = NearmeCategory::where('published', '=', 1)->orderBy('position')->get();
        $nearmes = array();
        foreach($categories as $category) {
            $items = $this->nearmeSearch($query, $category->id);

            if($items->count()) {
                $nearmes[] = array(
                    'category' => $category,
                    'items' => $items->orderBy('distance')->get()
                );
            }
        }
        
        return $nearmes;
    }
    
	public function search()
	{
        $limit = (int)Template::getSetting('listview_search_limit');
        $search_query = Request::get('query');
        /*$country = session('classifieds_location');
        
        if(!$country) {
            $country = $this->location['iso_code'];
        }*/
        
        if($search_query) {
            
            $blogs = $this->blogsSearch($search_query)->get();
            $pages = $this->pagesSearch($search_query)->get();
            //$classifieds = $this->classifiedsSearch($search_query)->get();
            $nearmes = $this->nearmeGlobalSearch($search_query);
            /*
            try {
                $category = ClassifiedCategory::where('country', 'LIKE', $country)->where('published', '=', 1)->firstOrFail();
            } catch (ModelNotFoundException $e) {
                $category = ClassifiedCategory::where('country', 'LIKE', 'US')->firstOrFail(); //default
            }
            
            $categories = ClassifiedCategory::getChildrens($category->id);
            */
            
            $location = Template::getCurrentLocation();
            try {
                $result = ClassifiedCategory::selectRaw(
                    DB::raw('*, ( 3959 * acos( cos( radians(' . $location['lat'] . ') ) * cos( radians( lattitude ) ) * cos( radians( longitude ) - radians(' . $location['lon'] . ') ) + sin( radians(' . $location['lat'] .') ) * sin( radians(lattitude) ) ) ) AS distance')
                )->where('published', '=', 1)->groupBy('id')->orderBy('distance')->firstOrFail();

                $result_id = $result->parent;
            } catch (ModelNotFoundException $e) {
                $result_id = 0;
            }

            $categories = ClassifiedCategory::getChildrens($result_id);
            
            return View('frontend.search.search', compact('search_query', 'categories', 'limit', 'blogs', 'pages', 'classifieds', 'nearmes'));
        }
        
        return redirect('/');
    }
    
    
    public function typeSearch($type, $search_query, $category = 0)
    {
        $limit = (int)Template::getSetting('listview_blog_limit');
        $location = GeoIP::getLocation();
        $view = false;
        
        switch($type) {
            case('blogs'):
                $collection_query = $this->blogsSearch($search_query, $category);
                $view = 'frontend.search.blogs';
                break;
            case('pages'):
                $collection_query = $this->pagesSearch($search_query);
                $view = 'frontend.search.pages';
                break;
            case('classifieds'):
                $collection_query = $this->classifiedsSearch($search_query, $category)->orderBy('distance');
                $view = 'frontend.search.classifieds';
                break;
            case('nearme'):
                $collection_query = $this->nearmeSearch($search_query, $category)->orderBy('distance');
                $view = 'frontend.search.nearme';
                break;
        }
        if($view) {
            $results = $collection_query->orderBy('updated_at', 'DESC')->paginate($limit);
            return View($view, compact('search_query', 'type', 'results', 'location')); 
        }
        
        return redirect('/');
    }

}