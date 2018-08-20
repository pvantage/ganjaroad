<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use File;
use Carbon\Carbon;

use App\Blog;
use App\BlogCategory;
use App\Nearme as AppNearme;
use App\NearmeCategory;
use App\Classified as AppClassified;
use App\ClassifiedCategory;
use App\ReportedItems;
use App\Issues;
use App\Page;
use Sitemap;

use App\Helpers\Template;

class Weed extends Command
{
     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'main cron';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->generateSitemap();
    }
	
	public function generateSitemap()
	{
		//static pages
        Sitemap::addTag(route('home'), Carbon::now(), 'daily', '1');
        Sitemap::addTag(route('contact'), Carbon::now(), 'daily', '1');
        Sitemap::addTag(route('login'), Carbon::now(), 'daily', '1');
        Sitemap::addTag(route('register'), Carbon::now(), 'daily', '1');
        Sitemap::addTag(route('forgot-password'), Carbon::now(), 'daily', '1');
        Sitemap::addTag(route('nearme'), Carbon::now(), 'daily', '1');
        Sitemap::addTag(route('classifieds'), Carbon::now(), 'daily', '1');
        
        $pages = Page::where('published', '=', 1)->get();
        foreach ($pages as $page) {
            Sitemap::addTag(Template::pageLink($page->id), $page->updated_at, 'daily', '1');
        }
        
        $categories = BlogCategory::all();
        foreach ($categories as $category) {
            Sitemap::addTag(Template::blogCategoryLink($category->id), $category->updated_at, 'daily', '1');
        }

        $tags = Blog::allTags();
        foreach ($tags as $tag) {
            Sitemap::addTag(Template::blogTagLink($tag), Carbon::now(), 'daily', '1');
        }
        
        $blogs = Blog::getActiveBlogs()->get();
        foreach ($blogs as $blog) {
            Sitemap::addTag(Template::blogLink($blog->id), $blog->updated_at, 'daily', '1');
        }
        
        $categories = NearmeCategory::where('published', '=', 1)->get();
        foreach ($categories as $category) {
            Sitemap::addTag(Template::nearMeCategoryLink($category->id), $category->updated_at, 'daily', '1');
        }
        
        $nearmes = AppNearme::getActive()->get();
        foreach($nearmes as $item) {
            Sitemap::addTag(Template::nearMeLink($item->id), $item->updated_at, 'daily', '1');
        }
        
        $categories = ClassifiedCategory::getChildrens();
        foreach ($categories as $category) {
            $this->generateClassifiedCategoryLinks($category);
        }
        
        $classifieds = AppClassified::getActive()->get();
        foreach($classifieds as $item) {
            Sitemap::addTag(Template::classifiedsLink($item->id), $item->updated_at, 'daily', '1');
        }
        
        $xml = (string)Sitemap::xml();
        $path = public_path() . DIRECTORY_SEPARATOR . 'sitemap.xml';

        File::put($path, $xml);
	}
	
	public function generateClassifiedCategoryLinks($category, $level = 0)
    {
        if($level) {
            $priority = round(1/$level, 1);
        } else $priority = $level;
        Sitemap::addTag(Template::classifiedsCategoryLink($category->id), $category->updated_at, 'daily', $priority);
        if(isset($category['childrens']) && $category['childrens']) {
            foreach($category['childrens'] as $children) {
                if($children->classifieds()->count())
                    $this->generateClassifiedCategoryLinks($children, ++$level);
            }
        }
    }
}
