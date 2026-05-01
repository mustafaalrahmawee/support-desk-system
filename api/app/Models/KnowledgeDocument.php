<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Knowledge Document Model (Phase 3)
 *
 * RAG-fähiges Dokument aus Media/Contracts/Tickets.
 * Migration und Implementierung in Phase 3.
 */
class KnowledgeDocument extends Model
{
    use SoftDeletes;
}
