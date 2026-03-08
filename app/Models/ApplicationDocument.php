<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationDocument extends Model
{

    protected $fillable = ['promotion_application_id','doc_code','file_path','original_name','size'];

    public function application():BelongsTo
    {
        return $this->belongsTo(PromotionApplication::class, 'promotion_application_id');
    }


    public function up(): void
    {
        Schema::create('application_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_application_id')->constrained()->cascadeOnDelete();
            $table->string('doc_code'); // contoh: D-01, D-05
            $table->string('file_path'); // path storage
            $table->string('original_name')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->timestamps();
        });
    }
}
