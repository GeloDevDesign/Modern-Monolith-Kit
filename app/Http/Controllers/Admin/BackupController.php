<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BackupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $sluggedName = str_replace(' ', '-', config('app.name'));
        $rawBackups = Storage::disk('local')->allFiles($sluggedName);
        
        $backups = collect($rawBackups)->map(function ($file) {
            return [
                'filename' => basename($file),
                'path' => $file,
                'size' => $this->formatSize(Storage::disk('local')->size($file)),
                'date' => Carbon::createFromTimestamp(Storage::disk('local')->lastModified($file))->format('D, M j, Y h:i a'),
                'type' => str_starts_with(basename($file), 'db-') ? 'Database' : (str_starts_with(basename($file), 'file-') ? 'Full Backup' : 'Unknown'),
            ];
        });

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $backups = $backups->filter(function ($item) use ($search) {
                return str_contains(strtolower($item['filename']), $search) || 
                       str_contains(strtolower($item['type']), $search);
            });
        }

        if ($request->filled('sort_field')) {
            $sortField = $request->sort_field;
            $sortOrder = $request->sort_order ?? 1; // 1 for asc, -1 for desc (PrimeVue convention usually 1/-1, or asc/desc strings)
            
            // Handle PrimeVue sort order which sends 1 or -1
            $isDesc = $sortOrder == -1;

            if ($isDesc) {
                $backups = $backups->sortByDesc($sortField);
            } else {
                $backups = $backups->sortBy($sortField);
            }
        } else {
            // Default sort
            $backups = $backups->sortByDesc('date');
        }

        $backups = $backups->values();

        // Paginate using Controller's helper
        $paginatedBackups = $this->paginateData($request, $backups);

        return Inertia::render('Backups/Index', [
            'backups' => $paginatedBackups
        ]);
    }

    private function formatSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }

    public function generateDatabaseBackup(Request $request)
    {
        // Fix for Windows mysqldump socket error 10106
        
        $filename = 'db-' . Carbon::now()->format('Y-m-d-H-i-s') . '.zip';
        
        Artisan::call('backup:run', [
            '--only-db' => true,
            '--filename' => $filename
        ]);

        $sluggedName = str_replace(' ', '-', config('app.name'));
        $path = $sluggedName . '/' . $filename;

        // activity()->log('Created Database Backup: ' . $filename)
        //     ->causedBy(authUser());

        // return redirect()->route('admin.backups.index')
        return to_route('backups.index')
            ->withSuccess('Backup Generated Successfully');
    }

    public function generateFullBackup(Request $request)
    {
       

        $filename = 'file-' . Carbon::now()->format('Y-m-d-H-i-s') . '.zip';
        
        Artisan::call('backup:run', [
            '--filename' => $filename
        ]);

        $sluggedName = str_replace(' ', '-', config('app.name'));
        $path = $sluggedName . '/' . $filename;

        // activity()->log('Created Full Backup: '. $filename)
        //     ->causedBy(authUser());

        // return redirect()->route('admin.backups.index')
        return to_route('backups.index')
            ->withSuccess('Backup Generated Successfully');
    }

    public function downloadBackup(Request $request)
    {
        return Storage::download($request->file);
    }
    
    public function deleteBackup(Request $request)
    {
        $deleteBackup = Storage::disk('local')->delete($request->file);

        $explode = explode('/', $request->file);
        $sanitizedFilename = $explode && count($explode) > 1 ? $explode[1] : $request->file;

        // activity()->log('Deleted Database Backup: '.$sanitizedFilename)
        //     ->causedBy(authUser());

        // return redirect()->route('admin.backups.index')
        return to_route('backups.index')
        ->withSuccess('Backup Deleted Successfully');
    }
}
