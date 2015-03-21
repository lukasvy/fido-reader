<?php

namespace Fido\Tags;

class Tag extends \Eloquent {

use \Fido\Core\Validation\ValidationTrait;

	// Tags tablename
	protected $table = 'tags';

	// Fillable properties on tag
	protected $fillable = array('tag');

	// Use timestamps
	public $timestamps = true;
	
	// Validation rules
	private static $rules = array(
        'tag'  => 'Required|alpha_dash'
    );


	/**
	 * Relationship to Users
	 * @return belongsToMany
	 */
	public function users () {
		return $this->belongsToMany('User','user_tags','tag_id','user_id')->whereActive(true);
	}
    
    public static function save_tag ($tag = NULL) {
	    if (!$tag) return false;
	    $t = self::whereActive(true)
					->whereTag($tag)
					->first();
		if (!$t) {
			$v = self::validate(array(
				'tag'	=> $tag));
			if ($v->passes()){
				$new_tag = new Tag();
				$new_tag->tag = $tag;
				$new_tag->save();
			}
		}
    }
    
    public static function get_tags ($id=NULL,$offset=NULL,$skip=NULL,$sort=NULL,$filter=NULL)
	{	
		$total = false;
		if ($id) {
			$user = Tag::whereId($id)
			->select(array('id','tag','active', 'created_at'))
			->first()
			->toArray();
			return $user;
		}
		if ($filter){
			$tags = self::where('tag','ILIKE','%'.$filter['filter'].'%')
					->select(array('id','tag','active', 'created_at'));
			$total = count ($tags->get()->toArray());
			$tags = $tags->take($offset)
					->skip($skip);
			if ($sort){
				$key = key($sort);
				$tags = $tags->orderBy($key, $sort[$key]);
			}
			$tags = $tags->get();
			$tags = $tags->toArray();
		} else {
			$tags = self::
				 take($offset)
				 ->skip($skip)
				 ->select(array('id','tag','active', 'created_at'));
			if ($sort){
				$key = key($sort);
				$tags = $tags->orderBy($key, $sort[$key]);
			}
			$tags = $tags->get()
				 ->toArray();
		} 
		if (!$total) {
			$total = self::count();
		}
		$result = array($tags,$total);
		return $result;
	}

}