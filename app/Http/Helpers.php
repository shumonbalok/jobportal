<?php

use App\Models\CompanySetting;

function uploadFile($file, $folder = '/'): ?string
{
    if ($file) {
        $image_name = Rand() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($folder, $image_name, 'public');
    }
    return null;
}

function setImage($url = null, $type = null, $default_image = true): string
{
    if ($type == 'user') {
        return ($url != null) ? asset('storage/' . $url) : ($default_image ? asset('default/default_user.png') : '');
    }
    return ($url != null) ? asset('storage/' . $url) : ($default_image ? asset('default/default_image.png') : '');
}

function company(): CompanySetting
{
    return CompanySetting::first();
}

function updateFile($new_image = null, $folder = '/', $old_image =null)
{
    if($old_image == null) {
        $image_name = Rand() . '.' . $new_image->getClientOriginalExtension();
        return $new_image->storeAs($folder, $image_name, 'public');
    }
    if($new_image != $old_image) {
        unlink(public_path().'/storage/'.$old_image);
        $image_name = Rand() . '.' . $new_image->getClientOriginalExtension();
        return $new_image->storeAs($folder, $image_name, 'public');
    }
    return $old_image;
}

function setDateTime($dateTime)
{
    return date('d-m-Y h:i', strtotime($dateTime));
}

