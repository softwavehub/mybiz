<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Enhanced Users Table (SoftDeletes, Role, MFA)
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone_number')) {
                $table->string('phone_number')->nullable()->after('email');
                $table->enum('role', ['admin', 'seller', 'merchant', 'customer'])->default('merchant')->after('password');
                $table->boolean('mfa_enabled')->default(false)->after('role');
                $table->string('mfa_secret')->nullable()->after('mfa_enabled');
                $table->softDeletes();
            }
        });

        // 2. Subscription Plans
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Free, Pro, Elite
            $table->decimal('price', 10, 2)->default(0.00);
            $table->enum('billing_cycle', ['monthly', 'yearly', '5_year'])->default('monthly');
            $table->integer('product_limit')->default(25);
            $table->boolean('custom_domain_allowed')->default(false);
            $table->boolean('escrow_accelerator_allowed')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Sellers
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('company_name');
            $table->string('gstin')->nullable();
            $table->string('pan')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_ifsc')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('pincode', 10)->nullable();
            $table->enum('kyc_status', ['pending', 'under_review', 'approved', 'rejected'])->default('pending');
            $table->integer('health_score')->default(100);
            $table->enum('health_tier', ['excellent', 'good', 'at_risk', 'suspended'])->default('excellent');
            $table->enum('escrow_tier', ['15_days', '7_days'])->default('15_days');
            $table->integer('tenure_days')->default(0); // Continuous streak for escrow tier 2
            $table->decimal('defect_rate', 5, 2)->default(0.00);
            $table->timestamps();
            $table->softDeletes();
        });

        // 4. Merchants
        Schema::create('merchants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subscription_plan_id')->nullable()->constrained('subscription_plans');
            $table->string('store_name');
            $table->string('subdomain')->unique();
            $table->string('custom_domain')->nullable()->unique();
            $table->string('gstin')->nullable();
            $table->string('pan')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_ifsc')->nullable();
            $table->enum('kyc_status', ['pending', 'under_review', 'approved', 'rejected'])->default('pending');
            $table->integer('health_score')->default(100);
            $table->enum('health_tier', ['excellent', 'good', 'at_risk', 'suspended'])->default('excellent');
            $table->enum('escrow_tier', ['15_days', '7_days'])->default('15_days');
            $table->integer('tenure_days')->default(0);
            $table->decimal('rto_reserve_balance', 10, 2)->default(0.00);
            $table->enum('store_status', ['live', 'disabled'])->default('live');
            $table->timestamps();
            $table->softDeletes();
        });

        // 5. Taxonomy (Categories & Brands)
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_disabled')->default(false);
            $table->timestamps();
        });

        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('attribute_definitions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Color, Size, Material, Storage
            $table->timestamps();
        });

        // 6. Products (Seller Owned)
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('sellers')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('brand_id')->nullable()->constrained('brands');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('hsn_code', 20);
            $table->decimal('gst_rate', 5, 2)->default(18.00);
            $table->string('unit_of_measurement')->default('piece');
            $table->decimal('base_price', 10, 2);
            $table->decimal('shipping_zone_a', 10, 2)->default(30.00); // District
            $table->decimal('shipping_zone_b', 10, 2)->default(50.00); // State
            $table->decimal('shipping_zone_c', 10, 2)->default(80.00); // Rest of India
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'rejected', 'discontinued'])->default('pending_approval');
            $table->string('thumbnail_image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 7. Product Variants (Exempt from approval workflow for quantity updates!)
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('sku')->unique();
            $table->json('attributes'); // e.g. {"Color": "Red", "Size": "M"}
            $table->integer('quantity')->default(0);
            $table->decimal('variant_base_price', 10, 2)->nullable();
            $table->string('thumbnail_image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 8. Product Revisions (State Machine: Draft -> Pending -> Approved)
        Schema::create('product_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('sellers');
            $table->json('proposed_data');
            $table->enum('status', ['pending_approval', 'approved', 'rejected'])->default('pending_approval');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });

        // 9. Merchant Product Imports (Reference Only - Never duplicates product data!)
        Schema::create('merchant_product_imports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->constrained('merchants')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->json('imported_variant_ids');
            $table->enum('pricing_mode', ['fixed', 'markup_rule'])->default('markup_rule');
            $table->decimal('fixed_price', 10, 2)->nullable();
            $table->decimal('markup_percentage', 5, 2)->nullable()->default(30.00);
            $table->json('collection_tags')->nullable();
            $table->timestamps();

            $table->unique(['merchant_id', 'product_id']);
        });

        // 10. Tenant Scoped Customers (Tenant ID = Merchant ID)
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->constrained('merchants')->onDelete('cascade');
            $table->string('name');
            $table->string('phone_number');
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('pincode', 10)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['merchant_id', 'phone_number']);
        });

        // 11. Orders & Fulfillment
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('merchant_id')->constrained('merchants');
            $table->foreignId('seller_id')->constrained('sellers');
            $table->foreignId('customer_id')->constrained('customers');
            $table->enum('status', ['placed', 'packed', 'shipped', 'in_transit', 'delivered', 'cancelled', 'refused'])->default('placed');
            $table->timestamp('placed_at')->useCurrent();
            $table->timestamp('packed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 12. Order Line Items (IMMUTABLE SNAPSHOT!)
        Schema::create('order_line_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_variant_id')->constrained('product_variants');
            
            // Snapshots captured at order confirmation
            $table->string('product_name');
            $table->string('hsn_code', 20);
            $table->decimal('gst_rate', 5, 2);
            $table->decimal('base_price', 10, 2);
            $table->decimal('shipping_fee', 10, 2);
            $table->decimal('platform_commission', 10, 2);
            $table->decimal('pg_fee', 10, 2);
            $table->decimal('price_floor', 10, 2);
            $table->decimal('retail_price', 10, 2);
            $table->decimal('merchant_profit', 10, 2);
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });

        // 13. Shipments & Blind AWBs
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('awb_number')->unique();
            $table->string('courier_name');
            $table->string('blind_sender_name'); // Merchant Store Name
            $table->string('blind_sender_address')->nullable();
            $table->string('tracking_status')->default('label_generated');
            $table->json('tracking_events')->nullable();
            $table->timestamps();
        });

        // 14. Double-Entry Append-Only Financial Ledger (Rule #3: No UPDATE/DELETE)
        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained('orders');
            $table->enum('account_type', [
                'customer_payment',
                'escrow_holding',
                'seller_payout',
                'merchant_payout',
                'platform_commission',
                'pg_fee',
                'tcs_tax'
            ]);
            $table->enum('entry_type', ['debit', 'credit']);
            $table->decimal('amount', 12, 2);
            $table->string('reference_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // 15. Invoices (Model B: Bill-to-Ship-to)
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders');
            $table->string('invoice_number')->unique();
            $table->enum('invoice_type', ['seller_to_merchant', 'merchant_to_customer', 'platform_commission']);
            $table->string('seller_gstin')->nullable();
            $table->string('merchant_gstin')->nullable();
            $table->decimal('taxable_amount', 10, 2);
            $table->decimal('cgst', 10, 2)->default(0.00);
            $table->decimal('sgst', 10, 2)->default(0.00);
            $table->decimal('igst', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2);
            $table->string('pdf_path')->nullable();
            $table->timestamps();
        });

        // 16. Credit Notes (Reverses Invoices)
        Schema::create('credit_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices');
            $table->foreignId('order_id')->constrained('orders');
            $table->string('credit_note_number')->unique();
            $table->text('reason');
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });

        // 17. Rejection Reasons (Shared Component)
        Schema::create('rejection_reasons', function (Blueprint $table) {
            $table->id();
            $table->enum('context_type', ['kyc', 'product', 'dispute', 'payout']);
            $table->string('label');
            $table->integer('usage_count')->default(0);
            $table->timestamps();
        });

        // 18. KYC Submissions
        Schema::create('kyc_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('actor_id');
            $table->enum('actor_type', ['seller', 'merchant']);
            $table->string('document_type');
            $table->json('document_refs');
            $table->enum('status', ['submitted', 'under_review', 'approved', 'rejected'])->default('submitted');
            $table->json('rejection_reason_ids')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 19. Resolution Center & Disputes
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders');
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('merchant_id')->constrained('merchants');
            $table->foreignId('seller_id')->constrained('sellers');
            $table->enum('claim_type', ['wrong_item', 'damaged_item']);
            $table->json('proof_photos');
            $table->string('unboxing_video_url')->nullable();
            $table->enum('status', ['open', 'under_review', 'approved', 'rejected'])->default('open');
            $table->foreignId('rejection_reason_id')->nullable()->constrained('rejection_reasons');
            $table->timestamps();
        });

        // 20. Feature Flags & Kill Switches
        Schema::create('feature_flags', function (Blueprint $table) {
            $table->id();
            $table->enum('scope', ['platform', 'category', 'seller', 'merchant'])->default('platform');
            $table->unsignedBigInteger('scope_id')->nullable();
            $table->string('feature_key'); // e.g. returns_kill_switch, store_status, account_status
            $table->boolean('enabled')->default(true);
            $table->string('reason')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();

            $table->unique(['scope', 'scope_id', 'feature_key']);
        });

        // 21. Audit Logs
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('actor_type');
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->string('action');
            $table->string('target');
            $table->text('reason')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('feature_flags');
        Schema::dropIfExists('disputes');
        Schema::dropIfExists('kyc_submissions');
        Schema::dropIfExists('rejection_reasons');
        Schema::dropIfExists('credit_notes');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('ledger_entries');
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('order_line_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('merchant_product_imports');
        Schema::dropIfExists('product_revisions');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
        Schema::dropIfExists('attribute_definitions');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('merchants');
        Schema::dropIfExists('sellers');
        Schema::dropIfExists('subscription_plans');
    }
};
