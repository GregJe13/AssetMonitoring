<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractWorkflow;
use App\Models\WorkflowEvidence;
use Illuminate\Http\Request;

class WorkflowController extends Controller
{
    /**
     * Show the workflow for a contract.
     */
    public function show(Contract $contract)
    {
        $workflow = $contract->workflow;

        return view('workflow', compact('contract', 'workflow'));
    }

    /**
     * Start a new workflow for a contract.
     */
    public function start(Contract $contract)
    {
        // Don't create duplicate workflows
        if ($contract->workflow) {
            return redirect()->route('workflow.show', $contract)
                ->with('info', 'Workflow sudah dimulai untuk kontrak ini.');
        }

        $workflow = ContractWorkflow::create([
            'contract_id' => $contract->id,
            'current_step' => 'confirmation_sent',
            'started_at' => now(),
        ]);

        return redirect()->route('workflow.show', $contract)
            ->with('success', 'Workflow berhasil dimulai.');
    }

    /**
     * Upload evidence file for the current step.
     */
    public function uploadEvidence(Request $request, Contract $contract)
    {
        $workflow = $contract->workflow;

        if (!$workflow || $workflow->isCompleted()) {
            return back()->with('error', 'Workflow tidak ditemukan atau sudah selesai.');
        }

        $request->validate([
            'evidence_file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ], [
            'evidence_file.required' => 'File bukti wajib diupload.',
            'evidence_file.mimes' => 'File harus berformat PDF, JPG, PNG, DOC, atau DOCX.',
            'evidence_file.max' => 'Ukuran file maksimal 10MB.',
        ]);

        $file = $request->file('evidence_file');
        $path = $file->store('workflow-evidence', 'public');

        WorkflowEvidence::create([
            'workflow_id' => $workflow->id,
            'step' => $workflow->current_step,
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'uploaded_at' => now(),
        ]);

        return back()->with('success', 'File bukti berhasil diupload.');
    }

    /**
     * Advance to the next step.
     */
    public function advance(Contract $contract)
    {
        $workflow = $contract->workflow;

        if (!$workflow || $workflow->isCompleted()) {
            return back()->with('error', 'Workflow tidak ditemukan atau sudah selesai.');
        }

        // Must decide a branch first at step 2
        if ($workflow->isAtDecisionPoint()) {
            return back()->with('error', 'Silakan pilih keputusan (Akhir Kontrak atau Perpanjangan) terlebih dahulu.');
        }

        // Check evidence uploaded
        if (!$workflow->canAdvance()) {
            return back()->with('error', 'Upload file bukti terlebih dahulu sebelum melanjutkan.');
        }

        $nextStep = $workflow->getNextStepKey();

        if ($nextStep === null) {
            // If at decision point, don't complete - wait for decision
            if ($workflow->isAtDecisionPoint()) {
                return back()->with('info', 'Silakan pilih keputusan: Akhir Kontrak atau Perpanjangan.');
            }
            // We're at the last step, mark as completed
            $workflow->update(['completed_at' => now()]);

            // If Branch B (perpanjangan), set pending and redirect to choice page
            if ($workflow->branch === 'B') {
                $workflow->update(['renewal_action' => 'pending']);
                return redirect()->route('workflow.renewalChoice', $contract)
                    ->with('success', 'Workflow perpanjangan selesai! Silakan pilih langkah selanjutnya.');
            }

            return back()->with('success', 'Workflow telah selesai.');
        }

        $workflow->update(['current_step' => $nextStep]);

        // If the new step is the last step AND not a decision point, auto-complete
        if ($workflow->getNextStepKey() === null && !$workflow->isAtDecisionPoint()) {
            $workflow->update(['completed_at' => now()]);

            // If Branch B (perpanjangan), set pending and redirect to choice page
            if ($workflow->branch === 'B') {
                $workflow->update(['renewal_action' => 'pending']);
                return redirect()->route('workflow.renewalChoice', $contract)
                    ->with('success', 'Workflow perpanjangan selesai! Silakan pilih langkah selanjutnya.');
            }
        }

        return back()->with('success', 'Berhasil melanjutkan ke step: ' . ContractWorkflow::STEPS[$nextStep]['label']);
    }

    /**
     * Set the branch decision (A = End, B = Renew).
     */
    public function decide(Request $request, Contract $contract)
    {
        $workflow = $contract->workflow;

        if (!$workflow) {
            return back()->with('error', 'Workflow tidak ditemukan.');
        }

        if (!$workflow->isAtDecisionPoint()) {
            return back()->with('error', 'Keputusan hanya bisa dibuat di step "Menunggu Jawaban".');
        }

        // Check evidence for current step before deciding
        if (!$workflow->canAdvance()) {
            return back()->with('error', 'Upload file bukti terlebih dahulu sebelum membuat keputusan.');
        }

        $request->validate([
            'branch' => 'required|in:A,B',
        ]);

        $branch = $request->branch;
        // Skip the response step (order 3) — go directly to step 4
        $nextStep = $branch === 'A' ? 'closed' : 'draft_bak';

        $updateData = [
            'branch' => $branch,
            'decided_at' => now(),
            'current_step' => $nextStep,
        ];

        // Branch A: 'closed' is the final step, auto-complete
        if ($branch === 'A') {
            $updateData['completed_at'] = now();
        }

        $workflow->update($updateData);

        $label = $branch === 'A' ? 'Akhir Kontrak' : 'Perpanjangan';
        return back()->with('success', "Keputusan dipilih: {$label}.");
    }

    /**
     * Show the renewal choice page (new contract or amendment).
     */
    public function renewalChoice(Contract $contract)
    {
        $contract->load('tenant');
        return view('workflow-renewal-choice', compact('contract'));
    }
}
