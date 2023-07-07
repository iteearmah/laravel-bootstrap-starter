<?php

namespace App\Facades;

use Illuminate\Support\Facades\File;

class Helper
{
    public static function deleteFileFromPublicFolder($path, $filename)
    : void {
        // delete file
        File::delete(public_path($path.DIRECTORY_SEPARATOR.$filename));
    }

    //getRoleNames

    public static function getRoleNames($roles)
    {
        $roles =$roles->pluck('name')->map(function ($item, $key) {
            $item = str_replace('-', ' ', $item);
            return ucfirst($item);
        })->implode(', ');

        return empty($roles) ?  __('N/A') : $roles;
    }

    public static function superAdminRoleName()
    : string
    {
        return 'super-admin';
    }
}
