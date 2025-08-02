<?php

namespace App\Listeners;

use App\Events\AlertProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSosAlertNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AlertProcessed $event): void
    {
        $victim_id = $event->user_id;
        $latitude = $event->latitude;
        $longitude = $event->longitude;

        $previous_data = session('sos_alerts');

        if (empty($previous_data)) {
            session(['sos_alerts' => [
                $victim_id => [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'read' => false,
                ]
            ]]);
        } else {
            if (!array_key_exists($victim_id, $previous_data)) {
                $previous_data[$victim_id] = [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'read' => false,
                ];
            }

            session(['sos_alerts' => $previous_data]);
        }
    }
}
