<?php

namespace App\GraphQL\Query;

use Rebing\GraphQL\Support\Query;
use App\Traits\CheckPermissions;
use Illuminate\Support\Str;

abstract class MainQuery extends Query
{
    use CheckPermissions;

    protected $incrementing = true;

    protected $acceptable = true;

    protected $translatable = false;

    protected $acceptable_field = 'is_active';

    protected $attributes = [
        'name' => 'BaseQuery',
        'description' => 'A query'
    ];

    /**
     * Get all data of the model,
     * used by index method controller
     *
     * @return Collection
     */
    public function getAllData($args, $fields)
    {
        if (isset($this->filter))
            $data = $this->model::filter($args, $this->filter);
        else
            $data = (new $this->model)->timestamps ? $this->model::latest() : $this->model::orderBy('id', 'desc');

        if ($args['ids'] ?? false)
            $data->whereIn('id', $args['ids']);

        $this->showOnlyAtiveData($data);

        if (method_exists($this, 'applyFilters'))
            $this->applyFilters($args, $data);

        if ($this->translatable && !isset($args['query']))
            $data->whereHas('translations');

        $data->with($fields->getRelations())->select($this->getSelectFields($fields));

        return $this->getPortionOfData($data, $args);
    }

    /**
     * Find an get a data from Database,
     * or abort 404 not found exception if can't find
     *
     * @param ID $feature
     * @return Model
     */
    public function getSingleData($args, $fields)
    {
        $data = $this->model::select($this->getSelectFields($fields))
            ->with($fields->getRelations());


        $this->showOnlyAtiveData($data);


        if ($args['id'] ?? false)
            return $data->findOrFail($args['id']);

        elseif ($args['slug'] ?? false) {
            return $data->whereHas('translations', function ($query) use ($args) {
                $query->where('slug', $args['slug']);
            })->first();
        }
    }

    public function showOnlyAtiveData($data)
    {
        if (!$this->acceptable)
            return;

        if (!$this->checkPermission("read-{$this->type}"))
            $data->where($this->acceptable_field, 1);
    }

    /**
     * Get the dynamik per_page property of the models
     *
     * @param int|10 $max
     * @param int|100 $min
     * @return void
     */
    public function getPerPage($args, int $max = 100, int $min = 1)
    {
        if ($args['per_page'] <= $min)
            return $min;

        elseif ($args['per_page'] >= $max)
            return $max;

        else
            return $args['per_page'];
    }

    public function getPortionOfData($data, $args)
    {
        return $data->paginate(
            isset($args['per_page']) ? $this->getPerPage($args) : 20,
            ['*'],
            'page',
            $args['page'] ?? 1
        );
    }

    public function getSelectFields($fields)
    {
        $select = $fields->getSelect();

        if (($index = array_search(Str::plural($this->type) . ".is_mine", $select)) !== false)
            $select[$index] = Str::plural($this->type) . ".user_id";

        return $select;
    }
}
