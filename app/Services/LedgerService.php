<?php

namespace App\Services;

use App\Models\LedgerEntry;
use Illuminate\Support\Facades\DB;
use Exception;

class LedgerService
{
    /**
     * Record a Customer Payment into Escrow (Double Entry):
     * Debit: customer_payment (Money IN)
     * Credit: escrow_holding (Held in platform escrow)
     */
    public function recordCustomerPayment(int $orderId, float $totalPaid, string $paymentRef): void
    {
        DB::transaction(function () use ($orderId, $totalPaid, $paymentRef) {
            // Entry 1: Debit Customer Payment
            LedgerEntry::create([
                'order_id' => $orderId,
                'account_type' => 'customer_payment',
                'entry_type' => 'debit',
                'amount' => $totalPaid,
                'reference_id' => $paymentRef,
                'description' => 'Customer payment received at checkout'
            ]);

            // Entry 2: Credit Escrow Holding
            LedgerEntry::create([
                'order_id' => $orderId,
                'account_type' => 'escrow_holding',
                'entry_type' => 'credit',
                'amount' => $totalPaid,
                'reference_id' => $paymentRef,
                'description' => 'Funds deposited into escrow holding tank'
            ]);
        });
    }

    /**
     * Release Escrow Payouts upon timer completion (15/7 days):
     * Debit: escrow_holding
     * Credit: seller_payout (Base + Shipping)
     * Credit: merchant_payout (Profit)
     * Credit: platform_commission (10%)
     * Credit: pg_fee (2%)
     */
    public function releaseEscrowPayout(
        int $orderId,
        float $totalPaid,
        float $sellerAmount,
        float $merchantAmount,
        float $platformCommission,
        float $pgFee,
        string $payoutRef
    ): void {
        DB::transaction(function () use (
            $orderId, $totalPaid, $sellerAmount, $merchantAmount, $platformCommission, $pgFee, $payoutRef
        ) {
            // Verify ledger math equation: totalPaid must equal sum of disbursements
            $disbursementSum = round($sellerAmount + $merchantAmount + $platformCommission + $pgFee, 2);
            if (abs($totalPaid - $disbursementSum) > 0.01) {
                throw new Exception("Escrow disbursement math error: Total Paid (₹{$totalPaid}) != Sum (₹{$disbursementSum})");
            }

            // 1. Debit Escrow Holding (Clear Escrow)
            LedgerEntry::create([
                'order_id' => $orderId,
                'account_type' => 'escrow_holding',
                'entry_type' => 'debit',
                'amount' => $totalPaid,
                'reference_id' => $payoutRef,
                'description' => 'Escrow funds released post-hold period'
            ]);

            // 2. Credit Seller Payout
            LedgerEntry::create([
                'order_id' => $orderId,
                'account_type' => 'seller_payout',
                'entry_type' => 'credit',
                'amount' => $sellerAmount,
                'reference_id' => $payoutRef,
                'description' => 'Base price + Shipping payout to Seller'
            ]);

            // 3. Credit Merchant Payout
            LedgerEntry::create([
                'order_id' => $orderId,
                'account_type' => 'merchant_payout',
                'entry_type' => 'credit',
                'amount' => $merchantAmount,
                'reference_id' => $payoutRef,
                'description' => 'Net profit margin payout to Merchant'
            ]);

            // 4. Credit Platform Commission
            LedgerEntry::create([
                'order_id' => $orderId,
                'account_type' => 'platform_commission',
                'entry_type' => 'credit',
                'amount' => $platformCommission,
                'reference_id' => $payoutRef,
                'description' => '10% Platform transaction commission'
            ]);

            // 5. Credit PG Fee
            LedgerEntry::create([
                'order_id' => $orderId,
                'account_type' => 'pg_fee',
                'entry_type' => 'credit',
                'amount' => $pgFee,
                'reference_id' => $payoutRef,
                'description' => '2% Payment Gateway fee allocation'
            ]);
        });
    }
}
