<?php

namespace App;

class Option extends SoftDeleteModel
{
		protected $fillable = [
			'hospital_id', 'name', 'confirm', 'price', 'tax_class_id', 'order', 'status'
		];
}
