<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use App\Models\Conference;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function login()
    {
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        $validToken = config('app.admin_token', 'admin123');
        
        if ($request->token === $validToken) {
            session(['admin_authenticated' => true]);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['token' => 'Неверный токен']);
    }

    public function dashboard()
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login');
        }

        $workers = Worker::with(['conferences'])->get();
        $conferences = Conference::with(['worker'])->get();
        
        $downloadLinks = [
            'windows' => Setting::getValue('download_links_windows', ''),
            'mac' => Setting::getValue('download_links_mac', '')
        ];

        return view('admin.dashboard', compact('workers', 'conferences', 'downloadLinks'));
    }

    public function logout()
    {
        session()->forget('admin_authenticated');
        return redirect()->route('admin.login');
    }

    public function createWorker()
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login');
        }

        return view('admin.worker.create');
    }

    public function storeWorker(Request $request)
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:workers',
            'email' => 'required|email|unique:workers',
            'password' => 'required|string|min:6',
            'telegram_id' => 'nullable|string'
        ]);

        $tag = 'worker_' . Str::random(8);

        Worker::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tag' => $tag,
            'telegram_id' => $request->telegram_id,
            'is_active' => true
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Воркер успешно создан');
    }

    public function deleteWorker($id)
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login');
        }

        $worker = Worker::findOrFail($id);
        $worker->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Воркер удален');
    }

    public function deleteConference($id)
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login');
        }

        $conference = Conference::findOrFail($id);
        $conference->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Конференция удалена');
    }

    public function updateDownloadLinks(Request $request)
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'windows' => 'nullable|string',
            'mac' => 'nullable|string'
        ]);

        Setting::setValue('download_links_windows', $request->windows ?? '');
        Setting::setValue('download_links_mac', $request->mac ?? '');

        return redirect()->route('admin.dashboard')->with('success', 'Ссылки для скачивания обновлены');
    }
}
