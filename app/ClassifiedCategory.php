<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Request;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

use App\TierPrices;
use App\Classified;
use App\Helpers\Template;
use App\Helpers\CacheHelper;

class ClassifiedCategory extends Model implements SluggableInterface {

    use SoftDeletes;
	use SluggableTrait;

    protected $dates = ['deleted_at'];
	
	protected $sluggable = [
        'build_from' => 'title',
        'save_to'    => 'slug',
    ];

    protected $table = 'classified_categories';
    protected $guarded = ['id'];
    
    protected $fillable = ['title', 'slug', 'home', 'address', 'lattitude', 'longitude', 'parent', 'schema_id', 'position', 'published', 'amount'];

    public function save(array $options = [])
    {
        parent::save();

        $tiers = Request::get('tier');
        TierPrices::insertClassifiedsTierPrices($this->id, 'classifieds', $tiers);
        
        return $this;
    }
    
    public function classifieds()
    {
        $category_id = array($this->id);
        return Classified::getActive()->whereHas('categories', function($query) use ($category_id){
            $query->where('category_id', '=', $category_id);
        });
    }

    public function isChecked($classified_id)
    {
        $exist = ClassifiedCategoryValues::where('category_id', '=', $this->id)->where('classified_id', '=', $classified_id)->exists();

        return $exist;
    }
    
    public function getCost($category_id = false)
    {
        $price = 0;
        if(!$category_id) {
            $category_id = $this->id;
            $category = $this;
        } else {
            $category = ClassifiedCategory::find($category_id);
        }

        if($category->amount > 0) {
            $price = $category->amount;
        } elseif($category->parent) {
            $price = $category->getCost($category->parent);
        }
        
        return $price;
    }
    
    public function tierPrices()
    {
        return $this->hasMany('App\TierPrices', 'category_id')->where('type', '=', 'classifieds')->orderBy('priority');
    }
    
    public function parentCategory()
    {
        try {
            return ClassifiedCategory::where('id', '=', $this->parent)->firstOrFail();
        } catch (ModelNotFoundException $e) {}
        
        return 0;
    }
    
    public function getCategoryTierPricing($category_id = false)
    {
        $tiers = false;
        if(!$category_id) {
            $category_id = $this->id;
            $category = $this;
        } else {
            $category = ClassifiedCategory::find($category_id);
        }
        
        if($category->tierPrices->count()) {
            $tiers = $category->tierPrices;
        } elseif($category->parent) {
            $tiers = $category->getCategoryTierPricing($category->parent);
        }
        
        if($tiers) {
            $tiers->map(function ($tier) {
                $tier['formated_price'] = Template::convertPrice($tier['price']);
                return $tier;
            });
        }
        
        return $tiers;
    }
    
    public function matchTierPrice($items = 0)
    {
        $tier_prices = $this->getCategoryTierPricing();
        $matching_tiers = array();
        
        if($tier_prices) {
            foreach($tier_prices as $tier) {
                if($items >= $tier->from && $items <= $tier->to) {
                    $matching_tiers[$tier->priority] = $tier->price;
                }
            }

            if($matching_tiers) {
                ksort($matching_tiers);

                return array_shift($matching_tiers);
            }
        }
        
        return false;
    }
    
    public function getCategoryMaxTier()
    {
        $max = 0;
        $category_tier = $this->getCategoryTierPricing();
        if($category_tier) {
            foreach($category_tier as $tier) {
                if($tier->to > $max) $max = $tier->to;
            }
        }
        
        if($max) --$max; //include main category
        
        return $max;
    }
    
    public function getParentsArray($parent_id = false, $parents = array())
    {
        if($parent_id !== 0) {
            if(!$parent_id) $parent_id = $this->parent;
            try {
				$category = ClassifiedCategory::where('id', '=', $parent_id)->firstOrFail();
				if($category && !in_array($category->id, $parents)) {
					$parents = array_merge($parents, array($category->id));
					$parents = $this->getParentsArray($category->parent, $parents);
				}
			} catch (ModelNotFoundException $e) {

			}
        }
        
        return $parents;
    }
    
    public function haveSchemaChildren($schema_id, $parent = false, $return_children = false)
    {
        $exist = CacheHelper::haveClassifiedCategorySchemaChildren($this, $schema_id, $parent);
        if($exist && $return_children) {
            try {
                $exist = ClassifiedCategory::where('id', '=', $exist)->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return false;
            }
        }
        
        return $exist;
    }
    
    public static function checkIfhaveSchemaChildren(ClassifiedCategory $category, $schema_id, $parent = false)
	{
		if(!$parent) $parent = $category->id;
        $exist = false;
        $categories = ClassifiedCategory::where('parent', '=', $parent);
        if($categories->count()) {
            foreach($categories->get() as $category) {
                if(!$exist) {
                    if($category->schema_id == $schema_id) {
                        return $category->id;
                    } else $exist = self::checkIfhaveSchemaChildren($category, $schema_id, $category->id);
                }
            }
        }
		
		return $exist;
	}
    
    public static function getChildrens($parent_id = 0, $recursive = true)
    {
        return CacheHelper::getClassifiedCategoryChildrensCache($parent_id, $recursive);
    }
    
    public static function getChildrensCategories($parent_id = 0, $recursive = true)
    {
        $array = array();
        $categories = ClassifiedCategory::where('parent', '=', $parent_id)->orderBy('position');
        if($categories->count()) {
            foreach($categories->get() as $category) {
                $array[$category->id] = $category;
                if($recursive) 
                    if(ClassifiedCategory::haveChildrens($category->id)) 
                        $array[$category->id]['childrens'] = ClassifiedCategory::getChildrensCategories($category->id);
            }
        }
        
        return $array;
    }
    
    public static function haveChildrens($parent_id)
    {
        $categories = ClassifiedCategory::where('parent', '=', $parent_id);
        
        return $categories->count();
    }
    
    public function classifiedsQuery($query = '')
    {
        $category = array($this->id);
        return Classified::getActive()->whereHas('categories', function($query) use ($category){
            if($category) $query->where('category_id', '=', $category);
        })->where(function ($classified) use ($query) {
            $classified->where('title','LIKE', '%'.$query.'%')
                ->orWhere('content', 'LIKE', '%'.$query.'%')
            ->orwhereHas('classifiedfieldsvalues', function($classifiedfieldsvalues) use ($query) {
                $classifiedfieldsvalues->where('value', 'LIKE', '%'.$query.'%');
            });
        });
    }
    
    public static function updatePositions($parent)
    {
        $collection = ClassifiedCategory::where('parent', '=', $parent)->orderBy('position', 'asc')->get();
        
        $counter = 0;
        foreach($collection as $item) {
            $category = ClassifiedCategory::find($item->id);
            $category->position = ++$counter;
            $category->save();
        }
    }
    
    public static function getMainCategories()
    {
        return ClassifiedCategory::where('home', '=', 1);
    }
}