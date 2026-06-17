<?php
// app/Jobs/TranslateCommentJob.php

namespace App\Jobs;

use App\Models\Komentar;
use App\Services\TranslationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TranslateCommentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public int $postinganId,
        public string $commentId
    ) {
        $this->onQueue('translations');
    }

    public function backoff(): array
    {
        return [10, 30, 60];
    }

    public function handle(TranslationService $translator): void
    {
        DB::transaction(function () use ($translator) {
            $komentar = Komentar::where('id_postingan', $this->postinganId)
                ->lockForUpdate()
                ->first();

            if (!$komentar) return;

            $data = $komentar->komentar_data;
            $found = $this->translateNode($data['comments'] ?? [], $translator);

            if ($found) {
                $data['comments'] = $found;
                $komentar->komentar_data = $data;
                $komentar->saveQuietly();
            }
        });
    }

    protected function translateNode(array $nodes, TranslationService $translator): array
    {
        foreach ($nodes as &$node) {
            if (($node['id_komentar'] ?? null) == $this->commentId) {
                $node['komentar_en'] = $translator->toEnglish($node['komentar'] ?? '');
            } elseif (isset($node['balasan'])) {
                $node['balasan'] = $this->translateNode($node['balasan'], $translator);
            }
        }
        unset($node);

        return $nodes;
    }
}