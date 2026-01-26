<?php

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    // This model extends the standard Laravel DatabaseNotification
    // to ensure compatibility with the 'notifications' table schema
    // created by the Notifiable trait/migrations.
}