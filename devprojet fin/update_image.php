<?php
$r = App\Models\Resource::first();
if($r) {
    $r->image = 'server.png';
    $r->save();
    echo "Updated resource " . $r->id . "\n";
}
