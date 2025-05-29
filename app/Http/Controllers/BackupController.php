<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BackupController extends Controller
{
    public function index()
    {
        return view('admin.backup');
    }

    public function export()
    {
        $db = config('database.connections.mysql');
        $filename = 'backup-' . date('Y-m-d_H-i-s') . '.sql';
        $path = storage_path('app/backups/' . $filename);

        if (!File::exists(storage_path('app/backups'))) {
            File::makeDirectory(storage_path('app/backups'));
        }

        $command = "mysqldump -u {$db['username']} -p'{$db['password']}' {$db['database']} > {$path}";
        exec($command, $output, $result);

        if ($result !== 0 || !file_exists($path)) {
            return back()->with('error', 'Gagal membuat backup database.');
        }

        return response()->download($path)->deleteFileAfterSend(true);
    }
    
    public function import(Request $request)
    {
        $request->validate([
            'sql_file' => 'required|file|mimes:sql',
        ]);

        $path = $request->file('sql_file')->storeAs('backups', 'import.sql');

        $db = config('database.connections.mysql');
        $fullPath = storage_path('app/' . $path);
        $command = "mysql -u {$db['username']} -p'{$db['password']}' {$db['database']} < {$fullPath}";
        exec($command, $output, $result);

        if ($result === 0) {
            return back()->with('success', 'Import database berhasil.');
        } else {
            return back()->with('error', 'Gagal import database.');
        }
    }
}
