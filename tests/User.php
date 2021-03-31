<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Tests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed email
 * @property mixed username
 */
class User extends Model implements Authenticatable
{
    use \Illuminate\Auth\Authenticatable;

    protected $fillable = ['id', 'email', 'username'];
}
