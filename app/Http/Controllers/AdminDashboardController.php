<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Seller;
use App\Models\Merchant;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderLineItem;
use App\Models\Dispute;
use App\Models\FeatureFlag;
use App\Models\LedgerEntry;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Comprehensive Super Admin Control Center (§5.1)
     */
    public function index()
    {
        // 1. Real Database Aggregated Metrics (§5.1.1)
        $totalOrders = Order::count();
        $gmv = OrderLineItem::sum('total_price') ?? 0.00;
        $platformCommission = OrderLineItem::sum('platform_commission') ?? 0.00;
        $pgFees = OrderLineItem::sum('pg_fee') ?? 0.00;
        $escrowBalance = OrderLineItem::whereHas('order', function ($q) {
            $q->where('escrow_status', 'holding');
        })->sum('base_price') ?? 0.00;

        $activeDisputesCount = Dispute::where('status', 'open')->count();

        // 2. Seller Strike Engine & Defect Rate Tracker (§3.4 & §5.1.2)
        $sellers = Seller::with('user')->get()->map(function ($s) {
            $totalSellerOrders = Order::where('seller_id', $s->id)->count();
            $defectiveOrders = Dispute::where('seller_id', $s->id)->count();
            $defectRate = $totalSellerOrders > 0 ? round(($defectiveOrders / $totalSellerOrders) * 100, 2) : 0.00;

            // Auto-lock check if defect rate > 3% (§3.4)
            if ($defectRate > 3.00 && $s->kyc_status !== 'suspended') {
                $s->update(['kyc_status' => 'suspended']);
            }

            $s->total_orders_count = $totalSellerOrders;
            $s->defect_rate = $defectRate;
            return $s;
        });

        // 3. Escrow Clearance Queue (§3.5)
        $escrowOrders = Order::where('escrow_status', 'holding')
            ->with(['merchant', 'seller', 'lineItems'])
            ->latest()
            ->get();

        // 4. Disputes Desk (§5.5)
        $disputes = Dispute::with(['order', 'customer', 'merchant', 'seller'])
            ->latest()
            ->get();

        // 5. Taxonomy Category Management
        $categories = Category::all();

        // 6. Platform Feature Flags & Kill Switches (§0.7)
        $featureFlags = FeatureFlag::where('scope', 'platform')->get();
        if ($featureFlags->isEmpty()) {
            FeatureFlag::create(['feature_key' => 'catalog_import', 'scope' => 'platform', 'enabled' => true, 'reason' => 'Default']);
            FeatureFlag::create(['feature_key' => 'seller_registration', 'scope' => 'platform', 'enabled' => true, 'reason' => 'Default']);
            FeatureFlag::create(['feature_key' => 'custom_domain_cname', 'scope' => 'platform', 'enabled' => true, 'reason' => 'Default']);
            $featureFlags = FeatureFlag::where('scope', 'platform')->get();
        }

        // 7. System Audit Logs
        $auditLogs = LedgerEntry::latest()->limit(15)->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'gmv',
            'platformCommission',
            'pgFees',
            'escrowBalance',
            'activeDisputesCount',
            'sellers',
            'escrowOrders',
            'disputes',
            'categories',
            'featureFlags',
            'auditLogs'
        ));
    }

    /**
     * Toggle Seller Account Lock / Unlock (§3.4)
     */
    public function toggleSellerStatus(Seller $seller)
    {
        $newStatus = $seller->kyc_status === 'suspended' ? 'approved' : 'suspended';
        $seller->update(['kyc_status' => $newStatus]);

        return back()->with('success', "Seller account [{$seller->company_name}] status changed to: " . strtoupper($newStatus));
    }

    /**
     * Change Seller Escrow Acceleration Tier (15-Day / 7-Day) (§3.5)
     */
    public function updateEscrowTier(Seller $seller, string $tier)
    {
        if (!in_array($tier, ['15_days', '7_days'])) {
            return back()->with('error', 'Invalid escrow tier selected.');
        }

        $seller->update(['escrow_tier' => $tier]);

        return back()->with('success', "Seller [{$seller->company_name}] escrow hold tier updated to {$tier}.");
    }

    /**
     * Manual Override: Instantly Release Escrow Funds (§3.5)
     */
    public function releaseEscrow(Order $order)
    {
        $order->update([
            'escrow_status' => 'released',
            'released_at' => now(),
        ]);

        return back()->with('success', "Escrow funds for Order #{$order->order_number} released to seller and merchant accounts.");
    }

    /**
     * Toggle Master Category Enabled/Disabled (§4.3 Check #2)
     */
    public function toggleCategory(Category $category)
    {
        $category->update(['is_disabled' => !$category->is_disabled]);
        $statusText = $category->is_disabled ? 'Disabled (Blocks Catalog Imports)' : 'Active';

        return back()->with('success', "Category [{$category->name}] status updated to: {$statusText}");
    }

    /**
     * Toggle Platform Feature Flag / Kill Switch (§0.7)
     */
    public function toggleFeatureFlag(FeatureFlag $flag)
    {
        $flag->update(['enabled' => !$flag->enabled]);
        $statusText = $flag->enabled ? 'ENABLED' : 'DISABLED (Kill Switch Active)';

        return back()->with('success', "Feature Flag [{$flag->feature_key}] is now: {$statusText}");
    }
}
