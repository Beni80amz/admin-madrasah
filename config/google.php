<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google OAuth Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Google Drive API integration
    |
    */

    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect_uri' => env('GOOGLE_REDIRECT_URI'),

    /*
    |--------------------------------------------------------------------------
    | Google Drive Scopes
    |--------------------------------------------------------------------------
    |
    | The scopes to request from the user during OAuth
    |
    */
    'scopes' => [
        'https://www.googleapis.com/auth/drive.file',
        'https://www.googleapis.com/auth/drive.metadata.readonly',
    ],

    /*
    |--------------------------------------------------------------------------
    | Folder Structure
    |--------------------------------------------------------------------------
    |
    | The folder structure to create in teacher's Google Drive
    |
    */
    'main_folder_name' => 'Administrasi_Guru',
    'subfolders' => [
        'planning' => 'Perencanaan Pembelajaran',
        'execution' => 'Pelaksanaan & Evaluasi',
        'support' => 'Bukti Pendukung',
    ],
];
