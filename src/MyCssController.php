<?php
namespace HamCQ\UserCSS;

use Flarum\Http\RequestUtil;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Illuminate\Support\Arr;
use Flarum\User\UserRepository;

class MyCssController implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $id = Arr::get($request->getQueryParams(), 'id');
        $u = resolve(UserRepository::class);
        $user = $u->findOrFail($id);
        if(!$user->phone){
            return new CssResonse("");
        }
        return new CssResonse($user->getPreference("myStyle"));
    }
}