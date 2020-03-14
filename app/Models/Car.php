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
 *      schema="Cars",
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
class Car extends Model
{

    protected $table = 'cars';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'release_year', 'color', 'category', 'price', 'brand_id'
    ];


    protected $casts = [
        'price' => 'integer'
    ];
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|min:2|max:50',
        'color' => 'required|string|min:4|max:50',
        'price' => 'required|integer',
        'release_year' => 'string',
        'brand_id' => 'required|integer|exists:car_brands,id'
    ];

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

}
