<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\UserResolver;
use Laravel\Passport\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;
    use EntrustUserTrait;
    use HasApiTokens, Notifiable;
    // use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'avatar', 'email', 'phone', 'password', 'active', 'address', 'start_date', 'basic_salary'
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
     * Full path of images
     */
    public $imgPath = 'storage/images/users';

    /**
     * Physical path of upload folder
     */
    public $uploadPath = 'app/public/images/users';

    /**
     * Image width
     */
    public $imgWidth  = 200;

    /**
     * Image height
     */
    public $imgHeight = 200;

    const ENABLE = 1;
    const DISABLE = 0;

    const LIST_STATUS = [
        self::ENABLE => 'Đã kích hoạt',
        self::DISABLE => 'Chưa kích hoạt'
    ];

    public static function resolveId()
    {
        return Auth::check() ? Auth::user()->getAuthIdentifier() : null;
    }

    public function isActive () {
        return $this->active == self::ENABLE;
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('superadmin');
    }

    /**
     * tra ve duong dan ah day du
     * @param  string $pic_name Ten anh
     * @return string           Duong dan day du cua anh
     */
    public function getImage()
    {
        if ($this->avatar == '') {
            return asset('images/default-avatar.png');
        }
        return asset($this->imgPath . '/' . $this->avatar);
    }

    public function getCreatedAt()
    {
        return $this->created_at ? $this->created_at->format('d-m-Y H:i:s') : null;
    }

    public function getDeletedAt()
    {
        return $this->deleted_at ? $this->deleted_at->format('d-m-Y') : null;
    }

}
