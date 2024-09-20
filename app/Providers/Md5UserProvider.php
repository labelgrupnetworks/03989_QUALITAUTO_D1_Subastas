<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Config;

class Md5UserProvider implements UserProvider
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function retrieveById($identifier)
    {
        return $this->createModel()->newQuery()->find($identifier);
    }

    public function retrieveByToken($identifier, $token)
    {
        $model = $this->createModel();

        return $model->newQuery()
            ->where($model->getAuthIdentifierName(), $identifier)
            ->where($model->getRememberTokenName(), $token)
            ->first();
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        $user->setRememberToken($token);
        $user->save();
    }

    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials)) {
            return null;
        }

        $query = $this->createModel()->newQuery();

        foreach ($credentials as $key => $value) {
            if (!str_contains($key, 'password')) {
                $query->where("LOWER($key)", mb_strtolower($value));
            }
        }

        return $query->first();
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $plainPassword = $credentials['password'];
        $hashedPassword = md5(Config::get('app.password_MD5') . $plainPassword);

        return $hashedPassword === $user->getAuthPassword();
    }

    protected function createModel()
    {
        $class = '\\' . ltrim($this->model, '\\');

        return new $class;
    }
}
