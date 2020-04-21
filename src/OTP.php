<?php

namespace BhaktijKoli\OTP;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OTP extends Model
{
  protected $table = "otps";

  public function getType()
  {
    return $this->belongsTo($this->type, 'type_id');
  }

  public static function generate($model, $password=null)
  {
    $type = get_class($model);
    $type_id = $model->getKey();
    $otp = OTP::where('type', $type)->where('type_id', $type_id)->first();
    if($otp) {
      $otp->forceDelete();
    }
    $otp = new OTP();
    $otp->type = $type;
    $otp->type_id = $type_id;
    if($password) {
      $otp->password = $password;
    } else {
      $otp->password = rand(000000,999999);
    }
    $otp->token = Str::random(128);
    $otp->save();
    return [
      'password' => $otp->password,
      'token' => $otp->token,
    ];
  }

  public static function verify($token, $password)
  {
    $otp = OTP::where('token', $token)->where("password", $password)->first();
    if(!$otp) return null;
    $type = "$otp->type";
    return $type::find($otp->type_id);
  }

}
