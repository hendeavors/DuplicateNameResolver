<?php
return array(

    /*
     |--------------------------------------------------------------------------
     | Name resolver settings
     |--------------------------------------------------------------------------
     |
     | Configure the class that implements the INameDataSource contract
     | The namespace and class configured without a schema is the default
     | Used when using Resolve::name without a second argument
     */
    'options' => array(
        'class' => 'NamesDataSource',
        'namespace' => 'App\\',
        'templates' => [
            'class' => 'NamesDataSource',
            'namespace' => 'App\\'
        ]
    )
);