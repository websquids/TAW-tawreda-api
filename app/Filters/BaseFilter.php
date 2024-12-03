<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class BaseFilter {
  protected const DEFAULT_SORT_BY = 'created_at';
  protected const DEFAULT_SORT_ORDER = 'asc';

  protected $model;

  public function __construct($model = null) {
    $this->model = $model;
  }

  /**
   * Apply filters to the query.
   *
   * @param Builder $query
   * @param Request $request
   * @return Builder
   */
  public function apply(Builder $query, Request $request): Builder {
    $search = $this->getSearchParameters($request);
    $this->applySearchFilters($query, $search);
    $this->applySorting($query, $request);
    return $query;
  }

  /**
   * Extract and decode search parameters from the request.
   *
   * @param Request $request
   * @return array
   */
  protected function getSearchParameters(Request $request): array {
    // to ignore errors when we pass search as string or null or any types except array :)
    return is_array($request->get('search')) ? $request->get('search') : [];
  }

  /**
   * Apply search filters to the query.
   *
   * @param Builder $query
   * @param array $search
   * @return void
   */
  protected function applySearchFilters(Builder $query, array $search): void {
    foreach ($this->getAllowedFields() as $field => $options) {
      if ($options['searchable'] ?? false) {
        $query->when(isset($search[$field]), function ($query) use ($search, $field, $options) {
          if ($options['translated'] ?? false) {
            $query->whereTranslationLike($field, '%' . $search[$field] . '%');
          } else {
            $query->where($field, 'like', '%' . $search[$field] . '%');
          }
        });
      }
    }
  }

  /**
   * Apply sorting to the query.
   *
   * @param Builder $query
   * @param Request $request
   * @return void
   */
  protected function applySorting(Builder $query, Request $request): void {
    $sortBy = $request->get('sortBy', self::DEFAULT_SORT_BY);
    $sort = $request->get('sort', self::DEFAULT_SORT_ORDER);

    $fields = $this->getAllowedFields();

    if (isset($fields[$sortBy]) && ($fields[$sortBy]['sortable'] ?? false)) {
      if ($fields[$sortBy]['translated'] ?? false) {
        $query->orderByTranslation($sortBy, $sort);
      } else {
        $query->orderBy($sortBy, $sort);
      }
    } else {
      // Fallback to default sorting if field is not sortable
      $query->orderBy(self::DEFAULT_SORT_BY, self::DEFAULT_SORT_ORDER);
    }
  }

  /**
   * Get searchable fields for the model.
   *
   * @return array
   */
  protected function getAllowedFields(): array {
    return $this->model::getFields();
  }
}
