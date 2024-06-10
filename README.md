composer.json auto load added one class
 "files": [
        "app/Helpers/ResponseHelper.php"
    ]
inside appservice porvider added handle santerlized json responses use a custom resonse macro
This approach integrates more seamlessly with Laravel's response system and doesn't require manually including files or modifying the autoloader.

