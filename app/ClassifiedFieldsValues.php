<?php

namespace App;

use Log;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\ClassifiedRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class ClassifiedFieldsValues extends Model {

    protected $table = 'classified_fields_values';

    protected $guarded = ['id'];
    
    public function classified()
    {
        return $this->belongsTo('App\Classified', 'classified_id');
    }
    
    public function field()
    {
        try {
            return ClassifiedFields::where('code', 'LIKE', $this->code)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }

    public static function updateFields(ClassifiedRequest $request, $classified_id)
    {
        ClassifiedFieldsValues::removeFields($classified_id);
        
        return ClassifiedFieldsValues::createFields($request, $classified_id);
    }
    
    public static function removeFields($classified_id)
    {
        $affectedRows = ClassifiedFieldsValues::where('classified_id', '=', $classified_id)->delete();
        
        return $affectedRows;
    }
    
    public static function createFields(ClassifiedRequest $request, $classified_id)
    {
        $classifiedfields = ClassifiedFields::all()->sortBy('position');
        try{
            foreach($classifiedfields as $field) {
                if($value = $request->get($field->code)) {
                    $classifiedFieldsValues = new ClassifiedFieldsValues($request->only('value'));
                    $classifiedFieldsValues->classified_id = $classified_id;
                    $classifiedFieldsValues->code = $field->code;
                    $classifiedFieldsValues->value = $value;

                    $classifiedFieldsValues->save();
                }
            }
        } catch(Exception $e){
            Log::error($e->getMessage());
            return false;
        }
        
        return true;
    }
}