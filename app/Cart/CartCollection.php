<?php

namespace App\Cart;

use Illuminate\Support\Collection;

/**
* Cart Collection Class
*/
class CartCollection
{
    private $instance;
    private $session;

    public function __construct()
    {
        $this->session = session();
        $this->instance('drafts');
    }

    public function instance($instance = null)
    {
        $instance = $instance ?: 'drafts';

        $this->instance = sprintf('%s.%s', 'transactions', $instance);

        return $this;
    }

    public function currentInstance()
    {
        return str_replace('transactions.', '', $this->instance);
    }

    public function add(TransactionDraft $draft)
    {
        $content = $this->getContent();
        $draft->draftKey = str_random(10);
        $content->put($draft->draftKey, $draft);

        $this->session->put($this->instance, $content);

        return $draft;
    }

    public function content()
    {
        if (is_null($this->session->get($this->instance))) {
            return collect([]);
        }

        return $this->session->get($this->instance);
    }

    protected function getContent()
    {
        $content = $this->session->has($this->instance) ? $this->session->get($this->instance) : collect([]);

        return $content;
    }

    public function count()
    {
        return $this->getContent()->count();
    }

    public function isEmpty()
    {
        return $this->count() == 0;
    }

    public function hasContent()
    {
        return !$this->isEmpty();
    }

}