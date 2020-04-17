<?php

namespace BhaktijKoli\OTP;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OTP extends Model
{
  public function getType()
  {
    return $this->belongsTo($this->type, 'type_id');
  }

  public static function generate($model)
  {
    $otp = new OTP();
    $otp->type = get_class($model);
    $otp->type_id = $model->getKey();
    $otp->password = "123456";
    $otp->token = Str::random(128);
    $otp->save();
    return $otp->token;
  }

  public static function verify($token, $password)
  {
    $otp = OTP::where('token', $token)->where("password", $password)->first();
    if(!$otp) return null;
    return $otp->type_id;
  }

}
