<?php

namespace App\Models;

use App\Traits\ModelActionTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory,ModelActionTrait;

    protected $fillable = ['name','phone','email'];

    public function getActionButtonsAttribute()
    {
        $deleteAction = $this->deleteModel(route("leads.delete", $this), csrf_token(), "leads-table");
        return '<div class="d-inline-block">'
            . '<a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical ti-md"></i></a>'
            . '<ul class="dropdown-menu dropdown-menu-end m-0">'
            . $this->editModal($this->id)
            . $deleteAction
//            . $this->assignStaffModal($this->id)
            . '</ul></div>';

    }
    public function staff()
    {
        return $this->belongsToMany(User::class, 'lead_staff', 'lead_id', 'staff_id')
            ->withTimestamps();
    }

    public function assignStaffModal($id){
        return '<li><a href="javascript:void(0);" class="dropdown-item" onclick="assignStaff(`' . $id . '`)">Assign Staff</a><li>';
    }


}
