<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Query\Builder as QueryBuilder;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    /**
     * App\Models\Book
     *
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
     * @property-read int|null                                                          $reviews_count
     * @method static \Database\Factories\BookFactory factory($count = NULL, $state = [])
     * @method static Builder|Book newModelQuery()
     * @method static Builder|Book newQuery()
     * @method static Builder|Book query()
     * @method static Builder|Book title($title)
     * @mixin \Eloquent
     */
    class Book extends Model
    {
        use HasFactory;

        public function reviews()
        {
            return $this->hasMany(Review::class);
        }


        public function scopeTitle(Builder $query, $title): Builder
        {
            return $query->where('title', 'LIKE', '%' . $title . '%');
        }

        public function scopePopular(Builder $query, $from = NULL, $to = NULL): Builder|QueryBuilder
        {
            return $query->withCount([
                'reviews' => fn(Builder $q) => $this->rangeDateFilter($q, $from, $to)
            ])
                         ->orderBy('reviews_count', 'desc');
        }

        public function scopeHighestRated(Builder $query, $from = NULL, $to = NULL): Builder|QueryBuilder
        {
            return $query->withAvg([
                'reviews' => fn(Builder $q) => $this->rangeDateFilter($q, $from, $to)
            ], 'rating')
                         ->orderBy('reviews_avg_rating', 'desc');
        }

        private function rangeDateFilter(Builder $query, $from, $to) {
            if ($from && !$to) {
                $query->where('created_at', '>=', $from);
            } elseif (!$from && $to) {
                $query->where('created_at', '<=', $to);
            } elseif ($from && $to) {
                $query->whereBetween('created_at', [ $from, $to ]);
            }
        }
    }
