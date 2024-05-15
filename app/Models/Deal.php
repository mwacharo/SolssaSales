<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deal extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'email',
        'website',
        'service_ids',
        'industry_ids',
        'user_id',
        'branch_ids',
        'deal_source',
        'phone',
        'status',
        'comment',
        'priority',
        'contact_person',
        'close_date',
        'created_at',
        'updated_at'
    ];
    protected $attributes = [
        'status' => 'Initiated',
    ];

    // public function industry()
    // {
    //     return $this->belongsTo(Industry::class);
    // }
    public function industries()
    {
        return $this->belongsToMany(Industry::class, 'deal_industry');
    }
    public function services()
    {
        return $this->belongsToMany(Service::class, 'deal_service');
    }
    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'deal_branch');
    }





    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the branch associated with the deal.
     */
    // public function branch()
    // {
    //     return $this->belongsTo(Branch::class);
    // }

    // Add any other relationships or business logic methods as necessary...

    /**
     * Scope a query to only include active deals.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include deals of a given priority.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $priority
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }
}
