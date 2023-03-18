<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_recommend extends Model
{
    use HasFactory;
    protected $table = "pro_recommend_products";
    protected $primaryKey = 'id';
    protected $dateFormat = 'Y-m-d';
    protected $fillable = [
    ];}
