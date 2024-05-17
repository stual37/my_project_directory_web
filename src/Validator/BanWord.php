<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class BanWord extends Constraint
{
    public function __construct(
        public string $message = 'Il y a un mot banni dans cette partie : "{{ banWord }}"', 
        public array $banWords = ['penis', 'spam', 'viagra', 'pussy', 'xxx', 'sexe', 'pussies', 'chatte', 'cul', 'anal', 'anus', 'merde'],
        ?array $groups = null,
        mixed $payload = null
    )
    {
        parent::__construct(null, $groups, $payload);
    }
    
}
