<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Tests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $email
 * @property string $username
 */
class User extends Model implements Authenticatable
{
    use \Illuminate\Auth\Authenticatable;

    /**
     * @var string[]
     */
    protected $fillable = ['id', 'email', 'username'];
}
