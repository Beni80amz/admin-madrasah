<?php

namespace App\Livewire;

use App\Services\AI\GeminiService;
use Livewire\Component;

class AiChatWidget extends Component
{
    public $messages = [];
    public $userInput = '';
    public $isOpen = false;
    public $isLoading = false;

    public function mount()
    {
        // Add welcome message
        $this->messages[] = [
            'role' => 'model',
            'content' => "Halo! 👋 Saya adalah AI Madrasah. Ada yang bisa saya bantu terkait administrasi guru, materi pembelajaran, RPP, atau urusan pendidikan lainnya?"
        ];
    }

    public function toggleChat()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function sendMessage(GeminiService $gemini)
    {
        if (empty(trim($this->userInput))) {
            return;
        }

        $userMessage = $this->userInput;
        $this->messages[] = [
            'role' => 'user',
            'content' => $userMessage
        ];

        $this->userInput = '';
        $this->isLoading = true;

        // Ensure state is updated before calling Gemini
        $this->dispatch('scroll-chat');

        try {
            $response = $gemini->chat($this->messages);

            $this->messages[] = [
                'role' => 'model',
                'content' => $response
            ];
        } catch (\Exception $e) {
            $this->messages[] = [
                'role' => 'model',
                'content' => "Maaf, terjadi kesalahan teknis. Silakan coba lagi nanti."
            ];
        }

        $this->isLoading = false;
        $this->dispatch('scroll-chat');
    }

    public function clearChat()
    {
        $this->messages = [];
        $this->mount();
    }

    public function render()
    {
        return view('livewire.ai-chat-widget');
    }
}
