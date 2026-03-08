<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationReview extends Model
{

    protected $fillable = ['promotion_application_id','reviewer_id','checklist','ulasan','action'];
    protected $casts = ['checklist' => 'array'];

    public function application() { return $this->belongsTo(PromotionApplication::class, 'promotion_application_id'); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewer_id'); }


    public function up(): void
    {
        Schema::create('application_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_application_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete(); // urusetia/penyemak
            $table->json('checklist')->nullable(); // contoh: {"D-01": true, "D-05": false}
            $table->text('ulasan')->nullable();
            $table->string('action')->nullable(); // contoh: SEMAK, PULANG, UNTUK_KELULUSAN
            $table->timestamps();
        });
    }
}
