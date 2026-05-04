<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExpireBossPoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bossku:expire-points';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire unused Boss Points older than 1 year';

    /**
     * Execute the console command.
     */
    public function handle(\App\Services\FirebaseService $firebase)
    {
        $this->info('Starting Boss Points expiration check...');
        
        $database = app('firebase.database');
        $pointsHistoryRef = $database->getReference('points_history');
        $allHistories = $pointsHistoryRef->getValue();
        
        if (!$allHistories) {
            $this->info('No points history found.');
            return;
        }

        $now = now()->toIso8601String();
        $expiredCount = 0;

        foreach ($allHistories as $userId => $history) {
            if (!$history) continue;
            
            $userRef = $database->getReference('users/' . $userId);
            $user = $userRef->getValue();
            if (!$user) continue;

            $pointsToDeduct = 0;

            foreach ($history as $transactionId => $transaction) {
                if (($transaction['status'] ?? '') === 'active' && isset($transaction['expires_at'])) {
                    if ($transaction['expires_at'] < $now) {
                        // Mark as expired
                        $database->getReference('points_history/' . $userId . '/' . $transactionId . '/status')->set('expired');
                        $pointsToDeduct += (int) $transaction['points'];
                        $expiredCount++;
                    }
                }
            }

            if ($pointsToDeduct > 0) {
                $currentPoints = $user['loyalty_points'] ?? 0;
                $newPoints = max(0, $currentPoints - $pointsToDeduct);
                $userRef->getChild('loyalty_points')->set($newPoints);
                
                $this->info("Deducted {$pointsToDeduct} expired points from user {$userId}. New balance: {$newPoints}.");
            }
        }

        $this->info("Completed! Marked {$expiredCount} transactions as expired.");
    }
}
