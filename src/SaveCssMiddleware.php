<?php

namespace HamCQ\UserCSS;

use Flarum\User\Event\Saving;
use Illuminate\Support\Arr;
use Flarum\Foundation\ValidationException;

class SaveCssMiddleware

{
    public function handle(Saving $event)
    {
        $user = $event->user;
        $data = $event->data;
        $actor = $event->actor;
        $isSelf = $actor->id === $user->id;
        $attributes = Arr::get($data, 'attributes', []);
        $canEdit = $actor->can('edit', $user);
        if ( isset($attributes["preferences"] ) && Arr::has($attributes["preferences"], 'myStyle') && !$actor->phone) {
            throw new ValidationException(["msg"=>"Yikes! You need to verify your mobile number."]);
        }

        if( isset($attributes["preferences"]) && isset($attributes["preferences"]['myStyle']) ){
            if (!$isSelf) {
                $actor->assertPermission($canEdit);
            }
            $temp = $attributes["preferences"]['myStyle'];
            if(strlen($temp)/1024 > 8){
                throw new ValidationException(["msg"=>"CSS file size must be less than 8 kb"]);
            }
            $temp = str_ireplace([
                "http://", "https://", "data:", "function",
                "@import", "import", "javascript", "url(",
                "expression", "XMLHttpRequest", "=>", "document.",
                ".js", "$.", "axios", "<?php", "<script>",
                "</script>", "?>", "behavior:", "script",
                "$(", "@namespace", "document", "window",
            ], "",$temp);
            $temp = preg_replace('/[a-zA-Z0-9_-]+\.[a-zA-Z0-9.-]+/',"",$temp);

            $user->setPreference("myStyle",$temp);
        }
    }

}