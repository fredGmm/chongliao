<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;
use \Illuminate\Database\Eloquent\Builder;

/**
 * Class Image
 * @package App\Models
 * @property integer $id
 * @property string $name
 * @property string $phone
 */
class Customer extends Model
{
    use ValidatesRequests;
    //
    protected $table = 'customer';

    protected $rules = [
        'name' => 'required|max:500',
        'phone' => 'required|numeric|unique:customer',
    ];
    protected $message = [];
    protected $fillable = ['name', 'phone', 'qq','weixin','height','birthday','gender','note','introduce'];

    protected $appends = [];
    protected $hidden = [];

    public $errors;

    public function validate($data)
    {
        // make a new validator object
        $v = Validator::make($data, $this->rules);

        // check for failure
        if ($v->fails()) {
            // set errors and return false
            $this->errors[] = $v->errors()->first();
            return false;
        }
        return true;
    }
}
