<?php

namespace App\Models;

trait StrIdModelTrait
{
    // 生成を繰り返す
    protected function genId()
    {
      $uid = uniqid();
      $exists = $this->where('id', $uid)->first;
      if(empty($exists)){
        return $uid;
      }
      return $this->genId();
    }

    protected function addId($data)
    {
      $data['data']['id'] = $this->genId();
      return $data;
    }
}
