<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailVerify extends Model
{
    protected $table = 'email_verifies';

    protected $fillable = ['email', 'token'];
}
