# Laravel Likeable

## Introduction

This package is basically a simplified fork of a [Laravel Love](https://github.com/cybercog/laravel-love) package with only using LIKE and UNLIKE capability. "Laravel Love" have 
more capabilities such as both "LIKE" and "DISLIKE" functionality.

Also, worth noting that this package utilizes usage of UUID's instead of integer ID's. And the "Likeable" and "Liker" models needs to utilize UUIDs as well. If you 
are not using UUID's please use the [cybercog/laravel-likeable](https://github.com/cybercog/laravel-likeable), [cybercog/laravel-love](https://github.com/cybercog/laravel-love) or any of the alternatives
listed below.

For the UUID generation this package uses [Ramsey UUID](https://github.com/ramsey/uuid).

## Features

- Uses UUIDs instead of integers (your user model must use them as well!)
- Designed to work with Laravel Eloquent models.
- Using contracts to keep high customization capabilities.
- Using traits to get functionality out of the box.
- Most part of the the logic is handled by the `LikeableService`.
- Has Artisan command `golike:recount {model?}` to re-fetch like counters.
- Subscribes for one model are mutually exclusive.
- Get Likeable models ordered by likes count.
- Events for `like`, `unlike` methods.
- Following PHP Standard Recommendations:
  - [PSR-1 (Basic Coding Standard)](http://www.php-fig.org/psr/psr-1/).
  - [PSR-2 (Coding Style Guide)](http://www.php-fig.org/psr/psr-2/).
  - [PSR-4 (Autoloading Standard)](http://www.php-fig.org/psr/psr-4/).
  
## Alternatives

- [cybercog/laravel-love](https://github.com/cybercog/laravel-love)
- [cybercog/laravel-likeable](https://github.com/cybercog/laravel-likeable)
- [rtconner/laravel-likeable](https://github.com/rtconner/laravel-likeable)
- [faustbrian/laravel-likeable](https://github.com/faustbrian/Laravel-Likeable)
- [sukohi/evaluation](https://github.com/SUKOHI/Evaluation)
- [zvermafia/lavoter](https://github.com/zvermafia/lavoter)
- [francescomalatesta/laravel-reactions](https://github.com/francescomalatesta/laravel-reactions)
- [muratbsts/laravel-reactable](https://github.com/muratbsts/laravel-reactable)  


  
## Installation

First, pull in the package through Composer.

```sh
$ composer require gorankrgovic/laravel-likeable
```

#### Perform Database Migration

At last you need to publish and run database migrations.

```sh
$ php artisan migrate
```

If you want to make changes in migrations, publish them to your application first.

```sh
$ php artisan vendor:publish --provider="Gox\Laravel\Likeable\Providers\LikeableServiceProvider" --tag=migrations
```

## Usage

### Prepare Liker Model

Use `Gox\Contracts\Likeble\Liker\Models\Liker` contract in model which will get likes
behavior and implement it or just use `Gox\Laravel\Likeable\Liker\Models\Traits\Liker` trait. 

```php
use Gox\Contracts\Likeable\Liker\Models\Liker as LikerContract;
use Gox\Laravel\Likeable\Liker\Models\Traits\Liker;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements LikerContract
{
    use Liker;
}
```

### Prepare Likeable Model

Use `Gox\Contracts\Likeable\Likeable\Models\Likeable` contract in model which will get likes
behavior and implement it or just use `Gox\Laravel\Likeable\Likeable\Models\Traits\Likeable` trait. 

```php
use Gox\Contracts\Likeable\Likeable\Models\Likeable as LikeableContract;
use Gox\Laravel\Likeable\Likeable\Models\Traits\Likeable;
use Illuminate\Database\Eloquent\Model;

class Article extends Model implements LikeableContract
{
    use Likeable;
}
```

### Available Methods

#### Likes

##### Like model


```php
$user->like($article);

$article->likeBy(); // current user
$article->likeBy($user->id);
```

##### Remove like mark from model

```php
$user->unlike($article);

$article->unlikeBy(); // current user
$article->unlikeBy($user->id);
```

##### Get model likes count

```php
$article->likesCount;
```

##### Get model likes counter

```php
$article->likesCounter;
```

##### Get likes relation

```php
$article->likes();
```

##### Get iterable `Illuminate\Database\Eloquent\Collection` of existing model likes

```php
$article->likes;
```

##### Boolean check if user likes model

```php
$user->hasLiked($article);

$article->liked; // current user
$article->isLikedBy(); // current user
$article->isLikedBy($user->id);
```

*Checks in eager loaded relations `likes` first.*

##### Get collection of users who likes model

```php
$article->collectLikers();
```

##### Delete all likers for model

```php
$article->removeLikes();
```

### Scopes

##### Find all articles liked by user

```php
Article::whereLikedBy($user->id)
    ->with('likesCounter') // Allow eager load (optional)
    ->get();
```


##### Fetch Likeable models by likes count

```php
$sortedArticles = Article::orderByLikesCount()->get();
$sortedArticles = Article::orderByLikesCount('asc')->get();
```

*Uses `desc` as default order direction.*

### Events

On each like added `\Gox\Laravel\Subscribe\Subscribeable\Events\LikeableWasLiked` event is fired.

On each like removed `\Gox\Laravel\Subscribe\Subscribeable\Events\LikeableWasUnliked` event is fired.

### Console Commands

##### Recount likes of all model types

```sh
$ golike:recount
```

##### Recount of concrete model type (using morph map alias)

```sh
$ golike:recount --model="article"
```

##### Recount of concrete model type (using fully qualified class name)

```sh
$ golike:recount --model="App\Models\Article"
```

## Security

If you discover any security related issues, please email me instead of using the issue tracker.

## License

- `Laravel Likeable` package is open-sourced software licensed under the [MIT license](LICENSE) by Goran Krgovic.
- `Laravel Love` package is open-sourced software licensed under the [MIT license](LICENSE) by Anton Komarev.