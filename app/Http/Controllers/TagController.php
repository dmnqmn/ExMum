<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Tag;

use Validator;

class TagController extends BaseController
{

    public function getTags() {
        return Tag::getAllTags();
    }
}
