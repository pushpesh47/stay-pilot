<?php

namespace App\Traits;

use App\Models\UserMeta;

trait HasMeta
{
    public function meta()
    {
        return $this->hasMany(UserMeta::class, 'user_id');
    }


    public function metaValue(string $key, $default = null)
    {
        return optional(
            $this->meta->firstWhere('meta_key', $key)
        )->meta_value ?? $default;
    }


    public function setMeta(string $key, $value)
    {

        if ($value === null || $value === '') {
            return;
        }

        UserMeta::updateOrCreate(
            ['user_id' => $this->id, 'meta_key' => $key],
            ['meta_value' => $value]
        );
    }


    public function setMetaBulk(array $data)
    {
        foreach ($data as $key => $value) {

            if ($value === null || $value === '') {
                continue;
            }

            $this->setMeta($key, $value);
        }
    }
}
