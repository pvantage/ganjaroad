<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Issues extends Model {

    protected $table = 'issues';

    protected $guarded = ['id'];
    
    public static function insertIssues($item_id, $type, $issues = array())
    {
        $issued = false;
        foreach($issues as $issue) {
            
            $issued = $issue['code'];
            
            try {
                $exist = Issues::where('type', 'LIKE', $type)->where('code', 'LIKE', $issue['code'])->where('item_id', '=', $item_id)->firstOrFail();
                
                $exist->reviewed = 0;
                $exist->save();
            } catch (ModelNotFoundException $e) {
                $issue_model = new Issues();
                $issue_model->item_id = $item_id;
                $issue_model->type = $type;
                $issue_model->code = $issue['code'];
                $issue_model->comment = $issue['comment'];

                $issue_model->save();
            }
            
            return $issued;
        }
        
        return false;
    }
    
    public static function removeIssues($item_id)
    {
        $affectedRows = Issues::where('item_id', '=', $item_id)->delete();
        if($affectedRows) {
            return true;
        }
        
        return false;
    }

    public function classified()
    {
        return $this->belongsTo('App\Classified', 'item_id');//->where('type', 'LIKE', 'classified');
    }
    
    public function item()
    {
        if($this->type == 'classified') return $this->classified();
    }
    
    public function isActive()
    {
        $active = false;
        if($this->type == 'classified') {
            if($this->classified) {
                $active = true;
            }
        }
        
        return $active;
    }
    
    public function getType()
    {
        if($this->code == 'words') return 'Bad words found';
        
        return '';
    }
    
    public static function getAllIssuedItems()
    {
        return Issues::where('reviewed', '=', 0)->groupBy('item_id');
    }
}
