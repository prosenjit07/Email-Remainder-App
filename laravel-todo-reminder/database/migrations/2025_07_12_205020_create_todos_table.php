public function up(): void
{
    Schema::create('todos', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description')->nullable();
        $table->dateTime('due_datetime');
        $table->boolean('email_notification_sent')->default(false);
        $table->timestamps();
    });
}
