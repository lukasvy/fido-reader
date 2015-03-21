<?php namespace Fido\Users;

class User extends \Eloquent {

use \Fido\Core\Validation\ValidationTrait;
use \Fido\Core\Filtering\FilterableTrait;
use \Fido\Core\Sorting\SortableTrait;
use \Fido\Core\Pagination\PaginatableTrait;

	protected $table      = 'users';
	public $timestamps    = true;
	protected $softDelete = false;

	// Fillable properties on user
	protected $fillable   = array('username', 'email', 'first_name', 'last_name' , 'password', 'role');

	// Define properties which can be filtered through
	protected $filterable = array('username', 'email', 'first_name', 'last_name');

	// Define sortable fields
	protected $sortable   = array('username', 'email', 'first_name', 'last_name');

	// Validation trait ruleset
	protected $rules = array(
		'username'   => 'unique:users|regex:/[a-z0-9_-]{4}/',
        'first_name' => 'regex:/[a-zA-Z\'_-]/',
        'last_name'  => 'regex:/[a-zA-Z\'_-]/',
        'email'		 => 'email|unique:users',
        'role'		 => 'in:admin,user'
    );

	/**
	 * Eloquent 
	 * @return belongsToMany
	 */
    public function tags() {
    	return $this->belongsToMany('Fido\Tags\Tag','user_tags','user_id','tag_id');
    }

    /**
     * Hash password attribute
     * @param [type] $value [description]
     */
    public function setPasswordAttribute($value) {
		$this->attributes['password'] = \Hash::make($value);
 	}
	
	/**
	 * Returns all user tags
	 * @param  integer $limit [description]
	 * @return array
	 */
	public function getUserTags ($limit = 10) {
		if (!$this->id) return array();
		$query = "
					SELECT t.tag, ut.footprint 
					FROM tags t, user_tags ut
					WHERE ut.tag_id = t.id
					AND t.active AND ut.active
					AND ut.user_id = ?
					ORDER BY ut.footprint DESC
					LIMIT ?;
				 ";
		return \DB::select($query, array($this->id, $limit));
	}
	

	// BELOW DEPRECATED
	public static function save_user($first_name=NULL,$last_name=NULL,$id=NULL,$role=NULL,$email=NULL,$password=NULL,$username=NULL) {
		$validation = array(
				'first_name'	=> $first_name,
				'last_name'		=> $last_name,
				'role'			=> $role,
			);
		if ($password) {
			$validation['password'] = $password;
		}
		if ($username && !$id) {
			$validation['username'] = $username;
		}
		if ($email && !$id) {
			$validation['email'] = $email;
		}
		if ($id) {
			$v = User::validate($validation,true);
			if (!$v->fails() && $user = User::find($id)) {
				$user->first_name = $first_name;
				$user->last_name = $last_name;
				$user->role = $role;
				if ($password) {
					$user->password = Hash::make($password);
				}
				$user->save();
			} else {
				return 0;
				var_dump($v->messages());
			}
		} else if (!$id){
			// add user if not exists
			$v = User::validate($validation,false);
			if (!$v->fails() ) {
				$user = new User();
				$user->first_name = $first_name;
				$user->last_name = $last_name;
				$user->username = $username;
				$user->email = $email;
				$user->role = $role;
				$user->password = Hash::make($password);
				$user->save();
			}
		} else {
			return 0;
		}
		return 1;
	}
	
		// returns unber of unread articles for user within the feed
	public static function get_no_of_unread($user, $feed_id = NULL) {
		if (!$user || !$feed_id) return false;
		$unread = 0;
		$articles = Article::whereActive(true)
					->whereFeed_id($feed_id)
					->get();
		foreach($articles as $article) {
			$user_article = User_article::whereActive(true)
							->whereArticle_id($article->id)
							->whereUser_id($user->id)
							->where('created_at','>=',$article->created_at)
							->first();
			if (!$user_article) {
				$unread++;
			}
		}
		return $unread; 
	}

	public static function get_unread_articles ($user_id = NULL,$offset=NULL,$skip=NULL,$key=NULL)
	{
		if (!$key) {
			$key = false;
		}
		$articles_ids = false;
		$search_id = 0;
		if (!$user_id) return array();
		if (!$offset) {
			$offset = 10;
		}
		if (!$skip) {
			$skip = 0;
		}
		$total = 0;
		if ($key && $key != 0) {
			if (Cache::has($key)) {
				$articles_ids = Cache::get($key);
				$search_id = $key;
			} else {
				$skip = 0;
			}
		}	
		$skip = $skip * $offset;
		if (!$articles_ids) {
			$articles_ids = DB::select("
				SELECT DISTINCT a.id,a.created_at FROM articles a, feeds f, user_feeds uf, users u
				WHERE
				a.active 
				AND f.active
				AND uf.active
				AND u.id = ?
				AND a.id NOT IN (SELECT article_id FROM user_articles WHERE active AND user_id = u.id AND created_at >= a.created_at)
				AND a.created_at >= uf.created_at
	AND a.feed_id IN (SELECT feed_id FROM user_feeds WHERE user_id = u.id AND active)
				AND a.created_at >= 'yesterday - 10'::timestamp
				ORDER BY a.created_at DESC,a.id
			",array($user_id));
			$search_id = md5(microtime().$user_id);
			Cache::put($search_id,$articles_ids,100);
		}
		if ($articles_ids) {
			$total = count($articles_ids);
			$articles_ids = Arrays::pluck($articles_ids,'id');
			if ($articles_ids) {
				$articles_ids = implode(',',$articles_ids);
				$articles = DB::select("
					SELECT DISTINCT a.id,a.created_at,a.feed_id,a.author,a.url,a.title,a.desc,f.name as source,a.media
					FROM articles a, feeds f
					WHERE a.id IN (".$articles_ids.")
					AND a.active
					AND f.active
					AND a.feed_id = f.id
					ORDER BY created_at DESC
					LIMIT ? OFFSET ?
				",array($offset,$skip));
				if ($articles) {
					$articles = json_decode(json_encode((array) $articles), true);
					//$articles = $articles->toArray();
					return array('articles'=> $articles, 'total' => $total,'search_id' => $search_id);		
				}
				
			}
			
		}
		return array();
	}

	public static function get_users ($id=NULL,$offset=NULL,$skip=NULL,$sort=NULL,$filter=NULL)
	{	
		$total = false;
		$id = NULL;
		if ($filter){
			$users = self::where('username','ILIKE','%'.$filter['filter'].'%')
					->orwhere('first_name','ILIKE','%'.$filter['filter'].'%')
					->orwhere('last_name','ILIKE','%'.$filter['filter'].'%')
					->orwhere('email','ILIKE','%'.$filter['filter'].'%');
			$total = $users->count();
			$users = $users->take($offset)
					->skip($skip)
					->select(array('id','first_name','last_name', 'username','email','role','active'));
			if ($sort){
				$key = key($sort);
				$users = $users->orderBy($key, $sort[$key]);
			}
			$users = $users->get()
					->toArray();
		} else {
			$users = self::
				 take($offset)
				 ->skip($skip)
				 ->select(array('id','first_name','last_name', 'username','email','role','active'));
			if ($sort){
				$key = key($sort);
				$users = $users->orderBy($key, $sort[$key]);
			}
			$users = $users->get()
				 ->toArray();
		} 
		if (!$total) {
			$total = self::count();
		}
		$result = array($users,$total);
		return $result;
	}
}