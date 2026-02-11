<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
    }

    public function chat(array $messages)
    {
        if (!$this->apiKey) {
            return "API Key Gemini belum dikonfigurasi. Silakan hubungi administrator.";
        }

        $systemInstruction = "Nama kamu adalah AI Madrasah. Kamu adalah asisten ahli administrasi Madrasah yang membantu guru membuat RPP/Modul Ajar, TP dan ATP, Program Semester, Program Tahunan, Materi Pembelajaran, Asesmen, menangani masalah administrasi siswa, dan memberikan saran pendidikan berkualitas. Selain itu, kamu juga ahli dalam memberikan informasi terkait data madrasah seperti profil madrasah, data siswa, dan kegiatan madrasah lainnya. Jawablah dengan nada profesional, ramah, dan solutif. Jika memberikan tabel atau list, gunakan format Markdown yang rapi. Kamu juga bisa memberikan link gambar jika relevan.";

        $contents = [];

        // Gemini expects specific format: { role: "user"|"model", parts: [{ text: "..." }] }
        // We add system instruction as part of the context if needed, 
        // but Gemini 1.5 Flash supports system_instruction field.

        foreach ($messages as $message) {
            $contents[] = [
                'role' => $message['role'] === 'user' ? 'user' : 'model',
                'parts' => [['text' => $message['content']]]
            ];
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '?key=' . $this->apiKey, [
                        'system_instruction' => [
                            'parts' => [['text' => $systemInstruction]]
                        ],
                        'contents' => $contents,
                        'generationConfig' => [
                            'temperature' => 0.7,
                            'topK' => 40,
                            'topP' => 0.95,
                            'maxOutputTokens' => 2048,
                        ],
                    ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['candidates'][0]['content']['parts'][0]['text'] ?? "Maaf, saya tidak bisa memproses permintaan tersebut.";
            }

            $errorDetail = $response->json()['error']['message'] ?? $response->body();
            Log::error('Gemini API Error: ' . $errorDetail);
            return "Error (v1): " . $errorDetail;
        } catch (\Exception $e) {
            Log::error('Gemini Service Exception: ' . $e->getMessage());
            return "Exception: " . $e->getMessage();
        }
    }
}
