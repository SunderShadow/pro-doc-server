<?php

namespace App\Contracts\Library\Advice;

use App\Entity\AdvicePost;
use App\Entity\AdvicePostTag;

interface PostStorage
{
    public function save(AdvicePost $post): void;

    public function delete(AdvicePost $post): void;

    public function publish(AdvicePost $post): void;

    public function draft(AdvicePost $post): void;

    /**
     * @param AdvicePost $post
     * @param array<AdvicePostTag> $tags
     * @return void
     */
    public function bindTags(AdvicePost $post, array $tags): void;
}