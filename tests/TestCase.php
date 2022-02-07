<?php

namespace Tests;

use App\Models\AbstractModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    use CreatesApplication;

    /**
     * Test case's base URL
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * Create the create page URL
     *
     * @return string
     */
    protected function getCreateUrl()
    {
        return $this->baseUrl . '/new';
    }

    /**
     * Create the edit page URL with model ID
     *
     * @param AbstractModel $model
     * @return string
     */
    protected function getEditUrl(AbstractModel $model)
    {
        return $this->baseUrl . '/' . $model->id . '/edit';
    }

    /**
     * Create the delete page URL with model ID
     *
     * @param AbstractModel $model
     * @return string
     */
    protected function getDeleteUrl(AbstractModel $model)
    {
        return $this->baseUrl . '/' . $model->id . '/delete';
    }
}
