<?php

namespace App\Models;

use App\Traits\ModelActionTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory,ModelActionTrait;

    public function parent(){
        return $this->belongsTo(Category::class,'parent_id');
    }

    public function getActionButtonsAttribute()
    {
        $deleteAction = $this->deleteModel(route("categories.delete", $this), csrf_token(), "category-table");
        return '<div class="d-inline-block">'
            . '<a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical ti-md"></i></a>'
            . '<ul class="dropdown-menu dropdown-menu-end m-0">'
            . $this->editModal($this->id)
            . $deleteAction
            . '</ul></div>';

    }
}
