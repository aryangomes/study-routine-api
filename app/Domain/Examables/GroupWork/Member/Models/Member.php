<?php

namespace App\Domain\Examables\GroupWork\Member\Models;

use App\Domain\Examables\GroupWork\Models\GroupWork;
use Database\Factories\Examables\GroupWork\MemberFactory;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members_group_work';
    protected $fillable = ['user_id', 'group_work_id'];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return MemberFactory::new();
    }


    /**
     * Get the groupWork that owns the Member
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function groupWork(): BelongsTo
    {
        return $this->belongsTo(GroupWork::class);
    }

    /**
     * Get the groupWork that owns the Member
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Owner's Group Work (who created the group work)
     * 
     * @return bool
     */
    public function getIsOwnerOfGroupWorkAttribute(): bool
    {
        $isOwnerOfGroupWork = ($this->groupWork->exam->subject->user_id ==
            $this->user_id);

        return $isOwnerOfGroupWork;
    }
}
