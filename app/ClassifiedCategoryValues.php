<?php

namespace App;

use App\ClassifiedMulticategory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\ClassifiedCategoryValuesRequest;
use App\Http\Requests\ClassifiedRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Helpers\Template;

class ClassifiedCategoryValues extends Model {

    protected $table = 'classified_categories_values';

    protected $guarded = ['id'];

    public function classified()
    {
        return $this->belongsTo('App\Classified', 'classified_id');
    }

	public static function updateCategoriesValues($categories, $classified_id, $multicategory = array())
	{
		$classified = ClassifiedFields::find($classified_id);
        ClassifiedCategoryValues::clearCategories($classified_id);
		$model = new ClassifiedCategoryValues();

        if($categories) {
			$values_array = array();
			foreach($categories as $category) {
				$values_array[] = array(
					'category_id' => $category,
					'classified_id' => $classified_id
				);

			}
			$model->insert($values_array);
		}
        
        if($multicategory) {
            $last_category = ClassifiedCategory::find(end($categories));
            $max = $last_category->getCategoryMaxTier();

            $values_array = array();
            $parents = array();
            $i = 0;
			foreach($multicategory as $category) {
                //no limits for admin
                if(Template::isAdminRoute()) {
                    
                } else {
                    if(++$i > $max ) break;
                }
                
                if($category) {
                    $classified_category = ClassifiedCategory::find($category);
                    if($search_category = $classified_category->haveSchemaChildren($last_category->schema_id, false, true)) {
                        $parents = array_merge($search_category->getParentsArray(), $parents);
                        $parents[] = $search_category->id;
                        
                        $multicategory = new ClassifiedMulticategory();
                        $multicategory->classified_id = $classified_id;
                        $multicategory->category_id = $category;
                        $multicategory->save();
                    }
                }
            }
            
            $parents = array_unique($parents);
            $diff = array_diff($categories, $parents);
            $values_array = array();
            
            foreach($parents as $insert_id) {
                if(!in_array($insert_id, $diff)) {
                    $values_array[] = array(
                        'category_id' => $insert_id,
                        'classified_id' => $classified_id,
                        'multicity' => 1
                    );
                }
            }
            $model->insert($values_array);
        }

        return true;
	}
    
    public static function clearCategories($classified_id)
    {
        $affectedCategories = ClassifiedCategoryValues::where('classified_id', '=', $classified_id)->delete();
        $affectedMultiCategories = ClassifiedMulticategory::where('classified_id', '=', $classified_id)->delete();
    }
}