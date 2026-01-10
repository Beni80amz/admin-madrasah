@extends('layouts.frontend')

@section('content')
    <div class="px-4 py-6 max-w-lg mx-auto min-h-screen pb-24">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Izin & Sakit</h1>
            <a href="{{ route('leave.create') }}"
                class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-md hover:bg-green-700 transition-colors flex items-center">
                <span class="material-symbols-outlined text-sm mr-1">add</span> Buat
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-4 text-sm border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tabs -->
        <div x-data="{ tab: 'history' }">
            <div class="flex space-x-1 bg-gray-100 p-1 rounded-xl mb-6">
                <button @click="tab = 'history'"
                    :class="{ 'bg-white shadow text-green-700': tab === 'history', 'text-gray-500': tab !== 'history' }"
                    class="flex-1 py-2 text-sm font-medium rounded-lg transition-all">
                    Riwayat Saya
                </button>
                @if($pendingApprovals->count() > 0)
                    <button @click="tab = 'approval'"
                        :class="{ 'bg-white shadow text-green-700': tab === 'approval', 'text-gray-500': tab !== 'approval' }"
                        class="flex-1 py-2 text-sm font-medium rounded-lg transition-all relative">
                        Menunggu Persetujuan
                        <span class="absolute top-1 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                @endif
            </div>

            <!-- History Tab -->
            <div x-show="tab === 'history'" class="space-y-4">
                @forelse($myRequests as $req)
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <span
                                    class="px-2 py-1 text-xs font-bold rounded-lg uppercase {{ $req->type == 'sakit' ? 'bg-red-50 text-red-600' : 'bg-blue-50 text-blue-600' }}">
                                    {{ $req->type }}
                                </span>
                                <p class="text-xs text-gray-400 mt-1">{{ $req->created_at->diffForHumans() }}</p>
                            </div>
                            <div>
                                @if($req->status == 'pending')
                                    <span
                                        class="bg-yellow-50 text-yellow-600 px-2 py-1 rounded-lg text-xs font-bold">Menunggu</span>
                                @elseif($req->status == 'approved')
                                    <span class="bg-green-50 text-green-600 px-2 py-1 rounded-lg text-xs font-bold">Disetujui</span>
                                @else
                                    <span class="bg-red-50 text-red-600 px-2 py-1 rounded-lg text-xs font-bold">Ditolak</span>
                                @endif
                            </div>
                        </div>
                        <h3 class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($req->start_date)->format('d M') }} -
                            {{ \Carbon\Carbon::parse($req->end_date)->format('d M Y') }}</h3>
                        <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $req->reason }}</p>

                        @if($req->status == 'rejected' && $req->rejection_note)
                            <div class="mt-3 bg-red-50 p-2 rounded-lg text-xs text-red-700">
                                <strong>Alasan Penolakan:</strong> {{ $req->rejection_note }}
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-10">
                        <span class="material-symbols-outlined text-gray-300 text-5xl mb-2">history_edu</span>
                        <p class="text-gray-500 text-sm">Belum ada riwayat pengajuan.</p>
                    </div>
                @endforelse
            </div>

            <!-- Approval Tab -->
            @if($pendingApprovals->count() > 0)
                <div x-show="tab === 'approval'" class="space-y-4" style="display: none;">
                    @foreach($pendingApprovals as $req)
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                            <div class="flex items-center gap-3 mb-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold overflow-hidden">
                                    @if($req->user->avatar_url) <!-- Accessor from User model assumed -->
                                        <img src="{{ $req->user->avatar_url }}" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($req->user->name, 0, 1) }}
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm">{{ $req->user->name }}</h4>
                                    <p class="text-xs text-gray-500">{{ $req->user->teacher ? 'Guru' : 'Siswa' }}</p>
                                </div>
                                <span
                                    class="ml-auto px-2 py-1 text-xs font-bold rounded-lg uppercase {{ $req->type == 'sakit' ? 'bg-red-50 text-red-600' : 'bg-blue-50 text-blue-600' }}">
                                    {{ $req->type }}
                                </span>
                            </div>

                            <div class="bg-gray-50 p-3 rounded-lg mb-4">
                                <p class="text-sm font-semibold text-gray-800 mb-1">
                                    {{ \Carbon\Carbon::parse($req->start_date)->format('d M') }} -
                                    {{ \Carbon\Carbon::parse($req->end_date)->format('d M Y') }}
                                    <span class="text-xs font-normal text-gray-500">({{ $req->duration }} hari)</span>
                                </p>
                                <p class="text-sm text-gray-600 italic">"{{ $req->reason }}"</p>
                                @if($req->attachment)
                                    <a href="{{ asset('storage/' . $req->attachment) }}" target="_blank"
                                        class="mt-2 inline-flex items-center text-xs text-blue-600 hover:underline">
                                        <span class="material-symbols-outlined text-sm mr-1">attachment</span> Lihat Lampiran
                                    </a>
                                @endif
                            </div>

                            <div class="flex gap-2">
                                <form action="{{ route('leave.approve', $req->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit"
                                        class="w-full bg-green-600 text-white py-2 rounded-lg text-sm font-semibold hover:bg-green-700">Terima</button>
                                </form>

                                <button onclick="document.getElementById('rejectForm{{$req->id}}').classList.toggle('hidden')"
                                    class="flex-1 bg-red-100 text-red-700 py-2 rounded-lg text-sm font-semibold hover:bg-red-200">Tolak</button>
                            </div>

                            <div id="rejectForm{{$req->id}}" class="hidden mt-3">
                                <form action="{{ route('leave.reject', $req->id) }}" method="POST">
                                    @csrf
                                    <input type="text" name="rejection_note" placeholder="Alasan penolakan..." required
                                        class="w-full text-sm rounded-lg border-gray-300 mb-2">
                                    <button type="submit"
                                        class="w-full bg-red-600 text-white py-1 rounded-lg text-xs font-medium">Konfirmasi
                                        Tolak</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection