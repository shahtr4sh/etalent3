<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenerbitanStaf extends Model
{
    protected $table = 'pub_items';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nostaf',
        'title',
        'type',
        'publish_date',
        'evidence',
        'journal',
        'volume',
        'issue',
        'pages',
        'publisher',
        'conference',
        'doi',
    ];

    public $timestamps = false;

    /**
     * Get all authors for this publication
     */
    public function authors()
    {
        return $this->hasMany(PubAuthor::class, 'pub_item_id', 'id');
    }

    public function getAllAuthorsAttribute(): string
    {
        $authors = $this->authors;

        if (! $authors || $authors->isEmpty()) {
            return '-';
        }

        return $authors->pluck('name')->filter()->implode(', ');
    }

    /**
     * Get publication year from publish_date
     */
    public function getTahunAttribute()
    {
        if ($this->publish_date) {
            return date('Y', strtotime($this->publish_date));
        }
        return null;
    }

    /**
     * Get publication indexes
     */
    public function indexes()
    {
        return $this->belongsToMany(
            PubIndex::class,
            'pub_item_indexes',
            'pub_item_id',
            'pub_index_id'
        );
    }

    public function getIndexNamesAttribute()
    {
        if (!$this->indexes || $this->indexes->isEmpty()) {
            return '-';
        }

        return $this->indexes->pluck('name')->implode(', ');
    }

    public function hasIndex($indexName)
    {
        return $this->indexes->contains('name', $indexName);
    }

    private function capitalizeFirstSentence($text)
    {
        if (empty($text)) {
            return '';
        }

        // Trim whitespace
        $text = trim($text);

        // If all uppercase, convert to proper case
        if (mb_strtoupper($text) === $text) {
            $text = mb_strtolower($text);
        }

        // Capitalize first letter
        return mb_strtoupper(mb_substr($text, 0, 1)) . mb_substr($text, 1);
    }


    public function getFormattedTitleAttribute()
    {
        return $this->capitalizeFirstSentence($this->title);
    }

    /**
     * Format authors in simple format: "Lastname, Initials"
     */
    public function getFormattedAuthorsAttribute()
    {
        $authors = $this->authors->map(function($author) {
            return $this->formatAuthorName($author->name);
        })->toArray();

        if (empty($authors)) {
            return 'Anonymous';
        }

        return implode(', ', $authors);
    }

    /**
     * Format for citation: "Authors (year). Title" with proper capitalization
     */
    public function getCitationAttribute()
    {
        $authors = $this->formatted_authors;
        $year = $this->tahun ?? 'n.d.';
        $title = $this->formatted_title ?? 'No title';

        return "{$authors} ({$year}). {$title}";
    }

    /**
     * Format individual author name for APA
     */
    private function formatAuthorName($fullName)
    {
        if (empty($fullName)) {
            return '';
        }

        // Remove titles (Prof, Dr, etc.)
        $name = preg_replace('/^(PROF|DR|DATIN|DATUK|HAJI|HJ|HAJJAH|USTAZ|USTAZAH)\s+/i', '', $fullName);
        $name = trim($name);

        // Handle names with commas (Last, First)
        if (strpos($name, ',') !== false) {
            $parts = explode(',', $name);
            $lastName = trim($parts[0]);
            $firstName = trim($parts[1] ?? '');

            if (!empty($firstName)) {
                $firstNameParts = explode(' ', $firstName);
                $initials = '';
                foreach ($firstNameParts as $part) {
                    if (!empty($part)) {
                        $initials .= strtoupper(substr($part, 0, 1)) . '.';
                    }
                }
                return $lastName . ', ' . $initials;
            }

            return $lastName;
        }

        // Handle normal names (First Middle Last)
        $parts = explode(' ', $name);

        if (count($parts) === 1) {
            return $parts[0];
        }

        // Last name is the last word
        $lastName = array_pop($parts);

        // Initials for first/middle names
        $initials = '';
        foreach ($parts as $part) {
            if (!empty($part)) {
                $initials .= strtoupper(substr($part, 0, 1)) . '.';
            }
        }

        return $lastName . ', ' . $initials;
    }

    /**
     * Get full APA citation with proper capitalization
     */
    public function getApaCitationAttribute()
    {
        $authors = $this->formatted_authors;
        $year = $this->tahun ?? 'n.d.';
        $title = $this->formatted_title ?? 'No title';
        $type = $this->type ? " [{$this->type}]" : '';

        return trim("{$authors} ({$year}). {$title}{$type}");
    }

    /**
     * Get short citation (for lists)
     */
    public function getShortCitationAttribute()
    {
        $authors = $this->formatted_authors;
        $year = $this->tahun ?? 'n.d.';

        // Get first author only
        $firstAuthor = explode(',', $authors)[0];

        return "{$firstAuthor} ({$year})";
    }

    /**
     * Get evidence URL if exists
     */
    public function getEvidenceUrlAttribute()
    {
        if ($this->evidence) {
            // Check if it's a full URL or just a path
            if (filter_var($this->evidence, FILTER_VALIDATE_URL)) {
                return $this->evidence;
            }

            // Assume it's stored in storage
            return asset('storage/' . $this->evidence);
        }

        return null;
    }
}
