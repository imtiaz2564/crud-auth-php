<?php

namespace Model;

class Sport
{
    public function __construct(
        private string $title,
        private string $post,
        private ?string $image = null,
        private ?int $id = null
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPost(): string
    {
        return $this->post;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }
}