<?php

namespace App\Traits;

/**
 * This trait provides methods for showing user feedback alerts in Livewire pages.
 * @author Blessed Zulu <bzulu@zut.edu.zm>
 */
trait CanShowAlerts
{
    /**
     * Shows a PNotify flash message using the flash() method defined in the application's custom JavaScript code.
     *
     * @param string $message The message to display in the flash notification.
     * @param string $type The type of the flash notification (success|warning|error).
     * @return mixed
     */
    public function flash(string $message = "Action completed successfully", string $type = 'success'): mixed
    {
        $flashNotificationData = [
            'msg' => $message,
            'type' => $type,
        ];

        return $this->js("flash(" . json_encode($flashNotificationData) . ")");
    }
}
