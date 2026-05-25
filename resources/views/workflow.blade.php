@extends('layouts.app')

@section('content')
    <style>
        .arrow-steps .step {
            font-size: 14px;
            text-align: center;
            color: #666;
            cursor: default;
            margin: 0 3px;
            padding: 10px 10px 10px 30px;
            min-width: 120px;
            float: left;
            position: relative;
            background-color: #f3f4f6;
            user-select: none;
            transition: all 0.3s ease;
            clip-path: polygon(0% 0%, 90% 0%, 100% 50%, 90% 100%, 0% 100%, 10% 50%);
        }

        .arrow-steps .step:first-child {
            padding-left: 20px;
            clip-path: polygon(0% 0%, 90% 0%, 100% 50%, 90% 100%, 0% 100%);
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }

        .arrow-steps .step:last-child {
            clip-path: polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%, 10% 50%);
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }

        .arrow-steps .step.current {
            color: #fff;
            background-color: #0ea5e9;
        }

        .arrow-steps .step.completed {
            color: #fff;
            background-color: #10b981;
        }

        .arrow-steps .step.upcoming {
            color: #9ca3af;
            background-color: #f3f4f6;
        }
    </style>

    <div class="max-w-5xl mx-auto">
        <!-- Back button -->
        <div class="mb-6">
            <a href="{{ route('contracts.show', $contract) }}" class="text-sm text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Detail Kontrak
            </a>
        </div>

        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Contract Life Cycle</h1>
            <p class="text-gray-500">{{ $contract->tenant->name }} — 
                {{ $contract->no_pks ? 'PKS: ' . $contract->no_pks : '' }}
                {{ $contract->no_bak ? 'BAK: ' . $contract->no_bak : '' }}
            </p>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-md bg-green-50 p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.06l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                    <p class="ml-3 text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-md bg-red-50 p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg>
                    <p class="ml-3 text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if(!$workflow)
            {{-- No workflow started yet --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-12 shadow-lg text-center">
                <div class="w-20 h-20 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Belum Ada Workflow</h2>
                <p class="text-gray-500 mb-8">Mulai proses perpanjangan/pengakhiran kontrak untuk kontrak ini.</p>
                <form action="{{ route('workflow.start', $contract) }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-md hover:bg-indigo-500 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Mulai Workflow
                    </button>
                </form>
            </div>
        @else
            @php
                $progress = $workflow->getProgress();
                $currentStepDef = \App\Models\ContractWorkflow::STEPS[$workflow->current_step] ?? null;
            @endphp

            {{-- Arrow Steps --}}
            <div class="flex justify-center mb-10">
                <div class="arrow-steps clearfix flex">
                    @foreach($progress as $step)
                        <div class="step {{ $step['status'] }}">
                            <span class="font-medium text-sm">{{ $step['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Detail Card --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-8 shadow-xl max-w-xl mx-auto">
                {{-- Step indicator --}}
                <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold
                    {{ $workflow->isCompleted() ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600' }}">
                    @if($workflow->isCompleted())
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    @else
                        {{ collect($progress)->search(fn($s) => $s['key'] === $workflow->current_step) + 1 }}
                    @endif
                </div>

                <h2 class="text-2xl font-bold text-gray-800 mb-2 text-center">
                    {{ $workflow->isCompleted() ? 'SOP Akhir Kontrak Selesai' : $workflow->getCurrentStepLabel() }}
                </h2>

                @if($workflow->isCompleted())
                    <p class="text-gray-500 text-center mb-4">
                        SOP Akhir Kontrak selesai pada {{ $workflow->completed_at->format('d M Y H:i') }}.
                        {{ $workflow->branch === 'A' ? 'Kontrak diakhiri.' : 'Kontrak diperpanjang.' }}
                    </p>
                @else
                    {{-- Evidence Upload Section --}}
                    @if($currentStepDef && $currentStepDef['file_required'])
                        @php
                            $currentEvidence = $workflow->evidence->where('step', $workflow->current_step)->first();
                        @endphp

                        <div class="mt-6 border-t pt-6">
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">Bukti / Dokumen</h3>

                            @if($currentEvidence)
                                {{-- File already uploaded --}}
                                <div class="flex items-center justify-between bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="text-sm text-green-800 font-medium">{{ $currentEvidence->original_name }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        @if(Str::endsWith($currentEvidence->original_name, '.pdf'))
                                            <a href="{{ asset('storage/' . $currentEvidence->file_path) }}" target="_blank" class="text-sm text-green-600 hover:text-green-800 underline">View</a>
                                        @endif
                                        <a href="{{ asset('storage/' . $currentEvidence->file_path) }}" download="{{ $currentEvidence->original_name }}" class="text-sm text-green-600 hover:text-green-800 underline">Download</a>
                                    </div>
                                </div>
                            @else
                                {{-- Upload form --}}
                                <form action="{{ route('workflow.upload', $contract) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="flex items-center gap-3">
                                        <input type="file" name="evidence_file" required
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                        <button type="submit" class="shrink-0 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                            Upload
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-2">Format: PDF, JPG, PNG, DOC, DOCX (maks 10MB)</p>
                                    @error('evidence_file')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </form>
                            @endif
                        </div>
                    @endif

                    {{-- Decision Controls (at step 2, waiting_response) --}}
                    @if($workflow->isAtDecisionPoint() && $workflow->canAdvance())
                        <div class="mt-6 border-t pt-6">
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">Keputusan Tenant</h3>
                            <div class="flex gap-4 justify-center">
                                <form action="{{ route('workflow.decide', $contract) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="branch" value="A">
                                    <button type="submit" class="px-6 py-2.5 bg-red-50 text-red-600 rounded-lg border border-red-200 hover:bg-red-100 transition font-medium text-sm">
                                        Akhir Kontrak
                                    </button>
                                </form>
                                <form action="{{ route('workflow.decide', $contract) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="branch" value="B">
                                    <button type="submit" class="px-6 py-2.5 bg-green-50 text-green-600 rounded-lg border border-green-200 hover:bg-green-100 transition font-medium text-sm">
                                        Perpanjangan
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    {{-- Next Step Button --}}
                    @if(!$workflow->isAtDecisionPoint() && !$workflow->isLastStep())
                        <div class="mt-6 text-center">
                            <form action="{{ route('workflow.advance', $contract) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                    @if(!$workflow->canAdvance()) disabled @endif
                                    class="inline-flex items-center gap-2 rounded-lg px-6 py-2.5 text-sm font-semibold shadow transition
                                        {{ $workflow->canAdvance() 
                                            ? 'bg-indigo-600 text-white hover:bg-indigo-500' 
                                            : 'bg-gray-200 text-gray-400 cursor-not-allowed' }}">
                                    Next Step
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </form>
                            @if(!$workflow->canAdvance())
                                <p class="text-xs text-gray-400 mt-2">Upload file bukti terlebih dahulu untuk melanjutkan</p>
                            @endif
                        </div>
                    @endif
                @endif

                {{-- Timestamps --}}
                <div class="mt-8 border-t pt-4 text-xs text-gray-400 space-y-1">
                    <p>Dimulai: {{ $workflow->started_at->format('d M Y H:i') }}</p>
                    @if($workflow->decided_at)
                        <p>Keputusan: {{ $workflow->decided_at->format('d M Y H:i') }} — {{ $workflow->branch === 'A' ? 'Akhir' : 'Perpanjangan' }}</p>
                    @endif
                    @if($workflow->completed_at)
                        <p>Selesai: {{ $workflow->completed_at->format('d M Y H:i') }}</p>
                    @endif
                </div>
            </div>

            {{-- Evidence History --}}
            @if($workflow->evidence->count() > 0)
                <div class="mt-8 max-w-xl mx-auto">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Riwayat Dokumen</h3>
                    <div class="bg-white border border-gray-200 rounded-lg divide-y">
                        @foreach($workflow->evidence->sortBy('created_at') as $evidence)
                            <div class="flex items-center justify-between px-4 py-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ \App\Models\ContractWorkflow::STEPS[$evidence->step]['label'] ?? $evidence->step }}</p>
                                    <p class="text-xs text-gray-500">{{ $evidence->original_name }} — {{ $evidence->uploaded_at->format('d M Y H:i') }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    @if(Str::endsWith($evidence->original_name, '.pdf'))
                                        <a href="{{ asset('storage/' . $evidence->file_path) }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800">View</a>
                                    @endif
                                    <a href="{{ asset('storage/' . $evidence->file_path) }}" download="{{ $evidence->original_name }}" class="text-sm text-indigo-600 hover:text-indigo-800">Download</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>
@endsection
