public function up(): void
{
    Schema::create('email_logs', function (Blueprint $table) {
        $table->id();
        $table->string('to_email');
        $table->string('subject');
        $table->longText('body')->nullable();
        $table->enum('status', ['success', 'failed'])->default('success');
        $table->timestamp('sent_at')->useCurrent();
        $table->timestamps();
    });
}
