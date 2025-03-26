<?php

namespace App\Contracts\Library\Advice;

use App\Entity\AdvicePost;

interface PostStorage
{
    public function save(AdvicePost $post): void;

    public function delete(AdvicePost $post): void;
}