<?php

namespace Matondojk\FilamentEventCalendar\Models;

use Matondojk\FilamentEventCalendar\Jobs\SendEventInvitationJob;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;

class Event extends Model
{
    use BroadcastsEvents, HasFactory;

    protected $fillable = [
        'title',
        'description',
        'format',
        'platform',
        'meeting_link',
        'starts_at',
        'ends_at',
        'location',
        'user_id',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->using(EventUser::class)
            ->withPivot('rsvp_status')
            ->withTimestamps();
    }

    /**
     * Get the channels that model events should broadcast on.
     *
     * @return array<int, Channel|Model>
     */
    public function broadcastOn(string $event): array
    {
        return [
            new PrivateChannel('events'),
        ];
    }
}
