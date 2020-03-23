<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as Model;

/**
 * @property integer $id
 *
 * @method static Builder exists($email)
 * @method static User create(array $data)
 *
 * @OA\Schema(
 *      schema="Customers",
 *      required={""},
 *      @OA\Property(
 *          property="id",
 *          description="id",
 *          format="int32"
 *      ),
 *      @OA\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @OA\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class Customer extends Model
{

    protected $table = 'customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'patronymic', 'last_name', 'salary', 'experience'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'salary' => 'integer',
    ];


    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|min:4|max:12',
        'patronymic' => 'string|min:4|max:12',
        'last_name' => 'string|min:4|max:12',
        'salary' => 'required|integer',
        'experience' => 'integer'
    ];
}
