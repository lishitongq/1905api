<?php
namespace App\Model;

use DemeterChain\C;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $primaryKey="id";

    /**
     * 关联到模型的数据表 @var string
     */
    protected $table = 'p_users';
    /**
     * 可以被批量赋值的属性. @var array
     */
    protected $guarded = [];

    /**
     * 表明模型是否应该被打上时间戳, @var bool
     */
    public $timestamps = false;
}
