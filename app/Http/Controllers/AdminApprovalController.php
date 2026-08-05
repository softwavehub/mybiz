<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\KycSubmission;
use App\Models\RejectionReason;
use App\Models\Seller;
use App\Models\Merchant;

class AdminApprovalController extends Controller
{
    /**
     * Display Product & KYC Approval Desk
     */
    public function index()
    {
        $pendingProducts = Product::where('status', 'pending_approval')
            ->with(['seller', 'category', 'variants'])
            ->latest()
            ->get();

        $pendingKycSellers = Seller::where('kyc_status', 'pending')->with('user')->get();
        $pendingKycMerchants = Merchant::where('kyc_status', 'pending')->with('user')->get();

        $rejectionReasons = RejectionReason::all();

        return view('admin.approvals.index', compact(
            'pendingProducts',
            'pendingKycSellers',
            'pendingKycMerchants',
            'rejectionReasons'
        ));
    }

    /**
     * Approve Product SKU (§3.2 State Machine)
     */
    public function approveProduct(Product $product)
    {
        $product->update([
            'status' => 'approved',
            'rejection_reason' => null,
        ]);

        return back()->with('success', "Product SKU [{$product->name}] approved and live for merchant import.");
    }

    /**
     * Reject Product SKU (§3.2 & §4.5 Standardized Rejection Reason)
     */
    public function rejectProduct(Request $request, Product $product)
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:255'],
        ]);

        $product->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', "Product SKU [{$product->name}] rejected.");
    }

    /**
     * Approve KYC Submission (§3.3 State Machine)
     */
    public function approveKyc(string $type, int $id)
    {
        if ($type === 'seller') {
            $model = Seller::findOrFail($id);
        } else {
            $model = Merchant::findOrFail($id);
        }

        $model->update(['kyc_status' => 'approved']);

        return back()->with('success', ucfirst($type) . " KYC approved successfully.");
    }
}
