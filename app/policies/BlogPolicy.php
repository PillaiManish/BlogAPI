<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Post;

class PostPolicy
{
    public function checkOwner(User $user, Post $post) {
        return $post->user_id === $user->id;
    }
}


