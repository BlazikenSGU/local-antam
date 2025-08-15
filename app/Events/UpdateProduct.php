<?php

namespace App\Events;

use App\Models\Product;
use App\Post;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UpdateProduct
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $old_product = null;
    public $user = null;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($old_product, $user)
    {
        $this->old_product = $old_product;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
