<?php

namespace App\Events;

use App\Core\CI_Controller;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CIEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var CI_Controller */
    public $ci;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CI_Controller $ci)
    {
        $this->ci = $ci;
    }
}
