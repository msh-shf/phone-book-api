<?php

namespace App\Services;

use Doctrine\Common\Collections\ArrayCollection;

class Pagination
{
    private ArrayCollection $content;
    private int $total = 0;
    private int $page = 0;
    private int $size = 10;

    public function __construct()
    {
        $this->content = new ArrayCollection();
    }


    public static function create(ArrayCollection $content, int $total, int $page = 0, int $size = 10): Pagination
    {
        $pagination = new self();
        $pagination->setContent($content)
            ->setTotal($total)
            ->setPage($page)
            ->setSize($size);

        return $pagination;
    }

    public function getContent(): ?ArrayCollection
    {
        return $this->content;
    }

    public function setContent(ArrayCollection $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function asArray(): array {
        $lastPage = ceil($this->getTotal() / $this->getSize());
        return [
            'data' => $this->getContent()->toArray(),
            'meta' => [
                'total' => $this->getTotal(),
                'page' => $this->getPage(),
                'size' => $this->getSize(),
                'next' => $this->getPage() < $lastPage ? $this->getPage() + 1 : null,
                'last' => $lastPage
            ],
        ];
    }

}