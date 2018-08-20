<?php

namespace App\Helpers;

use Cache;
use Carbon\Carbon;
use Artisan;
use File;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Classified;
use App\ClassifiedCategory;
use App\Block;
use App\Menu;
use App\Helpers\Template;

class CacheHelper
{
	public static function showCacheVersion()
	{
		if(Template::isAdminRoute()) {
			return false;
		}

		return true;
	}

	public static function getCacheLifeTime()
	{
		$now = Carbon::now();
        $end = Carbon::now();
        $end->hour = 23;
        $end->minute = 59;

        return $now->diffInMinutes($end);
	}

	public static function clearAllCache()
	{
		Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Cache::flush();

        $dir = storage_path() . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'cache';

		return File::cleanDirectory($dir);
	}

	public static function reloadActiveClassifieds()
	{
		Cache::store('file')->forget('get_active_classifieds');
	}

    /**
     * Get active classifieds.
     *
     * @param bool $includeDate
     * @param bool $useCache
     *
     * @return mixed
     */
	public static function getActiveClassifieds($includeDate = true, $useCache = true)
	{
	    if ($useCache && self::showCacheVersion()) {
            $ids = Cache::store('file')
                ->remember(
                    'get_active_classifieds',
                    self::getCacheLifeTime(),
                    function () use ($includeDate) {
                        return Classified::getActiveClassifieds($includeDate);
                    }
                )
            ;
        } else {
            $ids = Classified::getActiveClassifieds($includeDate);
        }

        return $ids;
	}

	public static function getClassifiedCategoryChildrensCache($parent_id = 0, $recursive = true)
	{
		$responsive_bool = ($recursive) ? 'true' : 'false';
        $categories = Cache::store('file')->rememberForever('categories['.$parent_id.']['.$responsive_bool.']', function() use($parent_id, $recursive) {
            return ClassifiedCategory::getChildrensCategories($parent_id, $recursive);
        });

        return $categories;
	}

	public static function getFooterBlockCache($slug)
	{
		$use_cache = self::showCacheVersion();
		if($use_cache) {
			$html = Cache::rememberForever('footer_block['.$slug.']', function() use($slug) {
				return self::getFooterBlock($slug);
			});
		} else $html = self::getFooterBlock($slug);

        return $html;
	}

	public static function getFooterBlock($slug)
	{
		try {
			$block = Block::where('slug', 'LIKE', $slug)->where('published', '=', 1)->firstOrFail();

			return '<div class="block-nav-footer block-nav">'
					. '<h2 class="title-block">'.$block->title.'</h2>'
					.$block->content.'</div>';

		} catch (ModelNotFoundException $e) {}

		return '';
	}

	public static function countClassifiedSubcategories(ClassifiedCategory $category, $query = false)
	{
		$use_cache = self::showCacheVersion();
		if($use_cache) {
			$minutes = self::getCacheLifeTime();
			$count = Cache::store('file')->remember('generate_classifieds_categories['.$category->id.']['.$query.']', $minutes, function() use($category, $query) {
				return $category->classifiedsQuery($query)->count();
			});
			return $count;
		} else return $category->classifiedsQuery($query)->count();
	}

	public static function haveClassifiedCategorySchemaChildren(ClassifiedCategory $category, $schema_id, $parent = false)
	{
		$use_cache = self::showCacheVersion();
		if($use_cache) {
			$exist = Cache::rememberForever('schema_children['.$category->id.']', function() use($category, $schema_id, $parent) {
				return ClassifiedCategory::checkIfhaveSchemaChildren($category, $schema_id, $parent);
			});
		} else {
			$exist = ClassifiedCategory::checkIfhaveSchemaChildren($category, $schema_id, $parent);
		}

		return $exist;
	}

	public static function checkIfMenuHaveChildrens(Menu $menu)
	{
		$use_cache = self::showCacheVersion();
		$count = false;
		if(false) {
			$count = Cache::store('file')->rememberForever('have_childrens['.$menu->id.']', function() use($menu) {
				$menus = Menu::getActiveByParent($menu);

				if($menus->count()) {
					return true;
				}

				return false;
			});
		} else {
			$menus = Menu::getActiveByParent($menu);

			if($menus->count()) {
				$count = true;
			}
		}

		return $count;
	}
}
