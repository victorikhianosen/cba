Schema::create('customers', function (Blueprint $table) {
    $table->id();

    $table->string('cif_number')->unique();

    $table->foreignId('branch_id')
        ->nullable()
        ->constrained('branches')
        ->nullOnDelete();

    $table->foreignId('account_officer_id')
        ->nullable()
        ->constrained('account_officers')
        ->nullOnDelete();

    $table->string('customer_type')->default('individual');

    $table->foreignId('guardian_id')
        ->nullable()
        ->constrained('customers')
        ->nullOnDelete();

    $table->string('title')->nullable();

    $table->string('first_name')->nullable();
    $table->string('middle_name')->nullable();
    $table->string('last_name')->nullable();

    $table->string('business_name')->nullable();

    $table->string('phone')->unique();
    $table->string('email')->nullable()->unique();
    $table->string('username')->nullable()->unique();

    $table->string('password')->nullable();
    $table->string('panic_password')->nullable();
    $table->string('pin')->nullable();

    $table->string('marital_status')->nullable();
    $table->string('gender')->nullable();
    $table->date('dob')->nullable();

    $table->string('occupation')->nullable();
    $table->string('working_status')->nullable();
    $table->string('referral_code')->nullable()->unique();

    $table->string('status')->default('pending');

    $table->string('bvn')->nullable()->unique();
    $table->string('nin_number')->nullable()->unique();
    $table->string('tin')->nullable()->unique();

    $table->boolean('is_staff')->default(false);
    $table->boolean('pep')->default(false);

    $table->boolean('enable_internet_bank')->default(false);
    $table->boolean('enable_sms')->default(true);
    $table->boolean('enable_email')->default(true);
    $table->boolean('enable_reset_password')->default(false);
    $table->boolean('enable_panic_password')->default(false);

    $table->boolean('id_verified')->default(false);
    $table->boolean('face_verified')->default(false);
    $table->boolean('utility_verified')->default(false);

    $table->string('mother_maiden_name')->nullable();
    $table->string('spouse_name')->nullable();

    $table->nullableUlidMorphs('approved_by');
    $table->timestamp('approved_at')->nullable();

    $table->nullableUlidMorphs('rejected_by');
    $table->timestamp('rejected_at')->nullable();
    $table->string('rejection_reason')->nullable();

    $table->nullableUlidMorphs('closed_by');
    $table->timestamp('closed_at')->nullable();
    $table->string('closure_reason')->nullable();

    $table->timestamps();

    $table->index('status');
    $table->index(['branch_id', 'status']);
});



     Schema::create('next_of_kin', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('relationship')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });
       Schema::create('documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();

            $table->foreignId('account_id')
                ->nullable()
                ->constrained('accounts')
                ->nullOnDelete();

            $table->string('title')->nullable();
            $table->string('name')->nullable();
            $table->string('path')->nullable();
            $table->string('type')->nullable();

            $table->ulidMorphs('uploaded_by');

            $table->enum('status', [
                'pending',
                'verified',
                'approved',
                'rejected',
            ])->default('pending');

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')->nullable();

            $table->foreignId('rejected_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('rejected_at')->nullable();

            $table->longText('comments')->nullable();

            $table->timestamps();

            $table->index(['customer_id', 'account_id']);
        });