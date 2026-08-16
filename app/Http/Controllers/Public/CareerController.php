<?php

namespace App\Http\Controllers\Public;

use App\Enums\CareerApplicationStatus;
use App\Enums\JobVacancyStatus;
use App\Http\Controllers\Controller;
use App\Models\CareerApplication;
use App\Models\JobVacancy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class CareerController extends Controller
{
    public function index()
    {
        $vacancies = JobVacancy::where('status', JobVacancyStatus::Open->value)
            ->withCount('applications')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('careers.index', compact('vacancies'));
    }

    public function apply(Request $request): RedirectResponse
    {
        RateLimiter::hit('career-form', 60);

        $validated = $request->validate([
            'job_vacancy_id' => ['required', 'exists:job_vacancies,id'],
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:20'],
            'resume' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'cover_letter' => ['nullable', 'string', 'max:3000'],
        ]);

        $vacancy = JobVacancy::findOrFail($validated['job_vacancy_id']);

        if ($vacancy->status !== JobVacancyStatus::Open) {
            return back()->with('error', 'Lowongan ini sudah ditutup.');
        }

        $path = $request->file('resume')->store('resumes', 'public');

        CareerApplication::create([
            'job_vacancy_id' => $vacancy->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'resume_path' => $path,
            'cover_letter' => $validated['cover_letter'] ?? null,
            'status' => CareerApplicationStatus::New,
        ]);

        return back()->with('success', 'Lamaran Anda telah terkirim. Terima kasih!');
    }
}
